<?php

namespace App;

use Mailgun\Mailgun;
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * E-mail class handling outgoing emails
 * 
 * PHP version 8.0
 */
class Mail
{

    /**
     * Send a message
     *
     * @param string $to Recipient
     * @param string $subject Subject
     * @param string $text Text only content of the message
     * @param string $html Html only content of the message
     * @return void
     */
    public static function send($to, $subject, $text, $html)
    {
        (new Mail)->email([
            'recipient' => $to,
            'subject' => $subject,
            'messageText' => $text,
            'messageHTML' => $html
        ]);
    }

    public function email($data = [])
    {

        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        if (Config::MAIL == 'Mailgun') {
            // First, instantiate the SDK with your API credentials
            $mg = Mailgun::create(CONFIG::MAILGUN_API); // For EU servers

            // Now, compose and send your message.
            // $mg->messages()->send($domain, $params);
            $mg->messages()->send(CONFIG::MAILGUN_DOMAIN, [
                'from'    => CONFIG::MAILGUN_FROM,
                'to'      => $this->recipient,
                'subject' => $this->subject,
                'text'    => $this->messageText,
                'html'    => $this->messageHTML
            ]);
        } elseif (Config::MAIL == 'PHPMailer') {
            //Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = CONFIG::SMTP_HOST;                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = CONFIG::SMTP_USERNAME;                     //SMTP username
                $mail->Password   = CONFIG::SMTP_PASSWORD;                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = CONFIG::SMTP_PORT;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom(CONFIG::SMTP_FROM, 'Mailer');
                $mail->addAddress($this->recipient);     //Add a recipient//Name is optional


                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = $this->subject;
                $mail->Body    = $this->messageHTML;
                $mail->AltBody = $this->messageText;

                $mail->send();
                //echo 'Message has been sent';
            } catch (Exception $e) {
                //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Error! config parameter Config::MAIL missing parameter or is invalid: " . Config::MAIL;
        }

    }
}