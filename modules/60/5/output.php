<?php
// Get placeholder wildcard tags and other presets

$d2u_guestbook = rex_addon::get('d2u_guestbook');
$show_link = ($d2u_guestbook->hasConfig('guestbook_article_id') && 0 < (int) $d2u_guestbook->getConfig('guestbook_article_id')) ? true : false;

$rating = round(FriendsOfRedaxo\D2UGuestbook\Entry::getRating(), 1);
$num_recommendations = FriendsOfRedaxo\D2UGuestbook\Entry::getRecommendation();
$entries = FriendsOfRedaxo\D2UGuestbook\Entry::getAll(true);
?>
<div class="col-12 col-sm-6 col-md-4 col-lg-12">
	<div class="infobox">
		<div class="infobox-header"><?= \Sprog\Wildcard::get('d2u_guestbook_ratings') ?></div>
		<div class="infobox-content" style="text-align: center" title="<?= \Sprog\Wildcard::get('d2u_guestbook_rating') ?>">
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
                            echo '<span class="fas fa-star"></span> ';
                        } elseif ($i <= $rating + 0.99) {
                            echo '<span class="fas fa-star-half"></span> ';
                        }
                    }
                    echo '</div>';
                    if ($show_link) {
                        echo '</a>';
                        echo '<a href="'. rex_getUrl((int) $d2u_guestbook->getConfig('guestbook_article_id')) .'">';
                    }
                    echo \Sprog\Wildcard::get('d2u_guestbook_recommended_pre') .' '. $num_recommendations .' '. \Sprog\Wildcard::get('d2u_guestbook_recommended_post');
                    if ($show_link) {
                        echo '</a>';
                    }
                }
            ?>
		</div>
	</div>
</div>