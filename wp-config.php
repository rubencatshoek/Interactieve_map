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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'interactieve_map');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'RlzA-<l+<$~`6u1-LaQ5gX-YG;*)ccMag3&_!dUN]bH.b}sg.#-L<eiP6`R>uw7@');
define('SECURE_AUTH_KEY',  'rJXjE|} mR1)T:X5-b|#LstdjjuL**YYQsG6}SHJj|jf0z-Zq5qO#@U |h8!T&28');
define('LOGGED_IN_KEY',    '1X<jHnl0x.<e}xhlz`:cCraF90yr}[3dfqul;pC5(.m/<Iy])dGz~(K9k*t3ye!M');
define('NONCE_KEY',        'QjFOL122+*`8V#E#@]&>W|;r2D>qL7t.mM-mEsGgU/S0c0G;,A3bLH)-][CzinBV');
define('AUTH_SALT',        '^)*@z>fz12:gN>aKm(>T#kX)0-Wg8^@qsNlu9ctR>(?P,GtjqudT.Xs}T-I[ W2m');
define('SECURE_AUTH_SALT', '(xUXGb)OpYO7Kpb&#=0+-U&gHvc4Mk-4:{+Bn7&kb4/([aYe=4-s1~IW;jG8K5]P');
define('LOGGED_IN_SALT',   '{]g?C,J8yf%uL5XfcoE_~DdF}Ss fgvJ0|nw WuQHm$N ;r*Y/m9kb^RC%8p7&ee');
define('NONCE_SALT',       'iHyTN4W;d-dk1{M2u+*7QBYX2o|b) %z]n^SZr{d~Q[j2X( <Xb+W0.OTBsSlUim');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
