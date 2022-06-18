<?php

namespace App\Controllers;

use App\Auth;
use \Core\View;
use App\Models\User;

/**
 * Register controller
 *
 * PHP version 8
 */
class Register extends \Core\Controller
{

    /**
     * Before filter. 
     * This can be used for pre-validation that you would want to do before server proceeds something. 
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
        /**
         * Re-route to index if user is logged in
         * However, since logout is in the same file we need to make small exception 
         */
        !Auth::getUser() ?: $this->redirect('/');

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
        View::render('Account/Signup/register.html');
    }

    public function loginAction() 
    {
        View::render('Account/Signup/login.html');
    }

    public function createAction() 
    {
        $user = new User($_POST);

        // ternary operator 
        // If POST is empty redirect to register account, otherwise proceed
        !empty($_POST) ?: $this->redirect('/register/new');

        // If no errors during validation and query executed successfully, proceed with success message
        if($user->save()) {

            $user->sendActivationEmail();

            // TODO: Fix this later with new function for redirect
            $this->redirect('/register/success');

        } else {
            // If some validation failed, include error message array
            View::render('Account/Signup/register.html', [
                'user' => $user
            ]);
        }
    }


    /**
     * Activate a new account
     *
     * @return void
     */
    public function successAction()
    {
        View::render('Account/Signup/success.html'); 
    }

    public function activateAction()
    {
        User::activate($this->route_params['token']);
        $this->redirect('/register/activated');
    }

    /**
     * Show the activation success page
     *
     * @return void
     */
    public function activatedAction()
    {
        View::render('Account/Signup/activated.html');
    }
}
