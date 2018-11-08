<?php /**
 * Register our custom taxonomy qgroup and add any taxonomies as submenu pages
 */

//Register taxonomy
function alopex_survey_create_tax() {
	register_taxonomy(
		'qgroup',
		'alopex_survey',
		array(
			'label' => __( 'Group Questions' ),
			'rewrite' => array( 'slug' => 'qgroup' ),
			'hierarchical' => true,
		)
	);
}
add_action( 'init', 'alopex_survey_create_tax' );

//Add custom taxonomy as submenu pages
function alopex_survey_submenu_pages() {
    $categoryType = 'qgroup';
    $postType = 'alopex_survey';
    $wp_term = get_categories( 'taxonomy='.$categoryType.'&type='.$postType ); 
    if ( $wp_term ) {
        foreach ( $wp_term as $term ) {
            // add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '' )
            add_submenu_page( 'edit.php?post_type=' . $postType, $term->name, $term->name, 'manage_options', 'edit.php?post_type=' . $postType . '&' . $categoryType . '=' . $term->slug, '' ); 
        }
    } 
}
add_action('admin_menu', 'alopex_survey_submenu_pages');