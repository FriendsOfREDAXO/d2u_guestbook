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
$moduleId = 'd2u_guestbook_module_60_6_' . $this->getCurrentSlice()->getId(); /** @phpstan-ignore-line */

if ('add' === rex_get('entry', 'string')) {
    // Entry Form
    echo '<div class="col-12">';
    echo '<fieldset><legend>'. Wildcard::get('d2u_guestbook_tab_write') .'</legend>';
    $stars = '';
    for ($i = 1; $i <= 5; ++$i) {
        $stars .= '<span class="recommendation-stars"><span class="far fa-star" id="'. $moduleId .'_star'. $i .'" onmouseover="d2uGuestbookSetStars_60_6(\''. $moduleId .'\', '. $i .')" onmouseout="d2uGuestbookResetStars_60_6(\''. $moduleId .'\')" onclick="d2uGuestbookClickStars_60_6(\''. $moduleId .'\', '. $i .')"></span></span> ';
    }
    $form_data = '
		text|name|'. Wildcard::get('d2u_guestbook_form_name') .' *
		email|email|'. Wildcard::get('d2u_guestbook_form_email') .'
		html|honeypot||<div class="hide-validation">
		text|mailvalidate|'. Wildcard::get('d2u_guestbook_form_email') .'||no_db
		validate|compare_value|mailvalidate||!=|'. Wildcard::get('d2u_guestbook_form_validate_spam_detected') .'|
		html|honeypot||</div>
		textarea|description|'. Wildcard::get('d2u_guestbook_form_message') .'
		choice|recommendation|'. Wildcard::get('d2u_guestbook_form_recommendation') .'|{"'. Wildcard::get('d2u_guestbook_no') .'":"0","'. Wildcard::get('d2u_guestbook_yes') .'":"1"}|1|0|
		checkbox|privacy_policy_accepted|'. Wildcard::get('d2u_guestbook_form_privacy_policy') . ' *|0,1|0
		text|rating|'. Wildcard::get('d2u_guestbook_form_rating') .'   '. $stars.'|0||{"style":"display:none"}
		html||<br>* '. Wildcard::get('d2u_guestbook_form_required') .'<br><br>
		php|validate_timer|Spamprotection|<input name="validate_timer" type="hidden" value="'. microtime(true) .'" />|
		hidden|online_status|offline
		hidden|create_date|'. date('Y-m-d H:i:s') .'
		hidden|clang_id|'. rex_clang::getCurrentId() .'

		submit|submit|'. Wildcard::get('d2u_guestbook_form_send') .'|no_db

		validate|empty|name|'. Wildcard::get('d2u_guestbook_form_validate_name') .'
		validate|empty|description|'. Wildcard::get('d2u_guestbook_form_validate_description') .'
		validate|empty|privacy_policy_accepted|'. Wildcard::get('d2u_guestbook_form_validate_privacy_policy') .'
		validate|customfunction|validate_timer|TobiasKrais\D2UHelper\FrontendHelper::yform_validate_timer|5|'. Wildcard::get('d2u_guestbook_form_validate_spambots') .'|

		action|callback|sendAdminNotification
		action|db|'. rex::getTablePrefix() .'d2u_guestbook|';

    $yform = new rex_yform();
    $yform->setFormData(trim($form_data));
    $yform->setObjectparams('Error-occured', Wildcard::get('d2u_guestbook_form_validate_title'));
    $yform->setObjectparams('form_action', rex_getUrl(rex_article::getCurrentId(), null, ['entry' => 'add']));
    $yform->setObjectparams('form_name', $moduleId);
    $yform->setObjectparams('real_field_names', true);

    // action - showtext
    $yform->setActionField('showtext', [Wildcard::get('d2u_guestbook_form_thanks')]);

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
    echo '<a href="'. rex_getUrl(rex_article::getCurrentId(), null, ['entry' => 'add']) .'" class="btn btn-primary">'. Wildcard::get('d2u_guestbook_tab_write') .'</a><br><br>';
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
                echo '<div class="row guestbook-page '. $moduleId .'-page" data-page="'. $page_no .'">';
            }

            echo '<div class="col-12">';
            echo '<div class="entry-header">';
            echo '<div class="row">';
            echo '<div class="col-6 left"><b>'. Wildcard::get('d2u_guestbook_form_name') .': ';
            if ('' !== $entry->email && 'true' === (string) rex_config::get('d2u_guestbook', 'allow_answer', 'false')) {
                echo '<a href="mailto:'. rex_escape($entry->email) .'">';
                echo rex_escape($entry->name) .' <span class="icon mail"></span>';
                echo '</a>';
            } else {
                echo rex_escape($entry->name);
            }
            echo '</b></div>';
            $time = strtotime($entry->create_date);
            if(false !== $time) {
                echo '<div class="col-6 right">'. date('d.m.Y H:i', $time) .' '. Wildcard::get('d2u_guestbook_oclock') .'</div>';
            }
            echo '</div>';
            echo '</div>';

            echo '<div class="entry-body">';
            echo '<div class="row">';
            echo '<div class="col-12">'. nl2br(rex_escape((string) $entry->description)) .'</div>';
            if ($entry->rating > 0) {
                echo '<div class="col-12"><b>'. Wildcard::get('d2u_guestbook_rating') .': ';
                for ($j = 1; $j <= 5; ++$j) {
                    if ($j <= $entry->rating) {
                        echo ' <span class="fas fa-star"></span>';
                    } else {
                        echo ' <span class="far fa-star"></span>';
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
        echo '<div class="row">';
        echo '<div class="col-12 page-selection">'. Wildcard::get('d2u_guestbook_page') .': ';
        for ($i = 1; $i <= $page_no; ++$i) {
            echo '<a href="#" class="page'. (1 === $i ? ' active-page' : '') .'" data-page-target="'. $i .'">'. $i .'</a>';
        }
        echo '</div>';
        echo '</div>';
    }
}
?>
<script>
function d2uGuestbookSetStars_60_6(moduleId, value) {
    for (var index = 1; index <= 5; index++) {
        var star = document.getElementById(moduleId + '_star' + index);
        if (!star) {
            continue;
        }
        star.classList.toggle('fas', index <= value);
        star.classList.toggle('far', index > value);
    }
}

function d2uGuestbookResetStars_60_6(moduleId) {
    var form = document.querySelector('form[name="' + moduleId + '"]');
    if (!form) {
        return;
    }
    var ratingInput = form.querySelector('input[name="rating"]');
    d2uGuestbookSetStars_60_6(moduleId, ratingInput ? parseInt(ratingInput.value || '0', 10) : 0);
}

function d2uGuestbookClickStars_60_6(moduleId, value) {
    var form = document.querySelector('form[name="' + moduleId + '"]');
    if (!form) {
        return;
    }
    var ratingInput = form.querySelector('input[name="rating"]');
    if (ratingInput) {
        ratingInput.value = value;
    }
    d2uGuestbookSetStars_60_6(moduleId, value);
}

document.addEventListener('DOMContentLoaded', function() {
    var pages = document.querySelectorAll('.<?= $moduleId ?>-page');
    var pageLinks = document.querySelectorAll('[data-page-target]');

    function showPage(pageNumber) {
        pages.forEach(function(page) {
            page.style.display = page.getAttribute('data-page') === String(pageNumber) ? '' : 'none';
        });
        pageLinks.forEach(function(link) {
            link.classList.toggle('active-page', link.getAttribute('data-page-target') === String(pageNumber));
        });
    }

    if (pages.length > 1) {
        showPage(1);
        pageLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                showPage(link.getAttribute('data-page-target'));
            });
        });
    }

    d2uGuestbookResetStars_60_6('<?= $moduleId ?>');
});
</script>
