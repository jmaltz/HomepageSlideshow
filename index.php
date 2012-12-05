<?php require('header.php'); ?>

<div class="container">
	<?php
	global $config;
	$controller = new Controller(array('config' => $config));
	$controller->route($_REQUEST);
	?>
</div>

<?php require('footer.php'); ?>
