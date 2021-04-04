<?php

namespace App\Http\Controllers\Payment\Course;

use App\Http\Controllers\Controller;
use App\Language;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPMailer\PHPMailer\PHPMailer;

class MailController extends Controller
{
	public static function sendMail($file_name)
	{
		if (session()->has('lang')) {
			$currentLang = Language::where('code', session()->get('lang'))->first();
		} else {
			$currentLang = Language::where('is_default', 1)->first();
		}
		
		// bse = basic settings extended
		$bse = $currentLang->basic_extended;
		
		$mail = new PHPMailer(true);
		$user = Auth::user();

		if ($bse->is_smtp == 1) {
			try {
				$mail->isSMTP();
				$mail->SMTPAuth   = true;
				$mail->Host       = $bse->smtp_host;
				$mail->Port       = $bse->smtp_port;
				$mail->SMTPSecure = $bse->encryption;
				$mail->Username   = $bse->smtp_username;
				$mail->Password   = $bse->smtp_password;

				// sender
				$mail->setFrom($bse->from_mail, $bse->from_name);
				// recipient
				$mail->addAddress($user->email, $user->fname);

				// attachment
				$mail->addAttachment('assets/front/invoices/course/' . $file_name);

				// content
				$mail->isHTML(true);
				$mail->Subject = 'Purchase Course';
				$mail->Body = 'Hello <strong>' . $user->fname . '</strong>,<br/>Your order has been placed successfully. We have attached an invoice in this mail.<br/>Thank you.';

				$mail->send();
			} catch (Exception $e) {
				return back()->with('error', $e->getMessage());
			}
		} else {
			try {
				// sender
				$mail->setFrom($bse->from_mail, $bse->from_name);
				// recipient
				$mail->addAddress($user->email, $user->fname);

				// content
				$mail->isHTML(true);
				$mail->Subject = 'Purchase Course';
				$mail->Body = 'Hello <strong>' . $user->fname . '</strong>,<br/>Your order has been placed successfully. We have attached an invoice in this mail.<br/>Thank you.';

				$mail->send();
			} catch (Exception $e) {
				return back()->with('error', $e->getMessage());
			}
		}
	}
}
