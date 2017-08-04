
/*! Copyright (c) 2011 Brandon Aaron (http://brandonaaron.net)
 * Licensed under the MIT License (LICENSE.txt).
 *
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 * Thanks to: Seamus Leahy for adding deltaX and deltaY
 *
 * Version: 3.0.6
 * 
 * Requires: 1.2.2+
 */

(function($) {

var types = ['DOMMouseScroll', 'mousewheel'];

if ($.event.fixHooks) {
    for ( var i=types.length; i; ) {
        $.event.fixHooks[ types[--i] ] = $.event.mouseHooks;
    }
}

$.event.special.mousewheel = {
    setup: function() {
        if ( this.addEventListener ) {
            for ( var i=types.length; i; ) {
                this.addEventListener( types[--i], handler, false );
            }
        } else {
            this.onmousewheel = handler;
        }
    },
    
    teardown: function() {
        if ( this.removeEventListener ) {
            for ( var i=types.length; i; ) {
                this.removeEventListener( types[--i], handler, false );
            }
        } else {
            this.onmousewheel = null;
        }
    }
};

$.fn.extend({
    mousewheel: function(fn) {
        return fn ? this.bind("mousewheel", fn) : this.trigger("mousewheel");
    },
    unmousewheel: function(fn) {
        return this.unbind("mousewheel", fn);
    }
});


function handler(event) {
    var orgEvent = event || window.event, args = [].slice.call( arguments, 1 ), delta = 0, returnValue = true, deltaX = 0, deltaY = 0;
    event = $.event.fix(orgEvent);
    event.type = "mousewheel";
    
    // Old school scrollwheel delta
    if ( orgEvent.wheelDelta ) { delta = orgEvent.wheelDelta/120; }
    if ( orgEvent.detail     ) { delta = -orgEvent.detail/3; }
    
    // New school multidimensional scroll (touchpads) deltas
    deltaY = delta;
    
    // Gecko
    if ( orgEvent.axis !== undefined && orgEvent.axis === orgEvent.HORIZONTAL_AXIS ) {
        deltaY = 0;
        deltaX = -1*delta;
    }
    
    // Webkit
    if ( orgEvent.wheelDeltaY !== undefined ) { deltaY = orgEvent.wheelDeltaY/120; }
    if ( orgEvent.wheelDeltaX !== undefined ) { deltaX = -1*orgEvent.wheelDeltaX/120; }
    
    // Add event and delta to the front of the arguments
    args.unshift(event, delta, deltaX, deltaY);
    
    return ($.event.dispatch || $.event.handle).apply(this, args);
}

})(jQuery);



/*! Copyright (c) 2012 Slava Balasanov (http://balasan.net)
 * Licensed under the MIT License (LICENSE.txt).
 *
 * Version: 0.1.0
 * 
 */



