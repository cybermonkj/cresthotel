<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Carbon\Carbon;

class SubscriptionReminderMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $as;
    public $be;
    public $bex;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($be, $bex, $as)
    {
        $this->be = $be;
        $this->bex = $bex;
        $this->as = $as;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $be = $this->be;
        $bex = $this->bex;
        $as = $this->as;

        $mail = new PHPMailer(true);

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
            } catch (Exception $e) {
                // die($e->getMessage());
            }
        }

        //Recipients
        $mail->setFrom($be->from_mail, $be->from_name);
        $mail->addAddress($as->email, $as->name);     // Add a recipient

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = "Reminder of Subscription Expiry";
        $mail->Body    = "Hello $as->name,<br><br>Your subscription is about to expire.<br>You have only <strong>$bex->expiration_reminder days</strong> remaining.<br>Please extend your current package / change to new one.<br><strong>Current Package:</strong> " . $as->current_package->title . "<br><strong>Expire Date: </strong>" . Carbon::parse($as->expire_date)->toFormattedDateString() . "<br><br>Thank you.";
        $mail->send();
    }
}
