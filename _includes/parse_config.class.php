<?php
/**
 * 解析Markdown中的配置
 * @author http://www.xiaocai.name/about/
 * @since  2015-11-24
 */
class parseConfig{

    private $_conf;
    private $_matches;
    private $_content;

    const PREG_WIKI_CONF = "/<wiki\stype=.config.>(?:.|[\r\n])*?<\/wiki>/i";

    public function __construct($content){
        $this->_content = $content;
        $this->_setDefaultValue();
        $this->_readLabel();
    }
    private function _setDefaultValue(){
        $this->_conf = array(
            'title'   => '', //文件名称
            'date'    => '', //文件修改时间
            'summary' => '',
            'layout'  => null,
        );
    }
    private function _readLabel(){
        preg_match("/<wiki\stype=.config.>(?:.|[\r\n])*?<\/wiki>/i", $this->_content, $this->_matches);
        if(empty($this->_matches)){
            return;
        }
        $config_text = trim(strip_tags($this->_matches[0]));
        $config_text = explode('<br />', nl2br($config_text));
        foreach ($config_text as $line) {
            list($key, $val) = explode('=', $line);
            $this->_conf[trim($key)] = trim($val);
        }
    }

    public function getContent(){
        if(empty($this->_matches)){
            return $this->_content;
        }
        return str_replace($this->_matches[0], '', $this->_content);
    }
    public function getConf(){
        return $this->_conf;
    }

}