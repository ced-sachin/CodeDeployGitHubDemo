<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress_OB' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'password' );

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
define( 'AUTH_KEY',         '51W&?D fl4[[{j][:Q !3:CBo(D>0$C^tS tydpY8gnu1yprD)KM+uP=DKavKx-G' );
define( 'SECURE_AUTH_KEY',  'fIkVd>^?3IzK}-)=G[fz[0|(2DZ5y%jj1+g;@p,9,(gtH>>Zo*02X]g#g1*|:Bf_' );
define( 'LOGGED_IN_KEY',    'GND8kAnGd3k+3n7{Bi,Jhj),pDN9,1-6n6l=^4X+UT0{<t&9+B++gU)~ne Q>>MB' );
define( 'NONCE_KEY',        'QKU<gvNWRQy*GCzfp(/x2{IBMnsZ-!1%xST$L@z>(L.d3*Q<6~FdSJqDV6,uo:U]' );
define( 'AUTH_SALT',        '_jIm(()+w:Z#pvn;<VHPpm AZJZ;}`VF5NDlV?VJi;Yml.UIu<b?Z5G,aKRWbN ;' );
define( 'SECURE_AUTH_SALT', ',mPv%L[ i*.PWPkHD,IV:DJ@;Q#.~oLX?q>~KP{WGyb]P~rHs __/BLwfM7J/uce' );
define( 'LOGGED_IN_SALT',   '0+W2}Y3!OtdISn%:y+RE%6J]4-df>I:1>7w<zRVPU/LgIDqvRmjol*2kpG}C9cWT' );
define( 'NONCE_SALT',       '*7xcb<fpsn,[2~o.hd=1)%jSP7:YM8bOpIS3~~Sr$3e)*H2{KAxoGs(m2^%F&P_l' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );
define('FS_METHOD','direct');

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
