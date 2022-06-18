<?php

namespace App\Controllers;

use \App\Models\User;
use \Core\View;
use \App\Auth;
use \App\Flash;

/**
 * Login controller
 *
 * PHP version 8
 */
class Login extends \Core\Controller
{

    /**
     * Before filter. 
     * This can be used for pre-validation that you would want to do before server proceeds with something. 
     * For instance you could check if user has rights to enter some page. If it returns false it will stop executing 
     * further code and stops in the before section
     * First before --> Then your functions and classes --> and lastly after
     * You can test this out by echoing before and after. Before renders on top of the page 
     * and after renders on the bottom of the page
     * @return boolean Accepts only true / false - default is true
     * 
     */
    protected function before()
    {
        
    }

    /**
     * After filter
     * 
     * @return void
     */
    protected function after()
    {
        //echo " (after)";
        
    }

    /**
     * Show the index page
     *
     * @return void
     */

    public function newAction()
    {
        !Auth::getUser() ?: $this->redirect('/');

        View::render('Account/Login/login.html');
    }

    public function createAction()
    {
        !empty($_POST) ?: $this->redirect('/login');
        $user = User::authenticate($_POST['email'], $_POST['password']);

        $remember_me = isset($_POST['remember_me']);

        if ($user) {

            Auth::login($user, $remember_me);

            Flash::addMessage('Login successful');

            $this->redirect(Auth::getReturnToPage());

        } else {

            View::render('Account/Login/login.html', [
                        'email' => $_POST['email'],
                        'error' => Flash::addMessage('Invalid email and or password. Please try again', Flash::WARNING),
                        'remember_me' => $remember_me
                    ]);
        }
    }

    /**
     * Destroy all user session
     * @see https://www.php.net/manual/en/function.session-destroy.php
     * @return void
     */
    public function destroyAction()
    {
        Auth::logout();

        $this->redirect('/login/show-logout-message');
    }

    /**
     * Show a logged out flash message and redirect to the homepage. Necessary to use the flash message as they
     * use the session and at the end of the logout method the session is destroyed
     * so a new action needs to be called to use the session
     *
     * @return void
     */
    public function showLogOutMessageAction()
    {
        // FIXME:: Due to redirect back to root to store flash we are also taking the navbar and playbar with us 
        // This happends due to the load was not ajax call since we check for is ajax call or not we attached the nvarbar and playbar
        // Suggested solution is to make a redirect somewhere else, for instance /logout/redirect where we store flash and require auth there which forces back to login page
        Flash::addMessage('Successfully logged out');
        $this->redirect('/');
    }
}