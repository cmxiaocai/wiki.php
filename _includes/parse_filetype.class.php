<?php 

class ParseFileType{

    public function __construct($file){
        $this->_filepath  = ROOTDIRECTORY_PATH.POSTS.$file;
        $this->_is_file   = $this->_isFile();
        $this->_is_dir    = $this->_isDir();
        $this->_file_mime = $this->_parseMime();
    }

    private function _isFile(){
        return is_file($this->_filepath);
    }

    private function _isDir(){
        return is_dir($this->_filepath);
    }
    
    private function _parseMime(){
        if( !$this->_is_file ) return false;
        return mime_content_type($this->_filepath);
    }

    public function makeMarkdown($runfunction){
        if( !$this->_is_file ) return;
        //if( !in_array($this->_file_mime, array('text/plain','inode/x-empty')) ) return;
        if( substr($this->_filepath, -3) !== '.md' ) return;
        call_user_func($runfunction, $this->_filepath);
        exit();
    }

    public function makeFileList($runfunction){
        if( !$this->_is_dir ) return;
        if( !file_exists($this->_filepath) ) return;
        call_user_func($runfunction, $this->_filepath);
        exit();
    }

    public function getMime(){
        return $this->_file_mime;
    }

}