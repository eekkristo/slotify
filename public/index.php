<?php

/**
 * Front controller
 *
 * PHP version 8
 */

/**
 * Composer
 */
require '../vendor/autoload.php';

/**
 * ENV VARIBLE LOADING AND DEFINING
 * Lets find ENV variables for docker components and pass them to Config.php
 */
define('DB_HOST', getenv('DB_HOST'));
define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('SHOW_ERRORS', getenv('SHOW_ERRORS'));
define('SECRET_KEY', getenv('SECRET_KEY'));
define('MAIL', getenv('MAIL'));
define('MAILGUN_DOMAIN', getenv('MAILGUN_DOMAIN'));
define('MAILGUN_API', getenv('MAILGUN_API'));
define('MAILGUN_FROM', getenv('MAILGUN_FROM'));
define('SMTP_HOST', getenv('SMTP_HOST'));
define('SMTP_USERNAME', getenv('SMTP_USERNAME'));
define('SMTP_PASSWORD', getenv('SMTP_PASSWORD'));
define('SMTP_FROM', getenv('SMTP_FROM'));

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Session
 */
session_start();
ob_start();

require_once ('../Routes/Web.php');
