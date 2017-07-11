<?php
$func = rex_request('func', 'string');
$entry_id = rex_request('entry_id', 'int');
$message = rex_get('message', 'string');

// Print comments
if($message != "") {
	print rex_view::success(rex_i18n::msg($message));
}

// save settings
if (filter_input(INPUT_POST, "btn_save") == 1 || filter_input(INPUT_POST, "btn_apply") == 1) {
	$form = (array) rex_post('form', 'array', []);

	// Media fields and links need special treatment
	$entry = new Entry($form['id']);
	$entry->clang_id = $form['clang_id'];
	$entry->name = $form['name'];
	$entry->email = $form['email'];
	$entry->rating = $form['rating'];
	$entry->recommendation =  array_key_exists('recommendation', $form) ? TRUE : FALSE;
	$entry->description = $form['description'];
	$entry->online_status = array_key_exists('online_status', $form) ? 'online' : 'offline';

	// message output
	$message = 'form_save_error';
	if($entry->save() == 0) {
		$message = 'form_saved';
	}
	
	// Redirect to make reload and thus double save impossible
	if(filter_input(INPUT_POST, "btn_apply") == 1 && $entry !== FALSE) {
		header("Location: ". rex_url::currentBackendPage(array("entry_id"=>$entry->id, "func"=>'edit', "message"=>$message), FALSE));
	}
	else {
		header("Location: ". rex_url::currentBackendPage(array("message"=>$message), FALSE));
	}
	exit;
}
// Delete
else if(filter_input(INPUT_POST, "btn_delete") == 1 || $func == 'delete') {
	if($entry_id == 0) {
		$form = (array) rex_post('form', 'array', []);
		$entry_id = $form['$entry_id'];
	}
	$entry = new Entry($entry_id);
	$entry->delete();
	
	$func = '';
}
// Change online status of machine
else if($func == 'changestatus') {
	$entry = new Entry($entry_id);
	$entry->changeStatus();
	
	header("Location: ". rex_url::currentBackendPage());
	exit;
}


// Eingabeformular
if ($func == 'edit' || $func == 'add') {
?>
	<form action="<?php print rex_url::currentBackendPage(); ?>" method="post">
		<div class="panel panel-edit">
			<header class="panel-heading"><div class="panel-title"><?php print rex_i18n::msg('d2u_guestbook_entries'); ?></div></header>
			<div class="panel-body">
				<input type="hidden" name="form[id]" value="<?php echo $entry_id; ?>">
				<fieldset>
					<legend><?php echo rex_i18n::msg('d2u_guestbook_entry'); ?></legend>
					<div class="panel-body-wrapper slide">
						<?php
							$entry = new Entry($entry_id);
							if(count(rex_clang::getAllIds(TRUE)) > 1) {
								$options_clang = [];
								foreach(rex_clang::getAll(TRUE) as $rex_clang){
									$options_clang[$rex_clang->getId()] = $rex_clang->getName();
								}
								d2u_addon_backend_helper::form_select('d2u_guestbook_clang', 'form[clang_id]', $options_clang, array($entry->clang_id), 1, FALSE);
							}
							else {
								print '<input type="hidden" name="form[clang_id]" value="'. rex_clang::getStartId() .'">';
							}

							d2u_addon_backend_helper::form_input('d2u_guestbook_name', 'form[name]', $entry->name, TRUE, FALSE);
							d2u_addon_backend_helper::form_input('d2u_guestbook_email', 'form[email]', $entry->email, FALSE, FALSE);
							d2u_addon_backend_helper::form_input('d2u_guestbook_rating', 'form[rating]', $entry->rating, FALSE, FALSE, 'number');
							d2u_addon_backend_helper::form_checkbox('d2u_guestbook_recommendation', 'form[recommendation]', 'true', $entry->recommendation);
							d2u_addon_backend_helper::form_textarea('d2u_guestbook_description', "form[description]", $entry->description, 10, TRUE, FALSE, FALSE);
							d2u_addon_backend_helper::form_checkbox('d2u_guestbook_online_status', 'form[online_status]', 'online', $entry->online_status == 'online' ? TRUE : FALSE);
						?>
					</div>
				</fieldset>
			</div>
			<footer class="panel-footer">
				<div class="rex-form-panel-footer">
					<div class="btn-toolbar">
						<button class="btn btn-save rex-form-aligned" type="submit" name="btn_save" value="1"><?php echo rex_i18n::msg('form_save'); ?></button>
						<button class="btn btn-apply" type="submit" name="btn_apply" value="1"><?php echo rex_i18n::msg('form_apply'); ?></button>
						<button class="btn btn-abort" type="submit" name="btn_abort" formnovalidate="formnovalidate" value="1"><?php echo rex_i18n::msg('form_abort'); ?></button>
						<button class="btn btn-delete" type="submit" name="btn_delete" formnovalidate="formnovalidate" data-confirm="<?php echo rex_i18n::msg('form_delete'); ?>?" value="1"><?php echo rex_i18n::msg('form_delete'); ?></button>
					</div>
				</div>
			</footer>
		</div>
	</form>
	<br>
	<script type='text/javascript'>
		jQuery(document).ready(function($) {
			$('legend').each(function() {
				$(this).addClass('open');
				$(this).next('.panel-body-wrapper.slide').slideToggle();
			});
		});
	</script>
	<?php
		print d2u_addon_backend_helper::getCSS();
//		print d2u_addon_backend_helper::getJS();
}

