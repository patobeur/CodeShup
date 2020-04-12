var $jQ = jQuery.noConflict();

    jQuery('#scroll-to-top').fadeOut();
    jQuery(document).ready(
        function()
        { // bug firefox
            jQuery(document).ready(
                function($){
                    jQuery(window).scroll(function()
                    {
                        if ($(this).scrollTop() > 200) {
                            jQuery('#scroll-to-top').fadeIn();
                        } else {
                            jQuery('#scroll-to-top').fadeOut();
                        }
                    }
                );
        }
    );// bug firefox

    jQuery('#scroll-to-top').click(function(){
        jQuery('html, body').animate({scrollTop : 0},1200);
        return false;
    });
    jQuery('#scroll-to-bottom').click(function(){
        jQuery('html, body').animate({scrollTop: $(document).height()-$(window).height()},1200);
        return false;
    });
});
