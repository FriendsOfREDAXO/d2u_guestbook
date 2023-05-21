<?php

namespace D2U_Guestbook;

use rex;
use rex_addon;
use rex_config;
use rex_mailer;
use rex_yrewrite;

/**
 * Offers helper functions for backend.
 */
class d2u_guestbook_backend_helper
{
    /**
     * Send mail to admin address when news guestbook entry is created.
     * @param \rex_yform_action_callback $yform
     */
    public static function sendAdminNotification($yform):void
    {
        if (isset($yform->params['values']) && '' !== (string) rex_config::get('d2u_guestbook', 'request_form_email')) {
            $fields = [];
            foreach ($yform->params['values'] as $value) {
                if ('' !== $value->name) {
                    $fields[$value->name] = $value->value;
                }
            }

            $mail = new rex_mailer();
            $mail->isHTML(false);
            $mail->CharSet = 'utf-8';
            $mail->From = (string) rex_config::get('d2u_guestbook', 'request_form_email');
            $mail->Sender = (string)  rex_config::get('d2u_guestbook', 'request_form_email');

            $mail->addAddress((string) rex_config::get('d2u_guestbook', 'request_form_email'));
            $mail->addReplyTo($fields['email'], $fields['name']);
            $mail->Subject = 'New Guestbook entry - Neuer Gästebuch eintrag - '. (rex_addon::get('yrewrite')->isAvailable() ? rex_yrewrite::getCurrentDomain()->getUrl() : rex::getServer());

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
            $mail->send();
        }
    }
}
