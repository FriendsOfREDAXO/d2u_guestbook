<?php
// Get placeholder wildcard tags and other presets
$sprog = rex_addon::get("sprog");
$tag_open = $sprog->getConfig('wildcard_open_tag');
$tag_close = $sprog->getConfig('wildcard_close_tag');

$d2u_guestbook = rex_addon::get("d2u_guestbook");
$show_link = ($d2u_guestbook->hasConfig('guestbook_article_id') && $d2u_guestbook->getConfig('guestbook_article_id') != "") ? TRUE : FALSE;

$rating = round(Entry::getRating(), 1);
$num_recommendations = Entry::getRecommendation();

?>
<div class="col-12 col-sm-6 col-md-4 col-lg-12">
	<div class="infobox">
		<div class="infobox-header"><?php print $tag_open .'d2u_guestbook_ratings'. $tag_close; ?></div>
		<div class="infobox-content" style="text-align: center" title="<?php print $tag_open .'d2u_guestbook_rating'. $tag_close; ?>">
			<?php
				if($show_link) {
					print '<a href="'. rex_getUrl($d2u_guestbook->getConfig('guestbook_article_id')) .'" class="recommendation">';
				}
				print '<div class="recommendation-stars">';
				for($i = 1; $i <= 5; $i++) {
					if($i <= $rating) {
						print '<span class="icon star-full"></span> ';
					}
					else if($i <= $rating + 1) {
						print '<span class="icon star-half"></span> ';
					}
					else {
						print '<span class="icon star-empty"></span> ';
					}
				}
				print '</div>';
				if($show_link) {
					print '</a>';
					print '<a href="'. rex_getUrl($d2u_guestbook->getConfig('guestbook_article_id')) .'">';
				}
				print $tag_open .'d2u_guestbook_recommended_pre'. $tag_close .' '. $num_recommendations .' '. $tag_open .'d2u_guestbook_recommended_post'. $tag_close;
				if($show_link) {
					print '</a>';
				}
			?>
		</div>
	</div>
</div>