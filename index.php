<?php
	if( ! function_exists('_')){
		function _($string) {
			return $string;
		}
	}
	if (isset ( $_POST['htaccess'] )){
		file_put_contents('.htaccess', $_POST['htaccess']);

		header('Location: http://'.$_SERVER['SERVER_NAME'].'/', true, 302);
		exit;
	}
?>
<style type="text/css">
body{
	background-color: #AB2727;
	color: #fff;
}
</style>
<h1><?php echo _('Warning!'); ?></h1>
<p>
	<?php echo _('If you have reading this you don\'t point the document root of the virtual host to the public folder'); ?>
</p>
<p>
	<?php echo _('Alternately if you can\'t change the document root because you are using a shared hosting then you can save this .htaccess in your root host folder'); ?>
</p>
<form method="POST">
	<textarea name="htaccess" style="width: 98%; height: 200px; border: #ccc 1px solid;">
RewriteEngine on
RewriteBase /public/
RewriteRule .* - [E=HTTP_IF_MODIFIED_SINCE:%{HTTP:If-Modified-Since}]
RewriteRule .* - [E=HTTP_IF_NONE_MATCH:%{HTTP:If-None-Match}]
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteCond %{REQUEST_URI} !.*\.(png|jpg|gif|jpeg|psd)
RewriteCond $1 !^(public|assets|upload|log\.php|crossdomain\.xml|humans\.txt|robots\.txt)
RewriteRule ^(.*)$ /public/index.php?/$1 [L]

ErrorDocument 404 /application/error/error_404.php
</textarea>
	<p><button type="submit"><?php echo _('Try to save the htacess file');?></button></p>
</form>