if ($func == '') {
	$query = 'SELECT id, name, email, rating, online_status '
		. 'FROM '. rex::getTablePrefix() .'d2u_guestbook '
		. 'ORDER BY create_date DESC';
    $list = rex_list::factory($query);

    $list->addTableAttribute('class', 'table-striped table-hover');

    $tdIcon = '<i class="rex-icon fa-book"></i>';
    $thIcon = '<a href="' . $list->getUrl(['func' => 'add']) . '" title="' . rex_i18n::msg('add') . '"><i class="rex-icon rex-icon-add-module"></i></a>';
    $list->addColumn($thIcon, $tdIcon, 0, ['<th class="rex-table-icon">###VALUE###</th>', '<td class="rex-table-icon">###VALUE###</td>']);
    $list->setColumnParams($thIcon, ['func' => 'edit', 'entry_id' => '###id###']);

    $list->setColumnLabel('id', rex_i18n::msg('id'));
    $list->setColumnLayout('id', ['<th class="rex-table-id">###VALUE###</th>', '<td class="rex-table-id">###VALUE###</td>']);

    $list->setColumnLabel('name', rex_i18n::msg('d2u_guestbook_name'));
    $list->setColumnParams('name', ['func' => 'edit', 'entry_id' => '###id###']);

    $list->setColumnLabel('email', rex_i18n::msg('d2u_guestbook_email'));

    $list->setColumnLabel('rating', rex_i18n::msg('d2u_guestbook_rating'));

  	$list->removeColumn('online_status');
    $list->addColumn(rex_i18n::msg('status_online'), '<a class="rex-###online_status###" href="' . rex_url::currentBackendPage(['func' => 'changestatus']) . '&entry_id=###id###"><i class="rex-icon rex-icon-###online_status###"></i> ###online_status###</a>');
	$list->setColumnLayout(rex_i18n::msg('status_online'), ['', '<td class="rex-table-action">###VALUE###</td>']);

	$list->addColumn(rex_i18n::msg('module_functions'), '<i class="rex-icon rex-icon-edit"></i> ' . rex_i18n::msg('system_update'));
    $list->setColumnLayout(rex_i18n::msg('module_functions'), ['<th class="rex-table-action" colspan="2">###VALUE###</th>', '<td class="rex-table-action">###VALUE###</td>']);
    $list->setColumnParams(rex_i18n::msg('module_functions'), ['func' => 'edit', 'entry_id' => '###id###']);

    $list->addColumn(rex_i18n::msg('delete_module'), '<i class="rex-icon rex-icon-delete"></i> ' . rex_i18n::msg('delete'));
    $list->setColumnLayout(rex_i18n::msg('delete_module'), ['', '<td class="rex-table-action">###VALUE###</td>']);
    $list->setColumnParams(rex_i18n::msg('delete_module'), ['func' => 'delete', 'entry_id' => '###id###']);
    $list->addLinkAttribute(rex_i18n::msg('delete_module'), 'data-confirm', rex_i18n::msg('d2u_helper_confirm_delete'));

    $list->setNoRowsMessage(rex_i18n::msg('d2u_guestbook_no_entries_found'));

    $fragment = new rex_fragment();
    $fragment->setVar('title', rex_i18n::msg('d2u_guestbook_entries'), false);
    $fragment->setVar('content', $list->get(), false);
    echo $fragment->parse('core/page/section.php');
}