<?php
/**
* Enhaced Log viewer for CodeIgniter
* SeViR @2015
* based in old version of 2010
*/
class Viewer{
	//Please for security set your external ip
	private static $allow_ips = array(
		'127.0.0.1',
		'84.124.50.10'
	);
	//default logs path
	private static $log_path = '../application/logs/';
	private static $initialized = false;

	private static $redis;
	private static $redis_enabled;
	private static $log_prefix;

	public static $assets = array(
		'error_icon' => 'data:image/gif;base64,R0lGODlhEAAQAPQfAOaQhNFiTfrUzPrq5vWEZ/KnmfN9YedxW+3FvPCXhOmJe8NPNuySheKmmctZQ/ePd+uBa/i0ovOypvSupO94Xviiifq8qPrAs+NgVN19b96hle+fkuuvov///+doWQAAACH5BAUAAB8ALAAAAAAQABAAQAWV4CeOZClSkUGtQue9XuGIw5NYBNF1hmEph8EIEVBMFC9FIbNAlAQPSCESKUAgAtKjUOkZdqsDI3EQSQ6Rw2uHaRcCjdKgEVgsHBqh6TO4PHIPF3okAgQ3FTkVFgwHWSIXEDgEXx0rKAccJxGTKzsHagcyIhAbCRSfGxMvGAoAATQHRi8MDG4ZDoMfRAEAGxuuTXvCJCEAOw%3D%3D',
		'load_icon' => 'data:image/gif;base64,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',
		'debug_icon' => 'data:image/gif;base64,R0lGODlhEAAPAMQfAP3shv/12vvcS+vCc/vgXt6yPOvFeuuiJ+uaFOvLivraQuvJhfzhY+upN+ueHPvdUuvAbvzfWvveVv70zuu+aeu3Wbx8EuuwR/3vof7xsv3umP7yv/ziZv/24////////yH5BAEAAB8ALAAAAAAQAA8AAAVz4CeOn+eRKOklyZmqSdct7ustHcd1Ro16htyO4hsBhRxGoFIsDXIFC2NyKXqeuighsmn4PJAcIxqRZA61q5ixlTwEGLQI3GGwo2+BxnE62tt5CgoAfEABARMTGxkZGBoAkHwlFBUXDQcOCJqbaSaenyYiIQA7',
		'info_icon' => 'data:image/gif;base64,R0lGODlhEAAQAPU/AD5xtKvE4Xqlx6bC3YWr0+Tr9LvL4mmVwz1XkZ282klhlI2qzZC00muYxVJronKdzGih1P7//1uc06K+3LLJ5ZuqyF2Esr3T6NDc6kaKxXahz3mk0maQvk2Rz1R7rZ674JKz2uPo78bd8vT3++bw+Up3tL3G2Yyy2fX4+5i001uKuVKQxZO41Juu0pe62GKKuWiTwWKXyH2p1tTg7miczTxco6HG5KTJ56jM6khmo3KWvnidxDyDwaC/4F6Rv////yH5BAUAAD8ALAAAAAAQABAAQAavwJ9wSCwKeRRNo3GYjThQWAAhJElOFNkmENBsJoRSYWioCRK7l0WXEiBMRZEE0uN+YpkLUfIBaR4PExNLDQQuDkIJPgEHMAYRERgWFikeFUUhFQ4ICA4VIUZVOBAdHRI2JEYiHScGHwQEIAEEAHpCNzQUGxoaIyiAGgMqLUIZug9LkI0wDQE1QisJBEwwTi/XOyyIPwUlBBMcLyqTFgsCCqBkORYsAwMMHgpwofRDQQA7',
		'favicon' => 'data:image/png;base64,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'
	);
	
	private function __construct() {}
	
	private static function initialize()
	{
		if (self::$initialized)
			return;

		self::disable_ob();

		try{
			self::$redis = new Redis();
			self::$redis_enabled = self::$redis->pconnect('127.0.0.1', 6379,0.01);
		}catch(Exception $e){}

		if (isset ($_SERVER['HTTP_HOST'])){
			self::$log_prefix = $_SERVER['HTTP_HOST'];
		}else{
			self::$log_prefix =  basename(realpath(__DIR__.'/../../../'));
		}

		if (file_exists(dirname( __FILE__).'/system/logs/')){
			self::$log_path = dirname( __FILE__).'/system/logs/';
		}else if (file_exists(dirname( __FILE__).'/../application/logs/')){
			self::$log_path = dirname( __FILE__).'/../application/logs/';
		}
		self::$initialized = true;
	}

	//check if Redis server is enabled
	public static function checkRedis(){
		return self::$redis_enabled;
	}

