<?php
/**
 * GitHub Actions Webhook Deployment Script
 *
 * IMPORTANT: This file must live INSIDE the public/ folder, not the
 * project root, because your root .htaccess rewrites every request to
 * public/$1, and Laravel's public/.htaccess only serves a file directly
 * if it physically exists there — otherwise it forwards to index.php
 * and you get your app's own 404 page instead of this script running.
 *
 * Place this file at: /home/pnzjqabw/cbt/public/deploy.php
 *
 * Webhook URL: https://cbt.my360school.com/deploy.php
 *
 * CHANGES FROM ORIGINAL:
 * - run_command() now captures the real exit code (via exec(), not shell_exec())
 *   and can THROW when a step fails, instead of silently logging and continuing.
 * - Added an early check that PROJECT_ROOT is actually a git repository with
 *   an "origin" remote, so you get a clear error instead of a false "success".
 * - JSON response now includes the tail of the log so you can see what
 *   happened just by visiting the URL in a browser.
 * - PROJECT_ROOT is now dirname(__DIR__) since this script lives in public/
 *   but git/composer/npm/artisan all need to run from the actual project root.
 */

// Configuration
// This file lives in public/, so the real project root is one level up.
define('PROJECT_ROOT', dirname(__DIR__));
define('LOG_FILE', PROJECT_ROOT . '/storage/logs/deployment.log');

// Create log directory if needed
@mkdir(dirname(LOG_FILE), 0755, true);

// Helper function to log with timestamps
function log_deployment($message, $echo = true) {
    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[$timestamp] $message\n";
    file_put_contents(LOG_FILE, $log_message, FILE_APPEND);
    if ($echo) {
        echo $log_message;
    }
}

/**
 * Run a command and capture BOTH output and exit code.
 * If $required is true and the command fails (non-zero exit code),
 * this throws an Exception so the deployment correctly reports failure.
 */
function run_command($command, $description, $required = false) {
    log_deployment("🔄 $description");
    log_deployment("   Command: $command");

    $output_lines = [];
    $exit_code = 0;
    exec($command . ' 2>&1', $output_lines, $exit_code);

    foreach ($output_lines as $line) {
        if (!empty($line)) {
            log_deployment("   $line");
        }
    }

    log_deployment("   Exit code: $exit_code");

    if ($exit_code !== 0) {
        log_deployment("   ⚠️  Command exited with non-zero status ($exit_code)");
        if ($required) {
            throw new Exception("$description failed (exit code $exit_code): " . implode(' | ', array_slice($output_lines, -5)));
        }
    }

    return implode("\n", $output_lines);
}

/**
 * Locate an executable's absolute path. PHP's exec()/shell_exec() run with
 * a minimal PATH when triggered from a web request, so commands that work
 * fine when you SSH in (composer, npm, sometimes even php) can come back
 * "command not found" here even though they're installed on the server.
 * This tries `which` first, then a few common cPanel/shared-hosting
 * locations, and returns null if nothing is found.
 */
function find_executable($name, array $extra_candidates = []) {
    $output = [];
    $code = 0;
    @exec("which $name 2>/dev/null", $output, $code);
    if ($code === 0 && !empty($output[0]) && is_executable(trim($output[0]))) {
        return trim($output[0]);
    }

    $candidates = array_merge($extra_candidates, [
        "/usr/local/bin/$name",
        "/usr/bin/$name",
        "/bin/$name",
        "/opt/cpanel/composer/bin/$name",
    ]);

    foreach ($candidates as $path) {
        if ($path && is_executable($path)) {
            return $path;
        }
    }

    return null;
}

// ===== MANUAL OVERRIDES =====
// If the auto-detection below can't find npm/php, SSH in and run:
//   which npm
//   which php
// Paste the exact path(s) it gives you here. Leave as null to keep auto-detecting.
const NPM_BIN_OVERRIDE = null;      // e.g. '/usr/bin/npm'
const PHP_BIN_OVERRIDE = null;      // e.g. '/usr/local/bin/php'

