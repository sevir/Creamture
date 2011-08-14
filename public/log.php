<?php
/**
 * LOG utility by SeViR
 */

	$allow_ips = array(
		'127.0.0.1'
	);
	if (!in_array($_SERVER['REMOTE_ADDR'], $allow_ips)) die('Ip not allowed');

	$cache_paths = array();
	if (file_exists(dirname( __FILE__).'/system/logs/')){
		define('LOG_PATH', dirname( __FILE__).'/system/logs/');
	}else if (file_exists(dirname( __FILE__).'/../application/logs/')){
		define('LOG_PATH',dirname( __FILE__).'/../application/logs/');
	}



   if(isset($_GET['f'])){
    	if(strpos($_GET['f'], './') !== FALSE) die('invalid access');

    	if (file_exists(LOG_PATH.$_GET['f'])){
 			echo file_get_contents(LOG_PATH.$_GET['f']);
    	}
    }
    else if (isset($_GET['log']))
    {
        if (file_exists(LOG_PATH.'log-'.date('Y-m-d').'.php'))
        {
            echo file_get_contents(LOG_PATH.'log-'.date('Y-m-d').'.php');

            if (isset($_GET['r']) && $_GET['r']=='1')
            {
                    rename(LOG_PATH.'log-'.date('Y-m-d').'.php',LOG_PATH.'log-'.date('Y-m-d').'_'.date('Gis').'.php');
            }
        }
    }else if(isset($_GET['r'])){
    	if (file_exists(LOG_PATH.$_GET['r'])) rename(LOG_PATH.$_GET['r'],LOG_PATH.str_replace('.php', '', $_GET['r']).'_'.date('Gis').'.php');
		//echo "<pre>";
		//var_dump( $_SERVER);
		header('Location: log.php');

    }else if(isset($_GET['d'])){
		$log_files = glob(LOG_PATH.'*.php');

		$zip = new ZipArchive();
		$zip->open(dirname( __FILE__).'/system/logs/backup_'.date('Y-m-d').'_'.date('Gis').'.zip',ZIPARCHIVE::OVERWRITE);
		foreach($log_files as $file) {
      		$zip->addFile($file,str_replace(dirname($file), '', $file));
    	}

	    $zip->close();

	    foreach($log_files as $file) {
	    	unlink($file);
    	}

		header('Location: log.php');
    }else if(isset($_GET['z'])){
		$log_files = glob(LOG_PATH.'*.php');

		$zipname = LOG_PATH.'backup_'.date('Y-m-d').'_'.date('Gis').'.zip';

		$zip = new ZipArchive();
		$zip->open($zipname,ZIPARCHIVE::OVERWRITE);
		foreach($log_files as $file) {
      		$zip->addFile($file,str_replace(dirname($file), '', $file));
    	}

	    $zip->close();

		header('Content-disposition: attachment; filename='.str_replace(dirname($zipname), '', $zipname));
		header('Content-type: application/octet-stream');
		readfile($zipname);
		unlink($zipname);
    }else if(isset($_GET['down'])){
		if((strpos($_GET['down'], './') !== FALSE) || (strpos($_GET['down'],'.zip') === FALSE)) die('invalid access');

		header('Content-disposition: attachment; filename='.$_GET['down']);
		header('Content-type: application/octet-stream');
		readfile(LOG_PATH.$_GET['down']);
	}
    else{
    	$log_files = glob(LOG_PATH.'*.php');
    	usort(
		   $log_files,
		   create_function('$a,$b', 'return filemtime($b) - filemtime($a);')
		);

		$backups_files = glob(dirname( __FILE__).'/system/logs/*.zip');
    	usort(
		   $backups_files,
		   create_function('$a,$b', 'return filemtime($b) - filemtime($a);')
		);
?>
<html>
	<head>
		<title>Log viewer : <?=$_SERVER["SERVER_NAME"]?></title>
		<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.6.2.min.js"></script>
		<script>
			var logfile = "";
			var autorefresh_interval;

			function loadLog(href){
				logfile = href;

				$("#log").load(href, function(){
					$("#endcode").get(0).scrollIntoView();
				});
				file = href.substring(href.indexOf("=")+1);

				$("#backup_clear").attr("href",$("#backup_clear").attr("href").replace(/=.*/,'='+file));
				return false;
			}
			$(document).ready(function(){
				logfile = document.location.href+"?log=1";

				$("#log").load(document.location.href+"?log=1", function(){
					$("#endcode").get(0).scrollIntoView();
				});
				$("#left_panel a, #left_panel li").not("#backups a, #backups li").click(function(){
					$("#left_panel li").removeClass("active");
					if(this.tagName.toUpperCase()!='LI'){
						$(this).parent().addClass("active");
					}else{
						$(this).addClass("active");
					}
					return loadLog((this.tagName.toUpperCase()=='LI')?$("a",this).attr("href"):this.href);
				});
				$("#left_panel").hover(function(){
					$("#backups").show();
				},function(){
					$("#backups").hide();
				});
				$("#client_ip").toggle(function(){
					$("#tools").show();
				}, function(){
					$("#tools").hide();
				});
				$(window).resize(function(){
					$("#left_panel").height($("body").height()-60);
				});
				$("#left_panel").height($("body").height()-60);

				$("#autorefresh").change(function(){
					if(this.checked){
						autorefresh_interval = setInterval(function(){
							loadLog(logfile);
						},50000);
					}else{
						clearInterval(autorefresh_interval);
					}

				});
			});

		</script>
		<style>
body {
    font-family: Arial,sans-serif;
    margin: 0;
    padding: 0;
    color: #3D3D3D;
}

#topbar{
    background-color: #2D2D2D;
    color: #fff;
    font-weight: bold;
    padding: 5px;
    padding-left: 15px;
    font-size: 14px;
    position: fixed;
    width: 100%;
}

