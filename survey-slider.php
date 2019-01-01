<?php
/*
Plugin Name:  Survey Slider
Plugin URI:   https://wildnine.net
Description:  This plugin allows you to create a points-based, paged list of survey questions via custom post type and taxonomy.
Version:      20181107
Author:       Tim Schorr
Author URI:   https://wildnine.net
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) or die( 'No script kiddies!' );

//Function to determine if a post is new
require( plugin_dir_path( __FILE__ ) . 'inc/editpage.php' );

//Register our custom post type for adding questions
require( plugin_dir_path( __FILE__ ) . 'inc/custompost.php' );

//Add default, incremental post title using max(ID)
function survey_slider_default_title() {
    global $post_type;
    global $wpdb;

    //Default title: 'Question' + max(ID)
    $qid = $wpdb->get_var("SELECT max(ID) FROM " . $wpdb->prefix . "posts WHERE post_type = 'survey_slider' ") + 1;

    if ('survey_slider' == $post_type) {
        return 'Question ' . $qid;
    }
}
add_filter('default_title', 'survey_slider_default_title');


//Add metaboxes to custom post type survey_slider
require( plugin_dir_path( __FILE__ ) . 'inc/metaboxes.php' );

//Register our custom taxonomy qgroup and add any taxonomies as submenu pages
require( plugin_dir_path( __FILE__ ) . 'inc/taxonomy.php' );

//Create out a shortcode for outputting survey
require( plugin_dir_path( __FILE__ ) . 'inc/shortcode.php' );

//Enqueue scripts and styles
function survey_slider_enqueue_script() {   
    wp_enqueue_script( 'slick-js', plugin_dir_url( __FILE__ ) . 'assets/slick/slick.js', array( 'jquery' ) );
    wp_enqueue_script( 'survey-js', plugin_dir_url( __FILE__ ) . 'assets/js/survey.js', array( 'slick-js' ) );
    wp_enqueue_style( 'slick-css', plugin_dir_url( __FILE__ ) . 'assets/slick/slick.css' );
    wp_enqueue_style( 'slick-theme', plugin_dir_url( __FILE__ ) . 'assets/slick/slick-theme.css', array( 'slick-css' ) );
    wp_enqueue_style( 'survey-css', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );
}
add_action('wp_enqueue_scripts', 'survey_slider_enqueue_script', 9999);

//Add opengraph tags if on results page for facebook sharing
function doctype_opengraph($output) {
    return $output . '
    xmlns:og="http://opengraphprotocol.org/schema/"
    xmlns:fb="http://www.facebook.com/2008/fbml"';
}
add_filter('language_attributes', 'doctype_opengraph');

function fb_opengraph() {
    global $wp;

    //Prepare overall score for sharing
    $score_total = 0;
    if (!empty($_GET['avpd']) && !empty($_GET['mdep']) && !empty($_GET['dep'])) {
        $score_total = floor( ( intval($_GET['avpd']) + intval($_GET['mdep']) + intval($_GET['dep']) ) / 3);
    }
    ?>
    <meta property="og:title" content="AVPD and Depression Quiz"/>
    <meta property="og:description" content="I scored <?php echo $score_total; ?>% for Avoidant Personality Disorder, Major Depression and Depression. Take the test and see how you score."/>
    <meta property="og:type" content="article"/>
    <meta property="og:url" content="<?php echo home_url( $wp->request ); ?>/"/>
    <meta property="og:site_name" content=""/>
    <meta property="og:image" content=""/>
<?php

}
add_action('wp_head', 'fb_opengraph', 5);

//Add facebook SDK to footer
function ssldr_fbook_share () {
    ?>
    <!-- Load Facebook SDK for JavaScript -->
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    
    <!-- Load twitter SDK for JavaScript -->
    <script>window.twttr = (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0],
        t = window.twttr || {};
    if (d.getElementById(id)) return t;
    js = d.createElement(s);
    js.id = id;
    js.src = "https://platform.twitter.com/widgets.js";
    fjs.parentNode.insertBefore(js, fjs);

    t._e = [];
    t.ready = function(f) {
        t._e.push(f);
    };

    return t;
    }(document, "script", "twitter-wjs"));</script>
    <?php
}
add_action('wp_print_footer_scripts', 'ssldr_fbook_share');