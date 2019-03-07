<?php

namespace Xolens\PgLarautil\App\Exception;
use Exception;

class InvalidArgumentTypeException extends Exception
{
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function throwIf($condition, $espected, $used) {
        $message = "Invalid argument type exception [ Espected: \"".$espected."\", Used \"".$used."\" ]";
    }
}