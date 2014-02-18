<?php
// This is the home page showing blog posts

add_action('genesis_before_content', 'center_home_top');
function center_home_top() {
	echo '<div id="home-top" class="home-top widget-area"><div class="wrap">';
	genesis_widget_area( 'home-top' );
	echo '</div></div>';
}
genesis();