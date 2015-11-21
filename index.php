<?php
define('ROOTDIRECTORY_PATH', dirname(__FILE__).'/');
define('THEME', 'default');

$file = $_GET['read'];


include ROOTDIRECTORY_PATH.'_includes/Parsedown.php';
include ROOTDIRECTORY_PATH.'_includes/simple_html_dom.php';

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

$titles   = array();
$html_dom = str_get_html($html);
foreach($html_dom->find('h1') as $index => $h1){
    $text = $h1->innertext;
    $h1->outertext="<h1 id='{$text}' index='{$index}'>{$text}</h1>";
    $titles[] = $text;
}


include ROOTDIRECTORY_PATH.'/_theme/'.THEME.'/post.html';