/*
 * TBra
 * Copyright(c) 2007, taobao.com.
 * 
 * Taobao JavaScript Framework base on YUI
 * T-Bra or TB-ra whatever you like name it...
 * 
 */

if(!Array.prototype.indexOf){Array.prototype.indexOf=function(obj,fromIndex){if(fromIndex==null){fromIndex=0;}else if(fromIndex<0){fromIndex=Math.max(0,this.length+fromIndex);}
for(var i=fromIndex;i<this.length;i++){if(this[i]===obj)
return i;}
return-1;};}
if(!Array.prototype.lastIndexOf){Array.prototype.lastIndexOf=function(obj,fromIndex){if(fromIndex==null){fromIndex=this.length-1;}else if(fromIndex<0){fromIndex=Math.max(0,this.length+fromIndex);}
for(var i=fromIndex;i>=0;i--){if(this[i]===obj)
return i;}
return-1;};}
if(!Array.prototype.forEach){Array.prototype.forEach=function(f,obj){var l=this.length;for(var i=0;i<l;i++){f.call(obj,this[i],i,this);}};}
if(!Array.prototype.filter){Array.prototype.filter=function(f,obj){var l=this.length;var res=[];for(var i=0;i<l;i++){if(f.call(obj,this[i],i,this)){res.push(this[i]);}}
return res;};}
if(!Array.prototype.map){Array.prototype.map=function(f,obj){var l=this.length;var res=[];for(var i=0;i<l;i++){res.push(f.call(obj,this[i],i,this));}
return res;};}
if(!Array.prototype.some){Array.prototype.some=function(f,obj){var l=this.length;for(var i=0;i<l;i++){if(f.call(obj,this[i],i,this)){return true;}}
return false;};}
if(!Array.prototype.every){Array.prototype.every=function(f,obj){var l=this.length;for(var i=0;i<l;i++){if(!f.call(obj,this[i],i,this)){return false;}}
return true;};}
Array.prototype.contains=function(obj){return this.indexOf(obj)!=-1;};Array.prototype.copy=function(obj){return this.concat();};Array.prototype.insertAt=function(obj,i){this.splice(i,0,obj);};Array.prototype.insertBefore=function(obj,obj2){var i=this.indexOf(obj2);if(i==-1)
this.push(obj);else
this.splice(i,0,obj);};Array.prototype.removeAt=function(i){this.splice(i,1);};Array.prototype.remove=function(obj){var i=this.indexOf(obj);if(i!=-1)
this.splice(i,1);};

TB={};TB.common={trim:function(str){return str.replace(/(^\s*)|(\s*$)/g,'');},escapeHTML:function(str){var div=document.createElement('div');var text=document.createTextNode(str);div.appendChild(text);return div.innerHTML;},unescapeHTML:function(str){var div=document.createElement('div');div.innerHTML=str.replace(/<\/?[^>]+>/gi,'');return div.childNodes[0]?div.childNodes[0].nodeValue:'';},toArray:function(list,start){var array=[];for(var i=start||0;i<list.length;i++){array[array.length]=list[i];}
return array;},applyConfig:function(obj,config){if(obj&&config&&typeof config=='object'){for(var p in config){if(!YAHOO.lang.hasOwnProperty(obj,p))
obj[p]=config[p];}}
return obj;}};

(function(){var ua=navigator.userAgent.toLowerCase();isOpera=(ua.indexOf('opera')>-1),isSafari=(ua.indexOf('safari')>-1),isGecko=(!isOpera&&!isSafari&&ua.indexOf('gecko')>-1),isIE=(!isOpera&&ua.indexOf('msie')>-1);TB.bom={isGecko:(ua.indexOf('gecko')!=-1),isOpera:(ua.indexOf('opera')!=-1),isSafari:(ua.indexOf('safari')!=-1),isIE:(ua.indexOf('msie')!=-1)&&!this.isOpera,isIE6:(ua.indexOf('msie 6')!=-1)&&!this.isOpera,getCookie:function(name){var value=document.cookie.match('(?:^|;)\\s*'+name+'=([^;]*)');return value?unescape(value[1]):'';},setCookie:function(name,value,expire,domain,path){value=escape(value);value+=(domain)?'; domain='+domain:'';value+=(path)?"; path="+path:'';if(expire){var date=new Date();date.setTime(date.getTime()+(expire*86400000));value+="; expires="+date.toGMTString();}
document.cookie=name+"="+value;},removeCookie:function(name){setCookie(name,'',-1);},pickDocumentDomain:function(){var da=location.hostname.split('.'),len=da.length;var deep=arguments[0]||(len<3?0:1);if(deep>=len||len-deep<2)
deep=len-2;return da.slice(deep).join('.')+(location.port?':'+location.port:'');}}})();

