<?php

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
define('DB_NAME', 'wp-one');

/** Database username */
define('DB_USER', 'root');

/** Database password */
define('DB_PASSWORD', '');

/** Database hostname */
define('DB_HOST', 'localhost');

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

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
define('AUTH_KEY',         'q!L5h$`p=L.XV;1UYEZU}|vm{&o.@ekeboCF&o61o<kS]Jo@=YA/Cbq6)G]@ob@O');
define('SECURE_AUTH_KEY',  '5`iagPx%RBCW)2<%/Vcdy@`KnHT!_N{Flet4/eD30<f)5<tb]LaBox@nsfayzLa!');
define('LOGGED_IN_KEY',    'Mll/ssfhB6Mn@=ydwX:aA5I-)ABJ0MhB&wpd]2MAq&=;0;p2D5$NK/Z~#WC4Pd/5');
define('NONCE_KEY',        '!ZSK{$pw.Td>og4jgBVFP33lj*u4k1{j%IvD;[q F.xIgvBMc;!0PQpV$.`6W$iR');
define('AUTH_SALT',        '#5Nb67i.]7E8^q*+OV%8u9p=aX}XEH)hEzj(H?$ee[|S|+;EZc>/gl4zq?xm(yfb');
define('SECURE_AUTH_SALT', 'z}C}BuXb:n|}NMz#W_t#<m.hfhc?tK)8n}?yZ3#ee1JrL^E8^. ta2bt.8NCOA)g');
define('LOGGED_IN_SALT',   'Et>x9b6*s$ Af2<#WJ;r?,f*w<m=xNxXXx^T%&JHPYt{#2VU VL+3P2*.-_+#$.u');
define('NONCE_SALT',       '{ma*ETWLiOp&p|pVh8W^6?xq3b}i)1E}CjNC0}YkkHn~;c~jTMZ^_gf9u+w1u%MA');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define('WP_DEBUG', false);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (! defined('ABSPATH')) {
	define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
