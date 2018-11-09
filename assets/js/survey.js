jQuery(document).ready(function(){
    //Initiate slick slider
    jQuery('.slider').slick({
        draggable: false,
        infinit: false
    });
    
    //Get page count from hidden form element
    var pages = jQuery("input[name=alos_pagecount").val();
    var total_points = jQuery("input[name=alos_totalpoints").val();
    console.log(pages);
    
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
            jQuery('#alosurvey_section'+i+'_points').html(section_points);
            console.log('section'+i);
        }

        //Loop through all radio buttons, regardless of section
        var curr_points = 0;
        var curr_questions = 0;
        jQuery(".alosurvey_radio:checked").each(function() {
            curr_points = ( curr_points + parseInt(jQuery(this).val()) );
            curr_questions++;
        });
        jQuery('#alosurvey_totalq').html(curr_questions);
        jQuery('#alosurvey_totalp').html(curr_points);

        //Update background image
        var bgpos = 0;
        bgpos = Math.round((curr_points / total_points) * 100);
        jQuery('#alosurvey_container').css('background-position-y', (100-bgpos)+'%');
        //console.log('points:'+curr_points + ' total_points:'+total_points+' bgpos:'+bgpos); 
        
    })

    jQuery(".alosurvey_button").click(function() {
        jQuery('.slider').slick('slickNext');
    });
});