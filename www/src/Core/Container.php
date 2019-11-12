<?php

namespace App\Core;

use ReflectionParameter;

class Container
{
    private $services = [];

    public function get(string $id, array $parameters = [])
    {
        if (!$this->has($id)) {
            $this->set($id);
        }

        return $this->resolve($this->services[$id], $parameters);
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }

    public function set(string $abstract, string $concrete = null)
    {
        if (null === $concrete) {
            $concrete = $abstract;
        }

        $this->services[$abstract] = $concrete;
    }

    private function resolve($class, $parameters)
    {
        if ($class instanceof \Closure) {
            return $class($this, $parameters);
        }

        $reflector = new \ReflectionClass($class);
        if (!$reflector->isInstantiable()) {
            throw new \Exception();
        }

        $constructor = $reflector->getConstructor();
        if (null === $constructor) {
            return $reflector->newInstance();
        }

        $parameters = $constructor->getParameters();
        $dependencies = $this->getDependencies($parameters);

        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * @param array|ReflectionParameter[] $parameters
     */
    private function getDependencies(array $parameters)
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();
            if (null === $dependency) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new \Exception();
                }
            } else {
                $dependencies[] = $this->get($dependency->name);
            }
        }

        return $dependencies;
    }
}