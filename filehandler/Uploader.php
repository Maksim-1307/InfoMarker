<?php 

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';

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
            throw new Exception('Unsupported file extension. Change Uploader->$rules. Allowed extentions: ' . implode(", ", $this->rules["allowed_extensios"]) . ", your file extention is " . $file["name"] . ".", 15);
            return false;
        }
        return true;
    }
    
    public function upload($savePath, $errCallback = null){
        try {

            $file = reset($_FILES);
            if (!$file) throw new Exception("\$_FILES is empty");
            
            $ext = end((explode(".", $file["name"])));
            if (isset($this->rules["unque_name_prefix"])){
                $file["name"] = $this->rules["unque_name_prefix"] . time() . rand(0,9) . "." . $ext;
            } else {
                $file["name"] = transliterate($file["name"]);
            }

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
$TestUploader->set_rule("allowed_extensios", ["docx", "doc", "word"]);

?>