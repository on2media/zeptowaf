<?php

namespace On2Media\Zeptowaf\Exception;

class Validation extends Exception
{
    private array $errors;

    private array $reasons;

    public function __construct(
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null,
        array $errors = [],
        array $reasons = []
    ) {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
        $this->reasons = $reasons;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getReasons(): array
    {
        return $this->reasons;
    }
}
