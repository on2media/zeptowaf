<?php

class Demo extends \On2Media\Zeptowaf\Routable
{
    public function getDemo()
    {
        echo 'GET';
    }

    public function postDemo()
    {
        echo 'POST';
    }
}
