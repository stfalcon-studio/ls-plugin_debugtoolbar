//MooTools More, <http://mootools.net/more>. Copyright (c) 2006-2009 Aaron Newton <http://clientcide.com/>, Valerio Proietti <http://mad4milk.net> & the MooTools team <http://mootools.net/developers>, MIT Style License.

MooTools.More={version:"1.2.5.1",build:"254884f2b83651bf95260eed5c6cceb838e22d8e"};var Drag=new Class({Implements:[Events,Options],options:{snap:6,unit:"px",grid:false,style:true,limit:false,handle:false,invert:false,preventDefault:false,stopPropagation:false,modifiers:{x:"left",y:"top"}},initialize:function(){var b=Array.link(arguments,{options:Object.type,element:$defined});
this.element=document.id(b.element);this.document=this.element.getDocument();this.setOptions(b.options||{});var a=$type(this.options.handle);this.handles=((a=="array"||a=="collection")?$$(this.options.handle):document.id(this.options.handle))||this.element;
this.mouse={now:{},pos:{}};this.value={start:{},now:{}};this.selection=(Browser.Engine.trident)?"selectstart":"mousedown";this.bound={start:this.start.bind(this),check:this.check.bind(this),drag:this.drag.bind(this),stop:this.stop.bind(this),cancel:this.cancel.bind(this),eventStop:$lambda(false)};
this.attach();},attach:function(){this.handles.addEvent("mousedown",this.bound.start);return this;},detach:function(){this.handles.removeEvent("mousedown",this.bound.start);
return this;},start:function(e){if(e.rightClick){return;}if(this.options.preventDefault){e.preventDefault();}if(this.options.stopPropagation){e.stopPropagation();
}this.mouse.start=e.page;this.fireEvent("beforeStart",this.element);var a=this.options.limit;this.limit={x:[],y:[]};var d=this.element.getStyles("left","right","top","bottom");
this._invert={x:this.options.modifiers.x=="left"&&d.left=="auto"&&!isNaN(d.right.toInt())&&(this.options.modifiers.x="right"),y:this.options.modifiers.y=="top"&&d.top=="auto"&&!isNaN(d.bottom.toInt())&&(this.options.modifiers.y="bottom")};
var g,f;for(g in this.options.modifiers){if(!this.options.modifiers[g]){continue;}var c=this.element.getStyle(this.options.modifiers[g]);if(c&&!c.match(/px$/)){if(!f){f=this.element.getCoordinates(this.element.getOffsetParent());
}c=f[this.options.modifiers[g]];}if(this.options.style){this.value.now[g]=(c||0).toInt();}else{this.value.now[g]=this.element[this.options.modifiers[g]];
}if(this.options.invert){this.value.now[g]*=-1;}if(this._invert[g]){this.value.now[g]*=-1;}this.mouse.pos[g]=e.page[g]-this.value.now[g];if(a&&a[g]){for(var b=2;
b--;b){if($chk(a[g][b])){this.limit[g][b]=$lambda(a[g][b])();}}}}if($type(this.options.grid)=="number"){this.options.grid={x:this.options.grid,y:this.options.grid};
}this.document.addEvents({mousemove:this.bound.check,mouseup:this.bound.cancel});this.document.addEvent(this.selection,this.bound.eventStop);},check:function(a){if(this.options.preventDefault){a.preventDefault();
}var b=Math.round(Math.sqrt(Math.pow(a.page.x-this.mouse.start.x,2)+Math.pow(a.page.y-this.mouse.start.y,2)));if(b>this.options.snap){this.cancel();this.document.addEvents({mousemove:this.bound.drag,mouseup:this.bound.stop});
this.fireEvent("start",[this.element,a]).fireEvent("snap",this.element);}},drag:function(a){if(this.options.preventDefault){a.preventDefault();}this.mouse.now=a.page;
for(var b in this.options.modifiers){if(!this.options.modifiers[b]){continue;}this.value.now[b]=this.mouse.now[b]-this.mouse.pos[b];if(this.options.invert){this.value.now[b]*=-1;
}if(this._invert[b]){this.value.now[b]*=-1;}if(this.options.limit&&this.limit[b]){if($chk(this.limit[b][1])&&(this.value.now[b]>this.limit[b][1])){this.value.now[b]=this.limit[b][1];
}else{if($chk(this.limit[b][0])&&(this.value.now[b]<this.limit[b][0])){this.value.now[b]=this.limit[b][0];}}}if(this.options.grid[b]){this.value.now[b]-=((this.value.now[b]-(this.limit[b][0]||0))%this.options.grid[b]);
}if(this.options.style){this.element.setStyle(this.options.modifiers[b],this.value.now[b]+this.options.unit);}else{this.element[this.options.modifiers[b]]=this.value.now[b];
}}this.fireEvent("drag",[this.element,a]);},cancel:function(a){this.document.removeEvent("mousemove",this.bound.check);this.document.removeEvent("mouseup",this.bound.cancel);
if(a){this.document.removeEvent(this.selection,this.bound.eventStop);this.fireEvent("cancel",this.element);}},stop:function(a){this.document.removeEvent(this.selection,this.bound.eventStop);
this.document.removeEvent("mousemove",this.bound.drag);this.document.removeEvent("mouseup",this.bound.stop);if(a){this.fireEvent("complete",[this.element,a]);
}}});Element.implement({makeResizable:function(a){var b=new Drag(this,$merge({modifiers:{x:"width",y:"height"}},a));this.store("resizer",b);return b.addEvent("drag",function(){this.fireEvent("resize",b);
}.bind(this));}});