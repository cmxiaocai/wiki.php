<?php
/**
 * 匹配Markdown标题
 * @author http://www.xiaocai.name/about/
 * @since  2015-11-21
 */
class MatchTitle{

    private $_titles;
    private $DOM;

    public function __construct($html_dom){
        $this->_titles = array();
        $this->DOM    = $html_dom;
    }

    public function makeTitle($tag_name){
        $dom = $this->DOM->find($tag_name);
        foreach($dom as $index => $tag){
            $name = $tag->innertext;
            $tag->outertext="<{$tag_name} id='{$name}' index='{$index}'>{$name}</{$tag_name}>";
        }
    }

    public function find(){
        $h1_dom = $this->DOM->find('h1');
        foreach($h1_dom as $index => $h1){
            $h1_name = $h1->innertext;
            $this->_titles[$h1_name] = array();
            $this->findChildH2($h1_name, $h1);
        }
    }

    public function findChildH2($h1_name, $dom){
        $next_dom  = $dom->next_sibling();
        if(!$next_dom){
            return;
        }
        $node_name = $next_dom->nodeName();
        if($node_name=='h2'){
            if(!empty($next_dom->innertext)){
                $this->_titles[$h1_name][] = $next_dom->innertext;
            }
        }
        if($node_name=='h1'){
            return;
        }else{
            $this->findChildH2($h1_name, $next_dom);
        }
    }

    public function getDom(){
        return $this->DOM;
    }

    public function getTreeData(){
        return $this->_titles;
    }

}