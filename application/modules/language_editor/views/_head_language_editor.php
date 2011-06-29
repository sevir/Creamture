<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Creamture - Language Editor</title>
<?php foreach($scripts as $script): ?>
	<script type="text/javascript" src="<?php echo $script;?>"></script>
<?php endforeach; ?>

<?php foreach($styles as $style): ?>
<link rel='stylesheet' href='<?php echo $style;?>' media='screen'>
<?php endforeach; ?>


</style>

</head>
<body>
<h1>Creamture Language editor</h1>