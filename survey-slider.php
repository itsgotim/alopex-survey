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