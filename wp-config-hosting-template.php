<?php
/**
 * HOSTING wp-config.php TEMPLATE
 * ================================
 * Step 1: Copy this file content
 * Step 2: On mHosting cPanel → File Manager → public_html → create wp-config.php
 * Step 3: Paste this content and fill in YOUR hosting database details
 * Step 4: Save. Site will work!
 *
 * DO NOT upload this file via git — wp-config.php is in .gitignore
 */

// ✅ CHANGE THESE 4 VALUES — get from mHosting cPanel → MySQL Databases
define( 'DB_NAME',     'YOUR_DATABASE_NAME' );   // e.g. abdulreh_finspots
define( 'DB_USER',     'YOUR_DATABASE_USER' );   // e.g. abdulreh_admin
define( 'DB_PASSWORD', 'YOUR_DB_PASSWORD' );
define( 'DB_HOST',     'localhost' );             // usually 'localhost' on shared hosting

define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

// ✅ CHANGE THESE — generate fresh keys at: https://api.wordpress.org/secret-key/1.1/salt/
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');

$table_prefix = 'wp_';

// ✅ CHANGE THIS — your actual domain
define( 'WP_HOME',    'https://yourdomain.com' );
define( 'WP_SITEURL', 'https://yourdomain.com' );

define( 'WP_DEBUG', false );

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}
require_once ABSPATH . 'wp-settings.php';
