var ls = ls || {};

ls.debugtoolbar = (function ($) {
    this.options = { 
        toolbar: '#DebugToolbar',
        toolbar_button: '#DebugToolbarBtn',
        toolbar_display: 1,
        tabs: '#DebugToolbarMenu'
    }
	
    this.init = function(options) {
       
        if (options) $.extend(true,self.options,options);
        
        // Run highlighter
        sh_highlightDocument();
        
        // Init toolbar
        this.toggleToolbar(this.options.toolbar_button, 1);
        
        $(this.options.toolbar_button).bind('click',function(){
            ls.debugtoolbar.toggleToolbar(this);
        });
        
        // Init toolbar's tabs
        this.fluxTabs(this.options.tabs, {
            tabMode: 'toggle'
        });
        
    }
    
    this.toggleToolbar = function(btn, isCookie){
        
        isCookie = isCookie || 0;
        
        var toolbar = $(this.options.toolbar);
        
        var cookName = (this.options.toolbar + 'Display').replace(/[^\w]+/g,"");
        
        var display = $.cookie(cookName);
        
        if(isCookie) {
            display =  (display == null) ? this.options.toolbar_display : display;
        }else if(toolbar.css('display') == 'none'){
            display = 1;
        }else{
            display = 0;
        }
        
        if(display == 1) {
            $(btn).removeClass('dt-show').addClass('dt-hide');
            toolbar.show();
        }else{
            $(btn).removeClass('dt-hide').addClass('dt-show');
            toolbar.hide();
        }
        
        $.cookie(cookName, display);
    }
  
    this.fluxTabs = function(target, options){
        
        var items = $(target+" a");
        
        items.each(function(){
            
            var item =  $(this);
            
            item.bind('click', function(){
                
                $('div.dt-details').hide();

                // Get current item class
                var itemClass = $(this).attr('class');
                
                item.removeClass('active');
                
                var colId = $(this).attr('href') || null;

                if(!colId) return false;

                var colDetails = $(colId), colDetailsY = 0;
                
                if(colDetails.css('display') == 'none'){
                    colDetailsY = ($(window).height() * 0.7) - 100;
                }
            
                colDetails.find('div.dt-table-container').height(colDetailsY+'px');
            
                if(itemClass == $(this).attr('class')){
                    item.addClass('active');
                    colDetails.show();
                }else{
                    item.removeClass('active');
                    colDetails.hide();
                }
           
                return false;
            });
        });

    }
    return this;
}).call(ls.debugtoolbar || {},jQuery);

jQuery(window).load(function () {
    ls.debugtoolbar.init();
});