jQuery(document).ready(function(){
    jQuery('.slider').slick({
        draggable: false
    });
    jQuery("input[type='radio']").change(function(){
        // Do something interesting here
        jQuery('.slider').slick('slickNext');
    });
});