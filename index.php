<?php
define('ROOTDIRECTORY_PATH', dirname(__FILE__).'/');
define('THEME', 'default');
define('POSTS', '_posts');


include ROOTDIRECTORY_PATH.'_includes/bootstrap.php';

$file = isset($_GET['read']) ? $_GET['read'] : '';

$ParseRoute = new ParseFileType($file);

$ParseRoute->makeMarkdown(function($file){
    $MakeDom = new MakePagePosts();
    $MakeDom->readFile($file);
    $MakeDom->parseConfig();
    $MakeDom->parseHtml();
    $MakeDom->matchMenu();
    $MakeDom->display();
});

$ParseRoute->makeFileList(function($file){
    $MakeDom = new MakePageList();
    $MakeDom->setDir($file);
    $MakeDom->parseConfig();
    $MakeDom->readDir();
    $MakeDom->display();
});

$ParseRoute->makeOther(function($file){
    $request_uri = str_replace(ROOTDIRECTORY_PATH, '/', $file);
    header("Location: {$request_uri}");
});

//echo $ParseRoute->getMime();
