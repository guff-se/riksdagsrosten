<?php
/**
 *
 *
 *
 * @package WordPress
 */

define('DB_NAME', 'wordpress');

define('DB_USER', 'riksdagsrosten');

define('DB_PASSWORD', 'rrwww01');

/** MySQL-server */
define('DB_HOST', 'localhost');

define('DB_CHARSET', 'utf8');

define('DB_COLLATE', '');

/**#@+
 * Unika autentiseringsnycklar och salter.
 *
 * Du kan generera nycklar med {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '69=}jT 6F3A++I%2Zy<c@?-+a<vURa2$-YbQuLLw+}M^nD.KkrT$R1r4O)+D-Pe|');
define('SECURE_AUTH_KEY',  'vPZQ:,$F[G:V0+|=0oFXQzG`XO-JP|`%^g1~>T2 .@pFm<|aNV7>$!8t|+T+nXYM');
define('LOGGED_IN_KEY',    'un#h`%H8oLb5- T@&!.[g/lzl~sNJ=y|d6}A-|t!/4ni|26`$LgI^&rBIF)LOT/Q');
define('NONCE_KEY',        'HmjIso0Z.?Q<UXm.{I2=s8_o6!qL|52W 7=t+&00K1$><YH4#VgF:gl{6(pD+(7q');
define('AUTH_SALT',        'cw]#7+f6pB5V#v}k$mB=-P|lj~->QDf$8T](6qL/V)$bN|0=s3pU!U:e-JCWc j]');
define('SECURE_AUTH_SALT', 'KG+w,,be@r}j]CTB0l+yh7n(G]D.*VXoQ&,~q5,7 &4.1`Ty7@5igt$7ifI>u/<^');
define('LOGGED_IN_SALT',   'w?n<zI-uhn%f|wrz-)Q2#dbnXJ]7&WXS|Tl<]NJ^> 7:d(>G^o5u+JkE-|.+tC(9');
define('NONCE_SALT',       '93uK]TGAYF!dwk^LV}<},PT0kHe+lHQ8mq/k.USleiX&*wt6*2Bc5U[G:GKK6 ZJ');

/**#@-*/

/**
 *
 * Du kan ha flera installationer i samma databas om du ger varje installation ett unikt
 */
$table_prefix  = 'wp_';

/**
 *
 */
define('WPLANG', 'sv_SE');

/** 
 * 
 */ 
define('WP_DEBUG', false);


if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

require_once(ABSPATH . 'wp-settings.php');
