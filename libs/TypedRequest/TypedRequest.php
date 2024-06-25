<?php

namespace TypedRequest;

readonly class TypedRequest
{
  /**
   * @template T
   * @param class-string<T> $className
   * @param RawRequest $rawRequest
   * @return T|null
   */
  static function parse(string $className, RawRequest $rawRequest = null)
  {
    $rawRequest ??= RawRequest::fromGlobals();
    $class = new \ReflectionClass($className);
    /** @var Request $request */
    $request = $class->getAttributes(Request::class)[0]->newInstance();
    if ($rawRequest->method != $request->method) {
      return null;
    }
    $pattern = '@^' . $request->path;
    $arguments = [];
    $constructor = $class->getConstructor();
    if ($constructor) {
      $group = 1;
      foreach ($constructor->getParameters() as $parameter) {
        /** @var Field $field */
        $field = $parameter->getAttributes(Field::class)[0]->newInstance();
        if ($field->from == From::Subpath) {
          $pattern .= '/([^/]+)';
          $i = $group++;
          $arguments[] = fn($matches) => $matches[$i];
        } else {
          $from = $field->from->value;
          $name = $field->name ?? $parameter->getName();
          $type = $parameter->getType();
          if ($type && !$type->allowsNull() && !isset($rawRequest->$from[$name])) {
            return null;
          }
          $arguments[] = fn() => $rawRequest->$from[$name] ?? null;
        }
      }
    }
    $pattern .= '$@';
    if (!preg_match($pattern, $rawRequest->path, $matches)) {
      return null;
    }
    try {
      return $class->newInstanceArgs(
        array_map(
          fn($argument) => $argument($matches),
          $arguments
        )
      );
    } catch (\TypeError) {
      return null;
    }
  }
}
