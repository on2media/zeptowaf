<?php

namespace On2Media\Zeptowaf;

class Request
{
    private static $instance;
    private $protocol;
    private $method;
    private $scheme;
    private $host;
    private $path;
    private $base;
    private $uri;
    private $query;

    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function __construct()
    {
        $this->protocol = $_SERVER['SERVER_PROTOCOL'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->scheme = (
            !isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off' ? 'http' : 'https'
        );
        $path = dirname($_SERVER['SCRIPT_NAME']);
        $uri = $_SERVER['REQUEST_URI'];
        if (($pos = strpos($uri, '?')) !== false) {
            $this->query = substr($uri, $pos);
            $uri = substr($uri, 0, $pos);
        }
        if ($path != '/') {
            $uri = substr($uri, strlen($path));
        }
        if ($path == '/') {
            $path = '';
        }
        $this->path = $path;
        $this->host = $_SERVER['HTTP_HOST'];
        $this->base = $this->scheme . '://' . $this->host . $this->path;
        $this->uri = rawurldecode($uri);
    }

    public function getProtocol()
    {
        return $this->protocol;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getScheme()
    {
        return $this->scheme;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getBase()
    {
        return $this->base;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getQuery()
    {
        return $this->query;
    }
}
