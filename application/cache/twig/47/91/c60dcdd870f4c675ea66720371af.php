<?php

/* twigTest_example.html */
class __TwigTemplate_4791c60dcdd870f4c675ea66720371af extends Twig_Template
{
    protected function doDisplay(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        // line 1
        echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html>
\t<head>
\t\t<style type=\"text/css\">

\t\tbody {
\t\t background-color: #fff;
\t\t margin: 40px;
\t\t font-family: Lucida Grande, Verdana, Sans-serif;
\t\t font-size: 14px;
\t\t color: #4F5155;
\t\t}

\t\ta {
\t\t color: #003399;
\t\t background-color: transparent;
\t\t font-weight: normal;
\t\t}

\t\th1 {
\t\t color: #444;
\t\t background-color: transparent;
\t\t border-bottom: 1px solid #D0D0D0;
\t\t font-size: 16px;
\t\t font-weight: bold;
\t\t margin: 24px 0 2px 0;
\t\t padding: 5px 0 6px 0;
\t\t}
\t\t</style>
\t</head>
\t<body>
\t\t<h1>";
        // line 33
        echo twig_escape_filter($this->env, (isset($context['content']) ? $context['content'] : null));
        echo "</h1>
\t\t<ul>
\t\t\t";
        // line 35
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context['features']) ? $context['features'] : null));
        foreach ($context['_seq'] as $context['_key'] => $context['feature']) {
            // line 36
            echo "\t\t\t  <li>";
            echo twig_escape_filter($this->env, (isset($context['feature']) ? $context['feature'] : null), "html");
            echo "</li>
\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['feature'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 38
        echo "\t\t</ul>
\t</body>
</html>";
    }

    public function getTemplateName()
    {
        return "twigTest_example.html";
    }

    public function isTraitable()
    {
        return false;
    }
}
