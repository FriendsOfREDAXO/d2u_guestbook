<?php
if(!function_exists('sendAdminNotification')) {
	/**
	 * Send mail to admin address when news guestbook entry is created.
	 * @param mixed $yform
	 */
	function sendAdminNotification($yform) {
		\D2U_Guestbook\d2u_guestbook_backend_helper::sendAdminNotification($yform);
	}
}

// Get placeholder wildcard tags and other presets
$sprog = rex_addon::get("sprog");
$tag_open = $sprog->getConfig('wildcard_open_tag');
$tag_close = $sprog->getConfig('wildcard_close_tag');

if(rex_get('entry', 'string') == 'add') {
	// Entry Form
	print '<div class="col-12">';
	print '<fieldset><legend>'. $tag_open .'d2u_guestbook_tab_write'. $tag_close .'</legend>';
	?>
	<script>
		function set_stars(wert) {
			for(var x = 1; x <= 5; x++) {
				if(x <= wert) {
					if($('#star' + x).hasClass('star-empty')) {
						$('#star' + x).removeClass('star-empty');
						$('#star' + x).addClass('star-full');
					}
				}
				else {
					if($('#star' + x).hasClass('star-full')) {
						$('#star' + x).removeClass('star-full');
						$('#star' + x).addClass('star-empty');
					}
				}
			}
		}
		function reset_stars(wert) {
			set_stars($('input[name=rating]').val());
		}
		function click_stars(wert) {
			$('input[name=rating]').val(wert);
			set_stars(wert);
		}
	</script>
	<?php
	$stars = '<span class="icon star-empty" id="star1" onmouseover="set_stars(1)" onmouseout="reset_stars(1)" onclick="click_stars(1)"></span> <span class="icon star-empty" id="star2" onmouseover="set_stars(2)" onmouseout="reset_stars(2)" onclick="click_stars(2)"></span> <span class="icon star-empty" id="star3" onmouseover="set_stars(3)" onmouseout="reset_stars(3)" onclick="click_stars(3)"></span> <span class="icon star-empty" id="star4" onmouseover="set_stars(4)" onmouseout="reset_stars(4)" onclick="click_stars(4)"></span> <span class="icon star-empty" id="star5" onmouseover="set_stars(5)" onmouseout="reset_stars(5)" onclick="click_stars(5)"></span>';
	$form_data = '
		text|name|'. $tag_open .'d2u_guestbook_form_name'. $tag_close .' *
		text|email|'. $tag_open .'d2u_guestbook_form_email'. $tag_close .'
		textarea|description|'. $tag_open .'d2u_guestbook_form_message'. $tag_close .'
		radio|recommendation|'. $tag_open .'d2u_guestbook_form_recommendation'. $tag_close .'|'. $tag_open .'d2u_guestbook_no'. $tag_close .'=0,'. $tag_open .'d2u_guestbook_yes'. $tag_close .'=1|1|
		checkbox|privacy_policy_accepted|'. $tag_open .'d2u_guestbook_form_privacy_policy'. $tag_close . ' *|no,yes|no
		text|rating|'. $tag_open .'d2u_guestbook_form_rating'. $tag_close .'   '. $stars.'|0||{"style":"display:none"}
		html||<br>* '. $tag_open .'d2u_guestbook_form_required'. $tag_close .'<br><br>
		php|validate_timer|Spamprotection|<input name="validate_timer" type="hidden" value="'. microtime(true) .'" />|
		captcha|'. $tag_open .'d2u_guestbook_form_captcha'. $tag_close .'|'. $tag_open .'d2u_guestbook_form_validate_captcha'. $tag_close .'|'. rex_getUrl(rex_article::getCurrentId(), null, ['entry' => 'add']) .'
		hidden|online_status|offline
		hidden|create_date|'. time() .'
		hidden|clang_id|'. rex_clang::getCurrentId() .'

		submit|submit|'. $tag_open .'d2u_guestbook_form_send'. $tag_close .'|no_db

		validate|empty|name|'. $tag_open .'d2u_guestbook_form_validate_name'. $tag_close .'
		validate|empty|description|'. $tag_open .'d2u_guestbook_form_validate_description'. $tag_close .'
		validate|customfunction|validate_timer|d2u_addon_frontend_helper::yform_validate_timer|10|'. $tag_open .'d2u_guestbook_form_validate_spambots'. $tag_close .'|

		action|callback|sendAdminNotification
		action|db|'. rex::getTablePrefix() .'d2u_guestbook|';

	$yform = new rex_yform;
	$yform->setFormData(trim($form_data));
	$yform->setObjectparams('real_field_names', TRUE);
	$yform->setObjectparams("form_action", rex_getUrl(rex_article::getCurrentId(), null, ['entry' => 'add']));
	$yform->setObjectparams("Error-occured", $tag_open .'d2u_guestbook_form_validate_title'. $tag_close);

	// action - showtext
	$yform->setActionField("showtext", [$tag_open .'d2u_guestbook_form_thanks'. $tag_close]);

	echo $yform->getForm();
	print '</fieldset>';
	print '</div>';
	// End request form
}
else {
	// Add entry button
	print '<div class="col-12">';
	print '<a href="'. rex_getUrl(rex_article::getCurrentId(), null, ['entry' => 'add']) .'"><button>'. $tag_open .'d2u_guestbook_tab_write'. $tag_close .'</button></a><br><br>';
	print '</div>';
	
	// Entries
	print '<div class="col-12">';
	$entries = D2U_Guestbook\Entry::getAll(TRUE);
	$page_no = 0;
	for($i = 0; $i < count($entries); $i++) {
		$entry = $entries[$i];

		if($i % rex_config::get('d2u_guestbook', 'no_entries_page', 10) == 0) {
			$page_no++;
			if($page_no != 1) {
				print '</div>';
			}
			print '<div class="row guestbook-page pages-'. $page_no .'">'; // Pagination div
		}

		print '<div class="col-12">';
		print '<div class="entry-header">';
		print '<div class="row">';
		print '<div class="col-6 left"><b>'. $tag_open .'d2u_guestbook_form_name'. $tag_close .': ';
		if($entry->email != '' && rex_config::get('d2u_guestbook', 'allow_answer', 'false') == 'true') {
			print '<a href="mailto:'. $entry->email .'">';
			print $entry->name .' <span class="icon mail"></span>';
			print '</a>';
		}
		else {
			print $entry->name;
		}
		print '</b></div>';
		print '<div class="col-6 right">'. date('d.m.Y H:i', $entry->create_date) .' '. $tag_open .'d2u_guestbook_oclock'. $tag_close .'</div>';
		print '</div>';
		print '</div>';

		print '<div class="entry-body">';
		print '<div class="row">';
		print '<div class="col-12">'. nl2br($entry->description) .'</div>';
		if($entry->rating > 0) {
			print '<div class="col-12"><b>'. $tag_open .'d2u_guestbook_rating'. $tag_close .': ';
			for($j = 1; $j <= 5; $j++) {
				if($j <= $entry->rating) {
					print ' <span class="icon star-full"></span>';
				}
				else {
					print ' <span class="icon star-empty"></span>';
				}
			}
			print '</b></div>';
		}
		print '</div>';
		print '</div>';

		print '</div>';
	}
	print '</div>'; // End pagination
	// Page selection
	if($page_no > 1) {
		print "<script type='text/javascript'>
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
		print '<div class="row">';
		print '<div class="col-12 page-selection">'. $tag_open .'d2u_guestbook_page'. $tag_close .': ';
		for($i = 1; $i <= $page_no; $i++) {
			print '<a href="javascript:changePage('. $i .')" class="page'. ($i == 1 ? ' active-page' : '') .'" id="page-'. $i .'">'. $i .'</a>';
		}
		print '</div>';
		print '</div>';
	}

	print '</div>';
}
?>