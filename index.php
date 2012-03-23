<?php

error_reporting(E_ALL);

$page = 1;
$max_page = 10;
$onpage = 10;
if (isset($_GET['page'])) { 
  // ajax
  $page = abs((int)$_GET['page']);
  if ($page > $max_page) { die; }
  echo '<h2>page '.$page.'</h2>';
  for ($i = 1; $i <= $onpage; $i++) {
    echo '<p>'.$i.'</p>';
  }
  die;
}


?>

<!doctype html>
<html lang="ru">  
<head>
  <meta charset="utf-8" />
  <style>
    #scroll_wrapper {border: 1px solid gray; padding: 20px}
    .scrollLoad {background: url('spinner16.gif') bottom center no-repeat}
  </style>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/mootools/1.4.1/mootools-yui-compressed.js"></script>
  <script src="mootools-more-1.4.0.1.js"></script>
  <script>

    var InfScroll = new Class({

      Extends: Request.HTML,

      options: {
        buffer: 100,
        maxPage: 2,
        data: { page: 2 },
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

    window.addEvent('domready', function() {
      var infScroll = new InfScroll('scroll_wrapper', {
        url: 'index.php',
        method: 'get',
        maxPage: 10,
        loadClassName: 'scrollLoad',
        data: {
          page: 2
        }
      });
    });

  </script>
</head>
<body>
  <h1>Mootools infinity scroll</h1>
  
  <div id="scroll_wrapper" class="scrollLoad">
    <h2>page 1</h2>
    <?php
    for ($i = 1; $i <= $onpage; $i++) {
      echo '<p>'; echo $i * $page; echo '</p>';
    }
    ?>
  </div>
  
</body>
</html>