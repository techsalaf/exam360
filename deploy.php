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

// Helper function to log
function log_deployment($message) {
    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[$timestamp] $message\n";
    file_put_contents(LOG_FILE, $log_message, FILE_APPEND);
    echo $log_message;
}

// Create log directory if needed
@mkdir(dirname(LOG_FILE), 0755, true);

// Accept GET or POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    log_deployment('=== Deployment Started ===');
    log_deployment('Request from: ' . $_SERVER['REMOTE_ADDR']);
    log_deployment('Request method: ' . $_SERVER['REQUEST_METHOD']);
    
    chdir(PROJECT_ROOT);
    log_deployment('Working directory: ' . getcwd());
    
    // Step 1: Pull latest code from GitHub
    log_deployment('Step 1: Pulling latest code from GitHub...');
    $output = shell_exec('git fetch origin main 2>&1');
    log_deployment($output);
    
    $output = shell_exec('git reset --hard origin/main 2>&1');
    log_deployment($output);
    
    // Step 2: Install PHP dependencies
    log_deployment('Step 2: Installing PHP dependencies...');
    if (file_exists('composer.json')) {
        $output = shell_exec('composer install --no-dev --optimize-autoloader 2>&1');
        log_deployment($output);
    }
    
    // Step 3: Install Node dependencies
    log_deployment('Step 3: Installing Node dependencies...');
    if (file_exists('package.json')) {
        $output = shell_exec('npm ci 2>&1');
        log_deployment($output);
        
        // Step 4: Build frontend assets
        log_deployment('Step 4: Building frontend assets...');
        $output = shell_exec('npm run build 2>&1');
        log_deployment($output);
    }
    
    // Step 5: Set permissions
    log_deployment('Step 5: Setting application permissions...');
    @chmod('storage', 0775);
    @chmod('bootstrap/cache', 0775);
    array_map(function($dir) {
        if (is_dir($dir)) @chmod($dir, 0775);
    }, glob('storage/*'));
    log_deployment('Permissions updated');
    
    // Step 6: Run migrations
    log_deployment('Step 6: Running database migrations...');
    $output = shell_exec('php artisan migrate --force 2>&1');
    log_deployment($output);
    
    // Step 7: Clear caches
    log_deployment('Step 7: Clearing application cache...');
    shell_exec('php artisan cache:clear 2>&1');
    shell_exec('php artisan config:clear 2>&1');
    shell_exec('php artisan view:clear 2>&1');
    shell_exec('php artisan route:clear 2>&1');
    log_deployment('Caches cleared');
    
    // Step 8: Optimize
    log_deployment('Step 8: Optimizing application...');
    $output = shell_exec('php artisan optimize 2>&1');
    log_deployment($output);
    
    log_deployment('=== Deployment Completed Successfully ===');
    
    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => 'Deployment completed']);
    
} catch (Exception $e) {
    log_deployment('ERROR: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'failed', 'message' => $e->getMessage()]);
}
?>

