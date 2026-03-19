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
$moduleId = 'd2u_guestbook_module_60_6_' . $this->getCurrentSlice()->getId(); /** @phpstan-ignore-line */

if ('add' === rex_get('entry', 'string')) {
    // Entry Form
    echo '<div class="col-12">';
    echo '<fieldset><legend>'. $tag_open .'d2u_guestbook_tab_write'. $tag_close .'</legend>';
    $stars = '';
    for ($i = 1; $i <= 5; ++$i) {
        $stars .= '<span class="recommendation-stars"><span class="far fa-star" id="'. $moduleId .'_star'. $i .'" onmouseover="d2uGuestbookSetStars_60_6(\''. $moduleId .'\', '. $i .')" onmouseout="d2uGuestbookResetStars_60_6(\''. $moduleId .'\')" onclick="d2uGuestbookClickStars_60_6(\''. $moduleId .'\', '. $i .')"></span></span> ';
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
    $yform->setObjectparams('form_name', $moduleId);
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
    echo '<a href="'. rex_getUrl(rex_article::getCurrentId(), null, ['entry' => 'add']) .'" class="btn btn-primary">'. $tag_open .'d2u_guestbook_tab_write'. $tag_close .'</a><br><br>';
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
        echo '<div class="col-12 page-selection">'. $tag_open .'d2u_guestbook_page'. $tag_close .': ';
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
