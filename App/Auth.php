<?php

namespace App;

use App\Models\RememberedLogin;
use \App\Models\User;

class Auth
{

    /**
     * Undocumented function
     *
     * @param $user
     * @return void
     */
    public static function login($user, $remember_me)
    {
        // Session fixation attack prevention
        session_regenerate_id(true);
        $_SESSION['user'] = $user->id;

        if ($remember_me) {

            if ($user->rememberLogin()) {
                setcookie('remember_me', $user->remember_token, $user->expiry_timestamp, '/');
            }
        }

    }

    public static function logout()
    {
        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();

        static::forgetLogin();
    }

    public static function rememberRequestPage()
    {
        $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
    }

    /**
     * Get the originally requested page to return after requiring login, or default to the homepage
     *
     * @return void
     */
    public static function getReturnToPage()
    {
        return $_SESSION['return_to'] ?? '/';
    }

    /**
     * Get the current logged in user from the session or remember me cookie
     *
     * @return mixed The user model or null if not logged in
     */
    public static function getUser()
    {
        if (isset($_SESSION['user'])) {
            return User::findByID($_SESSION['user']);
        } else {
            return static::loginFromRememberCookie();
        }
    }

    public static function userStatus()
    {
        $user_id = $_SESSION['user'];

        if (isset($_SESSION['user'])) {
            // TODO: Implement a check where we will authorize artists / label owners and administrators with different privileges
        }
    }

    /**
     * Login the user from a remembe me login cookie
     *
     * @return mixed The user model if login cookie found; null otherwise
     */
    protected static function loginFromRememberCookie()
    {
        $cookie = $_COOKIE['remember_me'] ?? false;

        if ($cookie) {
            $remembered_login = RememberedLogin::findByToken($cookie);

            if ($remembered_login && !$remembered_login->hasExpired()) {
                $user = $remembered_login->getuser();

                static::login($user, false);

                return $user;
            }
        }
    }

    /**
     * Forget the remembred login, if present
     *
     * @return void
     */
    protected static function forgetLogin()
    {
        $cookie = $_COOKIE['remember_me'] ?? false;

        if ($cookie) {

            $remembered_login = RememberedLogin::findByToken($cookie);

            if ($remembered_login) {

                $remembered_login->delete();
            }

            setcookie('remember_me', '', time() - 3600); //set the expire date in the past
        }
    }
    
}


