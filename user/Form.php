<?php

class InputField {

    public $type = "text";
    public $required = false;
    public $name;
    public $value;
    public $error;
    public $validate_function;

    public function validate(){
        if (isset($this->validate_function)) {
            $this->error = $this->validate_function($this);
        }
    }

}

enum Method {

    case GET;
    case POST;
    case DELETE;

}

class Form {
    
    private Method $method;
    private $enctype;
    private $fields;
    private $action;

    public function __construct (Method $method, $action, $enctype = null){
        $this->fields = [];
        $this->method = $method;
        $this->enctype = $enctype;
        $this->action = $action;
    }


    public function add_field($name, $type = "text", $required = false, $validate_function){
        $field = new InputField();
        $field->name = $name;
        $field->type = $type;
        $field->required = $required;
        $field->validate_function = $validate_function;
        array_push($this->fields, $field);
    }


    // used so that the front-end knows which fields to draw and where to send data

    function json_describe(){
        return json_encode($this);
    }
}


?>