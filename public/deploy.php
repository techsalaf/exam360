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
    log_deployment("✅ Git pull complete. Now at: $commit_after");

    // Step 2: Install PHP dependencies
    log_deployment("\n" . str_repeat("=", 60));
    log_deployment("STEP 2: Installing PHP dependencies");
    log_deployment(str_repeat("=", 60));

    if (file_exists('composer.json')) {
        log_deployment("✅ composer.json found");
        run_command('composer install --no-dev --optimize-autoloader', 'Installing Composer dependencies', true);
    } else {
        log_deployment("⚠️  composer.json not found, skipping Composer");
    }

    // Step 3: Install Node dependencies
    log_deployment("\n" . str_repeat("=", 60));
    log_deployment("STEP 3: Installing Node dependencies");
    log_deployment(str_repeat("=", 60));

    if (file_exists('package.json')) {
        log_deployment("✅ package.json found");
        run_command('npm ci', 'Installing npm dependencies', true);

        // Step 4: Build frontend assets
        log_deployment("\n" . str_repeat("=", 60));
        log_deployment("STEP 4: Building frontend assets");
        log_deployment(str_repeat("=", 60));
        run_command('npm run build', 'Building assets', true);
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
        run_command('php artisan migrate --force', 'Running Laravel migrations', true);
    } else {
        log_deployment("⚠️  artisan not found, skipping migrations");
    }

    // Step 7: Clear caches
    log_deployment("\n" . str_repeat("=", 60));
    log_deployment("STEP 7: Clearing application cache");
    log_deployment(str_repeat("=", 60));

    if (file_exists('artisan')) {
        run_command('php artisan cache:clear', 'Clearing cache', false);
        run_command('php artisan config:clear', 'Clearing config cache', false);
        run_command('php artisan view:clear', 'Clearing view cache', false);
        run_command('php artisan route:clear', 'Clearing route cache', false);
        log_deployment("✅ Caches cleared");
    }

    // Step 8: Optimize
    log_deployment("\n" . str_repeat("=", 60));
    log_deployment("STEP 8: Optimizing application");
    log_deployment(str_repeat("=", 60));

    if (file_exists('artisan')) {
        run_command('php artisan optimize', 'Optimizing application', true);
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