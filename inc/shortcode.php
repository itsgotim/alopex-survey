<?php /**
 * Display our questionnaire with a simple shortcode 
 * Usage: [alo_survey]
 *  */

function alopex_survey_shortcode() {

    //Register our URL variable
    /*function add_get_val() { 
        global $wp; 
        $wp->add_query_var('qpage'); 
    }
    add_action('init','add_get_val');*/

    //Get the terms/pages (qgroup) from our custom taxonomy
    $terms = get_terms( array(
        'taxonomy' => 'qgroup',
        'hide_empty' => false,
    ) );
    echo '<!--';
    print_r($terms);
    echo '-->';
    foreach($terms as $term) {
        echo '<a href="' . add_query_arg( 'qpage', $term->slug ) . '">' . $term->slug . '</a> | ';
    }

    echo '<br>Current: '.$_GET['qpage'];

    $args = array(
        'post_type' => 'alopex_survey',
        'post_status' => 'publish',
        'posts_per_page' => '-1',
        'tax_query' => array(
            array(
                'taxonomy' => 'qgroup',
                'field'    => 'slug',
                'terms'    => $_GET['qpage'],
            ),
        ),
    );

    $alo_query = null;
    $theoutput = null;
    $alo_query = new WP_query($args);
    echo '<br># of Questions: ' . $alo_query->post_count;
    if($alo_query->have_posts()):
        $theoutput = '<form action="" name="alopex_survey" id="alopex_survey">'."\n"
                    .'<div id="alopex_survey_container" class="slider">'."\n";
        $i = 0;
        while($alo_query->have_posts()) : $alo_query->the_post();
            $i++;
            $custom = get_post_custom( get_the_ID() );
            $theoutput .= '<div><h4>'.get_the_content().'</h4>'."\n"
                    .'<fieldset id="q'.$i.'">'."\n"
                    .'<input type="radio" name="q'.$i.'" id="always'.$i.'" value="' . alopex_survey_get_meta( 'alopex_survey_answer01_points' ) . '"><label class="survey-label" for="always'.$i.'">' . alopex_survey_get_meta( 'alopex_survey_answer01_name' ) . '</label>'."\n"
                    .'<input type="radio" name="q'.$i.'" id="occa'.$i.'" value="' . alopex_survey_get_meta( 'alopex_survey_answer02_points' ) . '"><label class="survey-label" for="occa'.$i.'">' . alopex_survey_get_meta( 'alopex_survey_answer02_name' ) . '</label>'."\n"
                    .'<input type="radio" name="q'.$i.'" id="never'.$i.'" value="' . alopex_survey_get_meta( 'alopex_survey_answer03_points' ) . '"><label class="survey-label" for="never'.$i.'">' . alopex_survey_get_meta( 'alopex_survey_answer03_name' ) . '</label>'."\n"
                    .'</fieldset></div>'."\n";
        endwhile;
        $theoutput .= '</div>'."\n"
                    .'</form>'."\n";
        return $theoutput;
        wp_reset_postdata();
    else :
    _e( 'Sorry, no posts matched your criteria.' );
    endif;

}
add_shortcode('alo_survey', 'alopex_survey_shortcode');