<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

?>
<!DOCTYPE html>
<head>
<meta name="viewport" content="width=device-width">
  <?php echo $this->Html->charset(); ?>
  <title>
    <?php echo $title_for_layout; ?>
  </title>

  <?php
    echo $this->Html->meta('icon');
    echo $scripts_for_layout;
  ?>
  <script src="/public/libraries.js"></script>
  <script src="/public/app.js"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
  <link rel="stylesheet" href="/public/app.css" />
  <!-- <link href='//fonts.googleapis.com/css?family=Noto+Sans' rel='stylesheet' type='text/css'> -->
  <script>
  var floatUser = "<?php echo $this->Session->read('Auth.User.username'); ?>";
  </script>
</head>
<body class="user-<?echo$this->Session->read("Auth.User.id");?>">

<div class="loader-bg loader-item"></div>
<div class="loader loader-item">
  <img id="new-img-target" src="/img/smiley.svg" />
</div>
<div class="loader-text loader-item">Loading...</div>
<div class="loader-progress"><div></div></div>
<div id="result-panel-wrapper">
  <div id="result-panel">
    <div class="hell">
      <div id="result-panel-text-top">Congratulations</div>
      <div id="result-panel-text-bottom">You uploaded the worst photo ever</div>
      <button class="large round" id="results-panel-close" onClick="closeResult();">Ok</button>
    </div>
  </div>
</div>

<div class="fixed">
  <nav class="top-bar" data-topbar role="navigation">
    <ul class="title-area">
      <li class="name">
        <h1><a href="/">Float</a></h1>
      </li>
       <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
      <li class="toggle-topbar menu-icon"><a href="#"><span></span></a></li>
    </ul>
  
    <section class="top-bar-section">
      <!-- Right Nav Section -->
      <ul class="right">
        <li><a href="/pages/about">About</a></li>
        <li><a href="/">Leaders</a></li>
        <li><a href="/profile">Me</a></li>
        <!-- <li class=""><a href="#">Logout</a></li> -->
      </ul>
    </section>
  </nav>
</div>
<div id="loading" style="position:absolute; top:20px; right:20px;"></div>
  <div id="container">
    <div id="content">

      <?php echo $this->Session->flash(); ?>
      <?php echo $this->Session->flash("auth"); ?>

      <?php echo $content_for_layout; ?>

    </div>
    <div id="footer">
    </div>
  </div>
    <div id="add-photo">
    <?php echo $this->Form->create('Photo', array("type"=>"file", "accept"=>"image/*;capture=camera")); ?>
    <label for="PhotoImage"><i class="material-icons">radio_button_checked</i>
  <?php
    echo $this->Form->file('image');
  ?></label>
<?php echo $this->Form->end(__('Upload')); ?>
  </div>
  <?php // echo $this->element('sql_dump'); ?>
  <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-70817016-1', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>