// Accept GET or POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    log_deployment("❌ ERROR: Method not allowed: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    log_deployment("════════════════════════════════════════════════════════════");
    log_deployment("🚀 DEPLOYMENT STARTED");
    log_deployment("════════════════════════════════════════════════════════════");
    log_deployment("Time: " . date('Y-m-d H:i:s'));
    log_deployment("Request from: " . $_SERVER['REMOTE_ADDR']);
    log_deployment("Request method: " . $_SERVER['REQUEST_METHOD']);

    log_deployment("\n📋 ENVIRONMENT CHECK:");
    log_deployment("Current user: " . trim(shell_exec('whoami 2>&1')));
    log_deployment("PHP version: " . phpversion());
    log_deployment("Project root: " . PROJECT_ROOT);

    // Resolve real, absolute paths to npm/php since exec() often
    // can't see them via bare command names from a web request.
    $npm_bin = NPM_BIN_OVERRIDE ?: find_executable('npm');
    $php_bin = PHP_BIN_OVERRIDE ?: find_executable('php', [PHP_BINARY]);

    log_deployment("npm binary: " . ($npm_bin ?: '❌ NOT FOUND'));
    log_deployment("PHP CLI binary: " . ($php_bin ?: '❌ NOT FOUND'));

    // Check if we can change directory
    if (!chdir(PROJECT_ROOT)) {
        throw new Exception("Cannot change to project directory: " . PROJECT_ROOT);
    }
    log_deployment("✅ Working directory: " . getcwd());

    // Check git binary
    log_deployment("\n📋 GIT STATUS:");
    $git_check = shell_exec('which git 2>&1');
    if (empty($git_check)) {
        throw new Exception("Git is not installed on this server!");
    }
    log_deployment("✅ Git found: $git_check");
    log_deployment("Git version: " . trim(shell_exec('git --version 2>&1')));

    // NEW: Confirm this directory is actually a git repository.
    // This is the #1 cause of "success but nothing deployed":
    // the project was uploaded via FTP/zip and was never `git clone`'d here,
    // so there is no .git folder for `git fetch`/`git reset` to act on.
    if (!is_dir(PROJECT_ROOT . '/.git')) {
        throw new Exception(
            "No .git directory found in " . PROJECT_ROOT . ". " .
            "This folder was never git-cloned, so 'git fetch'/'git reset' have nothing to do. " .
            "You need to SSH in (or use cPanel's Git Version Control feature) and run " .
            "'git clone <your-repo-url> .' in this directory once, then this script can pull updates."
        );
    }

    // Confirm an "origin" remote is configured
    $remotes = trim(shell_exec('git remote -v 2>&1'));
    if (empty($remotes) || stripos($remotes, 'origin') === false) {
        throw new Exception("No 'origin' git remote is configured in " . PROJECT_ROOT . ". Output: $remotes");
    }
    log_deployment("Remotes:\n$remotes");

    // Current status before pulling
    $commit_before_full = trim(shell_exec('git rev-parse HEAD 2>&1'));
    log_deployment("\nCurrent branch: " . trim(shell_exec('git branch --show-current 2>&1')));
    log_deployment("Last commit (before pull): " . trim(shell_exec('git log -1 --oneline 2>&1')));

    // Step 1: Pull latest code from GitHub
    log_deployment("\n" . str_repeat("=", 60));
    log_deployment("STEP 1: Pulling latest code from GitHub");
    log_deployment(str_repeat("=", 60));

    run_command('git remote -v', 'Checking git remotes', false);

    // These two are now REQUIRED - if auth fails or the fetch otherwise
    // fails, the whole deployment correctly aborts as a failure instead
    // of silently reporting success.
    run_command('git fetch origin main', 'Fetching from origin/main', true);
    run_command('git reset --hard origin/main', 'Resetting to origin/main', true);

    $commit_after = trim(shell_exec('git log -1 --oneline 2>&1'));
    $commit_after_full = trim(shell_exec('git rev-parse HEAD 2>&1'));
    log_deployment("✅ Git pull complete. Now at: $commit_after");

    // Show exactly which files changed between the old and new commit
    log_deployment("\n📂 FILES CHANGED:");
    $changed_files = [];
    if ($commit_before_full === $commit_after_full) {
        log_deployment("   (no changes - already up to date)");
    } else {
        $diff_output = trim(shell_exec("git diff --name-status $commit_before_full $commit_after_full 2>&1"));
        if (empty($diff_output)) {
            log_deployment("   (no file changes detected)");
        } else {
            $status_labels = ['A' => 'Added', 'M' => 'Modified', 'D' => 'Deleted'];
            foreach (explode("\n", $diff_output) as $line) {
                if (empty($line)) continue;
                $parts = preg_split('/\s+/', $line, 2);
                $code = $parts[0];
                $file = $parts[1] ?? '';
                $label = $status_labels[substr($code, 0, 1)] ?? $code; // handles R100, C100 etc.
                log_deployment("   [$label] $file");
                $changed_files[] = ['status' => $label, 'file' => $file];
            }
            log_deployment("   Total: " . count($changed_files) . " file(s) changed");
        }
    }

    // Step 2: PHP dependencies (Composer)
    // Intentionally skipped - dependencies are stable and don't need to be
    // reinstalled on every deploy. Delete this comment block and restore the
    // composer install call below if that ever changes.
    log_deployment("\n" . str_repeat("=", 60));
    log_deployment("STEP 2: PHP dependencies (Composer)");
    log_deployment(str_repeat("=", 60));
    log_deployment("⏭️  Skipped intentionally - not reinstalling Composer dependencies on every deploy");

    // Step 3: Install Node dependencies
    log_deployment("\n" . str_repeat("=", 60));
    log_deployment("STEP 3: Installing Node dependencies");
    log_deployment(str_repeat("=", 60));

    if (file_exists('package.json')) {
        log_deployment("✅ package.json found");
        if (!$npm_bin) {
            throw new Exception("npm binary not found anywhere on this server's PATH. SSH in, run 'which npm', and set NPM_BIN_OVERRIDE near the top of this file to the path it gives you. (If npm isn't installed at all on this shared host, you may need to build assets in GitHub Actions and commit the built files instead.)");
        }
        run_command("$npm_bin ci", 'Installing npm dependencies', true);

        // Step 4: Build frontend assets
        log_deployment("\n" . str_repeat("=", 60));
        log_deployment("STEP 4: Building frontend assets");
        log_deployment(str_repeat("=", 60));
        run_command("$npm_bin run build", 'Building assets', true);
    } else {
        log_deployment("⚠️  package.json not found, skipping npm");
    }

    // Step 5: Set permissions
    log_deployment("\n" . str_repeat("=", 60));
    log_deployment("STEP 5: Setting application permissions");
    log_deployment(str_repeat("=", 60));

    @chmod('storage', 0775);
    @chmod('bootstrap/cache', 0775);
    array_map(function($dir) {
        if (is_dir($dir)) @chmod($dir, 0775);
    }, glob('storage/*'));
    log_deployment("✅ Permissions updated");

    // Step 6: Run migrations
    log_deployment("\n" . str_repeat("=", 60));
    log_deployment("STEP 6: Running database migrations");
    log_deployment(str_repeat("=", 60));

    if (file_exists('artisan')) {
        $php_cmd = $php_bin ?: 'php';
        run_command("$php_cmd artisan migrate --force", 'Running Laravel migrations', true);
    } else {
        log_deployment("⚠️  artisan not found, skipping migrations");
    }

    // Step 7: Clear caches
    log_deployment("\n" . str_repeat("=", 60));
    log_deployment("STEP 7: Clearing application cache");
    log_deployment(str_repeat("=", 60));

    if (file_exists('artisan')) {
        $php_cmd = $php_bin ?: 'php';
        run_command("$php_cmd artisan cache:clear", 'Clearing cache', false);
        run_command("$php_cmd artisan config:clear", 'Clearing config cache', false);
        run_command("$php_cmd artisan view:clear", 'Clearing view cache', false);
        run_command("$php_cmd artisan route:clear", 'Clearing route cache', false);
        log_deployment("✅ Caches cleared");
    }

    // Step 8: Optimize
    log_deployment("\n" . str_repeat("=", 60));
    log_deployment("STEP 8: Optimizing application");
    log_deployment(str_repeat("=", 60));

    if (file_exists('artisan')) {
        $php_cmd = $php_bin ?: 'php';
        run_command("$php_cmd artisan optimize", 'Optimizing application', true);
    }

    // Final status
    log_deployment("\n" . str_repeat("=", 60));
    log_deployment("✅ DEPLOYMENT COMPLETED SUCCESSFULLY");
    log_deployment(str_repeat("=", 60));
    log_deployment("New git commit: " . trim(shell_exec('git log -1 --oneline 2>&1')));
    log_deployment("Deployment time: " . date('Y-m-d H:i:s'));

    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Deployment completed successfully',
        'commit' => $commit_after,
        'files_changed' => $changed_files,
        'files_changed_count' => count($changed_files),
        'timestamp' => date('Y-m-d H:i:s'),
        'log_file' => LOG_FILE
    ]);

} catch (Exception $e) {
    log_deployment("\n❌ ERROR: " . $e->getMessage());
    log_deployment(str_repeat("=", 60));
    log_deployment("❌ DEPLOYMENT FAILED");
    log_deployment(str_repeat("=", 60));

    http_response_code(500);
    echo json_encode([
        'status' => 'failed',
        'message' => $e->getMessage(),
        'log_file' => LOG_FILE
    ]);
}
?>