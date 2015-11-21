<?php
define('ROOTDIRECTORY_PATH', dirname(__FILE__).'/');
define('THEME', 'default');

$file = $_GET['read'];

include ROOTDIRECTORY_PATH.'_includes/Parsedown.php';
include ROOTDIRECTORY_PATH.'_includes/simple_html_dom.php';
include ROOTDIRECTORY_PATH.'_includes/match_title.class.php';

$content   = file_get_contents(ROOTDIRECTORY_PATH.'_posts/'.$file);


$config = array();
preg_match("/<wiki\stype=.config.>(?:.|[\r\n])*?<\/wiki>/i", $content, $matches);
if(!empty($matches)){
    $config_text = trim(strip_tags($matches[0]));
    $config_text = explode('<br />', nl2br($config_text));
    foreach ($config_text as $line) {
        list($key, $val) = explode('=', $line);
        $config[trim($key)] = trim($val);
    }
    $content = str_replace($matches[0], '', $content);
}


$Parsedown = new Parsedown();
$html      = $Parsedown->setBreaksEnabled(true)
                       ->setMarkupEscaped(true)
                       ->setUrlsLinked(false)
                       ->text($content); 

$html_dom = str_get_html($html);
$Match    = new MatchTitle($html_dom);
$Match->makeTitle('h1');
$Match->makeTitle('h2');
$Match->find();
$html_dom = $Match->getDom();


$titles = $Match->getTreeData();


include ROOTDIRECTORY_PATH.'/_theme/'.THEME.'/post.html';