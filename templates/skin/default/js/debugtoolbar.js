//jQuery.noConflict();
jQuery(document).ready(function(){
    // Fix table header on large tables
    jQuery("#DebugToolbar-mysql").click(function(){
        var self = jQuery(this);

        jQuery('div.panelColDetails',self).toggle();
        return false;
    });
});



