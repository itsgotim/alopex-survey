<?php /**
 * Display our questionnaire with a simple shortcode 
 * Usage 1 (Display content between tags): 
 *      [ssldr_popup type="content(default)" linktext="Open Verse(default)"]Content (verse) goes here[/ssldr_poup]
 * Usage 2 (Show report question form, sends to phillip@avoidants.com): 
 *      [ssldr_popup type="report" linktext="Report Question(default)" question="Question 69"]
 * Usage 3 (Share results via email):
 *      [ssldr_popup type="share" linktext="Email(default)"]
 *  */
$report_email = "phillip@avoidants.com";
//$from_address = "reports@avoidants.com";
$from_address = "reports@dev-quiz.wildnine.net";

 //Verse shortcode
function ssldr_popup_shortcode( $atts, $content = "" ) {
    $type = (empty($atts['type'])) ? 'content' : $atts['type'];
    $the_content = ''; //What the shortcode returns

    //Decide what to do based on $type: content, report or share
    switch ($type) {
        //Default if type is empty or type is "content". Displays content that is between tags in popup window
        case 'content':
            $linktext = (empty($atts['linktext'])) ? 'Open Verse' : $atts['linktext'];
            $the_content = '<div class="popup-overlay">'
                                .'<div class="popup-content">'
                                    .$content
                                    .'<button class="close">Close</button>'
                                .'</div>'
                            .'</div>'
                            .'<p><a href="#" class="open">'.$linktext.'</a></p>';
            return $the_content;
            break;
        //Reporting question, display email form that goes to phillip@avoidants.com
        case 'report':
            $linktext = (empty($atts['linktext'])) ? 'Report Question' : $atts['linktext'];
            $question = (empty($atts['question'])) ? 'Unknown Question' : $atts['question'];
            $the_content = '<div class="popup-overlay">'
                                .'<div class="popup-content">'
                                    .'The email form using wp_mail() goes here for reporting '.$question
                                    .'<button class="close">Close</button>'
                                .'</div>'
                            .'</div></div>'
                            .'<p><a href="#" class="open">'.$linktext.'</a></p>';
            return $the_content;
            break;
        //Sharing email form, user enters email (must sanitize)
        case 'share':

            break;
    } 
    
}

add_shortcode('ssldr_popup', 'ssldr_popup_shortcode');