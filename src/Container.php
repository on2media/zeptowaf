<?php

namespace On2Media\Zeptowaf;

use Psr\Container\ContainerInterface;

class Container implements \ArrayAccess, ContainerInterface
{
    private $entries = [];

    public function __construct(array $entries = [])
    {
        foreach ($entries as $id => $entry) {
            $this->isValidId($id);
            $this->set($id, $entry);
        }
    }

    public function set(string $id, $value): void
    {
        $this->entries[$id] = $value;
    }

    public function unset(string $id): void
    {
        unset($this->entries[$id]);
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->entries);
    }

    public function get(string $id): mixed
    {
        if (!$this->has($id)) {
            throw new Exception\Container('Entry `'.$id.'` not found in container');
        }
        if (is_callable($this->entries[$id])) {
            return $this->entries[$id]($this);
        }
        return $this->entries[$id];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->isValidId($offset);
        $this->set($offset, $value);
    }

    public function offsetExists(mixed $offset): bool
    {
        $this->isValidId($offset);
        return $this->has($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        $this->isValidId($offset);
        return $this->get($offset);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->isValidId($offset);
        $this->unset($offset);
    }

    private function isValidId(mixed $id): void
    {
        if (!is_string($id)) {
            throw new Exception\Exception('Container IDs must be strings');
        }
    }
}
