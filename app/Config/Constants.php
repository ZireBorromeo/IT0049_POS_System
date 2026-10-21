<?php
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');
/*
 * Application constants can be defined here.
 * Keep secrets and environment-specific settings out of source control.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');
defined('EXIT_SUCCESS') || define('EXIT_SUCCESS', 0);
defined('EXIT_ERROR') || define('EXIT_ERROR', 1);
defined('EXIT_CONFIG') || define('EXIT_CONFIG', 3);
defined('EXIT_UNKNOWN_FILE') || define('EXIT_UNKNOWN_FILE', 4);
defined('EXIT_UNAVAILABLE') || define('EXIT_UNAVAILABLE', 5);
defined('EXIT_USER_INPUT') || define('EXIT_USER_INPUT', 6);
defined('EXIT_DATABASE') || define('EXIT_DATABASE', 8);
defined('EXIT__AUTO_MIN') || define('EXIT__AUTO_MIN', 9);
defined('EXIT__AUTO_MAX') || define('EXIT__AUTO_MAX', 125);