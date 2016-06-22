<?php

class MakePagePosts{

    public function readFile($file){
        $this->_file = $file;
        $this->_text = file_get_contents(ROOTDIRECTORY_PATH.POSTS.'/'.$file);
    }

    public function parseConfig(){
        $ClsConfig = new parseConfig($this->_text);
        $this->_config  = $ClsConfig->getConf();
        $this->_content = $ClsConfig->getContent();
        if(!$this->_config['title']){
            $this->_config['title'] = basename($this->_file);
        }
        if(!$this->_config['date']){
            $mdate = filemtime(ROOTDIRECTORY_PATH.POSTS.'/'.$this->_file);
            $this->_config['date'] = date('Y-m-d H:i:s', $mdate);
        }
    }

    public function parseHtml(){
        $Parsedown   = new Parsedown();
        $this->_html = $Parsedown->setBreaksEnabled(true)
                                 ->setMarkupEscaped(true)
                                 ->setUrlsLinked(false)
                                 ->text($this->_content);
    }

    public function matchMenu(){
        $Match = new MatchTitle($this->_html);
        $Match->makeTitle('h1');
        $Match->makeTitle('h2');
        $Match->find();
        $this->_menu_html   = $Match->getDom();
        $this->_menu_titles = $Match->getTreeData();
    }

    public function display(){
        $layout = isset($this->_config['layout']) ? $this->_config['layout'] : 'post.html';
        $GLOBALS['template_data'] = array(
            'config'      => $this->_config,
            'html'        => $this->_html,
            'menu_html'   => $this->_menu_html,
            'menu_titles' => $this->_menu_titles,
        );
        include ROOTDIRECTORY_PATH.'_theme/'.THEME.'/'.$layout;
    }

}