<?php

/* install_view.twig */
class __TwigTemplate_d9e82aa431c70c7cde954a3ca8096a87 extends Twig_Template
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
        echo "<div class=\"span12 center\">
\t<img src=\"";
        // line 5
        if (isset($context["img_path"])) { $_img_path_ = $context["img_path"]; } else { $_img_path_ = null; }
        echo twig_escape_filter($this->env, $_img_path_, "html", null, true);
        echo "creamture_logo.png\" width=\"400\" />
</div>
<div class=\"row\">
\t<div class=\"offset2 span8\">
\t\t<p class=\"lead resume center\">";
        // line 9
        echo gettext("The modern framework for building big web applications, built-in over the latest practices and standards HTML5, CSS3 and JavaScript patterns");
        echo "</p>
\t\t<div class=\"offset1 span9 features\">
\t\t\t<h5>It includes all this features:</h5>
\t\t\t<ul class=\"unstyled\">
\t\t\t\t<li>
\t\t\t\t\t<i class=\"icon-ok\"></i> CodeIgniter Reactor
\t\t\t\t\t<p>Based in the latest community CodeIgniter version.</p>
\t\t\t\t</li>
\t\t\t\t<li>
\t\t\t\t\t<i class=\"icon-ok\"></i> REST Server
\t\t\t\t\t<p>Build the better REST API for your application, used with JS Backbone applications.</p>
\t\t\t\t</li>
\t\t\t\t<li>
\t\t\t\t\t<i class=\"icon-ok\"></i> Twig template library
\t\t\t\t\t<p>Flexible, fast and secure template engine for PHP. One of the more advanced template engine of the hand of Symfony creators.</p>
\t\t\t\t</li>
\t\t\t\t<li>
\t\t\t\t\t<i class=\"icon-ok\"></i> Sparks package management system
\t\t\t\t\t<p>Install easily CI libraries and extensions, extend your framework rapidly with thousand of features.</p>
\t\t\t\t</li>
\t\t\t\t<li>
\t\t\t\t\t<i class=\"icon-ok\"></i> Command Line CRUD &amp; functions
\t\t\t\t\t<p>Creamator is the CLI manager that you can extend easily with functions. Try now writing: <pre>./creamture creamator help.</pre></p>
\t\t\t\t</li>
\t\t\t\t<li>
\t\t\t\t\t<i class=\"icon-ok\"></i> HMVC Modules
\t\t\t\t\t<p>Write your application with modules, refactorize your app!!</p>
\t\t\t\t</li>
\t\t\t\t<li>
\t\t\t\t\t<i class=\"icon-ok\"></i> Bitauth light user management
\t\t\t\t\t<p>Construct your application with support of users, roles, groups and permissions.</p>
\t\t\t\t</li>
\t\t\t\t<li>
\t\t\t\t\t<i class=\"icon-ok\"></i> AJAX library
\t\t\t\t\t<p>Deploy fast AJAX responses with the AJAX sparks in JSON or XML format with Cross-Domain AJAX support.</p>
\t\t\t\t</li>
\t\t\t\t<li>
\t\t\t\t\t<i class=\"icon-ok\"></i> PHP Error improvement visualization
\t\t\t\t\t<p>PHP Error Library improves the error visualization also in the AJAX calls.</p>
\t\t\t\t</li>
\t\t\t\t<li>
\t\t\t\t\t<i class=\"icon-ok\"></i> SimpleTester Unit Testing
\t\t\t\t\t<p>Write your unit testing for your code.</p>
\t\t\t\t</li>
\t\t\t\t<li>
\t\t\t\t\t<i class=\"icon-ok\"></i> CI PHP Language Editor
\t\t\t\t\t<p>An online Language Editor for CI, edit easily your language files with automatic Google translation for help us.</p>
\t\t\t\t</li>
\t\t\t\t<li>
\t\t\t\t\t<i class=\"icon-ok\"></i> Automatic environment configuration
\t\t\t\t\t<p>Set the environment based in the operating system or the computer name. No more changes by hand, prepare your code for all the environments and run automatically with the correct routine.</p>
\t\t\t\t</li>
\t\t\t\t<li>
\t\t\t\t\t<i class=\"icon-ok\"></i> GetText i18n Support with Command Line generation
\t\t\t\t\t<p>Generation of PO files and support within Twig templates. Command line creamator commands for PO generation.</p>
\t\t\t\t</li>
\t\t\t\t<li>
\t\t\t\t\t<i class=\"icon-ok\"></i> Assets management with LESS, CoffeeScript, CSS and JS Minify
\t\t\t\t\t<p>Supported in Twig and CI standard views. You can write your CSS in LESS syntax, use variables, and unify files. Write in CoffeScript and generate new compilation cache when your source files has been changed.</p>
\t\t\t\t</li>
\t\t\t\t<li>
\t\t\t\t\t<i class=\"icon-ok\"></i> HTML5 Boilerplate
\t\t\t\t\t<p>Prepared for HTML5, with robots.txt, Flash crossdomain file, humans.txt,...</p>
\t\t\t\t</li>
\t\t\t\t<li>
\t\t\t\t\t<i class=\"icon-ok\"></i> Twitter Boostrap CSS Framework
\t\t\t\t\t<p>Frontend framework for easy web development.</p>
\t\t\t\t</li>
\t\t\t\t<li>
\t\t\t\t\t<i class=\"icon-ok\"></i> jQuery
\t\t\t\t\t<p>The JS DOM manipulation library by default.</p>
\t\t\t\t</li>
\t\t\t\t<li>
\t\t\t\t\t<i class=\"icon-ok\"></i> PlUpload
\t\t\t\t\t<p>Upload files with size limit, automatically trip the file using Flash, HTML5, and compatible with old browsers.</p>
\t\t\t\t</li>
\t\t\t\t<li>
\t\t\t\t\t<i class=\"icon-ok\"></i> Channels.js
\t\t\t\t\t<p>Write JavaScript modules communicating all the components within iframes, windows or in the same page. Go multi-desktop web apps!!</p>
\t\t\t\t</li>
\t\t\t\t<li>
\t\t\t\t\t<i class=\"icon-ok\"></i> Modernizr.js
\t\t\t\t\t<p>HTML5 compatibility for old browsers.</p>
\t\t\t\t</li>
\t\t\t\t<li>
\t\t\t\t\t<i class=\"icon-ok\"></i> Require.js
\t\t\t\t\t<p>AMD Support for your JavaScript Applications.</p>
\t\t\t\t</li>
\t\t\t\t<li>
\t\t\t\t\t<i class=\"icon-ok\"></i> Backbone &amp; Underscore js library
\t\t\t\t\t<p>Build true JavaScript Web Applications with REST backend.</p>
\t\t\t\t</li>
\t\t\t</ul>
\t\t</div>
\t</div>
</div>
";
    }

    public function getTemplateName()
    {
        return "install_view.twig";
    }

    public function isTraitable()
    {
        return false;
    }
}
