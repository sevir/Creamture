<?php if ( ! defined('CREAMATOR')) exit('Functions only for Creamator Manager');

/* Functions definition */
$creamator['Docs Commands'] = array(
    'generateDocs' => array(
        'description'=>_('Create static files of documentation'),
        'usage' => _('creamator generateDocs <folder>'),
        'parameters'=> array(_('docs folder')),
        'num_parameters' => 1
    )
);

if(!function_exists('directory_copy'))
{
    function directory_copy($srcdir, $dstdir)
    {
        //preparing the paths
        $srcdir=rtrim($srcdir,'/');
        $dstdir=rtrim($dstdir,'/');

        //creating the destination directory
        if(!is_dir($dstdir))mkdir($dstdir, 0777, true);

        //Mapping the directory
        $dir_map=directory_map($srcdir);

        foreach($dir_map as $object_key=>$object_value)
        {
            if(is_numeric($object_key))
                copy($srcdir.'/'.$object_value,$dstdir.'/'.$object_value);//This is a File not a directory
            else
                directory_copy($srcdir.'/'.$object_key,$dstdir.'/'.$object_key);//this is a directory
        }
    }
}

function generateDocs($folder = 'creamture'){
    if (! file_exists( 'docs/'.$folder)){
        println('Docs folder doesn\'t exists');
        return;
    }

    $daux_global = array(
            'docs_directory'=>'../../../docs/'.$folder,
            'valid_markdown_extensions'=>array('md', 'markdown' )
    );

    if (file_put_contents('docs/global.json', json_encode($daux_global)) === FALSE){
        println('Error writing global conf in docs folder');
        exit;
    }

    require_once('vendor/justinwalsh/daux.io/libs/daux.php');

    foreach (glob('docs/assets/templates/*') as $directory) {
        if (strpos('.', $directory)<0){
            @rmdir('vendor/justinwalsh/daux.io/templates/'.$directory);
        }
    }

    //Copy alternative imgs, js and templates
    directory_copy('docs/assets/templates','vendor/justinwalsh/daux.io/templates');
    if (file_exists('docs/assets/'.$folder.'_img'))
        directory_copy('docs/assets/'.$folder.'_img','vendor/justinwalsh/daux.io/img');

    $Daux = new \Todaymade\Daux\Daux(  realpath('docs/global.json') );
    $Daux->initialize();
    if (! file_exists(PUBLICPATH.'docs/'.$folder))
        mkdir(PUBLICPATH.'docs/'.$folder, 0777, TRUE);

    $Daux->generate_static( PUBLICPATH.'docs/'.$folder );
    println('Docs generated in public folder');
}