TB.form={};TB.form.CheckboxGroup=new function(){var Y=YAHOO.util;var defConfig={checkAllBox:'CheckAll',checkOnInit:true}
var getChecked=function(o,i){return o.checked;}
var setChecked=function(o,i){o.checked=true;}
var setUnchecked=function(o,i){o.checked=false;}
this.attach=function(checkboxGroup,config){config=TB.common.applyConfig(config||{},defConfig);var checkboxes=[];if(checkboxGroup){if(checkboxGroup.length)
checkboxes=TB.common.toArray(checkboxGroup);else
checkboxes[0]=checkboxGroup;}
var checkAllBox=Y.Dom.get(config.checkAllBox);var handler={_checkedBoxCount:0,onCheck:new Y.CustomEvent('onCheck',this,false,Y.CustomEvent.FLAT),isCheckAll:function(){return this._checkedBoxCount==checkboxes.length;},isCheckNone:function(){return this._checkedBoxCount==0;},isCheckSome:function(){return this._checkedBoxCount!=0;},isCheckSingle:function(){return this._checkedBoxCount==1;},isCheckMulti:function(){return this._checkedBoxCount>1;},toggleCheckAll:function(){if(checkboxes.length==0){if(checkAllBox&&checkAllBox.type=='checkbox')
checkAllBox.checked=0;return false;}
var allChecked=checkboxes.every(getChecked);checkboxes.forEach(allChecked?setUnchecked:setChecked);handler._checkedBoxCount=(allChecked)?0:checkboxes.length;checkboxes.forEach(function(o){handler.onCheck.fire(o);});},toggleChecked:function(checkbox){checkbox.checked=!checkbox.checked;doCheck();handler.onCheck.fire(checkbox);},getCheckedBoxes:function(){return checkboxes.filter(getChecked);}}
var doCheck=function(){var checkedBoxes=checkboxes.filter(getChecked);if(checkAllBox&&checkAllBox.type=='checkbox'){if(checkedBoxes.length==0){checkAllBox.checked=0;}else{checkAllBox.checked=(checkedBoxes.length==checkboxes.length);}}
handler._checkedBoxCount=checkedBoxes.length;}
var clickHandler=function(ev){var checkbox=Y.Event.getTarget(ev);doCheck();handler.onCheck.fire(checkbox);return true;}
if(config.onCheck&&YAHOO.lang.isFunction(config.onCheck))
handler.onCheck.subscribe(config.onCheck,handler,true);Y.Event.on(checkboxes,'click',clickHandler);if(checkAllBox){Y.Event.on(checkAllBox,'click',handler.toggleCheckAll);}
if(config.checkOnInit){doCheck();var checkOnInit=function(){checkboxes.forEach(function(o){handler.onCheck.fire(o);});}
setTimeout(checkOnInit,10);}
return handler;}}

TB.widget={};

TB.widget.SimpleTab=new function(){var Y=YAHOO.util;var defConfig={eventType:'click',currentClass:'Current',tabClass:'',autoSwitchToFirst:true,stopEvent:true,delay:0.3};var getImmediateDescendants=function(p){var ret=[];if(!p)return ret;for(var i=0,c=p.childNodes;i<c.length;i++){if(c[i].nodeType==1)
ret[ret.length]=c[i];}
return ret;};this.decorate=function(container,config){container=Y.Dom.get(container);config=TB.common.applyConfig(config||{},defConfig);var tabPanels=getImmediateDescendants(container);var tab=tabPanels.shift(0);var tabTriggerBoxs=tab.getElementsByTagName('li');var tabTriggers,delayTimeId;if(config.tabClass){tabTriggers=Y.Dom.getElementsByClassName(config.tabClass,'*',container);}else{tabTriggers=TB.common.toArray(tab.getElementsByTagName('a'));}
var onSwitchEvent=new Y.CustomEvent("onSwitch",null,false,Y.CustomEvent.FLAT);if(config.onSwitch){onSwitchEvent.subscribe(config.onSwitch);}
var handler={switchTab:function(idx){Y.Dom.setStyle(tabPanels,'display','none');Y.Dom.removeClass(tabTriggerBoxs,config.currentClass);Y.Dom.addClass(tabTriggerBoxs[idx],config.currentClass);Y.Dom.setStyle(tabPanels[idx],'display','block');},subscribeOnSwitch:function(func){onSwitchEvent.subscribe(func);}}
var focusHandler=function(ev){if(delayTimeId)
cacelHandler();var idx=tabTriggers.indexOf(this);handler.switchTab(idx);onSwitchEvent.fire(idx);if(config.stopEvent){try{Y.Event.stopEvent(ev);}catch(e){}}
return!config.stopEvent;}
var delayHandler=function(ev){var target=this;delayTimeId=setTimeout(function(){focusHandler.call(target,ev);},config.delay*1000);if(config.stopEvent)
Y.Event.stopEvent(ev);return!config.stopEvent;}
var cacelHandler=function(){clearTimeout(delayTimeId);}
for(var i=0;i<tabTriggers.length;i++){Y.Event.on(tabTriggers[i],'focus',focusHandler);if(config.eventType=='mouse'){Y.Event.on(tabTriggers[i],'mouseover',config.delay?delayHandler:focusHandler);Y.Event.on(tabTriggers[i],'mouseout',cacelHandler);}
else{Y.Event.on(tabTriggers[i],'click',focusHandler);}}
Y.Dom.setStyle(tabPanels,'display','none');if(config.autoSwitchToFirst)
handler.switchTab(0);return handler;}};

