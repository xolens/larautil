<?php

namespace Xolens\PgLarautil\App\Util;

class RepositoryResponse{
    private $_success=false;
    private $_errors=[];
    private $_response;

    /**
     * Get the value of success
     */ 
    public function success(){
        return $this->_success;
    }

    /**
     * Set the value of success
     *
     * @return  self
     */ 
    public function setSuccess($success){
        $this->_success = $success;
        return $this;
    }

    /**
     * Get the value of errors
     */ 
    public function errors(){
        return $this->_errors;
    }

    /**
     * Set the value of errors
     *
     * @return  self
     */ 
    public function setErrors($errors){
        $this->_errors = $errors;
        return $this;
    }

    /**
     * Get the value of _response
     */ 
    public function response(){
        return $this->_response;
    }

    /**
     * Set the value of _response
     *
     * @return  self
     */ 
    public function setResponse($response){
        $this->_response = $response;
        return $this;
    }
}