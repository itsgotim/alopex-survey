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
    
    //Get hidden form elements
    var pages = jQuery("input[name='ssldr_pagecount']").val();
    var total_points = jQuery("input[name='ssldr_totalpoints']").val();
    var s1total = jQuery("input[name='ssldr_s1total").val();
    var s2total = jQuery("input[name='ssldr_s2total").val();
    var s3total = jQuery("input[name='ssldr_s3total").val();
    //console.log(s1total+' '+s2total+' '+s3total);
    
    //Do things when any radio button is clicked
    jQuery("input[type='radio']").change(function() {
        jQuery('.slider').slick('slickNext');

        //Loop through radio button sections
        var i = 1;
        //var totals = [] //for end section summaries
        for(i = 1; i <= pages; i++) {
            var section_points = 0;
            var section_score = 0;
            //totals[i] = 0;
            jQuery('.sect'+i+':checked').each(function() { //Update totals for checked answers only
                section_points += parseInt(jQuery(this).val());  //section totals
                //totals[i] += parseInt(jQuery(this).val()); //section totals
            });
            jQuery('.ssldr_section'+i+'_score').each(function() { //update each end section score
                section_score = Math.round( ( section_points / jQuery("input[name='ssldr_s"+i+"total").val() * 100 ) );
                console.log(section_score);
                jQuery(this).html(section_score);
            }); 
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
        var score = 0;
        score = Math.round((curr_points / total_points) * 100);
        jQuery('.ssldr_score').each(function() {
            jQuery(this).html(score)
        });
        jQuery('#ssldr_container').css('background-position-y', (100-score)+'%');
        //console.log('points:'+curr_points + ' total_points:'+total_points+' bgpos:'+bgpos); 
        
    })

    jQuery(".ssldr_button").click(function() {
        jQuery('.slider').slick('slickNext');
    });
});