TB.widget.SimpleScroll=new function(){var Y=YAHOO.util;var defConfig={delay:2,speed:20,startDelay:5,scrollItemCount:1}
this.decorate=function(container,config){container=Y.Dom.get(container);config=TB.common.applyConfig(config||{},defConfig);var scrollTimeId=null,pause=false;var onScrollEvent=new Y.CustomEvent("onScroll",null,false,Y.CustomEvent.FLAT);if(config.onScroll){onScrollEvent.subscribe(config.onScroll);}else{onScrollEvent.subscribe(function(){for(var i=0;i<config.scrollItemCount;i++){container.appendChild(container.getElementsByTagName('li')[0]);}});}
var scroll=function(){if(pause)return;container.scrollTop+=2;var lh=config.lineHeight||container.getElementsByTagName('li')[0].offsetHeight;if(container.scrollTop%lh<=1){clearInterval(scrollTimeId);onScrollEvent.fire();container.scrollTop=0;setTimeout(start,config.delay*1000);}}
var start=function(){if(container.scrollHeight>container.offsetHeight)
scrollTimeId=setInterval(scroll,config.speed);}
var handler={subscribeOnScroll:function(func,override){if(override===true&&onScrollEvent.subscribers.length>0)
onScrollEvent.unsubscribeAll();onScrollEvent.subscribe(func);}}
Y.Event.on(container,'mouseover',function(){pause=true;});Y.Event.on(container,'mouseout',function(){pause=false;});setTimeout(start,(config.startDelay||config.delay)*1000);return handler;}};

