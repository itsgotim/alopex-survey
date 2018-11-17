<?php /**
 * Register our custom post type for adding questions
 */

function survey_slider_post_type()
{
    register_post_type('survey_slider',
                       array(
                           'labels'      => array(
                               'name'          => __('Survey'),
                               'singular_name' => __('Question'),
                           ),
                           'public'      => true,
                           'has_archive' => true,
						   'register_meta_box_cb' => 'survey_slider_add_meta_box',
                       )
    );
}
add_action('init', 'survey_slider_post_type');