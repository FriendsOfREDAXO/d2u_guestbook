<?php
$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 12 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 12 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg_cols = (int) 'REX_VALUE[17]';
$offset_lg = '';
if ($offset_lg_cols > 0) { /** @phpstan-ignore-line */
    $offset_lg = ' mr-lg-auto ml-lg-auto ';
}

echo '<div id="d2u_guestbook_module_60_1" class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';
echo '<div class="row">';

$hide_rating = 'REX_VALUE[1]' === 'true' ? true : false; /** @phpstan-ignore-line */

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

// Tabs
echo '<div class="col-12 d-print-none">';
echo '<ul class="nav nav-pills" id="guestbook_tabs">';
echo '<li class="nav-item"><a data-toggle="tab" href="#tab_guestbook" class="nav-link active">'. \Sprog\Wildcard::get('d2u_guestbook_tab_title') .'</a></li>';
echo '<li class="nav-item"><a data-toggle="tab" href="#tab_write" class="nav-link">'. \Sprog\Wildcard::get('d2u_guestbook_tab_write') .'</a></li>';
echo '</ul>';
echo '</div>';

echo '<div class="col-12">';
echo '<div class="tab-content">';
echo '<div id="tab_guestbook" class="tab-pane fade active show guestbook-tab">';

// Entries
$entries = \FriendsOfRedaxo\D2UGuestbook\Entry::getAll(true);
$page_no = 0;

if (0 === count($entries)) {
    echo '<p>'. \Sprog\Wildcard::get('d2u_guestbook_no_entries') . '</p>';
} else {
    for ($i = 0; $i < count($entries); ++$i) {
        $entry = $entries[$i];
        if (0 === $i % (int) rex_config::get('d2u_guestbook', 'no_entries_page', 10)) {
            ++$page_no;
            if (1 !== $page_no) {
                echo '</div>';
            }
            echo '<div class="row guestbook-page pages-'. $page_no .'">'; // Pagination div
        }

        echo '<div class="col-12">';

        echo '<div class="entry-header">';
        echo '<div class="row">';
        echo '<div class="col-6"><b>';
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
        	echo '<div class="col-6 right">'. date('d.m.Y H:i', $time) .' '. \Sprog\Wildcard::get('d2u_guestbook_oclock') .'</div>';
		}
        echo '</div>';
        echo '</div>'; // entry-header

        echo '<div class="entry-body">';
        echo '<div class="row">';
        echo '<div class="col-12">'. nl2br($entry->description) .'</div>';
        if (!$hide_rating && $entry->rating > 0) { /** @phpstan-ignore-line */
            echo '<div class="col-12 recommendation-stars"><b>'. \Sprog\Wildcard::get('d2u_guestbook_rating') .': ';
            for ($j = 1; $j <= 5; ++$j) {
                if ($j <= $entry->rating) {
                    echo ' <span class="fas fa-star"></span>';
                }
            }
            echo '</b></div>';
        }
        echo '</div>'; // row
        echo '</div>'; // entry-body

        echo '</div>'; // col-12
    }
	if($page_no > 0) {
		echo '</div>'; // row guestbook-page
		if($page_no > 1) {
			// Page selection
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
			echo '<div class="col-12 page-selection">'. \Sprog\Wildcard::get('d2u_guestbook_page') .': ';
			for ($i = 1; $i <= $page_no; ++$i) {
				echo '<a href="javascript:changePage('. $i .')" class="page'. (1 === $i ? ' active-page' : '') .'" id="page-'. $i .'">'. $i .'</a>';
			}
			echo '</div>';
			echo '</div>';
		}
	}
}
echo '</div>'; // tab_guestbook

// Entry Form
echo '<div id="tab_write" class="tab-pane fade guestbook-tab">';
echo '<div class="row">';
echo '<div class="col-12">';
echo '<fieldset><legend>'. \Sprog\Wildcard::get('d2u_guestbook_tab_write') .'</legend>';
?>
<script>
	function d2u_guestbook_module_60_1_set_stars(wert) {
		for(var x = 1; x <= 5; x++) {
			if(x <= wert) {
				if($('#d2u_guestbook_module_60_1_star' + x).hasClass('far')) {
					$('#d2u_guestbook_module_60_1_star' + x).removeClass('far');
					$('#d2u_guestbook_module_60_1_star' + x).addClass('fas');
				}
			}
			else {
				if($('#d2u_guestbook_module_60_1_star' + x).hasClass('fas')) {
					$('#d2u_guestbook_module_60_1_star' + x).removeClass('fas');
					$('#d2u_guestbook_module_60_1_star' + x).addClass('far');
				}
			}
		}
	}
	function d2u_guestbook_module_60_1_reset_stars(wert) {
		d2u_guestbook_module_60_1_set_stars($('input[name=rating]').val());
	}
	function d2u_guestbook_module_60_1_click_stars(wert) {
		$('input[name=rating]').val(wert);
		d2u_guestbook_module_60_1_set_stars(wert);
	}
