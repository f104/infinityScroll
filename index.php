<?php

error_reporting(E_ALL);

$page = 1;
$max_page = 10;
$onpage = 10;
if (isset($_GET['page'])) { 
  // ajax
  $page = abs((int)$_GET['page']);
  if ($page > $max_page) { die; }
  echo '<h2>page '.$page.' on '.$max_page.'</h2>';
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
    .scrollLoad {background: url('images/spinner16.gif') bottom center no-repeat}
  </style>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/mootools/1.4.1/mootools-yui-compressed.js"></script>
  <script type="text/javascript" src="js/infinityScroll.js"></script>
  <script>
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
    <h2>page 1 on <?= $max_page ?></h2>
    <?php
    for ($i = 1; $i <= $onpage; $i++) {
      echo '<p>'.$i.'</p>';
    }
    ?>
  </div>
  
</body>
</html>