<?php /**
 * Display our questionnaire with a simple shortcode 
 * Usage: [survey_slider_paged]
 *  */

function survey_slider_paged_shortcode( $atts ) {
    $nextIndex = 1;
    //Get the terms/pages (qgroup) from our custom taxonomy
    $terms = get_terms( array(
        'taxonomy' => 'qgroup',
        'hide_empty' => true,
    ) );
    echo '<!--';
    print_r($terms);
    echo '-->';
    
    $theoutput = null;
    $scoreme = false;
    //Handle paging and cumulitive scoring logic, hidden stuff
    if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
        //NOT first page
        $nextIndex = $_POST['nextIndex'];
        if ($nextIndex == -1) {
            //Last page (-1)
            echo 'last page, next is scoring';
            $scoreme = true;
        } else {
            //Middle page
            echo 'page' . $nextIndex;
            if ( ($nextIndex + 1) == (count($terms) - 1) ) {
                $nextIndex = -1;
                echo 'next page is last';
            } else {
                $nextIndex++;
            }
        }
        if ($_POST['next'])
        $theoutput .= $_POST['test'];
    } else {
        //First page, no post... nothing to really do here
        echo 'first page'; 

    }
    if ($scoreme) {
        $theoutput .= 'your score is...';
    } else {
        $theoutput .= '<form method="post" action="">';
        $theoutput .= '<input type="text" value="testing" name="test">';
        $theoutput .= '<input type="hidden" value="'.$nextIndex.'" name="nextIndex">';
        $theoutput .= '<input type="submit" value="Submit">';
        $theoutput .= '</form>';
        
    }
    return $theoutput;
}
add_shortcode('survey_slider_paged', 'survey_slider_paged_shortcode');

/* 
page 1
list first page/term questions
hidden fields to send:
    score
    next term slug index or 0 for last

page 2
    if post, not first page
        add posted score to scoretotal hidden field
        grab next term from POST
