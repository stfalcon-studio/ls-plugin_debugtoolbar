window.addEvent('domready', function() {
    // Run highlighter
    sh_highlightDocument();
    
    // Обработчик нажатия элементов панели
    var panelTabs = $$('ul.dt-panel-items li');
    
    panelTabs.each(function(current) {
        
        current.getElements('a').addEvent('click', function(event){
            
            // Capture current element class
            var elClass = $(this).getParent().get('class');

            $$('div.dt-details').hide();
            
            panelTabs.removeClass('dt-active');
            
           // event.stop();
            
            var colId = $(this).get('href').split('#')[1] || null;
        
            if(!colId) return false;

            var colDetails = $('dtItem'+colId);
        
            if(colDetails.getStyle('display') == 'none'){
                var colDetailsY = window.getSize().y * 0.7;
            }
        
            colDetails.getElements('div.dt-table-container').setStyle('height',colDetailsY+'px');
           
            if(elClass == $(this).getParent().get('class')){
                current.addClass('dt-active');
                colDetails.show();
            }else{
                current.removeClass('dt-active');
                colDetails.hide();
            }
            
            return false;
        });
    });

});