(function(){var Y=YAHOO.util;TB.widget.Slide=function(container,config){this.init(container,config);}
TB.widget.Slide.defConfig={slidesClass:'Slides',triggersClass:'SlideTriggers',currentClass:'Current',eventType:'click',autoPlayTimeout:5,disableAutoPlay:false};TB.widget.Slide.prototype={init:function(container,config){this.container=Y.Dom.get(container);this.config=TB.common.applyConfig(config||{},TB.widget.Slide.defConfig);try{this.slidesUL=Y.Dom.getElementsByClassName(this.config.slidesClass,'ul',this.container)[0];this.slides=this.slidesUL.getElementsByTagName('li');}catch(e){throw new Error("can't find slides!");}
this.delayTimeId=null;this.autoPlayTimeId=null;this.curSlide=-1;this.sliding=false;this.pause=false;this.onSlide=new Y.CustomEvent("onSlide",this,false,Y.CustomEvent.FLAT);if(YAHOO.lang.isFunction(this.config.onSlide)){this.onSlide.subscribe(this.config.onSlide,this,true);}
this.initSlides();this.initTriggers();if(this.slides.length>0)
this.play(1);if(!this.config.disableAutoPlay){this.autoPlay();}},initTriggers:function(){var ul=document.createElement('ul');this.container.appendChild(ul);for(var i=0;i<this.slides.length;i++){var li=document.createElement('li');li.innerHTML=i+1;ul.appendChild(li);}
ul.className=this.config.triggersClass;this.triggersUL=ul;if(this.config.eventType=='mouse'){Y.Event.on(this.triggersUL,'mouseover',this.mouseHandler,this,true);Y.Event.on(this.triggersUL,'mouseout',function(e){clearTimeout(this.delayTimeId);},this,true);}else{Y.Event.on(this.triggersUL,'click',this.clickHandler,this,true);}},initSlides:function(){Y.Event.on(this.slides,'mouseover',function(){this.pause=true;},this,true);Y.Event.on(this.slides,'mouseout',function(){this.pause=false;},this,true);Y.Dom.setStyle(this.slides,'display','none');},clickHandler:function(e){var t=Y.Event.getTarget(e);var idx=parseInt(t.innerHTML);while(t!=this.container){if(t.nodeName.toUpperCase()=="LI"){if(!this.sliding){this.play(idx,true);}
break;}else{t=t.parentNode;}}},mouseHandler:function(e){var t=Y.Event.getTarget(e);var idx=parseInt(t.innerHTML);while(t!=this.container){if(t.nodeName.toUpperCase()=="LI"){var self=this;this.delayTimeId=setTimeout(function(){self.play(idx,true);},(self.sliding?.5:.1)*1000);break;}else{t=t.parentNode;}}},play:function(n,flag){n=n-1;if(n==this.curSlide)return;var curSlide=this.curSlide>=0?this.curSlide:0;if(flag&&this.autoPlayTimeId)
clearInterval(this.autoPlayTimeId);var triggersLis=this.triggersUL.getElementsByTagName('li');triggersLis[curSlide].className='';triggersLis[n].className=this.config.currentClass;this.slide(n);this.curSlide=n;if(flag&&!this.config.disableAutoPlay)
this.autoPlay();},slide:function(n){var curSlide=this.curSlide>=0?this.curSlide:0;this.sliding=true;Y.Dom.setStyle(this.slides[curSlide],'display','none');Y.Dom.setStyle(this.slides[n],'display','block');this.sliding=false;this.onSlide.fire(n);},autoPlay:function(){var self=this;var callback=function(){if(!self.pause&&!self.sliding){var n=(self.curSlide+1)%self.slides.length+1;self.play(n,false);}}
this.autoPlayTimeId=setInterval(callback,this.config.autoPlayTimeout*1000);}}
TB.widget.ScrollSlide=function(container,config){this.init(container,config);}
YAHOO.extend(TB.widget.ScrollSlide,TB.widget.Slide,{initSlides:function(){TB.widget.ScrollSlide.superclass.initSlides.call(this);Y.Dom.setStyle(this.slides,'display','');},slide:function(n){var curSlide=this.curSlide>=0?this.curSlide:0;var args={scroll:{by:[0,this.slidesUL.offsetHeight*(n-curSlide)]}};var anim=new Y.Scroll(this.slidesUL,args,.5,Y.Easing.easeOutStrong);anim.onComplete.subscribe(function(){this.sliding=false;this.onSlide.fire(n);},this,true);anim.animate();this.sliding=true;}});TB.widget.FadeSlide=function(container,config){this.init(container,config);}
YAHOO.extend(TB.widget.FadeSlide,TB.widget.Slide,{initSlides:function(){TB.widget.FadeSlide.superclass.initSlides.call(this);Y.Dom.setStyle(this.slides,'position','absolute');Y.Dom.setStyle(this.slides,'top',this.config.slideOffsetY||0);Y.Dom.setStyle(this.slides,'left',this.config.slideOffsetX||0);Y.Dom.setStyle(this.slides,'z-index',1);},slide:function(n){if(this.curSlide==-1){Y.Dom.setStyle(this.slides[n],'display','block');}else{var curSlideLi=this.slides[this.curSlide];Y.Dom.setStyle(curSlideLi,'display','block');Y.Dom.setStyle(curSlideLi,'z-index',10);var fade=new Y.Anim(curSlideLi,{opacity:{to:0}},.5,Y.Easing.easeNone);fade.onComplete.subscribe(function(){Y.Dom.setStyle(curSlideLi,'z-index',1);Y.Dom.setStyle(curSlideLi,'display','none');Y.Dom.setStyle(curSlideLi,'opacity',1);this.sliding=false;this.onSlide.fire(n);},this,true);Y.Dom.setStyle(this.slides[n],'display','block');fade.animate();this.sliding=true;}}});})();TB.widget.SimpleSlide=new function(){this.decorate=function(container,config){if(!container)return;config=config||{};if(config.effect=='scroll'){if(navigator.product&&navigator.product=='Gecko'){if(YAHOO.util.Dom.get(container).getElementsByTagName('iframe').length>0){new TB.widget.Slide(container,config);return;}}
new TB.widget.ScrollSlide(container,config);}
else if(config.effect=='fade'){new TB.widget.FadeSlide(container,config);}
else{new TB.widget.Slide(container,config);}}}

