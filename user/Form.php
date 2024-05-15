<?php

$_POST = json_decode(file_get_contents("php://input"), true);

class InputField {

    public $type = "text";
    public $required = false;
    public $name;
    public $value;
    public $error;
    public $placeholder = "";
    public $validate_function;

    public function validate(){
        if (isset($this->validate_function)) {
            $funcArr = $this->validate_function;
            $this->error = $funcArr($this);
        }
    }
}

enum Method {

    case GET;
    case POST;
    case DELETE;

}

class Form {
    
    public $method;
    public $enctype;
    public $fields;
    public $action;

    public function __construct ($method, $action, $enctype = null){
        $this->fields = [];
        $this->method = $method;
        $this->enctype = $enctype;
        $this->action = $action;
    }


    public function add_field($name, $type = "text", $required = false, $placeholder, $validate_function){
        $field = new InputField();
        $field->name = $name;
        $field->type = $type;
        $field->required = $required;
        $field->placeholder = $placeholder;
        $field->validate_function = $validate_function;
        // $field->validate_function = function($field){
        //     return call_user_func($validate_function, $field);
        // };

        array_push($this->fields, $field);
    }

    public function get_values_array(){
        $result = [];
        foreach ($this->fields as $field){
            $result[$field->name] = $field->value;
        }
        return $result;
    }

    public function validate_fields(){

        $hasError = false;
        foreach ($this->fields as $field) {
            $field->validate();
            if ($field->error) $hasError = true;
        }
        return !$hasError;

    }

    function parse_data(){

        $data = null;

        switch ($this->method){
            case 'GET': $data = $_GET;
            case 'POST': $data = $_POST;
        }

        $data = $_POST;
        
        if (!$data){
            return false;
        } 

        foreach ($this->fields as $field) {
            if (isset($data[$field->name])){
                $field->value = $data[$field->name];
            }
        }
        return true;

    }


    // used so that the front-end knows which fields to draw and where to send data

    function json_describe(){
        return json_encode($this, JSON_UNESCAPED_UNICODE);
    }
}













?>