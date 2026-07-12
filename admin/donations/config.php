<?php
define('DB_DSN',  'mysql:host=127.0.0.1;dbname=YOUR_DB_NAME;charset=utf8mb4');
define('DB_USER', 'YOUR_DB_USER');
define('DB_PASS', 'YOUR_DB_PASSWORD');

// Generate your hash with: php -r "echo password_hash('yourpassword', PASSWORD_DEFAULT);"
define('ADMIN_USER',      'YOUR_USERNAME');
define('ADMIN_PASS_HASH', 'YOUR_BCRYPT_HASH');
