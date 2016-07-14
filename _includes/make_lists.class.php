<?php

class MakePageList{

    private $_config = array();
    private $_files  = array();
    private $_layout = 'lists.html';

    public function setDir($file){
        $this->_dirpath = $file;
    }

    public function parseConfig(){
        $fileconf = $this->_dirpath.'/.conf';
        if( !file_exists($fileconf) ){
            return;
        }
        $ClsConfig = new parseConfig(file_get_contents($fileconf));
        $this->_config  = $ClsConfig->getConf();
    }

    public function readDir(){
        $handler = opendir($this->_dirpath);  
        while (($filename = readdir($handler)) !== false) {
            if ( !in_array($filename, array('.','..','.conf')) ) {
                $filepath = $this->_dirpath.'/'.$filename;
                $fileinfo = stat($filepath);
                $filetype = is_dir($filepath) ? 'dir' : 'file';
                $files[$filetype][] = array(
                    'name'  => $filename,
                    'mtime' => $fileinfo['mtime'],
                    'ctime' => $fileinfo['ctime'],
                    'size'  => $this->_formatBytes($fileinfo['size']),
                );
            }
        }
        closedir($handler);
        $this->_files = $files;
    }

    private function _formatBytes($size) { 
        $units = array(' B', ' KB', ' MB', ' GB', ' TB'); 
        for ($i = 0; $size >= 1024 && $i < 4; $i++){
            $size /= 1024;
        }
        return round($size, 2).$units[$i]; 
    }

    public function display(){
        $GLOBALS['template_data']['path']   = str_replace(ROOTDIRECTORY_PATH.POSTS, '', $this->_dirpath);
        $GLOBALS['template_data']['lists']  = $this->_files;
        $GLOBALS['template_data']['config'] = $this->_config;
        include ROOTDIRECTORY_PATH.'/_theme/'.THEME.'/'.$this->_layout;
    }

}

