<?php

namespace App;

/**
 * Flash notification messages: messages for one-time display using session
 * for storage betweet requests.
 * 
 * PHP version 8.0
 */
class Flash
{

    const SUCCESS = 'success';
    const INFO = 'info';
    const WARNING = 'warning';
    const DANGER = 'danger';
    /**
     * Add a message
     *
     * @param string $message The message content
     * @return void
     */
    public static function addMessage($message, $type = 'success')
    {
        // Create an array in the session if it doesn't exist already
        if (!isset($_SESSION['flash_notifications'])) {
            $_SESSION['flash_notifications'] = [];
        }

        // Append the mssage to the array
        $_SESSION['flash_notifications'][] = [
            'body' => $message,
            'type' => $type
        ];
    }

    /**
     * Get all the messages
     *
     * @return mixed An array with all the messages or null if none set
     */
    public static function getMessage()
    {
        if (isset($_SESSION['flash_notifications'])) {
            $message = $_SESSION['flash_notifications'];
            unset($_SESSION['flash_notifications']);

            return $message;
        } 
    }
}