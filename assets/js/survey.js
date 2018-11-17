jQuery(document).ready(function(){
    //Initiate slick slider
    jQuery('.slider').slick({
        draggable: false,
        infinit: false,
        arrows: true,
    });

    //Fade out slider content background, then snap back in
    jQuery('.slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){
        jQuery('[data-slick-index="'+currentSlide+'"]').fadeOut('10').fadeIn('0');
    });

    //Detect first and last slides and hide controls
    jQuery('.slick-prev').css('display', 'none');
    jQuery('.slider').on('afterChange', function(event, slick, currentSlide) {
        //Hide next button if last slide
        if (slick.$slides.length-1 == currentSlide) {
            jQuery('.slick-next').css('display', 'none');
        } else {
            jQuery('.slick-next').css('display', 'block');
        }
        //Hide previou button if first slide
        if (0 == currentSlide) {
            console.log('first slide');
            jQuery('.slick-prev').css('display', 'none');
        } else {
            jQuery('.slick-prev').css('display', 'block');
        }
    })
    
    //Get page count from hidden form element
    var pages = jQuery("input[name='ssldr_pagecount']").val();
    var total_points = jQuery("input[name='ssldr_totalpoints']").val();
    
    //Do things when any radio button is clicked
    jQuery("input[type='radio']").change(function() {
        jQuery('.slider').slick('slickNext');

        //Loop through radio button sections
        var i = 1;
        for(i = 1; i <= pages; i++) {
            var section_points = 0;
            jQuery('.sect'+i+':checked').each(function() {
                section_points += parseInt(jQuery(this).val());
            });
            jQuery('#ssldr_section'+i+'_points').html(section_points);
        }

        //Loop through all radio buttons for totals, regardless of section
        var curr_points = 0;
        var curr_questions = 0;
        jQuery(".ssldr_radio:checked").each(function() {
            curr_points = ( curr_points + parseInt(jQuery(this).val()) );
            curr_questions++;
        });
        jQuery('#ssldr_totalq').html(curr_questions);
        jQuery('#ssldr_totalp').html(curr_points);

        //Update background image
        var bgpos = 0;
        bgpos = Math.round((curr_points / total_points) * 100);
        jQuery('#ssldr_container').css('background-position-y', (100-bgpos)+'%');
        //console.log('points:'+curr_points + ' total_points:'+total_points+' bgpos:'+bgpos); 
        
    })

    jQuery(".ssldr_button").click(function() {
        jQuery('.slider').slick('slickNext');
    });
});