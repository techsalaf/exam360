<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    DB::table('system_settings')->updateOrInsert(
        ['key' => 'active_homepage_design'],
        ['value' => 'design3']
    );
    echo "✓ Design3 activated successfully!\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
