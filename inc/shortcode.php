<?php /**
 * Display our questionnaire with a simple shortcode 
 * Usage: [survey_slider]
 *  */

function survey_slider_shortcode() {
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
        'post_type' => 'survey_slider',
        'post_status' => 'publish',
        'posts_per_page' => '-1', //Get ALL posts
    );
    $ssldr_query_total = new WP_query($args_total);

    if ($ssldr_query_total->have_posts()) {
        $theoutput = '<span id="ssldr_totalq">0</span> of '.$ssldr_query_total->post_count.' total questions.<br><br>'
                    //.'<span id="ssldr_totalp">0</span> of '.($ssldr_query_total->post_count * 5).' total points.<br><br>'
                    .'<form action="" name="alourvey" id="ssldr_form">'."\n"
                    .'<input type="hidden" name="ssldr_pagecount" value="'.wp_count_terms( 'qgroup' ).'">'."\n" //Pass javascript this section's number of sections
                    .'<input type="hidden" name="ssldr_totalpoints" value="'.($ssldr_query_total->post_count * 5).'">'."\n" //Pass jscript total points possible
                    .'<div id="ssldr_container" class="slider">'."\n";
        //For each term in our taxonomy (page of questions) create a query and loop through posts, outputting progress at end of each 
        $p = 0;
        foreach($terms as $term) {
            $p++;
            $args_term = array(
                'post_type' => 'survey_slider',
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

            
            $ssldr_query = new WP_query($args_term);
            //Search terms to replace from question group descriptions
            $search = array('%points%', '%points_total%', '%all_points%', '%all_points_total%', '%next%');
            $replace = array(
                '<span id="ssldr_section'.$p.'_points">0</span>', //%points%
                '',                                     //%points_total%, added in while loop below
                '<span id="ssldr_totalp">0</span>', //%all_points%
                ($ssldr_query_total->post_count * 5), //%all_points_total%
                '<button type="button" class="ssldr_button">Next Section</button>' //%next%
            );

            //Loop through current page of questions
            if($ssldr_query->have_posts()) {
                $i = 0;
                while($ssldr_query->have_posts()) : $ssldr_query->the_post();
                    $i++;
                    //$custom = get_post_custom( get_the_ID() );
                    $pi = $p.'-'.$i;
                    $theoutput .= '<div class="ssldr_slide"><h4>'.get_the_content().'</h4>'."\n"
                            .'<fieldset id="q'.$p.'-'.$i.'">'."\n"
                            .'<label class="ssldr_label" for="ssldr_always'.$pi.'"><input type="radio" name="q'.$pi.'" id="ssldr_always'.$pi.'" class="ssldr_radio sect'.$p.'" value="' . survey_slider_get_meta( 'survey_slider_answer01_points' ) . '">' . survey_slider_get_meta( 'survey_slider_answer01_name' ) . '<span class="checkmark"></span></label>'."\n"
                            .'<label class="ssldr_label" for="ssldr_occa'.$pi.'"><input type="radio" name="q'.$pi.'" id="ssldr_occa'.$pi.'" class="ssldr_radio sect'.$p.'" value="' . survey_slider_get_meta( 'survey_slider_answer02_points' ) . '">' . survey_slider_get_meta( 'survey_slider_answer02_name' ) . '<span class="checkmark"></span></label>'."\n"
                            .'<label class="ssldr_label" for="ssldr_never'.$pi.'"><input type="radio" name="q'.$pi.'" id="ssldr_never'.$pi.'" class="ssldr_radio sect'.$p.'" value="' . survey_slider_get_meta( 'survey_slider_answer03_points' ) . '">' . survey_slider_get_meta( 'survey_slider_answer03_name' ) . '<span class="checkmark"></span></label>'."\n"
                            .'</fieldset></div>'."\n";
                    if ($i == $ssldr_query->post_count) { //If end of section, output summary/term description
                        $replace[1] = '<span id="ssldr_section'.$p.'_total">'.($ssldr_query->post_count * 5).'</span>'; //Populate [1] in our replace function with total points
                        $theoutput .= '<div class="ssldr_slide">'.wpautop(str_replace($search, $replace, $term->description)).'</div>'."\n"; //End of section content
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
add_shortcode('survey_slider', 'survey_slider_shortcode');