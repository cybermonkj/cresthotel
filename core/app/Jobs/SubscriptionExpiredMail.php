<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class SubscriptionExpiredMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $be;
    public $email;
    public $name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($be, $email, $name)
    {
        $this->be = $be;
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Send Mail to Buyer
        $mail = new PHPMailer(true);
        $be = $this->be;
        $email = $this->email;
        $name = $this->name;

        if ($be->is_smtp == 1) {
            try {
                //Server settings
                // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
                $mail->isSMTP();                                            // Send using SMTP
                $mail->Host       = $be->smtp_host;                    // Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                $mail->Username   = $be->smtp_username;                     // SMTP username
                $mail->Password   = $be->smtp_password;                               // SMTP password
                $mail->SMTPSecure = $be->encryption;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $mail->Port       = $be->smtp_port;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                //Recipients
                $mail->setFrom($be->from_mail, $be->from_name);
                $mail->addAddress($email, $name);     // Add a recipient

                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = "Subscription Expired";
                $mail->Body    = 'Hello <strong>' . $name . '</strong>,<br/>Your subscription is expired. Please extend the current package or purchase a new one.<br/>Thank you.';

                $mail->send();
            } catch (Exception $e) {
                // die($e->getMessage());
            }
        } else {
            try {

                //Recipients
                $mail->setFrom($be->from_mail, $be->from_name);
                $mail->addAddress($email, $name);     // Add a recipient

                // Content
                $mail->isHTML(true);   // Set email format to HTML
                $mail->Subject = "Subscription Expired";
                $mail->Body    = 'Hello <strong>' . $name . '</strong>,<br/>Your subscription is expired. Please extend the current package or purchase a new one.<br/>Thank you.';

                $mail->send();
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
    }
}
