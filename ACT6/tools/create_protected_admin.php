<?php
// CLI-only helper: generate a protected admin config file
// Usage (PowerShell): php tools\create_protected_admin.php --username=site_admin

if (php_sapi_name() !== 'cli') {
    echo "This script must be run from the command line.\n";
    exit(1);
}

$opts = getopt('', ['username::']);
$username = $opts['username'] ?? 'site_admin';

// Generate a strong random password
$plain = bin2hex(random_bytes(8)); // 16 hex chars (~64 bits)
$hash = password_hash($plain, PASSWORD_DEFAULT);

$configDir = __DIR__ . '/../config';
if (!is_dir($configDir)) {
    if (!mkdir($configDir, 0750, true)) {
        echo "Failed to create config directory: $configDir\n";
        exit(1);
    }
}

$configFile = $configDir . '/admin.php';
$content = "<?php\nreturn [\n    'username' => '" . addslashes($username) . "',\n    'password_hash' => '" . addslashes($hash) . "',\n];\n";

if (file_put_contents($configFile, $content) === false) {
    echo "Failed to write admin config to $configFile\n";
    exit(1);
}

echo "Protected admin created.\n";
echo "Username: $username\n";
echo "Password: $plain\n";
echo "Config file: $configFile\n";
echo "IMPORTANT: Move this file outside any public directory or keep file permissions restricted.\n";
echo "Remove or rotate this credential when no longer needed.\n";

exit(0);
