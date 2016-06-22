<?php

class MakePageList{

    private $_files  = array();
    private $_layout = 'lists.html';

    public function setDir($file){
        $this->_dirpath = ROOTDIRECTORY_PATH.POSTS.'/'.$file;
    }

    public function readDir(){
        $handler = opendir($this->_dirpath);  
        while (($filename = readdir($handler)) !== false) {
            if ($filename != "." && $filename != "..") {
                $filepath = $this->_dirpath.'/'.$filename;
                $fileinfo = stat($filepath);
                $filetype = is_dir($filepath) ? 'dir' : 'file';
                $files[$filetype][] = array(
                    'name'  => $filename,
                    'mtime' => $fileinfo['mtime'],
                    'ctime' => $fileinfo['ctime'],
                    'size'  => $fileinfo['size'],
                );
            }
        }
        closedir($handler);
        $this->_files = $files;
    }

    public function display(){
        $GLOBALS['template_data']['path']  = str_replace(ROOTDIRECTORY_PATH.POSTS.'/', '', $this->_dirpath);
        $GLOBALS['template_data']['lists'] = $this->_files;
        include ROOTDIRECTORY_PATH.'/_theme/'.THEME.'/'.$this->_layout;
    }

}

