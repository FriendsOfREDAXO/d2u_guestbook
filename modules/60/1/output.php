<?php
// Get placeholder wildcard tags and other presets
$sprog = rex_addon::get("sprog");
$tag_open = $sprog->getConfig('wildcard_open_tag');
$tag_close = $sprog->getConfig('wildcard_close_tag');

// Tabs
print '<div class="col-12 d-print-none">';
print '<ul class="nav nav-pills" id="guestbook_tabs">';
print '<li class="nav-item"><a data-toggle="tab" href="#tab_guestbook" class="nav-link active">'. $tag_open .'d2u_guestbook_tab_title'. $tag_close .'</a></li>';
print '<li class="nav-item"><a data-toggle="tab" href="#tab_write" class="nav-link">'. $tag_open .'d2u_guestbook_tab_write'. $tag_close .'</a></li>';
print '</ul>';
print '</div>';
	
print '<div class="col-12">';
print '<div class="tab-content">';
print '<div id="tab_guestbook" class="tab-pane fade active show guestbook-tab">';
	
// Entries
print '<div class="row">';

$entries = D2U_Guestbook\Entry::getAll(TRUE);
foreach($entries as $entry) {
	print '<div class="col-12">';
	
	print '<div class="entry-header">';
	print '<div class="row">';
	print '<div class="col-6"><b>'. $tag_open .'d2u_guestbook_form_name'. $tag_close .': ';
	if($entry->email != '') {
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
		for($i = 1; $i <= 5; $i++) {
			if($i <= $entry->rating) {
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
print '</div>';
print '</div>';

// Entry Form
print '<div id="tab_write" class="tab-pane fade guestbook-tab">';
print '<div class="row">';
print '<div class="col-12">';
print '<fieldset><legend>'. $tag_open .'d2u_guestbook_tab_write'. $tag_close .'</legend>';
?>
<script type="text/javascript">
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
	text|rating|'. $tag_open .'d2u_guestbook_form_rating'. $tag_close .'   '. $stars.'|0||{"style":"visibility:hidden"}
	html||<br>* '. $tag_open .'d2u_guestbook_form_required'. $tag_close .'<br><br>
	captcha|'. $tag_open .'d2u_guestbook_form_captcha'. $tag_close .'|'. $tag_open .'d2u_guestbook_form_validate_captcha'. $tag_close .'|
	hidden|online_status|offline
	hidden|create_date|'. time() .'
	hidden|clang_id|'. rex_clang::getCurrentId() .'

	submit|submit|'. $tag_open .'d2u_guestbook_form_send'. $tag_close .'|no_db

	validate|empty|name|'. $tag_open .'d2u_guestbook_form_validate_name'. $tag_close .'
	validate|empty|description|'. $tag_open .'d2u_guestbook_form_validate_description'. $tag_close .'

	action|db|'. rex::getTablePrefix() .'d2u_guestbook|';
//	action|tpl2email|d2u_guestbook_request|emaillabel|'. $property->contact->email;

$yform = new rex_yform;
$yform->setFormData(trim($form_data));
$yform->setObjectparams('real_field_names', 1);
$yform->setObjectparams("form_action", rex_getUrl(rex_article::getCurrentId()));
$yform->setObjectparams("form_anchor", "tab_write");
$yform->setObjectparams("Error-occured", $tag_open .'d2u_guestbook_form_validate_title'. $tag_close);

// action - showtext
$yform->setActionField("showtext", array($tag_open .'d2u_guestbook_form_thanks'. $tag_close));

echo $yform->getForm();
print '</fieldset>';
print '</div>';
print '</div>';
// End request form
print '</div>';

print '</div>';
print '</div>';
?>
<script>
	// Allow activation of bootstrap tab via URL
	$(function() {
		var hash = window.location.hash;
		hash && $('ul.nav a[href="' + hash + '"]').tab('show');
	});

	// set stars on failure page correctly
	set_stars($('input[name=rating]').val());
</script>