</script>
<?php
$stars = '';
for ($i = 1; $i <= 5; ++$i) {
    $stars .= '<span class="recommendation-stars"><span class="far fa-star" id="d2u_guestbook_module_60_1_star'. $i.'" onmouseover="d2u_guestbook_module_60_1_set_stars('. $i.')" onmouseout="d2u_guestbook_module_60_1_reset_stars('. $i.')" onclick="d2u_guestbook_module_60_1_click_stars('. $i.')"></span></span> ';
}
$form_data = '
	text|name|'. \Sprog\Wildcard::get('d2u_guestbook_form_name') .' *
	email|email|'. \Sprog\Wildcard::get('d2u_guestbook_form_email') .'
	html|honeypot||<div class="hide-validation">
	text|mailvalidate|'. \Sprog\Wildcard::get('d2u_guestbook_form_email') .'||no_db
	validate|compare_value|mailvalidate||!=|'. \Sprog\Wildcard::get('d2u_guestbook_form_validate_spam_detected') .'|
    html|honeypot||</div>
	textarea|description|'. \Sprog\Wildcard::get('d2u_guestbook_form_message') .'
	choice|recommendation|'. \Sprog\Wildcard::get('d2u_guestbook_form_recommendation') .'|{"'. \Sprog\Wildcard::get('d2u_guestbook_no') .'":"0","'. \Sprog\Wildcard::get('d2u_guestbook_yes') .'":"1"}|1|0|
	checkbox|privacy_policy_accepted|'. \Sprog\Wildcard::get('d2u_guestbook_form_privacy_policy') . ' *|0,1|0
	'. ($hide_rating ? 'hidden|rating|0' : 'text|rating|'. \Sprog\Wildcard::get('d2u_guestbook_form_rating') .'   '. $stars.'|0||{"style":"display:none"}')  /** @phpstan-ignore-line */ .'
	html||<br>* '. \Sprog\Wildcard::get('d2u_guestbook_form_required') .'<br><br>
	php|validate_timer|Spamprotection|<input name="validate_timer" type="hidden" value="'. microtime(true) .'" />|
	hidden|online_status|offline
	hidden|create_date|'. date('Y-m-d H:i:s') .'
	hidden|clang_id|'. rex_clang::getCurrentId() .'

	submit|submit|'. \Sprog\Wildcard::get('d2u_guestbook_form_send') .'|no_db

	validate|empty|name|'. \Sprog\Wildcard::get('d2u_guestbook_form_validate_name') .'
	validate|empty|description|'. \Sprog\Wildcard::get('d2u_guestbook_form_validate_description') .'
	validate|empty|privacy_policy_accepted|'. \Sprog\Wildcard::get('d2u_guestbook_form_validate_privacy_policy') .'
	validate|customfunction|validate_timer|TobiasKrais\D2UHelper\FrontendHelper::yform_validate_timer|5|'. \Sprog\Wildcard::get('d2u_guestbook_form_validate_spambots') .'|

	action|callback|sendAdminNotification
	action|db|'. rex::getTablePrefix() .'d2u_guestbook|';

$yform = new rex_yform();
$yform->setFormData(trim($form_data));
$yform->setObjectparams('Error-occured', \Sprog\Wildcard::get('d2u_guestbook_form_validate_title'));
$yform->setObjectparams('form_action', rex_getUrl(rex_article::getCurrentId()));
$yform->setObjectparams('form_anchor', 'tab_write');
$yform->setObjectparams('form_name', 'd2u_guestbook_module_60_1_'. $this->getCurrentSlice()->getId()); /** @phpstan-ignore-line */
$yform->setObjectparams('real_field_names', true);

// action - showtext
$yform->setActionField('showtext', [\Sprog\Wildcard::get('d2u_guestbook_form_thanks')]);

echo $yform->getForm();
echo '</fieldset>';
echo '</div>'; // col-12
echo '</div>'; // row
// End request form
echo '</div>'; // tab_write

echo '</div>'; // tab_content

echo '</div>'; // col-12
echo '</div>'; // row
echo '</div>'; // d2u_guestbook_module_60_1
?>
<script>
	// Allow activation of bootstrap tab via URL
	$(function() {
		var hash = window.location.hash;
		hash && $('ul.nav a[href="' + hash + '"]').tab('show');
	});

	// set stars on failure page correctly
	d2u_guestbook_module_60_1_set_stars($('input[name=rating]').val());
</script>