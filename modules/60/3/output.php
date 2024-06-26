<?php

use Sprog\Wildcard;

if (!function_exists('sendAdminNotification')) {
    /**
     * Send mail to admin address when news guestbook entry is created.
     * @param \rex_yform_action_callback $yform
     */
    function sendAdminNotification($yform):void
    {
        \FriendsOfRedaxo\D2UGuestbook\BackendHelper::sendAdminNotification($yform);
    }
}

// Get placeholder wildcard tags and other presets
$tag_open = Wildcard::getOpenTag();
$tag_close = Wildcard::getCloseTag();

if ('add' === rex_get('entry', 'string')) {
    // Entry Form
    echo '<div class="col-12">';
    echo '<fieldset><legend>'. $tag_open .'d2u_guestbook_tab_write'. $tag_close .'</legend>';
    ?>
	<script>
		function d2u_guestbook_module_60_3_set_stars(wert) {
			for(var x = 1; x <= 5; x++) {
				if(x <= wert) {
					if($('#d2u_guestbook_module_60_3_star' + x).hasClass('star-empty')) {
						$('#d2u_guestbook_module_60_3_star' + x).removeClass('star-empty');
						$('#d2u_guestbook_module_60_3_star' + x).addClass('star-full');
					}
				}
				else {
					if($('#d2u_guestbook_module_60_3_star' + x).hasClass('star-full')) {
						$('#d2u_guestbook_module_60_3_star' + x).removeClass('star-full');
						$('#d2u_guestbook_module_60_3_star' + x).addClass('star-empty');
					}
				}
			}
		}
		function d2u_guestbook_module_60_3_reset_stars(wert) {
			d2u_guestbook_module_60_3_set_stars($('input[name=rating]').val());
		}
		function d2u_guestbook_module_60_3_click_stars(wert) {
			$('input[name=rating]').val(wert);
			d2u_guestbook_module_60_3_set_stars(wert);
		}
	</script>
	<?php
    $stars = '';
    for ($i = 1; $i <= 5; ++$i) {
        $stars .= '<span class="icon star-empty" id="d2u_guestbook_module_60_3_star'. $i.'" onmouseover="d2u_guestbook_module_60_3_set_stars('. $i.')" onmouseout="d2u_guestbook_module_60_3_reset_stars('. $i.')" onclick="d2u_guestbook_module_60_3_click_stars('. $i.')"></span> ';
    }
    $form_data = '
		text|name|'. $tag_open .'d2u_guestbook_form_name'. $tag_close .' *
		email|email|'. $tag_open .'d2u_guestbook_form_email'. $tag_close .'
		html|honeypot||<div class="hide-validation">
		text|mailvalidate|'. $tag_open .'d2u_guestbook_form_email'. $tag_close .'||no_db
		validate|compare_value|mailvalidate||!=|'. $tag_open .'d2u_guestbook_form_validate_spam_detected'. $tag_close .'|
		html|honeypot||</div>
		textarea|description|'. $tag_open .'d2u_guestbook_form_message'. $tag_close .'
		choice|recommendation|'. $tag_open .'d2u_guestbook_form_recommendation'. $tag_close .'|{"'. $tag_open .'d2u_guestbook_no'. $tag_close .'":"0","'. $tag_open .'d2u_guestbook_yes'. $tag_close .'":"1"}|1|0|
		checkbox|privacy_policy_accepted|'. $tag_open .'d2u_guestbook_form_privacy_policy'. $tag_close . ' *|0,1|0
		text|rating|'. $tag_open .'d2u_guestbook_form_rating'. $tag_close .'   '. $stars.'|0||{"style":"display:none"}
		html||<br>* '. $tag_open .'d2u_guestbook_form_required'. $tag_close .'<br><br>
		php|validate_timer|Spamprotection|<input name="validate_timer" type="hidden" value="'. microtime(true) .'" />|
		hidden|online_status|offline
		hidden|create_date|'. date('Y-m-d H:i:s') .'
		hidden|clang_id|'. rex_clang::getCurrentId() .'

		submit|submit|'. $tag_open .'d2u_guestbook_form_send'. $tag_close .'|no_db

		validate|empty|name|'. $tag_open .'d2u_guestbook_form_validate_name'. $tag_close .'
		validate|empty|description|'. $tag_open .'d2u_guestbook_form_validate_description'. $tag_close .'
		validate|empty|privacy_policy_accepted|'. $tag_open .'d2u_guestbook_form_validate_privacy_policy'. $tag_close .'
		validate|customfunction|validate_timer|TobiasKrais\D2UHelper\FrontendHelper::yform_validate_timer|5|'. $tag_open .'d2u_guestbook_form_validate_spambots'. $tag_close .'|

		action|callback|sendAdminNotification
		action|db|'. rex::getTablePrefix() .'d2u_guestbook|';

    $yform = new rex_yform();
    $yform->setFormData(trim($form_data));
    $yform->setObjectparams('Error-occured', $tag_open .'d2u_guestbook_form_validate_title'. $tag_close);
    $yform->setObjectparams('form_action', rex_getUrl(rex_article::getCurrentId(), null, ['entry' => 'add']));
    $yform->setObjectparams('form_name', 'd2u_guestbook_module_60_3_'. $this->getCurrentSlice()->getId()); /** @phpstan-ignore-line */
    $yform->setObjectparams('real_field_names', true);

    // action - showtext
    $yform->setActionField('showtext', [$tag_open .'d2u_guestbook_form_thanks'. $tag_close]);

    echo $yform->getForm();
    echo '</fieldset>';
    echo '</div>';
    // End request form
} else {
    $entries = FriendsOfRedaxo\D2UGuestbook\Entry::getAll(true);
    $page_no = 0;
    // Add entry button
    echo '<div class="col-12">';
    if (0 === count($entries)) {
        echo '<p>'. \Sprog\Wildcard::get('d2u_guestbook_no_entries') . '</p>';
    }
    echo '<a href="'. rex_getUrl(rex_article::getCurrentId(), null, ['entry' => 'add']) .'"><button class="btn btn-primary">'. $tag_open .'d2u_guestbook_tab_write'. $tag_close .'</button></a><br><br>';
    echo '</div>';

    // Entries
    echo '<div class="col-12">';
    if (count($entries) > 0) {
        for ($i = 0; $i < count($entries); ++$i) {
            $entry = $entries[$i];

            if (0 === $i % (int) rex_config::get('d2u_guestbook', 'no_entries_page', 10)) {
                ++$page_no;
                if ($page_no > 1) {
                    echo '</div>';
                }
                echo '<div class="row guestbook-page pages-'. $page_no .'">'; // Pagination div
            }

            echo '<div class="col-12">';
            echo '<div class="entry-header">';
            echo '<div class="row">';
            echo '<div class="col-6 left"><b>'. $tag_open .'d2u_guestbook_form_name'. $tag_close .': ';
            if ('' !== $entry->email && 'true' === (string) rex_config::get('d2u_guestbook', 'allow_answer', 'false')) {
                echo '<a href="mailto:'. $entry->email .'">';
                echo $entry->name .' <span class="icon mail"></span>';
                echo '</a>';
            } else {
                echo $entry->name;
            }
            echo '</b></div>';
            $time = strtotime($entry->create_date);
            if(false !== $time) {
                echo '<div class="col-6 right">'. date('d.m.Y H:i', $time) .' '. $tag_open .'d2u_guestbook_oclock'. $tag_close .'</div>';
            }
            echo '</div>';
            echo '</div>';

            echo '<div class="entry-body">';
            echo '<div class="row">';
            echo '<div class="col-12">'. nl2br($entry->description) .'</div>';
            if ($entry->rating > 0) {
                echo '<div class="col-12"><b>'. $tag_open .'d2u_guestbook_rating'. $tag_close .': ';
                for ($j = 1; $j <= 5; ++$j) {
                    if ($j <= $entry->rating) {
                        echo ' <span class="icon star-full"></span>';
                    } else {
                        echo ' <span class="icon star-empty"></span>';
                    }
                }
                echo '</b></div>';
            }
            echo '</div>';
            echo '</div>';

            echo '</div>';
        }
    }
    echo '</div>'; // End pagination
    // Page selection
    if ($page_no > 1) {
        echo "<script>
				// show only first page
				jQuery(document).ready(function($) {
					$('.guestbook-page').hide();
					$('.pages-1').show();
				});
				// hide pages and show selected page
				function changePage(pageno) {
					$('.guestbook-page').slideUp();
					$('.active-page').removeClass('active-page');
					$('.pages-' + pageno).slideDown();
					$('#page-' + pageno).addClass('active-page');
				}
			</script>";
        echo '<div class="row">';
        echo '<div class="col-12 page-selection">'. $tag_open .'d2u_guestbook_page'. $tag_close .': ';
        for ($i = 1; $i <= $page_no; ++$i) {
            echo '<a href="javascript:changePage('. $i .')" class="page'. (1 === $i ? ' active-page' : '') .'" id="page-'. $i .'">'. $i .'</a>';
        }
        echo '</div>';
        echo '</div>';
    }
}
?>