;(function( $ ){

  var methods = {
      init : function( options ) { 
    var settings = $.extend( {
    
      selector: '.swipeSection',
      current: '',
      urls : null,
      easing: 'swing',
      callback : function(self, el, time){

        if(time == undefined)
          time = 600

        var scrollOffset = 0
        if(self.prop("tagName")!="BODY")
           scrollOffset = self.scrollTop()
        target = el.offset().top + scrollOffset
        self.stop().animate({scrollTop: target},time, settings.easing)

      }
    }, options);
    
    var regularScroll=false;
    var scrolling= false;
    var doCheck = false;
    var maxY = minY =0
    var checkTimeout=false;
    var lastY=0;
    
    var self=this;
    
    this.data('current', settings.current)
    
    return this.each(function(){
    
      var $this = $(this)
      
      settings.current=$this.data('current')

      if(settings.current==""){

        var closest = null;
        var closestId

        settings.current = $this.children(settings.selector + ":first");        

        $(window).scroll(function(){

          $(window).unbind('scroll')

          $this.find(settings.selector).each(function(){

            if(closest ==null || Math.abs($this.scrollTop() - $(this).position().top) < closest){
              closest = Math.abs($this.scrollTop() - $(this).position().top)
              settings.current = $(this);
            }
          })
          
          settings.callback($this, settings.current, 300)

        })
      }

      var okToSwipe = true;
      var touchStartX;
      var touchStartY;
      var deltaX;
      var deltaY;

      var isMobile = {
          Android: function() {
              return navigator.userAgent.match(/Android/i);
          },
          BlackBerry: function() {
              return navigator.userAgent.match(/BlackBerry/i);
          },
          iOS: function() {
              return navigator.userAgent.match(/iPhone|iPad|iPod/i);
          },
          Opera: function() {
              return navigator.userAgent.match(/Opera Mini/i);
          },
          Windows: function() {
              return navigator.userAgent.match(/IEMobile/i);
          },
          any: function() {
              return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
          }
      };


      if(isMobile.any() || settings.hideScrollbar){

        $this.css({overflow:'hidden'})
        $(settings.selector).css({'max-height':'100%'
                                  ,'overflow-x':'auto'
                                  ,'webkitOverflowScrolling':'touch'
                                  })

                                  // .each(function(){

                                  //   var extra = $(window).width()-$(this).width()
                                  //   $(this).width( $(this).width + extra)

                                  // })

      }



      // TOUCH EVENTS
      $this.bind('touchstart', function(touch){
        touchStartX = touch.originalEvent.targetTouches[0].pageX
        touchStartY = touch.originalEvent.targetTouches[0].pageY
      })
      
      $this.bind('touchmove', function(touch){

        deltaX = touchStartX - touch.originalEvent.targetTouches[0].pageX;
        deltaY = touchStartY - touch.originalEvent.targetTouches[0].pageY;


        if(!okToSwipe){
          touch.preventDefault()
          return;
        }
        
        var wh = $(window).height()
        var ret=false;
        var rettop=false;

        var scrollOffset
        if($(settings.current).css('overflow') == 'auto' || $(settings.current).css('overflow') == 'scroll')
          scrollOffset = $(settings.current).scrollTop()
        else {        
          scrollOffset = $('body').scrollTop()-$(settings.current).offset().top 

          if(regularScroll && deltaY>20 && scrollOffset + wh > $(settings.current)[0].scrollHeight){
              ret=true
          }
          else if (regularScroll && deltaY<-20 && scrollOffset < 0){
              rettop=true;
          }
        }

        
        regularScroll = true;
        if(deltaY>20){
          if( scrollOffset + wh >= settings.current[0].scrollHeight){

            if(settings.current.next(settings.selector)[0]!=undefined){
              scrolling=true;

              settings.current = $(settings.current).next(settings.selector)
              if(settings.urls!=null && settings.urls[settings.current.attr('id')]!=undefined)
                  History.pushState(null, null, settings.urls[settings.current.attr('id')]);
              settings.callback($this, settings.current) 
              rettop=false;
              ret=false;  
              okToSwipe = false;

 
            }
            regularScroll=false;

          }
          else
            if(!$(settings.current).height()>wh)
              regularScroll=true;
        }
        if(deltaY<-20){
          if(scrollOffset <= 0){
            if(settings.current.prev(settings.selector)[0]!=undefined){
              scrolling=true;

              settings.current = $(settings.current).prev(settings.selector)
              if(settings.urls!=null && settings.urls[settings.current.attr('id')]!=undefined)
                History.pushState(null, null, settings.urls[settings.current.attr('id')]);
              settings.callback($this, settings.current)      
              rettop=false;
              ret=false; 
              okToSwipe = false;

            }
            regularScroll=false;
          }
          else
            if(!$(settings.current).height()>wh)
              regularScroll=true;
        }


        if(!regularScroll)
          touch.preventDefault()

        if(ret && regularScroll){
        
          dist = -scrollOffset + settings.current[0].scrollHeight - wh
          
          var target = $this.scrollTop() - Math.abs(dist)
          $this.stop().animate({scrollTop : target}, 300)
          regularScroll=false;

        }
        if(rettop && regularScroll){
          // $this.stop().scrollTo(settings.current, 300)
          settings.callback($this,settings.current, 300)
          regularScroll=false;
        }
       
      
      })
      
      $this.bind('touchend', function(touch){
        okToSwipe = true;
        console.log('touchEnded')
      })
      


      //BROWSER

      $this.mousewheel(function(event, delta, deltaX, deltaY){
        
        if($(window).scrollTop() == 0)
          $(window).unbind('scroll')

        wh = $(window).height()
        var ret=false;
        var rettop=false;

        var scrollOffset
        if($(settings.current).css('overflow') == 'auto' || $(settings.current).css('overflow') == 'scroll')
          scrollOffset = $(settings.current).scrollTop()
        else {        
          console.log($(settings));
          scrollOffset = $('body').scrollTop()-$(settings.current).offset().top 



          if(regularScroll && deltaY<0 && scrollOffset + wh > $(settings.current)[0].scrollHeight){
              ret=true
          }
          else if (regularScroll && deltaY>0 && scrollOffset < 0){
              rettop=true;
          }
        }
        if(doCheck){
          if( Math.abs(lastY)+.4<Math.abs(deltaY) || (lastY<0 && deltaY>0) || (lastY>0 && deltaY<0) ){
            
            scrolling=false;
            doCheck = false;
            maxY = minY = 0;
            regularScroll=false;
  
             if(settings.current[0].scrollHeight>wh)
              regularScroll=true;
          }         
        }
        
        lastY = deltaY;

        if(!scrolling){
          if(deltaY<0){
            if( scrollOffset + wh >= settings.current[0].scrollHeight){

              if(settings.current.next(settings.selector)[0]!=undefined){
                scrolling=true;

                settings.current = $(settings.current).next(settings.selector)
                if(settings.urls!=null && settings.urls[settings.current.attr('id')]!=undefined)
                    History.pushState(null, null, settings.urls[settings.current.attr('id')]);
                settings.callback($this, settings.current) 
                rettop=false;
                ret=false;  
                regularScroll=false;
   
              }
            }
            else
              regularScroll=true;
          }
          if(deltaY>0){
            if(scrollOffset <= 0){
              if(settings.current.prev(settings.selector)[0]!=undefined){
                scrolling=true;

                settings.current = $(settings.current).prev(settings.selector)
                if(settings.urls!=null && settings.urls[settings.current.attr('id')]!=undefined)
                  History.pushState(null, null, settings.urls[settings.current.attr('id')]);
                settings.callback($this, settings.current)      
                rettop=false;
                ret=false; 
                regularScroll=false;

              }
            }
            else
              regularScroll=true;
  
          }
  

          clearTimeout(checkTimeout);
    
          checkTimeout = setTimeout(function(){
            
            doCheck = true;
            
          }, 700)
    
          }
          scrolling=true;
          


          clearTimeout($.data($this, 'timer'));
          
          $.data($this, 'timer', setTimeout(function() {
            scrolling=false;
            doCheck = false;
            maxY = minY = 0;
            regularScroll=false;
    
            if(settings.current[0].scrollHeight>wh)
              regularScroll=true;
            
          }, 250));

            if(ret && regularScroll){
            
              dist = -scrollOffset + settings.current[0].scrollHeight - wh
              
              var target = $this.scrollTop() - Math.abs(dist)
              $this.stop().animate({scrollTop : target}, 300)
              regularScroll=false;

            }
            if(rettop && regularScroll){
              settings.callback($this,settings.current, 300)
              regularScroll=false;
            }
          
          if(scrolling && !regularScroll){
            event.preventDefault();

          }
        })
      })
      },
      current : function(page) {

        this.data('current',page)     
        
      }
  };
  
   $.fn.swipeScroll = function( method ) {
      
      // Method calling logic
      if ( methods[method] ) {
        return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
      } else if ( typeof method === 'object' || ! method ) {
        return methods.init.apply( this, arguments );
      } else {
        $.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
      } 
    
   }
      
})( jQuery );
