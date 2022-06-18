<?php

namespace App;

/**
 * Application configuration
 *
 * PHP version 8
 */

class Config
{

    /**
     * Database host
     * @var string
     */
    const DB_HOST = DB_HOST;

    /**
     * Database name
     * @var string
     */
    const DB_NAME = DB_NAME;

    /**
     * Database user
     * @var string
     */
    const DB_USER = DB_USER;

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = DB_PASSWORD;

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = SHOW_ERRORS;

    /**
     * Secret key for hashing. Use randomkeygen for unique random secret key
     * 256-bit key requirement
     * @link https://randomkeygen.com/ 
     * @var boolean
     */
    const SECRET_KEY = SECRET_KEY;

    /**
     * Method for sending email
     * Support either MAILGUN or SMTP
     *
     * @var string Returns string
     */
    const MAIL = MAIL; // Support either Mailgun or PHPMailer

    /**
     * Mailgun API for sending email
     *
     * @var any
     */
    const MAILGUN_DOMAIN = MAILGUN_DOMAIN;
    const MAILGUN_API = MAILGUN_API;
    const MAILGUN_FROM = MAILGUN_FROM;


    /**
     * SMTP configuration for sending email
     * Use any hosting that supports SMTP
     * e.g
     *
     * @var any
     */
    const SMTP_HOST = SMTP_HOST;
    const SMTP_PORT = 587;
    const SMTP_USERNAME = SMTP_USERNAME;
    const SMTP_PASSWORD = SMTP_PASSWORD;
    const SMTP_FROM = SMTP_FROM;

}
