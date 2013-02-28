<?php 
require('config.php');
require('classes/controller.php');
require('classes/slideshow_upload.php');
require('classes/slideshow_database.php');
require('classes/slideshow_controller.php');
require('classes/slideshow_model.php');

global $base_url;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

<script src="<?php echo $base_url ?>/js/jquery-1.8.0.min.js" type="text/javascript"></script>
<script src="<?php echo $base_url ?>/js/jquery-ui-1.8.23.custom.min.js" type="text/javascript"></script>
<script src="<?php echo $base_url ?>/js/jquery.timePicker.min.js" type="text/javascript"></script>
<script src="<?php echo $base_url ?>/js/underscore.min.js" type="text/javascript"></script>
<script src="<?php echo $base_url ?>/js/json2.js" type="text/javascript"></script>
<script src="<?php echo $base_url ?>/js/backbone.min.js" type="text/javascript"></script>
<script src="<?php echo $base_url ?>/js/backbone-query.min.js" type="text/javascript"></script>
<script src="<?php echo $base_url ?>/js/cycle.js" type="text/javascript"></script>


<link href="<?php echo $base_url ?>/css/bootstrap.min.css" type="text/css" rel="stylesheet">
<link href="<?php echo $base_url ?>/css/bootstrap-responsive.min.css" type="text/css" rel="stylesheet">
<link href="<?php echo $base_url ?>/css/jquery-ui-1.8.23.custom.css" type="text/css" rel="stylesheet">
<link href="<?php echo $base_url ?>/css/timePicker.css" type="text/css" rel="stylesheet">
<link href="<?php echo $base_url ?>/css/style.css" type="text/css" rel="stylesheet">
<style type="text/css">
body {
	padding: 60px 0px 40px;
}
.sidebar-nav {
	padding: 9px 0px;
}
</style>
</head>
<body>
<div class="navbar navbar-fixed-top">
<div class="navbar-inner">
<div class="container">
  <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </a>
  <a class="brand" href="<?php echo $base_url ?>">Project name</a>
  <div class="nav-collapse collapse">
    <ul class="nav">
      <li><a href="<?php echo $base_url ?>">Explore Slideshow</a></li>
      <li><a href="<?php echo $base_url ?>/upload">Upload New Images</a></li>
    </ul>
  </div>
</div>
</div>
</div>
