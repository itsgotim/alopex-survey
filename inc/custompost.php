<?php /**
 * Register our custom post type for adding questions
 */

function alopex_survey_post_type()
{
    register_post_type('alopex_survey',
                       array(
                           'labels'      => array(
                               'name'          => __('Survey'),
                               'singular_name' => __('Question'),
                           ),
                           'public'      => true,
                           'has_archive' => true,
						   'register_meta_box_cb' => 'alopex_survey_add_meta_box',
                       )
    );
}
add_action('init', 'alopex_survey_post_type');