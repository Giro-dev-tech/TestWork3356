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
define( 'DB_NAME', 'sample' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'L=R#0TGn.#|suLg-}Ey[YODO]:~JlC<wn+`B| w|jU-31{d@-3QGV(J==S#N47?3' );
define( 'SECURE_AUTH_KEY',  'M_N9c{>bj9liX;<Wp4y0l4t$m:MG|,s>eW(3QbD0xZEheA^!pm(j0P!Y1Agv7JGd' );
define( 'LOGGED_IN_KEY',    'Dd8yITB*<=5V(Hk<?$^x|,E;*ag8q)^$S:5`d2+R@4)a?alE@N!d6(udW;}!3)H0' );
define( 'NONCE_KEY',        'GP7O,66sV8+0n//X=%ufYsrjE6#e34y8Gv/mZI,Bnpf6j7jqY:z19kJnFZF^kJf!' );
define( 'AUTH_SALT',        'p=zg.wSK-U`0[?*McocJB6<cSA8WyD/WRoem.F6a0zCrMx3^>@gu~df>Z6Ny)@_@' );
define( 'SECURE_AUTH_SALT', 'Z<F6$2Zi+w}g_F[mJ$UpnE6`VGdZ)a9vh+3nxkp_L5- JtZ9-[4,&|pqLlDa ; p' );
define( 'LOGGED_IN_SALT',   '&1Vy.^!FH2!<M/Nib9ES?&*<f>Tj/aVYz9OVRU4N>y  *]}34vR%,J9nLi^n1US%' );
define( 'NONCE_SALT',       'D+B&#J6o_QKHJ0y:+mp}1e/@v;X/Q;kTHLy86S?f/E|TPzD2vMC9twZ!RSam5a(P' );

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
