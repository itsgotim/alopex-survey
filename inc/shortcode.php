<?php /**
 * Display our questionnaire with a simple shortcode 
 * Usage: [survey_slider]
 *  */

function survey_slider_shortcode() {
    $theoutput = null;
 
    if (!empty($_GET['avpd']) && !empty($_GET['mdep']) && !empty($_GET['dep'])) { //Output results page
        $score = floor( ( intval($_GET['avpd']) + intval($_GET['mdep']) + intval($_GET['dep']) ) / 3 );
        //Build results page
        //var_dump($_GET); 
        $theoutput = '<div id="ssldr_container">'."\n"
                        .'<div class="ssldr_slide" style="text-align:center;">'."\n"
                            .'<h1>Quiz Results</h1>'."\n"
                            .'<p>Quiz results introduction goes here.</p>'."\n"
                            .'<div class="results">'."\n"
                                .'<div><h4>Avoidant Personality Disorder</h4>'."\n"
                                .'<p><span class="score">You Scored: ' . $_GET['avpd'] . '%</span><br>'
                                .'Gentle explanation of disorder here.</p></div>'."\n"
                                .'<div><h4>Major Depression</h4>'."\n"
                                .'<p><span class="score">You Scored: ' . $_GET['mdep'] . '%</span><br>'
                                .'Gentle explanation of disorder here.</p></div>'."\n"
                                .'<div><h4>Depression</h4>'."\n"
                                .'<p><span class="score">You Scored: ' . $_GET['dep'] . '%</span><br>'
                                .'Gentle explanation of disorder here.</p></div>'."\n"
                            .'</div>'."\n"
                            //.'<a class="ssldr_button" href="https://www.facebook.com/sharer/sharer.php?u=' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '">Share on Facebook</a>'."\n"
                            .'<div class="fb-share-button" data-href="' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '" data-layout="button_count"></div>'
                            .'<a class="twitter-share-button" href="https://twitter.com/intent/tweet?text=I%20scored%20'.$score.'%25%20on%20the%20Avoidant%20Personality%2C%20Major%20Depression%20and%20Depression%20Quiz.%20Find%20out%20how%20you%20score.">Tweet</a>'
                        .'</div>'."\n"
                    .'</div>'
                    //Disclaimer
                    .'<p class="ssldr_disclaimer">This test is for evaluation purposes only. We are not Physiatrists, physiologists, or any other licensed professional so we cannot diagnose symptoms. This test is a useful guide that will help you determine if you might have Avoidant Personality, Major Depression, or Depression. Only a licensed professional can officially diagnose you.</p>';
        return $theoutput;
    } else { //Output quiz
        $ssldr_query_total = null;
        $ssldr_query = null;

        //Get the terms/pages (qgroup) from our custom taxonomy
        $terms = get_terms( array(
            'taxonomy' => 'qgroup',
            'hide_empty' => true,
        ) );
        
        //Get total questions
        $args_total = array(
            'post_type' => 'survey_slider',
            'post_status' => 'publish',
            'posts_per_page' => '-1', //Get ALL posts
        );
        $ssldr_query_total = new WP_query($args_total);

        //Output total questions and top of form and slider
        if ($ssldr_query_total->have_posts()) {
            $theoutput = '<p><span class="ssldr_totalq">0</span> of '.$ssldr_query_total->post_count.' total questions.</p>'
                        .'<div id="progressbar"><div class="progressbar_inner"><span class="ssldr_totalq">0</span> of '.$ssldr_query_total->post_count.'</div></div>'."\n"
                        //.'<span id="ssldr_totalp">0</span> of '.($ssldr_query_total->post_count * 5).' total points.<br><br>'
                        .'<form action="" method="GET" name="sslder" id="ssldr_form">'."\n"
                        .'<input type="hidden" class="ssldr_pagecount" value="'.wp_count_terms( 'qgroup' ).'">'."\n" //Pass javascript this section's number of sections
                        .'<input type="hidden" class="ssldr_totalquestions" value="'.$ssldr_query_total->post_count.'">'."\n" //Pass jscript total points possible
                        .'<div id="ssldr_container" class="slider">'."\n";
            
            //For each term in our taxonomy (group of questions) create a query and loop through posts
            $p = 0;
            foreach($terms as $term) {
                $p++;
                $args_term = array(
                    'post_type' => 'survey_slider',
                    'post_status' => 'publish',
                    'posts_per_page' => '-1', //Get ALL posts
                    'orderby'  => 'title',
                    'order' => 'ASC',
                    'tax_query' => array( //...in current term
                        array(
                            'taxonomy' => 'qgroup',
                            'field'    => 'slug',
                            'terms'    => $term
                        ),
                    ),
                );

                $ssldr_query = new WP_query($args_term);

                //Search terms to replace from question group descriptions
                $search = array('%all_points%', '%all_points_total%', '%sect1-score%', '%sect2-score%', '%sect3-score%', '%go-results%');
                $replace = array(
                    '<span id="ssldr_totalp">0</span>', //%all_points%
                    ($ssldr_query_total->post_count * 5), //%all_points_total%
                    '<span class="ssldr_section1_score">0</span>', //section 1 score
                    '<span class="ssldr_section2_score">0</span>', //section 2 score
                    '<span class="ssldr_section3_score">0</span>', //section 3 score
                    //'<span class="ssldr_score">0</span>', //percent score
                    '<input type="submit" class="ssldr_button" value="Get Results">' //%next%
                );

                //Loop through and output current section of questions
                if($ssldr_query->have_posts()) {
                    $i = 0;
                    while($ssldr_query->have_posts()) : $ssldr_query->the_post();
                        $i++;
                        $pi = $p.'-'.$i;
                        $theoutput .= '<div class="ssldr_slide"><h4 class="question">'.get_the_content().'</h4>'."\n"
                                .'<input type="hidden" class="ssldr_s'.$p.'total" value="'.($ssldr_query->post_count * 5).'">'."\n" //Send section total points to js to calculate section scores
                                .'<fieldset id="q'.$p.'-'.$i.'">'."\n"
                                .'<label class="ssldr_label" for="ssldr_always'.$pi.'"><input type="radio" id="ssldr_always'.$pi.'" class="ssldr_radio sect'.$p.'" value="' . survey_slider_get_meta( 'survey_slider_answer01_points' ) . '">' . survey_slider_get_meta( 'survey_slider_answer01_name' ) . '<span class="checkmark"></span></label>'."\n"
                                .'<label class="ssldr_label" for="ssldr_occa'.$pi.'"><input type="radio" id="ssldr_occa'.$pi.'" class="ssldr_radio sect'.$p.'" value="' . survey_slider_get_meta( 'survey_slider_answer02_points' ) . '">' . survey_slider_get_meta( 'survey_slider_answer02_name' ) . '<span class="checkmark"></span></label>'."\n"
                                .'<label class="ssldr_label" for="ssldr_never'.$pi.'"><input type="radio" id="ssldr_never'.$pi.'" class="ssldr_radio sect'.$p.'" value="' . survey_slider_get_meta( 'survey_slider_answer03_points' ) . '">' . survey_slider_get_meta( 'survey_slider_answer03_name' ) . '<span class="checkmark"></span></label>'."\n"
                                .'</fieldset><span class="qno">'.get_the_title().'<br><a href="#">Report Question</a></span></div>'."\n";
                    endwhile;

                    wp_reset_postdata();

                } else {

                _e( 'Sorry, no posts matched your criteria.' );

                }
            }
            //End section content
            $theoutput .= '<div class="ssldr_slide">'."\n"
                        .'<input type="hidden" name="avpd" class="ssldr_section1_score" value="0">'."\n"
                        .'<input type="hidden" name="mdep" class="ssldr_section2_score" value="0">'."\n"
                        .'<input type="hidden" name="dep" class="ssldr_section3_score" value="0">'."\n"
                        .wpautop(str_replace($search, $replace, $term->description))."\n"
                        .'</div>'."\n"
                        .'</div>'."\n"
                        .'</form>'."\n"
                        //Disclaimer
                        .'<p class="ssldr_disclaimer">This test is for evaluation purposes only. We are not Physiatrists, physiologists, or any other licensed professional so we cannot diagnose symptoms. This test is a useful guide that will help you determine if you might have Avoidant Personality, Major Depression, or Depression. Only a licensed professional can officially diagnose you.</p>';
            return $theoutput;
                
        }

    }

}
add_shortcode('survey_slider', 'survey_slider_shortcode');