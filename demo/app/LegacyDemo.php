<?php

class LegacyDemo extends \On2Media\Zeptowaf\Routable
{
    public function getDemo()
    {
        echo "GET\nfoo = ".$this->container['foo'];
        echo ', env = '.$this->container[ContainerDemo::class]->getEnv();
    }

    public function postDemo()
    {
        echo 'POST';
    }
}