TB.widget.SimplePopup=new function(){var Y=YAHOO.util;var popupShowTimeId,popupHideTimeId;var defConfig={position:'right',autoFit:true,eventType:'mouse',delay:.2,width:200,height:200};var checkContains=function(p,c){if(p.contains&&c!=null)
return p.contains(c);else{while(c){if(c==p)return true;c=c.parentNode;}
return false;}}
var triggerClickHandler=function tch(ev){var target=Y.Event.getTarget(ev);if(tch._target==target){this.popup.style.display=='block'?this.hide():this.show();}else{this.show();}
Y.Event.stopEvent(ev);tch._target=target;}
var triggerMouseOverHandler=function(ev){clearTimeout(popupHideTimeId);var self=this;popupShowTimeId=setTimeout(function(){self.show();},this.config.delay*1000);if(this.config.disableClick&&!this.trigger.onclick){trigger.onclick=function(e){Y.Event.stopEvent(Y.Event.getEvent(e));};}}
var popupMouseOverHandler=function(ev){clearTimeout(popupHideTimeId);Y.Event.stopEvent(ev);}
var mouseOutHandler=function(ev){clearTimeout(popupShowTimeId);Y.Event.stopEvent(ev);if(!checkContains(this.popup,Y.Event.getRelatedTarget(ev))){this.delayHide();}}
this.decorate=function(trigger,popup,config){if(YAHOO.lang.isArray(trigger)||(YAHOO.lang.isObject(trigger)&&trigger.length)){for(var i=0;i<trigger.length;i++){this.decorate(trigger[i],popup,config);}
return;}
trigger=Y.Dom.get(trigger);popup=Y.Dom.get(popup);if(!trigger||!popup)return;Y.Dom.setStyle(popup,'display','none');config=TB.common.applyConfig(config||{},defConfig);var handler={popup:popup,trigger:trigger,config:config,show:function(){this.hide();var pos=Y.Dom.getXY(this.trigger);if(YAHOO.lang.isArray(this.config.offset)){pos[0]+=parseInt(this.config.offset[0]);pos[1]+=parseInt(this.config.offset[1]);}
var tw=this.trigger.offsetWidth,th=this.trigger.offsetHeight;var pw=config.width,ph=config.height;var dw=Y.Dom.getViewportWidth(),dh=Y.Dom.getViewportHeight();var sl=Math.max(document.documentElement.scrollLeft,document.body.scrollLeft);var st=Math.max(document.documentElement.scrollTop,document.body.scrollTop);var l=pos[0],t=pos[1];if(config.position=='left'){l=pos[0]-pw;}
else if(config.position=='right'){l=pos[0]+tw;}else if(config.position=='bottom'){t=t+th;}else if(config.position=='top'){t=t-ph;if(t<0)t=0;}
if(this.config.autoFit){if(t-st+ph>dh){t=dh-ph+st-2;if(t<0){t=0;}}}
this.popup.style.position='absolute';this.popup.style.top=t+'px';this.popup.style.left=l+'px';if(this.config.effect){if(this.config.effect=='fade'){this.popup.style.display='block';Y.Dom.setStyle(this.popup,'opacity',0);var anim=new Y.Anim(this.popup,{opacity:{to:1}},.4);anim.animate();}}else{this.popup.style.display='block';}
onShowEvent.fire();},hide:function(){this.popup.style.display='none';onHideEvent.fire();},delayHide:function(){var self=this;popupHideTimeId=setTimeout(function(){self.hide();},this.config.delay*1000);}}
var onShowEvent=new Y.CustomEvent("onShow",handler,false,Y.CustomEvent.FLAT);if(config.onShow){onShowEvent.subscribe(config.onShow);}
var onHideEvent=new Y.CustomEvent("onHide",handler,false,Y.CustomEvent.FLAT);if(config.onHide){onHideEvent.subscribe(config.onHide);}
if(config.eventType=='mouse'){Y.Event.on(trigger,'mouseover',triggerMouseOverHandler,handler,true);Y.Event.on(trigger,'mouseout',mouseOutHandler,handler,true);if(!Y.Event.getListeners(popup,'mouseover')){Y.Event.on(popup,'mouseover',popupMouseOverHandler);}
if(!Y.Event.getListeners(popup,'mouseout')){Y.Event.on(popup,'mouseout',mouseOutHandler,handler,true);}}
else if(config.eventType=='click'){Y.Event.on(trigger,'click',triggerClickHandler,handler,true);}
return handler;}}
