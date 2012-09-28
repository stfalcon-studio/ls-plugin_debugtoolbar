
var ls = ls || {};

ls.debugtoolbar = (function ($) {
	this.options = { 
		toolbar: '#DTB',
		toolbar_show: '#DTBShow',
		toolbar_display: 1,
		tabs: '#DTBTabs',
		usedTemplates: {},
		overlayElement: 0,
		overlayBox:  $('<div class="dtb-overlay"><div class="dtb-overlay-close"></div></div>').css({
			display: 'none', 
			position: 'absolute', 
			zIndex: 65000, 
			background:'rgba(50, 100, 50, .3)'
		}),
		overlayBoxTagInfo: $('<div class="dtb-taginfo"/>').css({
			position: 'absolute', 
			zIndex: 65000
		})
		
	}
	
	this.init = function(options) {
       
		if (options) $.extend(true,self.options,options);
        
		// Run code highlighter
		sh_highlightDocument();
		
		// Get meta information about used templates' files
		$(this.options.toolbar+'[tpl]').removeAttr('tpl');
		this.findUsedTemplates($('*[tpl]'));

		// Show toolbar button
		$('.dtb-show-toolbar').click(function(){
			ls.debugtoolbar.show();
		});
		
		// Hide toolbar button
		$('.dtb-hide-toolbar').click(function(){
			ls.debugtoolbar.hide();
		});
		
		// Restore the previous status of toolbar
		(($.cookie('dtb-display') || 1) == 1) ? ls.debugtoolbar.show() : ls.debugtoolbar.hide();
		
		this.adjustPanel();
		$(window).resize(function () { 
			ls.debugtoolbar.adjustPanel();
		});
		
		// Hide all sub windows
		$(this.options.tabs+' div.dtb-sub span.dtb-close').click(function(e) { 
			$(ls.debugtoolbar.options.tabs+" div.dtb-sub").hide(); 
			$(ls.debugtoolbar.options.tabs+">li>a").removeClass('active'); 
		});
		
		this.fluxTabs();
		
		// Create table search instances
		this.doStatusFilter('database', $('div.dtb-switcher>a'));
		this.doTableSearch('database');
	
		$(this.options.tabs+">li>a").tipTip();
		
		// Create element finder handler
		this.setOverlayElement($('#DTBSwTplFinder'));
		this.setElementFinder($('#DTBTplList'));
		this.options.overlayBox.appendTo('body');
		this.options.overlayBoxTagInfo.appendTo(this.options.overlayBox);
		$('div.dtb-taginfo').click(function(){
			ls.debugtoolbar.options.overlayBox.removeClass('dtb-overlay-clicked').hide();
			return false;
		});
	};
	
	// Toolbar's buttons event
	this.fluxTabs = function(){
		var btns = $(this.options.tabs+">li>a");
		btns.click(function() { 
			var $this = $(this);
			btns.removeClass('active'); 
			var subpanel = $this.next("div.dtb-sub");
			if(subpanel.is(':visible')){
				subpanel.hide(); 
			} else {
				$("div.dtb-sub").hide(); 
				subpanel.toggle(); 
				$this.toggleClass('active'); 
			}
			return false; 
		});
	};
	
	// Sorts assoc array
	this.sortObj = function (arr){
		// Setup Arrays
		var sortedKeys = [], sortedObj = {};
		// Separate keys and sort them
		for (var i in arr){
			sortedKeys.push(i);
		}
		sortedKeys.sort();
		// Reconstruct sorted obj based on keys
		for (var i in sortedKeys){
			sortedObj[sortedKeys[i]] = arr[sortedKeys[i]];
		}
		return sortedObj;
	}

	this.show = function(){
		$(this.options.toolbar).show();
		$(this.options.toolbar_show).hide();
		return this.triggerDisplay(1)
	};
	
	// Hide toolbar
	this.hide = function(){
		$(this.options.toolbar).hide();
		$(this.options.toolbar_show).show();
		return this.triggerDisplay(0)
	};
	
	// Run common operations after toolbar display changes
	this.triggerDisplay = function(status){
		var panelBtn = $('li.dtb-panel-tpl>a>span.dtb-ico');
		$.cookie('dtb-display',status,{
			path: "/"
		});
		
		if(status == 1) {
			this.options.overlayElement = $('#DTBSwTplFinder').is(':checked') ? 1 : 0;
		}else{
			this.options.overlayElement = 0;
		}
		if(this.options.overlayElement){
			if(!panelBtn.hasClass('active')) panelBtn.addClass('active');
		}else{
			panelBtn.removeClass('active');
		}
		this.adjustPanel();
		return false;
	};
	
	// Set element highliter
	this.setOverlayElement = function(btn){
		var panelBtn = $('li.dtb-panel-tpl>a>span.dtb-ico');
		var status = 0;
		btn.change(function(){
			if(this.checked){
				status =  1;
				if(!panelBtn.hasClass('active')) panelBtn.addClass('active');
                                
                var lastTarget, last = +new Date;
                var dtOptions = ls.debugtoolbar.options;
                // Enable onmousemove listener for the block finder
                $('body').bind('mousemove',function(e){
                    if(dtOptions.overlayElement == 0){
                        if(dtOptions.overlayBox.is(':visible') && !dtOptions.overlayBox.hasClass('dtb-overlay-clicked')) {
                            dtOptions.overlayBox.hide();	
                        }
                        return false;
                    } 
                    var el = e.target;
                    var now = +new Date;
                    if (now-last < 25) 
                        return;
                    last = now;
                    if ((el === document.body) || (el.id === 'DTB') || ($(el).parents('#DTB').length)) {
                        dtOptions.overlayBox.hide(); 
                        return;
                    } else if (el.className === 'dtb-overlay') {
                        dtOptions.overlayBox.hide();
                        el = document.elementFromPoint(e.clientX, e.clientY);
                    }
                    dtOptions.overlayBox.show();   

                    if (el === lastTarget) 
                        return;
                    lastTarget = el;
                    el = $(el).closest('[tpl]');
                    ls.debugtoolbar.reDrawOverlay(el);
                });
			}else{
				status =  0;
				panelBtn.removeClass('active');
                // Disable onmousemove listener for the block finder
                $('body').unbind('mousemove');
			}
			ls.debugtoolbar.options.overlayElement = status;
			return false;
		});
		this.options.overlayElement = $(this.options.toolbar).is(':hidden') ? 0 : status;
	}
	
	// Set element finder
	this.setElementFinder = function(list){
		var dtOptions = ls.debugtoolbar.options;
		list.find('li>a').click(function(){
			var path = $(this).text();
			var el = $('[tpl="'+path+'"]');
			if(el.length){
				if(el.is(':visible')){
					// Отключаем автоподсветку эелемента
					dtOptions.overlayElement = 0;
					$('#DTBSwTplFinder').removeAttr('checked');
					// Фиксируем подсветку, установив спец. класс
					dtOptions.overlayBox.addClass('dtb-overlay-clicked').show();  
					ls.debugtoolbar.reDrawOverlay(el);
					// Переместимся к выбранному элементу
					var elTop = el.offset().top - 20;
					if(elTop < 0) elTop = 0;
					$(window).scrollTop(elTop,'slow');
				}else{
					if(dtOptions.overlayBox.hasClass('dtb-overlay-clicked')){
						dtOptions.overlayBox.removeClass('dtb-overlay-clicked').hide();   
					}
					ls.msg.error('Внимание','Этот элемент скрыт!');
				}
				$(dtOptions.tabs+" div.dtb-sub").hide(); 
				$(dtOptions.tabs+">li>a").removeClass('active'); 
				$('li.dtb-panel-tpl>a>span.dtb-ico').removeClass('active');
			}else{
				ls.msg.error('Error','Please try again later');
			}
            // Disable onmousemove listener for the block finder
            $('body').unbind('mousemove');
			return false;
		});

	}
	
	//Adjust panel height
	this.adjustPanel = function(){
		return $(this.options.tabs+">li").each(function(){
			var $this = $(this);
			$this.find(".dtb-sub-content, .dtb-sub").css({
				'height' : 'auto'
			});
			var panelsub = $this.find(".dtb-sub").height(); 
			var panelAdjust = $(window).height() - 100; 
			if ( panelsub >= panelAdjust ) {
				$(this).find(".dtb-sub-content").css({
					'height' : panelAdjust - 105
				});
			}
		});
	};
	
	this.filterTable = function(id, btns, current, status){
		var rows = $('#dtb-'+id+'-table>tbody>tr');
		btns.removeClass('active');
		current.addClass('active');
		if(status){
			rows.hide();
			switch(status){
				case 'fatal':
					rows.filter('.dtb-time-fatal').show();
					break;
				case 'warning':
					rows.filter('.dtb-time-fatal, .dtb-time-warning').show();
					break;
				case 'look':
					rows.filter('.dtb-time-fatal, .dtb-time-warning, .dtb-time-look').show();
					break;
				default:
					rows.show();
			}
			$.cookie('dtb-filter-'+id, status, {
				path: "/"
			});
		}else{
			rows.show();
			$.cookie('dtb-filter-'+id, '', {
				expires: -1, 
				path: "/"
			});
		}

		this.doTableZebra(rows);
	
		return false;
	};
	
	this.doTableZebra = function(rows){
		rows = rows.filter(function(index) {
			return $(this).css('display') != 'none';
		});
		
		rows.removeClass('dtb-tr-odd dtb-tr-even');
		
		rows.filter(':even').addClass('dtb-tr-even');
		rows.filter(':odd').addClass('dtb-tr-odd');
		
		return rows;
	};
	
	this.doTableSearch = function(id){
		var input = $('#dtb-'+id+'-keyword');
		var rows = $('#dtb-'+id+'-table>tbody>tr');
		var cells = rows.children('td.dtb-td-search');
		// Clean input text
		input.val('');
		input.keyup(function(){
			// get the current value of the text field
			var keyword = this.value.toUpperCase();
			// loop over each item in cells
			cells.each(function(){
				var row = $(this).parent('tr');
				// set a string equal to the contents of the cell
				var contents = this.innerHTML.toUpperCase();
				// check the string against that cell
				contents.match(keyword) ? row.show() : row.hide();
			});
			ls.debugtoolbar.doTableZebra(rows);
		});
	};
	
	this.doStatusFilter = function(id, btns){
		var status = $.cookie('dtb-filter-'+id);
		var current = status ? btns.filter('[href="#'+status+'"]') : btns.first();
		this.filterTable(id, btns, current, status);
		btns.click(function(){
			var $this = $(this);
			status = $this.attr('href').substr(1);
			ls.debugtoolbar.filterTable(id, btns, $this, status);
			return false;
		});
	}
	
	// Show overlay over element
	this.reDrawOverlay = function(el){
		if(el.length){
			var offset = el.offset();
			this.options.overlayBox.css({
				width:  el.outerWidth()  - 2, 
				height: el.outerHeight() - 2, 
				left:   offset.left, 
				top:    offset.top 
			});
				
			var boxOffset = this.options.overlayBox.offset();
			this.options.overlayBoxTagInfo.text(el.attr('tpl'));
			var boxTagInfoX = 1;
			var boxTagInfoY = this.options.overlayBoxTagInfo.outerHeight();
				
			boxTagInfoX = -boxTagInfoX;

			if((boxOffset.top - boxTagInfoY) > (boxTagInfoY * 2)){
				boxTagInfoY = -boxTagInfoY;
			}else if($(window).outerHeight() < this.options.overlayBox.outerHeight()){
				boxTagInfoY = 0;
			}else{
				boxTagInfoY = this.options.overlayBox.outerHeight()-1;
			}
				
			this.options.overlayBoxTagInfo.css({
				left:   boxTagInfoX, 
				top:    boxTagInfoY 
			});
		}
	}
	
	this.findUsedTemplates = function(blocks){
		var used_templates = {}, li = '', visibility = 1;
		$.each(blocks, function(i, item){
			var tpl = $(item).attr('tpl');
			if(tpl){
				visibility = ($(item).css('display') == "none") ? 0 : 1;
				used_templates[tpl] = visibility;
			}
		});

		this.options.usedTemplates = this.sortObj(used_templates);
		
		$.each(this.options.usedTemplates, function(tpl, visibility){
			li += '<li class="dtb-visibility-'+visibility+'"><a href="#">'+tpl+'</a></li>'
		});
        
		if(li){
			$('#DTBTplList').html('').append(li);
		}
	};
	return this;
}).call(ls.debugtoolbar || {},jQuery);

jQuery(window).load(function () {
	ls.debugtoolbar.init();
});


$(document).ready(function(){
    $(document).click(function(e){
        var element = $(e.target);        
        if( $('#DTB').css('display') == 'block' && element.closest('.dtb-sub')[0] == undefined)  
        {
                $('.dtb-sub').css('display','none');
         }
    });
});