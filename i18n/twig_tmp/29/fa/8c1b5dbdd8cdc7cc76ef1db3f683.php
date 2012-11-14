<?php

/* setup_view.twig */
class __TwigTemplate_29fa8c1b5dbdd8cdc7cc76ef1db3f683 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout/install_layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "<div class=\"span2 bs-docs-sidebar\">
\t<ul class=\"nav nav-pills nav-stacked bs-docs-sidenav inverse\">
\t\t<li><a href=\"#overview\"><i class=\"icon-chevron-right\"></i> Overview</a></li>
\t\t<li><a href=\"#overview\"><i class=\"icon-chevron-right\"></i> Phpinfo</a></li>
\t\t<li class=\"divider\"></li>
\t\t<li class=\"nav-header\">Server configuration</li>
\t\t<li><a href=\"#transitions\"><i class=\"icon-chevron-right\"></i> Apache .htaccess</a></li>
\t\t<li><a href=\"#transitions\"><i class=\"icon-chevron-right\"></i> Nginx</a></li>
\t</ul>
</div>
<div class=\"span9\"></div>
";
    }

    public function getTemplateName()
    {
        return "setup_view.twig";
    }

    public function isTraitable()
    {
        return false;
    }
}
