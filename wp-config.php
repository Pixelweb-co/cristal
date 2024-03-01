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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'demowp' );

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
define( 'AUTH_KEY',         'p)4()T=r7+Cl6J!m~&b6x}KJ,Q0&oE5r9CtCb@mpwUKb:&]dp;*y1nG|xpT>-2l@' );
define( 'SECURE_AUTH_KEY',  'O@A}{6loCj8:l{rJ`*q9ULsKEO~MMHgSBeEqv@}vey1I(~;$lv)VHg^q5p9rja&h' );
define( 'LOGGED_IN_KEY',    '26Yj8;kU8giNBKQR)38-m[dv`0{Tjn2XB2O^i)uPj<QX!hWOK5C^}uo$R~hl2QjE' );
define( 'NONCE_KEY',        'aR;?=d%kD/t@X|`o_Ct4*<*leq@@MFq*#vt28K%:D/QsVJHmbN^$}!tu~Du1AEeV' );
define( 'AUTH_SALT',        '8BOsry`HN9%Mv#U00L}^c[EWEl-Sx+]G0Dl?HZm8I6<5$TAxD=T<V&[+m58VS&sv' );
define( 'SECURE_AUTH_SALT', '2iztiin`*I~w]Tc0A%Mv~%}TLe<j-#w-<Qq}A3YiA}T},,!@,GsQOcsDugNF7*Y&' );
define( 'LOGGED_IN_SALT',   '&lgKcG6lPq<d@3Bj,Rl{3AxA?p<[+#W&L.TlEJ3Kl[(Gcm,Q~kue#:q`LIl!`5iz' );
define( 'NONCE_SALT',       'hKK7GCu<SCnw%[U,_d>`t+J7=d<8#jhV+>3j(v9^DVz?g-<$`RMersed,?7Ci0,G' );

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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
