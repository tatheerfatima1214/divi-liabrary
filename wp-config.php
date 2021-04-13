<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'myweb' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '=eI|~LGM#U6e;}5BV-n{!ED.q`aoB cY#(hWdK7#M`NNO+8TP4zNVs~ImU~SG4EX' );
define( 'SECURE_AUTH_KEY',  'cV&=7@c7f9>*Zi0:1(/e#9Ci;PRP:cc?mi0H>:9e [D( Zj+c1M@D!!T/u^O{Xw,' );
define( 'LOGGED_IN_KEY',    'qFZ68,zry% ({oC5%cP!t],o%q=KCO}b:J:_9ay)T>61>sCAEPi~i~50OMv`:5I4' );
define( 'NONCE_KEY',        'Q<w,Ax9RR|F`[JO<_SiMjUZ2b3/r5L:EPmZBHLzF1Dm&_~}VV)NfBZ;3H^2?|Q`U' );
define( 'AUTH_SALT',        '5{;7!~.Cf%0!TV);r9DFrBnxY^?2k0,y!g8E5Eli}pVC>)e^T9Du][M.?t~0YkWw' );
define( 'SECURE_AUTH_SALT', '{xlp@)cEv{Xm1{`o}ayVBJD$-).)}8N6Bu6psK{Ci{ogAu&Z%yMr2zIEZaPr|-A=' );
define( 'LOGGED_IN_SALT',   ']vMA h74|;LTN+[`]ha-Y3ytuqGo,]C3)r]aRq/uo_V&uz!<KbDn8`h)~#}&{(e`' );
define( 'NONCE_SALT',       'L-CE]Z}qOeQ1[z<A_oAu2!WXQ>bq5R @0c`SMQ2y{ax)Z]-C*Z<#%e8s#D5)/#EC' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
