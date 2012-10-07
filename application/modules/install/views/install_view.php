<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Creamture Framework</title>
        <meta name="description" content="Creamture Framework installation">
        <meta name="viewport" content="width=device-width">

        <? assets_css_group('main_css', array('bootstrap.min.css', 'bootstrap-responsive.min.css')) ?>
        <? assets_css_group('install_css', array($assets['css'].'install.css')) ?>
         

        <? assets_js_group('main_js', array('libs/modernizr-2.6.1-respond-1.1.0.min.js', 'libs/jquery-1.7.2.min.js', 'libs/bootstrap.min.js')) ?>
        <? assets_js_group('install_js', array($assets['js'].'install.js')) ?>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
        <![endif]-->
        <div class="navbar">
          <div class="navbar-inner">
            <a class="brand" href="#">Creamture <span>Framework</span></a>
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
            </ul>
          </div>
        </div>
    </body>
</html>
