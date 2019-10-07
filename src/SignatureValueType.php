<?php

namespace generatenfe;

class SignatureValueType {

    public $value;
    public $Id;
    
    function getValue() {
        return $this->value;
    }

    function getId() {
        return $this->Id;
    }

    function setValue($value) {
        $this->value = $value;
    }

    function setId($Id) {
        $this->Id = $Id;
    }
}
