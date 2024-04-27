<?php 

// requires started session

class Uploader{

    private $rules = [
        "allowed_extensios" => [],
        "max_size" => 10, // in Mb
        "allow_rewrite" => false,
        "allow_create_dir" => true,
        // default: filename.ext  
        // if defined: prefix + server time
        "unque_name_prefix" => null, 
    ];

    public function set_rule($name, $value){
        $this->rules[$name] = $value;
    }

    public function check_file($file){
        $ext = end((explode(".", $file["name"])));
        if (!in_array($ext, $this->rules["allowed_extensios"])){
            throw new Exception('Unsupported file extension. Change Uploader->$rules');
            return false;
        }
        return true;
    }
    
    public function save_file($name, $savePath, $errCallback){
        try {
            $file = $_FILES[$name];
            var_dump($_FILES);
            if (!$this->check_file($file)) return false;
            if (!is_dir($savePath)){
                if (!$this->rules["allow_create_dir"]){
                    throw new Exception('Directory ' . $savePath . ' not found. If you want it will be created automaticly, please change Uploader->$rules');
                    return false;
                }
                if (!mkdir($savePath)) {
                    throw new Exception('Failed to create a directory in Uploader.php');
                    return false;
                }
            }
            if (!move_uploaded_file($file['tmp_name'], $savePath . $file["name"])){
                throw new Exception('Failed to save file in Uploader.php');
                return false;
            }
            return $savePath . $file["name"];
            
        } catch (Exception $e){
            if (isset($errCallback)){
                $errCallback($e);
            } else {
                throw $e;
            }
            return false;
        }
    }
}

$TestUploader = new Uploader();
$TestUploader->set_rule("allowed_extensios", ["txt", "rtf", "jpeg", "jpg", "png"]);

?>