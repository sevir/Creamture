<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to Creamture</title>

<style type="text/css">

body {
 background-color: #fff;
 margin: 40px;
 font-family: Lucida Grande, Verdana, Sans-serif;
 font-size: 14px;
 color: #4F5155;
}

a {
 color: #143270;
 background-color: transparent;
 text-decoration: none;
 font-weight: bold;
}
a:hover{
 text-decoration: underline;
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

code {
 font-family: Monaco, Verdana, Sans-serif;
 font-size: 12px;
 background-color: #f9f9f9;
 border: 1px solid #D0D0D0;
 color: #002166;
 display: block;
 margin: 14px 0 14px 0;
 padding: 12px 10px 12px 10px;
}

.red{
	color: #FF0000;
}
.green{
	color: #008000;
}

textarea.htaccess{
	width:90%;
	margin: 0 auto;
	height: 100px;
}

p.notify{
	padding: 5px;
	background-color: #D1D1FF;
	border: 1px #ccc dashed;
}
.expandible{
	cursor: pointer;
}
table.checks{
	font-size: 11px;
}
</style>

<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.6.1.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("h1.expandible").toggle(function(){
			$(this).next("div").slideDown();
			$("span", this).text("-");
		},function(){
			$(this).next("div").slideUp();
			$("span", this).text("+");
		}).next("div").hide();
	});
</script>
</head>
<body>

<h1>Welcome to Creamture!</h1>
<?php if($this->session->flashdata('htaccess')): ?>
<p class="notify"><?php echo $this->session->flashdata('htaccess');?></p>
<?php endif ?>

<p>Creamture is a framework based in CodeIgniter but with many additional features for enterprise works. The page you are looking at is being generated dynamically by Creamture.</p>

<p>If you would like to edit this page you'll find it located at:</p>
<code>application/views/welcome_message.php</code>

<p>The corresponding controller for this page is found at:</p>
<code>application/controllers/welcome.php</code>

<p>If you are exploring CodeIgniter for the very first time, you should start by reading the <a href="http://www.codeigniter.com/user_guide/">User Guide</a>.</p>

<h1 class="expandible"><span>+</span> Creamture main packages over CodeIgniter</h1>
<div>
<p>
<ul>
	<li><a href="http://www.twig-project.org/">Twig</a> template library</li>
	<li><a href="http://simpletest.org/">SimpleTest</a> Unit testing library</li>
	<li><a href="http://getsparks.org/">Sparks</a> The package manager &amp; repository</li>
	<li><a href="https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc/wiki/Home">HMVC</a></li>
	<li><a href="<?php echo auto_link($this->config->item('index_page').'/../language_editor')?>">Multilanguage web editor</a> as module. Try with user: editor and pass: 3d1t0rp4ssw0rd
	<ul>
		<li>Edit online the language files, no more problems with UTF-8, html entities, etc...</li>
		<li>WYSIWYG editor</li>
		<li>Google Translator helper</li>
		<li>Create all languages file at the same time</li>
		<li>No broked code editing thousand lines</li>
	</ul>
	</li>
	<!-- <li>Session in db improvements</li>
	<li>Get params improvements, better support for $this->input->get_post()</li>
	<!-- <li>Phing - Ant for PHP - <span class="red">(not available yet)</span></li> -->
	<li>Creamator: Command line CRUD
		<ul>
			<li>Create new controllers, helpers, libraries, simply and fast, better than copy&amp;paste ;-)</li>
			<li>Customize the templates</li>
			<li>Create new commands</li>
			<li>Try the power of the command line typing: <code>php index.php creamator</code></li>
		</ul>
	</li>
	<!--<li><a href="http://nanoweb.si.kz/">Nanoweb</a> In built Web server <span class="red">(not available yet)</span></li>-->
	<li>MultiOS Desktop Application for easy installation. <span class="red">(not available yet)</span></li>
</ul>
</p>
</div>
<h1 class="expandible"><span>+</span> Try these example tests</h1>
<div>
<p>
<ul>
	<li><a href="<?php echo auto_link($this->config->item('index_page').'/../welcome/simpleTester')?>">Run the tests</a></li>
	<li><a href="<?php echo auto_link($this->config->item('index_page').'/../welcome/twigTest')?>">Try Twig template example</li></a>
	<li><a href="<?php echo auto_link($this->config->item('index_page').'/../test_hmvc')?>">Test HMVC Module</li></a>
</ul>
</p>
</div>
<h1 class="expandible"><span>+</span> Your system</h1>
<div>
	<table class="checks">
		<tr>
			<td>Write permissions for cache folder (0775 or 0777)</td><td><?php echo ($config['cache_write_permissions'] == '0775' || $config['cache_write_permissions'] == '0777')?'<span class="green">'.$config['cache_write_permissions'].'</span>':'<span class="red">'.$config['cache_write_permissions'].'</span>'; ?></td>
		</tr>
		<tr>
			<td>Write permissions for logs folder (0775 or 0777)</td><td><?php echo ($config['log_write_permissions'] == '0775' || $config['log_write_permissions'] == '0777')?'<span class="green">'.$config['log_write_permissions'].'</span>':'<span class="red">'.$config['log_write_permissions'].'</span>'; ?></td>
		</tr>
		<tr>
			<td>Support for short open tags < ?=$var; ? > in your templates:</td><td><?php echo($config['short_open_tag']?'<span class="green">OK</span>':'<span class="red">NO</span>')?></td>
		</tr>
		<tr>
			<td>Support for mod_rewrite (hide your index.php):</td><td><?php echo ($config['mod_rewrite']?'<span class="green">OK</span>':'<span class="red">NO</span>')?></td>
		</tr>
		<tr>
			<td>Support for mod_deflate (apache web compression):</td><td><?php echo ($config['mod_deflate']?'<span class="green">OK</span>':'<span class="red">NO</span>')?></td>
		</tr>
		<tr>
			<td>Support for PHP ZLib (PHP compression):</td><td><?php echo ($config['zlib_enabled']?'<span class="green">OK</span>':'<span class="red">NO</span>')?></td>
		</tr>
		<tr>
			<td>ZLib Compression enabled:</td><td><?php echo ($config['zlib_compression_enabled']?'<span class="green">OK</span>':'<span class="red">NO</span>')?></td>
		</tr>
	</table>
</div>

<h1 class="expandible"><span>+</span> Hide your index.php with .htaccess</h1>
<div>
<p>Save this code as .htaccess in the public folder. You need mod_rewrite module active in apache. You feel free to modify it and save then.</p>
<?php echo form_open('welcome/saveHtaccess');?>
<textarea name="htaccess" class="htaccess">
<IfModule mod_rewrite.c>
RewriteEngine on
RewriteRule .* - [E=HTTP_IF_MODIFIED_SINCE:%{HTTP:If-Modified-Since}]
RewriteRule .* - [E=HTTP_IF_NONE_MATCH:%{HTTP:If-None-Match}]
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteCond $1 !^(favicon\.ico|index\.php|js|css|imgs)
RewriteRule ^(.*)$ /index.php?/$1 [L]
</IfModule>
</textarea>
<button type="submit">Save this .htaccess automatically</button>
</form>
<?php echo form_open('welcome/removeHtaccess');?>
<button type="submit" name="remove">Remove .htaccess</button>
</form>
</div>

<p><br />Page rendered in {elapsed_time} seconds</p>

</body>
</html>