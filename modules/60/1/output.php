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
        \FriendsOfREDAXO\D2UGuestbook\BackendHelper::sendAdminNotification($yform);
    }
}

// Get placeholder wildcard tags and other presets
$sprog = rex_addon::get('sprog');
$tag_open = $sprog->getConfig('wildcard_open_tag');
$tag_close = $sprog->getConfig('wildcard_close_tag');

// Tabs
echo '<div class="col-12 d-print-none">';
echo '<ul class="nav nav-pills" id="guestbook_tabs">';
echo '<li class="nav-item"><a data-toggle="tab" href="#tab_guestbook" class="nav-link active">'. $tag_open .'d2u_guestbook_tab_title'. $tag_close .'</a></li>';
echo '<li class="nav-item"><a data-toggle="tab" href="#tab_write" class="nav-link">'. $tag_open .'d2u_guestbook_tab_write'. $tag_close .'</a></li>';
echo '</ul>';
echo '</div>';

echo '<div class="col-12">';
echo '<div class="tab-content">';
echo '<div id="tab_guestbook" class="tab-pane fade active show guestbook-tab">';

// Entries
$entries = \FriendsOfREDAXO\D2UGuestbook\Entry::getAll(true);
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
        echo '</div>'; // entry-header

        echo '<div class="entry-body">';
        echo '<div class="row">';
        echo '<div class="col-12">'. nl2br($entry->description) .'</div>';
        if (!$hide_rating && $entry->rating > 0) { /** @phpstan-ignore-line */
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
			echo '<div class="col-12 page-selection">'. $tag_open .'d2u_guestbook_page'. $tag_close .': ';
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
echo '<fieldset><legend>'. $tag_open .'d2u_guestbook_tab_write'. $tag_close .'</legend>';
?>
<script>
	function d2u_guestbook_module_60_1_set_stars(wert) {
		for(var x = 1; x <= 5; x++) {
			if(x <= wert) {
				if($('#d2u_guestbook_module_60_1_star' + x).hasClass('star-empty')) {
					$('#d2u_guestbook_module_60_1_star' + x).removeClass('star-empty');
					$('#d2u_guestbook_module_60_1_star' + x).addClass('star-full');
				}
			}
			else {
				if($('#d2u_guestbook_module_60_1_star' + x).hasClass('star-full')) {
					$('#d2u_guestbook_module_60_1_star' + x).removeClass('star-full');
					$('#d2u_guestbook_module_60_1_star' + x).addClass('star-empty');
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
    $stars .= '<span class="icon star-empty" id="d2u_guestbook_module_60_1_star'. $i.'" onmouseover="d2u_guestbook_module_60_1_set_stars('. $i.')" onmouseout="d2u_guestbook_module_60_1_reset_stars('. $i.')" onclick="d2u_guestbook_module_60_1_click_stars('. $i.')"></span> ';
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
	'. ($hide_rating ? 'hidden|rating|0' : 'text|rating|'. $tag_open .'d2u_guestbook_form_rating'. $tag_close .'   '. $stars.'|0||{"style":"display:none"}')  /** @phpstan-ignore-line */ .'
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
$yform->setObjectparams('form_action', rex_getUrl(rex_article::getCurrentId()));
$yform->setObjectparams('form_anchor', 'tab_write');
$yform->setObjectparams('form_name', 'd2u_guestbook_module_60_1_'. $this->getCurrentSlice()->getId()); /** @phpstan-ignore-line */
$yform->setObjectparams('real_field_names', true);

// action - showtext
$yform->setActionField('showtext', [$tag_open .'d2u_guestbook_form_thanks'. $tag_close]);

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