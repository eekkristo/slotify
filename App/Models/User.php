<?php

namespace App\Models;

use App\Token;
use App\Mail;
use Core\View;
use PDO;

/**
 * Model class that deals with everything related to user
 * @property array $errors
 * @property $first_name
 * @property $last_name
 * @property $email
 * @property $tos
 *
 */
class User extends \Core\Model 
{

    // TODO:: Implement a global $_POST check. By default we should always escape charset as we do not trust the end user. 
    // Currently only works with the Model where we run this function inside the __construct function. However, it should be posted inside global Model.
    public function __construct($data = [], $escape = true)
    {
        if ($escape) {
            foreach ($data as $key => $value) {
                $this->$key = htmlspecialchars($value);
            }
        }
    }
    /**
     * save the user model with the current property values
     *
     * @return boolean
     */

    public function save(): bool
    {

        $this->validate();

        if(empty($this->errors)) {

            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $token = new Token();
            $hashed_token = $token->getHash();
            $this->activation_token = $token->getValue();

            $sql = 'INSERT INTO users (email, first_name, last_name, password, activation_hash)
                            VALUES (:email, :first_name, :last_name, :password, :activation_hash)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':first_name', $this->first_name, PDO::PARAM_STR);
            $stmt->bindValue(':last_name', $this->last_name, PDO::PARAM_STR);
            $stmt->bindValue(':password', $password_hash, PDO::PARAM_STR);
            $stmt->bindValue(':activation_hash', $hashed_token, PDO::PARAM_STR);

            $stmt->execute();

            return true;
        
    }
    
    return false;
        
    }

    /**
     * Validate the input server side
     * 
     * @return void
     */
    public function validate()
    {


        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Invalid email';
        }

        if(static::emailExists($this->email, $this->id ?? null)) {
            $this->errors[] = 'Email already taken';
        }

        if ($this->first_name == '') {
            $this->errors[] = 'Name must not be empty';
        }

        if ($this->last_name == '') {
            $this->errors[] = 'Last name must not be empty';
        }

        if (strlen($this->password) < 6 ) {
            $this->errors[] = 'Password must be longer than 6 characters';
        }

        if(isset($_POST['register'])) {
            if (!isset($this->tos)) {
                $this->errors[] = 'You need to agree with TOS';
            }
        }

    }

    /**
     * Remember the login by inserting a new unique token into the
     * remembered_logins table for this user records
     *
     * @return bool True if the login was remembered successfully, false otherwise
     */
    public function rememberLogin()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        // get them off class so set them up as properties
        $this->remember_token = $token->getValue();

        $this->expiry_timestamp = time() + 60 * 60 * 24 * 30; // 30 days from now

        $sql = 'INSERT INTO remembered_logins (token_hash, user_id, expires_at)
                    VALUES (:token_hash, :user_id, :expires_at)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expiry_timestamp), PDO::PARAM_STR);

        return $stmt->execute();

    }

    /**
     * Check if user record already exists in the database with that email
     * 
     * @param string $email email address to search for
     * 
     * @return boolean True if email exists in the database, otherwise false
     */
    protected static function emailExists(string $email, $ignore_id = null): bool
    {
        $user = static::findByEmail($email);

        if ($user) {

            if ($user->id !== $ignore_id) {
                return true;
            }
        }

        return false;
    }

    public static function findByEmail($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        
        return $stmt->fetch();

    }

    public static function findByID($id)
    {
        
        $sql = 'SELECT * FROM users WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Authenticate user via email and password
     *
     * @param string $email
     * @param string $password
     * @return bool
     */
    public static function authenticate($email, $password)
    {
        $user = static::findByEmail($email);

        if ($user && $user->is_active) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        } 

        return false; 
    }

    /**
     * Send a password reset link to the user
     *
     * @param string $email The email address of the user
     * 
     * @return void
     */
    public static function resetPassword($email)
    {
        $user = static::findByEmail($email);

        if ($user) {

            if ($user->startPasswordReset()) {

                $user->sendPasswordResetEmail();

            }
        }
    }

    /**
     * Start the password reset process
     *
     * @return void
     */
    protected function startPasswordReset()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->password_reset_token = $token->getValue();

        $expiry_timestamp = time() + 60 * 60 * 2; // 2 hours from now
        
        $sql = 'UPDATE users 
                SET password_reset_hash = :token_hash,
                    password_reset_expires_at = :expires_at
                WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $expiry_timestamp), PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute(); 
    }


    /**
     * Send password reset instructions in an email to the user
     *
     * @return void
     */
    protected function sendPasswordResetEmail()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/password/reset/' . $this->password_reset_token;

        $text = View::getTemplate('Account/Password/reset_email.txt', ['url' => $url]);
        $html = View::getTemplate('Account/Password/reset_email.html', ['url' => $url]);

        $host = $_SERVER['HTTP_HOST'];

        Mail::send($this->email, "Password reset from $host", $text, $html);
    }

    /**
     * Find a user model by password reset token and expiry
     *
     * @param string $token Password reset token sent to user
     *
     * @return mixed User object if found and the token hasn't expired, null otherwise
     */
    public static function findByPasswordReset($token)
    {
        $token = new Token($token);
        $hashed_token = $token->getHash();

        $sql = 'SELECT * FROM users 
                WHERE password_reset_hash = :token_hash';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $user = $stmt->fetch();

        if ($user) {
             
            if (strtotime($user->password_reset_expires_at) > time()) {
                return $user;
            }
        }
    }

    public function resetUserPassword($password)
    {
        $this->password = $password;

        $this->validate();

        if (empty($this->errors)) {

            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = 'UPDATE users
                    SET password = :password_hash,
                        password_reset_hash = NULL,
                        password_reset_expires_at = NULL
                    WHERE id = :id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

            return $stmt->execute();

        }

        return false;
    }


    /**
     * Send email to the user containing the activation link
     *
     * @return mixed
     */
    public function sendActivationEmail()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/register/activate/' . $this->activation_token;

        $text = View::getTemplate('Account/Signup/activation_email.txt', ['url' => $url]);
        $html = View::getTemplate('Account/Signup/activation_email.html', ['url' => $url]);

        $host = $_SERVER['HTTP_HOST'];

        Mail::send($this->email, "Account activation from $host", $text, $html);
    }

    public static function activate($value)
    {
        $token = new Token($value);
        $hashed_token = $token->getHash();

        $sql = 'UPDATE users 
                SET is_active = 1,
                   activation_hash = null
                WHERE activation_hash = :hashed_token';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':hashed_token', $hashed_token, PDO::PARAM_STR);

        $stmt->execute();
    }
}