	//reads all log file
	public static function readLog($file){
		self::initialize();

		echo '<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">';
		echo '<style>pre:empty, pre:nth-of-type(1){ display:none; }body{margin: 60px 20px 20px 20px;}</style>';
		if(strpos($file, './') !== FALSE) die('invalid access');

		if (self::$redis_enabled){
			$log_lines = self::$redis->lRange($file,0, -1) ;
			$count = self::$redis->lLen($file);

			for ( $i = $count ; $i > 0; $i--) {
				echo '<pre>'.str_replace(
				 array(
				 'ERROR -',
				 'INFO -',
				 'DEBUG -'
				),
				 array(
						'</pre><pre class="alert alert-warning"><img alt="error" src="'.self::$assets['error_icon'].'" />',
						'</pre><pre class="info"><img alt="info" src="'.self::$assets['info_icon'].'" />',
						'</pre><pre class="debug"><img alt="debug" src="'.self::$assets['debug_icon'].'" />',
					), $log_lines[$i-1]
					).'</pre>';
			}
		}else{
			if (file_exists(self::$log_path.$file)){
			 echo '<pre>'.str_replace(
			 array(
			 'ERROR -',
			 'INFO -',
			 'DEBUG -'
			),
			 array(
					'</pre><pre class="alert alert-warning"><img alt="error" src="'.self::$assets['error_icon'].'" />',
					'</pre><pre class="info"><img alt="info" src="'.self::$assets['info_icon'].'" />',
					'</pre><pre class="debug"><img alt="debug" src="'.self::$assets['debug_icon'].'" />',
				),file_get_contents(self::$log_path.$file)
				).'</pre>';
			}
		}	
	}

