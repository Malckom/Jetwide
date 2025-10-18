<?php
define('WP_HOME','https://jetwide.org/New');
define('WP_SITEURL','https://jetwide.org/New/wp');

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'jetwideo_wp470' );

/** Database username */
define( 'DB_USER', 'jetwideo_wp470' );

/** Database password */
define( 'DB_PASSWORD', '5pS1878]B!' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'bf52o4d4jgavlplbjutcptzbjht05mtjvcvrxshy2cgayucqifvnga5nd4u3yg8o' );
define( 'SECURE_AUTH_KEY',  'ntejic3mapkhuweln6raxe0uog9slfqbm0z1ljfz53bh935rturfxm6nxx15jdqf' );
define( 'LOGGED_IN_KEY',    'u3s5ccjymaklsz30wsgpxfjqsvzkzxpft4lglt6wuxcvatawoxhncsso4zr9xojr' );
define( 'NONCE_KEY',        'bcwuw7wyoomqi5rnwuzgliq6t5ypmyqpihs1vlnzgpfryaulphdrm7ptpofczuuh' );
define( 'AUTH_SALT',        'kthumy3z1hfjvxgiw4rvbbmm7jlohncd2knjmftiwezjnrovdpx7xdk3leasieaj' );
define( 'SECURE_AUTH_SALT', 'wuo87zh9zwawfrrgwz1fhj4ek4dk60wxn4gtwizakamzoaex7hpuboo2uma9nwzg' );
define( 'LOGGED_IN_SALT',   'mgrqssx95baleq1kerlghhtbkr4rta5zh6x8x6fimraa6vhvywymfgczaglfg3kq' );
define( 'NONCE_SALT',       'bao7aqww8vvqdaw6ui8hb4e1pb1razj12ur7kekclltxlfyrc1oy9wpbnai2k0nr' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wpr0_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
