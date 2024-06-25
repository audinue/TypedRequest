<?php

namespace TypedRequest;

/**
 * @template T
 */
class TypedRouter
{
  private $routes;
  private $default;

  /**
   * @template U
   * @template V
   * @param class-string<U> $className
   * @param callable(U):V $callback
   * @return TypedRouter<T|V>
   */
  function case(string $className, callable $callback)
  {
    $this->routes[$className] = $callback;
    return $this;
  }

  /**
   * @template U
   * @param callable(RawRequest):U $callback
   * @return TypedRouter<T|U>
   */
  function default(callable $callback)
  {
    $this->default = $callback;
    return $this;
  }

  /**
   * @return T
   */
  function route(RawRequest $rawRequest = null)
  {
    $rawRequest ??= RawRequest::fromGlobals();
    foreach ($this->routes as $className => $callback) {
      $response = TypedRequest::parse($className, $rawRequest);
      if ($response) {
        return $response;
      }
    }
    return ($this->default)($rawRequest);
  }
}