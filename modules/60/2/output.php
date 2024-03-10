<?php
// Get placeholder wildcard tags and other presets
$sprog = rex_addon::get('sprog');
$tag_open = $sprog->getConfig('wildcard_open_tag');
$tag_close = $sprog->getConfig('wildcard_close_tag');

$d2u_guestbook = rex_addon::get('d2u_guestbook');
$show_link = ($d2u_guestbook->hasConfig('guestbook_article_id') && 0 < (int) $d2u_guestbook->getConfig('guestbook_article_id')) ? true : false;

$rating = round(FriendsOfREDAXO\D2UGuestbook\Entry::getRating(), 1);
$num_recommendations = FriendsOfREDAXO\D2UGuestbook\Entry::getRecommendation();
$entries = FriendsOfREDAXO\D2UGuestbook\Entry::getAll(true);
?>
<div class="col-12 col-sm-6 col-md-4 col-lg-12">
	<div class="infobox">
		<div class="infobox-header"><?= $tag_open .'d2u_guestbook_ratings'. $tag_close ?></div>
		<div class="infobox-content" style="text-align: center" title="<?= $tag_open .'d2u_guestbook_rating'. $tag_close ?>">
			<?php
                if (0 === count($entries)) {
                    echo '<p>'. \Sprog\Wildcard::get('d2u_guestbook_no_entries') . '</p>';
                } else {
                    if ($show_link) {
                        echo '<a href="'. rex_getUrl((int) $d2u_guestbook->getConfig('guestbook_article_id')) .'" class="recommendation">';
                    }
                    echo '<div class="recommendation-stars">';
                    for ($i = 1; $i <= 5; ++$i) {
                        if ($i <= $rating) {
                            echo '<span class="icon star-full"></span> ';
                        } elseif ($i <= $rating + 0.99) {
                            echo '<span class="icon star-half"></span> ';
                        } else {
                            echo '<span class="icon star-empty"></span> ';
                        }
                    }
                    echo '</div>';
                    if ($show_link) {
                        echo '</a>';
                        echo '<a href="'. rex_getUrl((int) $d2u_guestbook->getConfig('guestbook_article_id')) .'">';
                    }
                    echo $tag_open .'d2u_guestbook_recommended_pre'. $tag_close .' '. $num_recommendations .' '. $tag_open .'d2u_guestbook_recommended_post'. $tag_close;
                    if ($show_link) {
                        echo '</a>';
                    }
                }
            ?>
		</div>
	</div>
</div>