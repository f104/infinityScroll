/* 
 * Class for Mootools based infinity scroll
 * http://github.com/f104
 * @author    Kirill A. <kirusanov [at] gmail.com>
 * @license   Absolutely Free
 * Need Mootools.Core or later
 * Sorry for my english :-)
 */

var InfScroll = new Class({

  Extends: Request.HTML,

  options: {
    buffer: 100, // destination in px to element when start request
    maxPage: 2,
    data: { page: 2 }, // request data
    loadClassName: false, // class name for container on load
    navigation: false // {element: 'foo', will be destroyed}
  },

  initialize: function(element,options){
    this.parent(options);
    this.element = document.id(element);
    if (!this.element) { console.log('Scroll wrapper not found'); return; }
    this.bound = this.measure.bind(this);

    this.addEvent('onRequest',function(html){
      if(this.options.loadClassName) {
        this.element.addClass(this.options.loadClassName);
      }
    });

    this.addEvent('onComplete',function(html){
      this.adopt(html);
      this.options.data['page']++;
      this.measure();
      if(this.options.loadClassName) {
        this.element.removeClass(this.options.loadClassName);
      }
    });

    if(this.options.navigation) {
      document.id(this.options.navigation).destroy();
    }

    this.attach();
    this.measure();
  },

  adopt: function(html) {
    this.element.adopt(html);
  },

  measure: function() {
    // если высота элемента плюс его позиция по вертикали минус буфер меньше, чем высота окна плюс прокрутка - send
    var elHeight = this.element.getPosition().y + this.element.getSize().y,
      windowHeight = window.getSize().y + window.getScroll().y;
    if (elHeight - this.options.buffer < windowHeight) { this.send(); }
    return this;
  },

  send: function() {
    console.log('get page ' + this.options.data['page']);
    if(this.options.data['page'] <= this.options.maxPage) {
      this.parent();
    } else {
      this.detach();
    }
  },

  attach: function(){
    window.addEvent('resize',this.bound);
    window.addEvent('scroll',this.bound);
    return this;
  },

  detach: function(){
    window.removeEvent('resize',this.bound);
    window.removeEvent('scroll',this.bound);
    return this;
  }

});