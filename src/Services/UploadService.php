<?php
namespace App\Services;


class UploadService extends BaseService
{
    public function upload ($_directory,$_indexFilename)
    {
         
                $this->options = array(
                    //'script_url' => $this->getFullUrl().'/',            
                    'param_name' => $_indexFilename,
                    'uploads_dir' => $_directory,
                    'folder' => '',
                    'max_file_size' => null,
                    'accept_file_types' => '/.+$/i',
                    'allowed_extensions' => array('jpg', 'jpeg', 'png','gif','pdf','xls','ppt','docx','txt','rar','zip'),
                     );
           
            if ($_FILES) 
            {
                $this->options = array_replace_recursive($this->options, $_FILES);
                $_FILES = array_merge($this->options, $_FILES);
            }
            $upload = $_FILES[$this->options['param_name']];
            $tmp_name = $upload["tmp_name"];
            $fileName = $this->trim_file_name($upload["name"]);
            
            $size = $upload["size"];
            
            if(array_key_exists('allowed_extensions', $_FILES)){
                $allowedExtensions = $_FILES['allowed_extensions'];
                // Build a regular expression like /(\.gif|\.jpg|\.jpeg|\.png)$/i
                $allowedExtensionsRegex = '/(' . implode('|', array_map(function($extension) { return '\.' . $extension; }, $allowedExtensions)) . ')$/i';
                $this->options['accept_file_types'] = $allowedExtensionsRegex;
            }
            if($upload["error"])
            {
                $file->error = $upload["error"] ;
                
            }
            elseif($this->options['max_file_size'] && $this->options['max_file_size']< $size) 
            {
                $file->error = 'maxFileSize';
            }
            elseif (!preg_match($this->options['accept_file_types'], $fileName)) 
            {
                $file->error = 'acceptFileTypes';
            }
            else
            {
                move_uploaded_file($tmp_name, $_directory.'/'.$fileName);
            }
            return $fileName;
    }



    /**
    * Delete space filename and create new filename
    *
    * @param [type] $name
    * @return void
    */
    protected function trim_file_name($name, $_retainName = false) 
    {
        $file_name = trim(basename(stripslashes($name)), ".\x00..\x20");
        $extFile = pathinfo($file_name, PATHINFO_EXTENSION);
        $fileName = md5(uniqid()).'.'.$extFile;
        if($_retainName == true){
            $fileName = $name;
        }
         
        return $fileName;
        
    }

    
     /**
     * Create directory file 
     *
     * @param [type] $path
     * @return void
     */
    public function makePath($path)
	{
       // $dir = pathinfo($path , PATHINFO_DIRNAME);
        
        if (is_dir($path))
        {
           return true;
        }
        else 
        { 
            if(mkdir($path,0777,true))
            {
                return true;
            }
            else{
                return false;
            }
            
        }
		return false;
    }

}