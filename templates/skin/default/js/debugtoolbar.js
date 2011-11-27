var ls = ls || {};

ls.debugtoolbar = (function ($) {
	this.options = { 
		toolbar: '#DTB',
		toolbar_show: '#DTBShow',
		toolbar_display: 1,
		tabs: '#DTBTabs',
		usedTemplates: {},
		overlayElement: 1
		
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
		$(this.options.tabs+' div.dtb-sub span.dtb-close').click(function() { 
			$(ls.debugtoolbar.options.tabs+" div.dtb-sub").hide(); 
			$(ls.debugtoolbar.options.tabs+">li>a").removeClass('active'); 
		});
		
		this.fluxTabs();
		
		// Create table search instances
		this.doStatusFilter('database', $('div.dtb-switcher>a'));
		this.doTableSearch('database');
	
		$(this.options.tabs+">li").tipTip();
		
		// Create element finder handler
		this.setOverlayElement($('#DTBSwTplFinder'));
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
		$.cookie('dtb-display',status,{
			path: "/"
		});
		if(status) {
			this.options.overlayElement = $('#DTBSwTplFinder').is(':checked');
		}else{
			this.options.overlayElement = 0;
		}
		this.adjustPanel();
		return false;
	};
	
	// Set element highliter
	this.setOverlayElement = function(btn){
		var status = $.cookie('dtb-overlay-element') || 0;
		if(status) {
			btn.attr('checked','checked');
		}
		
		btn.change(function(){
			status = this.checked ? 1 : 0;
			$.cookie('dtb-overlay-element',status,{
				path: "/"
			});
			ls.debugtoolbar.options.overlayElement = status;
			return false;
		});
		
		this.options.overlayElement = $(this.options.toolbar).is(':hidden') ? 0 : status;
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
	
	this.findUsedTemplates = function(blocks){
		var used_templates = [], li = '';
		$.each(blocks, function(i, item){
			var tpl = item.getAttribute('tpl');
			if(tpl){
				used_templates[i] = tpl;
			}
		});
		used_templates = $.unique(used_templates);
		used_templates.sort(function(a, b) {
			return (a > b) ? 1 : ((a < b) ? -1 : 0);
		});
		this.options.usedTemplates = used_templates;
		
		$.each(this.options.usedTemplates, function(i, item){
			li += '<li><a href="#">'+item+'</a></li>'
		});
        
		if(li){
			$('#DTBTplList').html('').append(li);
		}

		var box = $('<div class="dtb-overlay">').css({
			display: 'none', 
			position: 'absolute', 
			zIndex: 65000, 
			background:'rgba(50, 100, 50, .3)'
		}) .appendTo('body')
		.mouseout(function(){
			$(this).hide();
		});
		
		var boxTagInfo = $('<div class="dtb-taginfo"/>').css({
			position: 'absolute', 
			zIndex: 65000
		}).appendTo(box);

		var lastTarget, last = +new Date;
		$('body').mousemove(function(e){
			if(!ls.debugtoolbar.options.overlayElement){
			//	if(box.is(':visible')) box.hide();	
				return false;
			} 
			var offset, el = e.target;
			var now = +new Date;
			if (now-last < 25) 
				return;
			last = now;
			if ((el === document.body) || (el.id === 'DTB') || ($(el).parents('#DTB').length)) {
				box.hide(); 
				return;
			} else if (el.className === 'dtb-overlay') {
				box.hide();
				el = document.elementFromPoint(e.clientX, e.clientY);
			}
			box.show();   

			if (el === lastTarget) 
				return;
			lastTarget = el;
			el = $(el).closest('[tpl]');
			if(el.length){
				offset = el.offset();
				box.css({
					width:  el.outerWidth()  - 2, 
					height: el.outerHeight() - 2, 
					left:   offset.left, 
					top:    offset.top 
				});
				
				boxOffset = box.offset();
				boxTagInfo.text(el.attr('tpl'));
				var boxTagInfoX = 1;
				var boxTagInfoY = boxTagInfo.outerHeight();
				
				boxTagInfoX = -boxTagInfoX;

				if((boxOffset.top - boxTagInfoY) > (boxTagInfoY * 2)){
					boxTagInfoY = -boxTagInfoY;
				}else if($(window).outerHeight() < box.outerHeight()){
					boxTagInfoY = 0;
				}else{
					boxTagInfoY = box.outerHeight()-1;
				}
				
				boxTagInfo.css({
					left:   boxTagInfoX, 
					top:    boxTagInfoY 
				});
			}
		});
	};
	return this;
}).call(ls.debugtoolbar || {},jQuery);

jQuery(window).load(function () {
	ls.debugtoolbar.init();
});


