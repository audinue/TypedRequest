<?php

namespace TypedRequest;

#[\Attribute(\Attribute::TARGET_CLASS)]
readonly class Request
{
  function __construct(
    public Method $method,
    public string $path,
  ) {
  }
}
