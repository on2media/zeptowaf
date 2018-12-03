<?php

namespace On2Media\Zeptowaf\Exception;

class Validation extends Exception
{
    private $errors;
    private $reasons;

    public function __construct(
        $message = null,
        $code = 0,
        \Exception $previous = null,
        array $errors = [],
        array $reasons = []
    ) {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
        $this->reasons = $reasons;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getReasons()
    {
        return $this->reasons;
    }
}
