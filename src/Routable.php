<?php

namespace On2Media\Zeptowaf;

abstract class Routable
{
    protected $request;
    protected $container;

    public function __construct($request, &$container)
    {
        $this->request = $request;
        $this->container = &$container;
    }
}