#topbar a{
	color: #fff;
}

#topbar span{
	color: #ccc !important;
}

#log_panel {
	float: right;
	margin-left: 20%;
	width: 80%;
	overflow-x:auto;
}

#left_panel {
    min-width: 170px;
    width: 15%;
    position: fixed;
    overflow: auto;
    top: 26px;
    font-size: 10px;
    padding: 5px 0 20px 20px;
    background-color: #F5F5F5;
    border: #E5E5E5 1px solid;
}
#left_panel ul, #left_panel li,#backups ul, #backups li{
	list-style: none;
	margin-bottom: 5px;
	padding-left:0;
}

#left_panel li{
	padding: 3px;
}

#left_panel li:hover, #left_panel li:hover a{
	background-color: #36C;
	color: #fff;
	cursor: pointer;
}

#client_ip{
	position: fixed;
	color: #fff;
	top: 15px;
	right: 5px;
	font-size: 10px;
	cursor: pointer;
}

#buttons{
	position: fixed;
	top: 34px;
	right: 3px;
	font-size: 10px;
}

a{
	color: #36C;
	text-decoration: none;
	font-weight: bold;
}

li.active{
	border-left: 4px solid #DE4A38;
}


#buttons a{
	border-radius: 3px;
-ms-border-radius: 3px;
-moz-border-radius: 3px;
-webkit-border-radius: 3px;
-khtml-border-radius: 3px;
	border: #2C58B0 2px solid;
	background-color: #36C;
	color: #fff;
	padding: 2px 4px;
	margin-left: 3px;
}

#buttons a:hover{
	background-color: #2C58B0;
}

pre{
	font-family: arial,sans-serif;;
	font-size: 12px;
	color: #C11;
	padding-left: 10px;
}
#log_panel{
	float: left;
	width: 80%;
}
#backups{
	display:none;
}
.triangle{
	position: relative;
top: -1px;
border-style: solid dashed dashed;
border-color: transparent;
border-top-color: silver;
display: -moz-inline-box;
display: inline-block;
font-size: 0;
height: 0;
line-height: 0;
width: 0;
border-width: 3px 3px 0;
padding-top: 1px;
left: 4px;
}
#tools{
	width: 500px;
	right: 0;
	top: 25px;
	position: fixed;
	background: #fff;
	border: #BEBEBE 1px solid;
	overflow: auto;
	padding: 10px;
	height: 300px;
	font-size: 12px;
	-moz-box-shadow: -1px 1px 1px #ccc;
	-webkit-box-shadow: 0 1px 5px #ccc;
	box-shadow: 0 1px 5px #ccc;
	display: none;
}
</style>
	</head>
<body>
<div id="topbar"><a title="refresh" href="<?=$_SERVER['QUERY_STRING'].$_SERVER['REQUEST_URI']; ?>">Log viewer :: <span><?=$_SERVER["SERVER_NAME"]?></span></a></div>
<div id="client_ip">my ip is <?=$_SERVER['REMOTE_ADDR'];?><span class="triangle"></span></div>
<div id="buttons">
	<a id="backup_clear" href="<?=$_SERVER['QUERY_STRING'].$_SERVER['REQUEST_URI'].'?r='.'log-'.date('Y-m-d').'.php'?>" title="backup & clear log">C</a>
	<a href="<?=$_SERVER['QUERY_STRING'].$_SERVER['REQUEST_URI'].'?z=1'?>" title="zip & download all the logs">Z</a>
	<a href="<?=$_SERVER['QUERY_STRING'].$_SERVER['REQUEST_URI'].'?d=1'?>" title="zip & remove all the logs" id="remove_all">R</a></div>
<div id="left_panel">
	<ul>
		<?php foreach ($log_files as $file): ?>
		<li class="<?=(file_exists('./system/logs/log-'.date('Y-m-d').'.php') && basename($file)=='log-'.date('Y-m-d').'.php')?'active':'';?>" ><a href="<?=$_SERVER['QUERY_STRING'].$_SERVER['REQUEST_URI'].'?f='.basename($file);?>"><?=basename($file)?></a></li>
		<?php endforeach ?>
	</ul>
	<?php if (count($backups_files)): ?>
	  <div id="backups">
		<h4>Backup list:</h4>
		<ul>
			<?php foreach ($backups_files as $file): ?>
			<li><a href="<?=$_SERVER['QUERY_STRING'].$_SERVER['REQUEST_URI'].'?down='.basename($file);?>"><?=basename($file)?></a></li>
			<?php endforeach ?>
		</ul>
	</div>
	<?php endif ?>

</div>
<div id="log_panel">
<pre id="log">

</pre>
<div id="endcode"></div>
</div>
<form id="clear_log" method="get">
	<input type="hidden" name="file" value="" />
	<input type="hidden" name="r" value="1" />
</form>
<div id="tools">
	<h4>Preferences:</h4>
	<label><input type="checkbox" id="autorefresh" /> autorefresh</label>
	<h4>Request data:</h4>
	<pre>
		<?php var_dump($_SERVER);?>
	</pre>
</div>
</body>
</html>
<?php
	}
/* eof */
