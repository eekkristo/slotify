<?php

namespace App\Controllers;

use Core\View;
use App\Models\User;
use App\Token;
use App\Flash;

/**
 * Password controller
 *
 * PHP version 8.0
 */
class Password extends \Core\Controller
{

    /**
     * Show the forgotten password page
     *
     * @return void
     */
    public function forgotAction()
    {

        View::render('Account/Password/forgot.html');
    }

    /**
     * POST request to generate reset password
     *
     * @return void
     */
    public function requestResetAction()
    {

        //Flash::addMessage('Email can\'t be empty!', Flash::DANGER );
        // ternary operator 
        // If POST is empty redirect to register account, otherwise proceed
        empty(($_POST)) ? $this->redirect('/password/forgot'): '';



        User::resetPassword($_POST['email']);

        View::render('Account/Password/reset_requested.html');
    }

    /**
     * Password reset unique url
     *
     * @return void
     */
    public function resetAction()
    {

        $token = $this->route_params['token'];

        $user = $this->getUserOrExit($token);

        View::render('Account/Password/reset.html', [
            'token' => $token,
        ]);

    }


    public function resetPasswordAction()
    {

        // ternary operator
        // If POST is empty redirect to register account, otherwise proceed
        empty($_POST) ? $this->redirect('/') : null;

        $token = $_POST['token'];

        $user = $this->getUserOrExit($token);
        if ($user->resetUserPassword($_POST['password'])) {

            View::render('Account/Password/reset_success.html');

        } else {
            View::render('Account/Password/reset.html', [
                'token' => $token,
                'user' => $user,

            ]);
        }
    }


    /**
     * Find the user model associated with the password reset token, or end the request with a message
     *
     * @param string
     *
     * @return mixed User object if found and the token hasn't expired, null otherwise
     */
    protected function getUserOrExit($token): mixed
    {
        $user = User::findByPasswordReset($token);

        if ($user) {
            return $user;
        } else {
            View::render('Account/Password/token_expired.html');
            exit;
        }
    }
}