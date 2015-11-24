<?php
define('ROOTDIRECTORY_PATH', dirname(__FILE__).'/');
define('THEME', 'default');

$file = $_GET['read'];

include ROOTDIRECTORY_PATH.'_includes/Parsedown.php';
include ROOTDIRECTORY_PATH.'_includes/simple_html_dom.php';
include ROOTDIRECTORY_PATH.'_includes/match_title.class.php';
include ROOTDIRECTORY_PATH.'_includes/parse_config.class.php';

$content   = file_get_contents(ROOTDIRECTORY_PATH.'_posts/'.$file);

$ClsConfig = new parseConfig($content);
$config    = $ClsConfig->getConf();
$content   = $ClsConfig->getContent();

$Parsedown = new Parsedown();
$html      = $Parsedown->setBreaksEnabled(true)
                       ->setMarkupEscaped(true)
                       ->setUrlsLinked(false)
                       ->text($content); 

$Match = new MatchTitle($html);
$Match->makeTitle('h1');
$Match->makeTitle('h2');
$Match->find();
$html_dom = $Match->getDom();
$titles   = $Match->getTreeData();

$viewfile = ROOTDIRECTORY_PATH.'/_theme/'.THEME.'/'.$config['layout'];
if(empty($config['layout']) || file_exists($viewfile)){
    echo $html_dom;
    exit();
}
include ROOTDIRECTORY_PATH.'/_theme/'.THEME.'/'.$config['layout'];
