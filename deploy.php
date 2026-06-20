<?php
/**
 * GitHub Actions Webhook Deployment Script
 * 
 * Place this file in your project root: /home/pnzjqabw/cbt/deploy.php
 * 
 * Webhook URL: https://cbt.my360school.com/deploy.php
 */

// Configuration
define('LOG_FILE', __DIR__ . '/storage/logs/deployment.log');
define('PROJECT_ROOT', __DIR__);

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

// Helper to run command and log output
function run_command($command, $description) {
    log_deployment("🔄 $description");
    log_deployment("   Command: $command");
    
    $output = shell_exec($command . ' 2>&1');
    
    if ($output) {
        foreach (explode("\n", trim($output)) as $line) {
            if (!empty($line)) {
                log_deployment("   $line");
            }
        }
    }
    
    return $output;
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
    log_deployment("Current user: " . shell_exec('whoami 2>&1'));
    log_deployment("PHP version: " . phpversion());
    log_deployment("Project root: " . PROJECT_ROOT);
    
    // Check if we can change directory
    if (!chdir(PROJECT_ROOT)) {
        throw new Exception("Cannot change to project directory: " . PROJECT_ROOT);
    }
    log_deployment("✅ Working directory: " . getcwd());
    
    // Check git
    log_deployment("\n📋 GIT STATUS:");
    $git_check = shell_exec('which git 2>&1');
    if (empty($git_check)) {
        throw new Exception("Git is not installed on this server!");
    }
    log_deployment("✅ Git found: $git_check");
    
    log_deployment("Git version: " . trim(shell_exec('git --version 2>&1')));
    
    // Check current git status
    log_deployment("\nCurrent branch: " . trim(shell_exec('git branch --show-current 2>&1')));
    log_deployment("Last commit: " . trim(shell_exec('git log -1 --oneline 2>&1')));
    
    // Step 1: Pull latest code from GitHub
    log_deployment("\n" . str_repeat("=", 60));
    log_deployment("STEP 1: Pulling latest code from GitHub");
    log_deployment(str_repeat("=", 60));
    
    run_command('git remote -v', 'Checking git remotes');
    run_command('git fetch origin main', 'Fetching from origin/main');
    run_command('git reset --hard origin/main', 'Resetting to origin/main');
    
    log_deployment("✅ Git pull complete");
    
    // Step 2: Install PHP dependencies
    log_deployment("\n" . str_repeat("=", 60));
    log_deployment("STEP 2: Installing PHP dependencies");
    log_deployment(str_repeat("=", 60));
    
    if (file_exists('composer.json')) {
        log_deployment("✅ composer.json found");
        run_command('composer install --no-dev --optimize-autoloader', 'Installing Composer dependencies');
    } else {
        log_deployment("⚠️  composer.json not found, skipping Composer");
    }
    
    // Step 3: Install Node dependencies
    log_deployment("\n" . str_repeat("=", 60));
    log_deployment("STEP 3: Installing Node dependencies");
    log_deployment(str_repeat("=", 60));
    
    if (file_exists('package.json')) {
        log_deployment("✅ package.json found");
        run_command('npm ci', 'Installing npm dependencies');
        
        // Step 4: Build frontend assets
        log_deployment("\n" . str_repeat("=", 60));
        log_deployment("STEP 4: Building frontend assets");
        log_deployment(str_repeat("=", 60));
        run_command('npm run build', 'Building assets');
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
        run_command('php artisan migrate --force', 'Running Laravel migrations');
    } else {
        log_deployment("⚠️  artisan not found, skipping migrations");
    }
    
    // Step 7: Clear caches
    log_deployment("\n" . str_repeat("=", 60));
    log_deployment("STEP 7: Clearing application cache");
    log_deployment(str_repeat("=", 60));
    
    if (file_exists('artisan')) {
        shell_exec('php artisan cache:clear 2>&1');
        shell_exec('php artisan config:clear 2>&1');
        shell_exec('php artisan view:clear 2>&1');
        shell_exec('php artisan route:clear 2>&1');
        log_deployment("✅ Caches cleared");
    }
    
    // Step 8: Optimize
    log_deployment("\n" . str_repeat("=", 60));
    log_deployment("STEP 8: Optimizing application");
    log_deployment(str_repeat("=", 60));
    
    if (file_exists('artisan')) {
        run_command('php artisan optimize', 'Optimizing application');
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
        'timestamp' => date('Y-m-d H:i:s'),
        'log_file' => LOG_FILE
    ]);
    
} catch (Exception $e) {
    log_deployment("\n❌ ERROR: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'failed', 
        'message' => $e->getMessage(),
        'log_file' => LOG_FILE
    ]);
}
?>

