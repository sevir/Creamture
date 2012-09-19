<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Creamture - HMVC sample</title>

<style type="text/css">

body {
 background-color: #fff;
 margin: 40px;
 font-family: Lucida Grande, Verdana, Sans-serif;
 font-size: 14px;
 color: #4F5155;
}

a {
 color: #003399;
 background-color: transparent;
 font-weight: normal;
}

h1 {
 color: #444;
 background-color: transparent;
 border-bottom: 1px solid #D0D0D0;
 font-size: 16px;
 font-weight: bold;
 margin: 24px 0 2px 0;
 padding: 5px 0 6px 0;
}

#widget{
 float: right;
 padding: 0 10px;
 width: 300px;
}


</style>

</head>
<body>
<h1>This is the test_hmvc module. </h1>
<?php echo Modules::run('test_hmvc/controller/widget_panel'); ?>
<p>All is working well. You can read this example in application/modules/test_hmvc</p>
<p>With HMVC you can define:</p>
<ul>
	<li>Own modules separated of the application code. Easy transfers with different projects.</li>
	<li>Embedded Widgets of different modules. Perfect for AJAX panels, social, or focused panels.</li>
</ul>

</body>
</html>