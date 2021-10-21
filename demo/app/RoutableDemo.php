<?php

class RoutableDemo extends \On2Media\Zeptowaf\Routable
{
    private $demoClass;

    private $foo;

    public function __construct(ContainerDemo $demoClass, $foo)
    {
        $this->demoClass = $demoClass;
        $this->foo = $foo;
    }

    public function getDemo()
    {
        echo "GET\nfoo = ".$this->foo;
        echo ', env = '.$this->demoClass->getEnv();
    }

    public function postDemo()
    {
        echo 'POST';
    }
}
