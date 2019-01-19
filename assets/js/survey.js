jQuery(document).ready(function(){
    //Initiate slick slider
    jQuery('.slider').slick({
        draggable: false,
        infinit: false,
        arrows: false,
    });

    //Update background, progress bar, etc. before slide
    jQuery('.slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){
        //Fade out slider content background, then snap back in
        jQuery('[data-slick-index="'+currentSlide+'"]').fadeOut('10').fadeIn('0');
        
        //Update background image position and progress bar
        var bgpos = 0;
        var currSlide = parseInt(currentSlide) + 1; //add 1 for index 0
        var totalSlides = parseInt(slick.slideCount) - 1; //remove last result slide from total
        bgpos = Math.round( (currSlide / totalSlides ) * 100 );
        jQuery('#ssldr_container').css('background-position-y', (100-bgpos)+'%');
        console.log((100-bgpos)+'%');
        jQuery('#progressbar > div').css('width', bgpos+'%');
    });

    //Detect first and last slides and hide controls
    /*jQuery('.slick-prev').css('display', 'none');
    jQuery('.slider').on('afterChange', function(event, slick, currentSlide) {
        //Hide next button if last slide
        if (slick.$slides.length-1 == currentSlide) {
            jQuery('.slick-next').css('display', 'none');
        } else {
            jQuery('.slick-next').css('display', 'block');
        }
        //Hide previou button if first slide
        if (0 == currentSlide) {
            jQuery('.slick-prev').css('display', 'none');
        } else {
            jQuery('.slick-prev').css('display', 'block');
        }

    })*/
    
    //Get hidden form elements
    var pages = jQuery('.ssldr_pagecount').val();
    var total_questions = parseInt( jQuery('.ssldr_totalquestions').val() );
    
    //Do things when any radio button is clicked
    jQuery("input[type='radio']").change(function() {
        //Go to next slide if radio button clicked
        jQuery('.slider').slick('slickNext');

        //Loop through radio button sections
        var i = 1;
        for(i = 1; i <= pages; i++) {
            var section_points = 0;
            var section_score = 0;

            //Grab points from checked answers only
            jQuery('.sect'+i+':checked').each(function() { 
                section_points += parseInt(jQuery(this).val());  //section totals
                //totals[i] += parseInt(jQuery(this).val()); //section totals
            });

            //Update each end section score
            section_score = Math.round( ( section_points / jQuery('.ssldr_s'+i+'total').val() * 100 ) );
            jQuery('.ssldr_section'+i+'_score').val(section_score);
        }

        //Loop through all radio buttons for totals, regardless of section
        //var curr_points = 0;
        var curr_questions = 0;
        jQuery(".ssldr_radio:checked").each(function() {
            //curr_points = curr_points + parseInt( jQuery(this).val() );
            curr_questions++;
        });
        jQuery('.ssldr_totalq').each(function(){
            jQuery(this).html(curr_questions);
        });
        //jQuery('#ssldr_totalp').html(curr_points);
        
        /*
        //Update total score
        var score = 0;
        score = Math.round((curr_points / (total_questions * 5) ) * 100);
        jQuery('.ssldr_score').html(score);
        */
    })

    /*jQuery(".ssldr_button").click(function() {
        jQuery('.slider').slick('slickNext');
    });*/

    //appends an "active" class to .popup and .popup-content when the "Open" button is clicked
    jQuery(".open").each(function() {
        jQuery(this).on("click", function( e ){
            //jQuery('.slider').slick("setPosition", 0);
            e.preventDefault();
            jQuery(this).parent().siblings(".popup-overlay").addClass("active");
        });
    });
    //removes the "active" class to .popup and .popup-content when the "Close" button is clicked 
    jQuery(".close, .popup-overlay").each(function() {
        jQuery(this).on("click", function( e ){
            e.preventDefault();
            jQuery(".popup-overlay").each(function() {
                jQuery(this).removeClass("active");
            });
        });
    });

});

