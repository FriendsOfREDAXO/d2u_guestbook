<?php
namespace D2U_Guestbook;
/**
 * Offers helper functions for backend.
 */
class d2u_guestbook_backend_helper {
	/**
	 * Send mail to admin address when news guestbook entry is created.
	 * @param mixed $yform
	 */
	public static function sendAdminNotification($yform) {
		if(isset($yform->params['values']) && \rex_config::get('d2u_guestbook', 'request_form_email') != "") {
			$fields = [];
			foreach($yform->params['values'] as $value) {
				if($value->name != "") {
					$fields[$value->name] = $value->value;
				}
			}
			
			$mail = new \rex_mailer();
			$mail->IsHTML(FALSE);
			$mail->CharSet = "utf-8";
			$mail->From = \rex_config::get('d2u_guestbook', 'request_form_email');
			$mail->Sender = \rex_config::get('d2u_guestbook', 'request_form_email');

			$mail->AddAddress(\rex_config::get('d2u_guestbook', 'request_form_email'));
			$mail->addReplyTo($fields['email'], $fields['name']);
			$mail->Subject = 'New Guestbook entry - Neuer Gästebuch eintrag - '. (\rex_addon::get('yrewrite') && \rex_addon::get('yrewrite')->isAvailable() ? \rex_yrewrite::getCurrentDomain()->getUrl() : \rex::getServer());

			$mail_body = "Guten Tag,\n\n";
			$mail_body .= $fields['name'] ." hat einen neuen Gästebucheintrag erstellt:\n";
			$mail_body .= $fields['description'] ."\n\n";
			$mail_body .= "Bitte melden sie sich in Redaxo an um den Gästebucheintrag online zu stellen.\n\n";
	
			$mail_body .= "----- ----- ----- ----- ----- ----- ----- -----\n\n";
			$mail_body .= "Hello,\n\n";
			$mail_body .= $fields['name'] ." created a new guestbook entry:\n";
			$mail_body .= $fields['description'] ."\n\n";
			$mail_body .= "Please log in to Redaxo to put the guestbook entry online.\n\n";

			$mail->Body = $mail_body;
			$mail->Send();
		}
	}
}