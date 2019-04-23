<?
require __DIR__ . "/vendor/autoload.php";
require __DIR__ . "/functions/setup_theme.php";
require __DIR__ . "/functions/custom_post_type.php";
require __DIR__ . "/functions/item.php";

define("THEME_HASH", substr(hash("md5", NONCE_KEY), 0, 7));
if(ART_ENV === 'development')add_filter('wpcf7_skip_mail', '__return_true');
