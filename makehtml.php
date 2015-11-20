<?php

define('ROOTDIRECTORY_PATH', dirname(__FILE__).'/');

include ROOTDIRECTORY_PATH.'_includes/Parsedown.php';

$file    = $_GET['file'];
$content = file_get_contents(ROOTDIRECTORY_PATH.'_posts/'.$file);

$Parsedown = new Parsedown();
$html      = $Parsedown->setBreaksEnabled(true)
                       ->setMarkupEscaped(true)
                       ->setUrlsLinked(false)
                       ->text($content); 

echo $html;