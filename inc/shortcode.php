<?php /**
 * Display our questionnaire with a simple shortcode 
 * Usage: [alo_survey]
 *  */

function alopex_survey_shortcode() {
    $theoutput = null;
    $alo_query_total = null;
    $alo_query = null;

    //Get the terms/pages (qgroup) from our custom taxonomy
    $terms = get_terms( array(
        'taxonomy' => 'qgroup',
        'hide_empty' => true,
    ) );

    //Term object output
    echo '<!--';
    print_r($terms);
    echo '-->';
    
    //Get total questions
    $args_total = array(
        'post_type' => 'alopex_survey',
        'post_status' => 'publish',
        'posts_per_page' => '-1', //Get ALL posts
    );
    $alos_query_total = new WP_query($args_total);

    if ($alos_query_total->have_posts()) {
        $theoutput = '<span id="alosurvey_totalq">0</span> of '.$alos_query_total->post_count.' total questions.<br>'
                    .'<span id="alosurvey_totalp">0</span> of '.($alos_query_total->post_count * 5).' total points.<br><br>'
                    .'<form action="" name="alourvey" id="alosurvey_form">'."\n"
                    .'<input type="hidden" name="alos_pagecount" value="'.wp_count_terms( 'qgroup' ).'">'."\n" //Pass javascript this section's number of sections
                    .'<input type="hidden" name="alos_totalpoints" value="'.($alos_query_total->post_count * 5).'">'."\n" //Pass jscript total points possible
                    .'<div id="alosurvey_container" class="slider">'."\n";
        //For each term in our taxonomy (page of questions) create a query and loop through posts, outputting progress at end of each 
        $p = 0;
        foreach($terms as $term) {
            $p++;
            $args_term = array(
                'post_type' => 'alopex_survey',
                'post_status' => 'publish',
                'posts_per_page' => '-1', //Get ALL posts
                'tax_query' => array( //...in current term
                    array(
                        'taxonomy' => 'qgroup',
                        'field'    => 'slug',
                        'terms'    => $term,
                    ),
                ),
            );

            
            $alos_query = new WP_query($args_term);
            
            //Loop through current page of questions
            if($alos_query->have_posts()) {
                $i = 0;
                while($alos_query->have_posts()) : $alos_query->the_post();
                    $i++;
                    //$custom = get_post_custom( get_the_ID() );
                    $pi = $p.'-'.$i;
                    $theoutput .= '<div class="alosurvey_slide"><h4>'.get_the_content().'</h4>'."\n"
                            .'<fieldset id="q'.$p.'-'.$i.'">'."\n"
                            .'<input type="radio" name="q'.$pi.'" id="alosurvey_always'.$pi.'" class="alosurvey_radio sect'.$p.'" value="' . alopex_survey_get_meta( 'alopex_survey_answer01_points' ) . '"><label class="alosurvey_label" for="alosurvey_always'.$pi.'">' . alopex_survey_get_meta( 'alopex_survey_answer01_name' ) . '</label>'."\n"
                            .'<input type="radio" name="q'.$pi.'" id="alosurvey_occa'.$pi.'" class="alosurvey_radio sect'.$p.'" value="' . alopex_survey_get_meta( 'alopex_survey_answer02_points' ) . '"><label class="alosurvey_label" for="alosurvey_occa'.$pi.'">' . alopex_survey_get_meta( 'alopex_survey_answer02_name' ) . '</label>'."\n"
                            .'<input type="radio" name="q'.$pi.'" id="alosurvey_never'.$pi.'" class="alosurvey_radio sect'.$p.'" value="' . alopex_survey_get_meta( 'alopex_survey_answer03_points' ) . '"><label class="alosurvey_label" for="alosurvey_never'.$pi.'">' . alopex_survey_get_meta( 'alopex_survey_answer03_name' ) . '</label>'."\n"
                            .'</fieldset></div>'."\n";
                    if ($i == $alos_query->post_count) { //need better logic for tracking section ends............
                        $theoutput .= '<div class="alosurvey_slide">Hey, you reached the end of this section, go you!<br>'."\n"
                                .'You scored <span id="alosurvey_section'.$p.'_points">0</span> out of <span id="alosurvey_section1_total">'.($alos_query->post_count * 5).'</span> possible points on this section.<br>'."\n"
                                .'<button type="button" class="alosurvey_button">Next Section</button></div>'."\n";
                    }
                endwhile;
                wp_reset_postdata();
            } else {
            _e( 'Sorry, no posts matched your criteria.' );
            }
        }
        $theoutput .= '</div>'."\n"
            .'</form>'."\n";
        return $theoutput;
    }

}
add_shortcode('alo_survey', 'alopex_survey_shortcode');