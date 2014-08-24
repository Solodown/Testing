<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'wordpress');

/** MySQL database password */
define('DB_PASSWORD', 'password');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'wcs!e96Bi&[p<14XCRl[$k#Mu`Omri@&{uIK/udOcKt%f/ZRI#!tZ4uaGCyqJ]vr');
define('SECURE_AUTH_KEY',  'V~,L`xN4+$Kpv}14FgEPkEM0YE_KDk=Ps :{8*^(lkr2&W8tn2y1+C@I,9Y9>14@');
define('LOGGED_IN_KEY',    '3]_e=h/V2S<WAp+s<UM>dnx5xe%^:9Ih]zroD`xq[,Z=pUl}@u]CSc`p~{qx6/zu');
define('NONCE_KEY',        '$*Aql:o+#WU#d:}l=%E~?7KBa`*R%xDQN$9Bn%+8EG]krz~3[sfpJI%TUC0FBV#e');
define('AUTH_SALT',        ',|Kib%8~YGe0RfAK{uDA|.One[S1e{ Bm]+QzhLe@FFR]YsbDBnfGk-8QbYRO3f~');
define('SECURE_AUTH_SALT', '*07u-3l}%YL/_QrMH/7Se;vXu>*a5j$(cFKN[s@4#.-arWT`+x?e?yhjJS>;|0%d');
define('LOGGED_IN_SALT',   '[7H]ZQy%`x1OgE}}c.ea66G,UH(Zt/NCNZ2!*.KEH3rGOv`x$7O*P$iW^)=#%;Zs');
define('NONCE_SALT',       'gHKrT>@@U(Go)LNs??G.E.Fqdt[j>NE=[Ka$I[d7b|tv2,Q>jz)j$,;/`kZd|11L');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpEx_';

/**
 * WordPress Localized Language, defaults to Canadian English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * en_CA.mo to wp-content/languages and set WPLANG to 'en_CA' to enable Canadian
 * English language support.
 */
define('WPLANG', 'en_CA');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
