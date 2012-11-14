<?php

/* layout/install_layout.twig */
class __TwigTemplate_ea5242f0a6f42481090483f999d3a142 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<!--[if lt IE 7]>      <html class=\"no-js lt-ie9 lt-ie8 lt-ie7\"> <![endif]-->
<!--[if IE 7]>         <html class=\"no-js lt-ie9 lt-ie8\"> <![endif]-->
<!--[if IE 8]>         <html class=\"no-js lt-ie9\"> <![endif]-->
<!--[if gt IE 8]><!--> <html class=\"no-js\"> <!--<![endif]-->
    <head>
        <meta charset=\"utf-8\">
        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\">
        <title>Creamture Framework</title>
        <meta name=\"description\" content=\"Creamture Framework installation\">
        <meta name=\"viewport\" content=\"width=device-width\">
        ";
        // line 12
        echo twig_escape_filter($this->env, assets_css_group("main_css", array("bootstrap.min.css", "bootstrap-responsive.min.css")), "html", null, true);
        echo "
        ";
        // line 13
        if (isset($context["module_assets"])) { $_module_assets_ = $context["module_assets"]; } else { $_module_assets_ = null; }
        echo twig_escape_filter($this->env, assets_css_group("install_css", array(($this->getAttribute($_module_assets_, "css", array(), "array") . "install.css"))), "html", null, true);
        echo "
         

        ";
        // line 16
        echo twig_escape_filter($this->env, assets_js_group("main_js", array("libs/modernizr-2.6.1-respond-1.1.0.min.js", "libs/jquery-1.7.2.min.js", "libs/bootstrap.min.js")), "html", null, true);
        echo "
        ";
        // line 17
        if (isset($context["module_assets"])) { $_module_assets_ = $context["module_assets"]; } else { $_module_assets_ = null; }
        echo twig_escape_filter($this->env, assets_js_group("install_js", array(($this->getAttribute($_module_assets_, "js", array(), "array") . "install.js"))), "html", null, true);
        echo "
        <link rel=\"shortcut icon\" href=\"";
        // line 18
        if (isset($context["img_path"])) { $_img_path_ = $context["img_path"]; } else { $_img_path_ = null; }
        echo twig_escape_filter($this->env, $_img_path_, "html", null, true);
        echo "logo-menu.png\" />
    </head>
    <body style=\"background:#fff url(";
        // line 20
        if (isset($context["img_path"])) { $_img_path_ = $context["img_path"]; } else { $_img_path_ = null; }
        echo twig_escape_filter($this->env, $_img_path_, "html", null, true);
        echo "bg.png);\">
        <div class=\"container-fluid\">
            <!--[if lt IE 7]>
                <p class=\"chromeframe\">You are using an outdated browser. <a href=\"http://browsehappy.com/\">Upgrade your browser today</a> or <a href=\"http://www.google.com/chromeframe/?redirect=true\">install Google Chrome Frame</a> to better experience this site.</p>
            <![endif]-->
            <div class=\"navbar\">
              <div class=\"navbar-inner\">
                <a class=\"brand\" href=\"";
        // line 27
        if (isset($context["install_path"])) { $_install_path_ = $context["install_path"]; } else { $_install_path_ = null; }
        echo twig_escape_filter($this->env, $_install_path_, "html", null, true);
        echo "/features\"><img src=\"";
        if (isset($context["img_path"])) { $_img_path_ = $context["img_path"]; } else { $_img_path_ = null; }
        echo twig_escape_filter($this->env, $_img_path_, "html", null, true);
        echo "logo-menu.png\" /> Creamture <span>Framework</span></a>
                <ul class=\"nav\">
                  <li class=\"";
        // line 29
        echo twig_escape_filter($this->env, getActualSection("features"), "html", null, true);
        echo "\"><a href=\"";
        if (isset($context["install_path"])) { $_install_path_ = $context["install_path"]; } else { $_install_path_ = null; }
        echo twig_escape_filter($this->env, $_install_path_, "html", null, true);
        echo "/features\">";
        echo gettext("Features");
        echo "</a></li>
                  <li class=\"";
        // line 30
        echo twig_escape_filter($this->env, getActualSection("setup"), "html", null, true);
        echo "\"><a href=\"";
        if (isset($context["install_path"])) { $_install_path_ = $context["install_path"]; } else { $_install_path_ = null; }
        echo twig_escape_filter($this->env, $_install_path_, "html", null, true);
        echo "/setup\">";
        echo gettext("Setup");
        echo "</a></li>
                  <li class=\"";
        // line 31
        echo twig_escape_filter($this->env, getActualSection("database"), "html", null, true);
        echo "\"><a href=\"";
        if (isset($context["install_path"])) { $_install_path_ = $context["install_path"]; } else { $_install_path_ = null; }
        echo twig_escape_filter($this->env, $_install_path_, "html", null, true);
        echo "/database\">";
        echo gettext("Database");
        echo "</a></li>
                  <li class=\"";
        // line 32
        echo twig_escape_filter($this->env, getActualSection("documentation"), "html", null, true);
        echo "\"><a href=\"";
        if (isset($context["install_path"])) { $_install_path_ = $context["install_path"]; } else { $_install_path_ = null; }
        echo twig_escape_filter($this->env, $_install_path_, "html", null, true);
        echo "/documentation\">";
        echo gettext("Documentation");
        echo "</a></li>
                  <li class=\"";
        // line 33
        echo twig_escape_filter($this->env, getActualSection("licenses"), "html", null, true);
        echo "\"><a href=\"";
        if (isset($context["install_path"])) { $_install_path_ = $context["install_path"]; } else { $_install_path_ = null; }
        echo twig_escape_filter($this->env, $_install_path_, "html", null, true);
        echo "/licenses\">";
        echo gettext("Licenses");
        echo "</a></li>
                  <li class=\"";
        // line 34
        echo twig_escape_filter($this->env, getActualSection("agreements"), "html", null, true);
        echo "\"><a href=\"";
        if (isset($context["install_path"])) { $_install_path_ = $context["install_path"]; } else { $_install_path_ = null; }
        echo twig_escape_filter($this->env, $_install_path_, "html", null, true);
        echo "/agreements\">";
        echo gettext("Agreements");
        echo "</a></li>
                </ul>
              </div>
            </div>

            <div id=\"content\" class=\"row-fluid container\">
              ";
        // line 40
        $this->displayBlock('content', $context, $blocks);
        // line 41
        echo "            </div>

            <div class=\"footer row-fluid\">";
        // line 43
        echo gettext("made with love in DIGIO Soluciones Digitales");
        echo " &copy; 2012</div>
        </div>
    </body>
</html>
";
    }

    // line 40
    public function block_content($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "layout/install_layout.twig";
    }

    public function isTraitable()
    {
        return false;
    }
}