	//Check if you access from a valid IP
	public static function checkAccess(){
		self::initialize();
		return in_array($_SERVER['REMOTE_ADDR'], self::$allow_ips);
	}
	//rename the old logfile
	public static function clearLog(){
		self::initialize();

		if (self::$redis_enabled){
			$today_log_key = self::$log_prefix.'-'.date('Y-m-d');
			self::$redis->del($today_log_key);
		}else{
			$the_file = 'log-'.date('Y-m-d').'.php';

			if (file_exists(self::$log_path.$the_file)) rename(self::$log_path.$the_file,self::$log_path.str_replace('.php', '', $the_file).'_'.date('Gis').'.php');
		}
		
		header('Location: '.str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']));
	}
	//compress logs in a ZipFile and it removes them
	public static function compressLogs(){
		self::initialize();

		$log_files = glob(self::$log_path.'*.php');
		$zip = new ZipArchive();
		$zip->open(self::$log_path.'backup_'.date('Y-m-d').'_'.date('Gis').'.zip',ZIPARCHIVE::OVERWRITE);
		foreach($log_files as $file) {
		$zip->addFile($file,str_replace(dirname($file), '', $file));
		}
		$zip->close();
		foreach($log_files as $file) {
			unlink($file);
		}
		header('Location: '.str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']));
	}
	//compress logs and download it
	public static function downloadLogs(){
		self::initialize();

		$log_files = glob(self::$log_path.'*.php');
		$zipname = self::$log_path.'backup_'.date('Y-m-d').'_'.date('Gis').'.zip';
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
	}
	//download a zip log backup file
	public static function downloadFile($file){
		self::initialize();

		if((strpos($file, './') !== FALSE) || (strpos($file,'.zip') === FALSE)) die('invalid access');
		header('Content-disposition: attachment; filename='.$file);
		header('Content-type: application/octet-stream');
		readfile(self::$log_path.$file);
	}
	
	//get list of logs and zip backups
	public static function getFiles(){
		if (self::$redis_enabled){
			return array(
				'logs'  => self::$redis->keys(self::$log_prefix.'-*'),
				'backups' => array()
				);
		}

		$log_files = glob(self::$log_path.'*.php');
		usort(
			$log_files,
			create_function('$a,$b', 'return filemtime($b) - filemtime($a);')
			);
			$backups_files = glob(self::$log_path.'*.zip');
		usort(
			$backups_files,
			create_function('$a,$b', 'return filemtime($b) - filemtime($a);')
			);
			return array(
				'logs'=> $log_files,
				'backups'=> $backups_files
			);
	}

	//hide progress
	public static function stopProgress(){
	?>
<script type="text/javascript">
	window.top.document.getElementById('progress').style.display = 'none';
	window.scrollTo(0,document.body.scrollHeight);
</script>
	<?php
	}

	public static function disable_ob() {
	    // Turn off output buffering
	    ini_set('output_buffering', 'off');
	    // Turn off PHP output compression
	    ini_set('zlib.output_compression', false);
	    // Implicitly flush the buffer(s)
	    ini_set('implicit_flush', true);
	    ob_implicit_flush(true);
	    // Clear, and turn off output buffering
	    while (ob_get_level() > 0) {
	        // Get the curent level
	        $level = ob_get_level();
	        // End the buffering
	        ob_end_clean();
	        // If the current level has not changed, abort
	        if (ob_get_level() == $level) break;
	    }
	    // Disable apache output buffering/compression
	    if (function_exists('apache_setenv')) {
	        apache_setenv('no-gzip', '1');
	        apache_setenv('dont-vary', '1');
	    }
	}

	public static function realtime(){
		echo '<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">';
?>
<style type="text/css">pre:empty, pre:nth-of-type(1){ display:none;}body{margin: 60px 20px 20px 20px;}</style>
<script type="text/javascript">
	setInterval(function(){
		window.scrollBy(0,200);
	}, 300);
</script>
<?php
		if (self::$redis_enabled){
			$today_log_key = self::$log_prefix.'-'.date('Y-m-d');
			if (count( self::$redis->keys($today_log_key )) > 0){
				ini_set('max_execution_time', 0);

	    			$count = self::$redis->lLen($today_log_key);
	    			foreach (self::$redis->lRange($today_log_key,0, 10) as $log_line) {
					echo '<pre>'.str_replace(
					 array(
					 'ERROR -',
					 'INFO -',
					 'DEBUG -'
					),
					 array(
							'</pre><pre class="alert alert-warning"><img alt="error" src="'.self::$assets['error_icon'].'" />',
							'</pre><pre class="info"><img alt="info" src="'.self::$assets['info_icon'].'" />',
							'</pre><pre class="debug"><img alt="debug" src="'.self::$assets['debug_icon'].'" />',
						), $log_line
						).'</pre>';
					flush();
				}

				while (true) {
					usleep(300000); //0.3 s
					$newcount  = self::$redis->lLen($today_log_key);

					if ($newcount > $count){
						foreach (self::$redis->lRange($today_log_key,0, $newcount-$count) as $log_line) {
							echo '<pre>'.str_replace(
							 array(
							 'ERROR -',
							 'INFO -',
							 'DEBUG -'
							),
							 array(
									'</pre><pre class="alert alert-warning"><img alt="error" src="'.self::$assets['error_icon'].'" />',
									'</pre><pre class="info"><img alt="info" src="'.self::$assets['info_icon'].'" />',
									'</pre><pre class="debug"><img alt="debug" src="'.self::$assets['debug_icon'].'" />',
								), $log_line
								).'</pre>';
							flush();
						}
					}

					$count = $newcount;
				}
			}else{
				echo 'No today log found';
			}
		}else{
			$the_file = self::$log_path.'log-'.date('Y-m-d').'.php';
	    		if ( file_exists($the_file)){
	    			ini_set('max_execution_time', 0);
	    			$lastpos = 0;
				while (true) {
				    usleep(300000); //0.3 s
				    clearstatcache(false, $the_file);
				    $len = filesize($the_file);
				    if ($len < $lastpos) {
				        //file deleted or reset
				        $lastpos = $len;
				    }
				    elseif ($len > $lastpos) {
				        $f = fopen($the_file, "rb");
				        if ($f === false)
				            die();
				        fseek($f, $lastpos);
				        while (!feof($f)) {
				            $buffer = fread($f, 4096);
				            echo '<pre>'.str_replace(
					 array(
					 'ERROR -',
					 'INFO -',
					 'DEBUG -'
					),
					 array(
							'</pre><pre class="alert alert-warning"><img alt="error" src="'.self::$assets['error_icon'].'" />',
							'</pre><pre class="info"><img alt="info" src="'.self::$assets['info_icon'].'" />',
							'</pre><pre class="debug"><img alt="debug" src="'.self::$assets['debug_icon'].'" />',
						),$buffer
						).'</pre>';
				            flush();
				        }
				        $lastpos = ftell($f);
				        fclose($f);
				    }
				}
	    		}else{
	    			echo 'No today log found';
	    		}
		}
	}
}
if (!Viewer::checkAccess()) die('Your IP ('.$_SERVER['REMOTE_ADDR'].') is not allowed');

if (isset($_GET['q'])){
	$query = $_GET['q'];
	switch ($query) {
		case 'phpinfo':
			echo '<style>body{margin: 60px 20px 20px 20px;}</style>';
			phpinfo();  Viewer::stopProgress(); die();
			break;
		
		case 'file':
			if(isset($_GET['f'])){
				Viewer::readLog($_GET['f']);
			}
			Viewer::stopProgress();
			die();
			break;

		case 'download':
			if(isset($_GET['f'])){
				Viewer::downloadFile($_GET['f']);
			}
			die();
			break;

		case 'clear':
			Viewer::clearLog();
			die();
			break;

		case 'downloadzip':
			Viewer::downloadLogs();
			die();
			break;

		case 'backup':
			Viewer::compressLogs();
			die();
			break;

		case 'realtime':
			Viewer::realtime();
			flush();
			die();
			break;

		default:
			echo "no action available";Viewer::stopProgress();die();
			break;
	}
}

$files = Viewer::getFiles();

//And now the main view
?>
<html>
	<head>
	<title>Log Viewer for CodeIgniter</title>

	<link rel='icon' type='image/png' href='<?php echo Viewer::$assets['favicon'];?>'>
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
		<script>
			$(function(){
				$("a.action").click(function(){
					$("#progress").show();
					$("#content").attr('src', $(this).attr("data-href") );
				});
				$("#logfilter, #backupfilter").click(function(){
					return false;
				}).keyup(function(){
					if(this.value==''){
						$("#"+this.id+" div").show();
					}else{
						filtertext = this.value;
						$("#"+$(this).attr("data-id")+" div").each(function(){
							console.log($("a:first",this).html());
							console.log(filtertext);
							if($("a:first",this).html().indexOf(filtertext) > -1){
								$(this).show();
							}else{
								$(this).hide();
							}
						});
					}
				});
			});
		</script>
		<style>
			.pr-info{
				padding-top: 5px;
				padding-left: 10px;
			}
			.pr-info span{
				display: block;
				font-size: 9px;
			}
			#backupfiles div, #logfiles div{
				font-size: 10px;
				margin-left: 10px;
			}
			#backupfiles, #logfiles{
				max-height: 200px;
			}
			#content{
				position:absolute;
				width: 100%;
				height: 100%;
				border: none;
			}
			#progress{
				display:none;
			}
			#logfilter, #backupfilter{
				margin: 5px 10px;
				font-size: 10px;
				height: 25px;
			}
			body{
				margin:0;
			}
		</style>
	</head>
	<body>
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" title="refresh" href="<?=$_SERVER['QUERY_STRING'].$_SERVER['REQUEST_URI']; ?>">Log Viewer for CodeIgniter</a>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-fixed">
						<li class="pr-info">
							<span><strong><?php echo $_SERVER["SERVER_NAME"]?></strong></span>
							<span>server ip: <?php echo gethostbyname ( $_SERVER["SERVER_NAME"] )?></span>
							<span>my ip: <?php echo $_SERVER['REMOTE_ADDR'];?></span>
						</li>

						<!-- DEPRECATED
						 <li><a title="backup and clear this log file" href="<?php echo str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']).'?q=clear&f=';?>">Clear</a></li>
						 -->
						 <li><a title="rename today log and create a new"  href="<?php echo str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']).'?q=clear';?>">Clear</a></li>
						<?php if( ! Viewer::checkRedis()) : ?>
						<li><a title="compress into a zip file and download all logs" target="_blank" href="<?php echo str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']).'?q=downloadzip';?>">Download</a></li>
						<li><a title="backup into a zip file all logs and removes them" href="<?php echo str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']).'?q=backup';?>">Backup</a></li>
						<?php endif; ?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Logs files<span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li>
									<a href="#">Log files</a>
									<div class="form-group">
								      <input id="logfilter" data-id="logfiles" type="text" class="form-control" placeholder="Filter for...">
								    </div><!-- /input-group -->
								</li>
								<li id="logfiles">
									<?php foreach ($files['logs'] as $file): ?>
									<div class="filelink"><a class="action log" href="#log" data-href="<?php echo str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']).'?q=file&f='.basename($file);?>"><?=basename($file)?></a></div>
									<?php endforeach ?>
								</li>
								<li class="divider"></li>
								<li>
									<a href="#">Backup files</a>
									<div class="form-group">
								      <input id="backupfilter" data-id="backupfiles" type="text" class="form-control" placeholder="Filter for..."/>
								    </div><!-- /input-group -->
								</li>
								<?php if(! Viewer::checkRedis()): ?>
								<li id="backupfiles">
									<?php foreach ($files['backups'] as $file): ?>
									<div class="filelink"><a target="_blank" href="<?php echo str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']).'?q=download&f='.basename($file);?>"><?=basename($file)?></a></div>
									<?php endforeach ?>
								</li>
								<?php endif; ?>
							</ul>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Extra info<span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a class="action" href="#phpinfo" data-href="<?php echo str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']).'?q=phpinfo'?>">Show phpinfo()</a></li>
								
							</ul>
						</li>
						<li id="progress"><a href="#"><img src="<?php echo Viewer::$assets['load_icon'];?>"></a></li>
					</ul>
					
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>

		<div class="">
			<iframe id="content" src="<?php echo str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']).'?q=realtime'?>"></iframe>
		</div>
	</body>
</html>