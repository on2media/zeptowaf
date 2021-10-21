<?php

class ContainerDemo
{
    private $env;

    public function __construct(string $env)
    {
        $this->env = $env;
    }

    public function getEnv(): string
    {
        return $this->env;
    }
}
