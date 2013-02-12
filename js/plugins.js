// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());


// Place any jQuery/helper plugins in here.

// jquery sprite - http://www.lukelutman.com

(function($){$.fn.sprite=function(options){var base=this,opts=$.extend(true,{},$.fn.sprite.defaults,options||{}),w=opts.cellSize[0],h=opts.cellSize[1],ys=opts.cells[0],xs=opts.cells[1],row=opts.initCell[0],col=opts.initCell[1],offx=opts.offset[0],offy=opts.offset[1],timer=null;this.next=function(){var lookup=col+1;if(lookup>xs-1){if(!opts.wrap){base.stop();return;}lookup=0;}col=lookup;_setSprite(base,row,col);};this.prev=function(){var lookup=col-1;if(lookup<0){if(!opts.wrap){base.stop();return;}lookup=xs-1;}col=lookup;_setSprite(base,row,col);};this.go=function(){if(timer){base.stop();}if(!timer){timer=setInterval(this.next,opts.interval);}};this.revert=function(){if(timer){base.stop();}if(!timer){timer=setInterval(this.prev,opts.interval);}};this.stop=function(){if(timer){clearTimeout(timer);timer=null;}};this.cell=function(r,c){row=r;col=c;_setSprite(base,row,col);};this.row=function(r){if(r>ys-1){r=(opts.wrap)?0:ys-1;}if(r<0){r=(opts.wrap)?ys-1:0;}this.cell(r,0);};this.col=function(c){if(c>xs-1){c=(opts.wrap)?0:xs-1;}if(c<0){c=(opts.wrap)?xs-1:0;}this.cell(row,c);};this.offset=function(x,y){offx=x;offy=y;_setSprite(0,0);};return this.each(function(index,el){var $this=$(this);$this.css({width:w,height:h});if($this.css("display")=="inline"){$this.css("display","inline-block");}_setSprite(this,row,col);});function _setSprite(el,row,col){var x=(-1*((w*col)+offx)),y=(-1*((h*row)+offy));/*console.log(x+"px "+y+" px")*/;$(el).css("background-position",x+"px "+y+"px");}};$.fn.sprite.defaults={cellSize:[0,0],cells:[1,1],initCell:[0,0],offset:[0,0],interval:50,wrap:true};})(jQuery);

/*
CSS Browser Selector v0.4.0 (Nov 02, 2010)
Rafael Lima (http://rafael.adm.br)
http://rafael.adm.br/css_browser_selector
License: http://creativecommons.org/licenses/by/2.5/
Contributors: http://rafael.adm.br/css_browser_selector#contributors
*/
function css_browser_selector(u){var ua=u.toLowerCase(),is=function(t){return ua.indexOf(t)>-1},g='gecko',w='webkit',s='safari',o='opera',m='mobile',h=document.documentElement,b=[(!(/opera|webtv/i.test(ua))&&/msie\s(\d)/.test(ua))?('ie ie'+RegExp.$1):is('firefox/2')?g+' ff2':is('firefox/3.5')?g+' ff3 ff3_5':is('firefox/3.6')?g+' ff3 ff3_6':is('firefox/3')?g+' ff3':is('gecko/')?g:is('opera')?o+(/version\/(\d+)/.test(ua)?' '+o+RegExp.$1:(/opera(\s|\/)(\d+)/.test(ua)?' '+o+RegExp.$2:'')):is('konqueror')?'konqueror':is('blackberry')?m+' blackberry':is('android')?m+' android':is('chrome')?w+' chrome':is('iron')?w+' iron':is('applewebkit/')?w+' '+s+(/version\/(\d+)/.test(ua)?' '+s+RegExp.$1:''):is('mozilla/')?g:'',is('j2me')?m+' j2me':is('iphone')?m+' iphone':is('ipod')?m+' ipod':is('ipad')?m+' ipad':is('mac')?'mac':is('darwin')?'mac':is('webtv')?'webtv':is('win')?'win'+(is('windows nt 6.0')?' vista':''):is('freebsd')?'freebsd':(is('x11')||is('linux'))?'linux':'','js']; c = b.join(' '); h.className += ' '+c; return c;}; css_browser_selector(navigator.userAgent);