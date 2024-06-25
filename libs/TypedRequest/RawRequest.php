<?php

namespace TypedRequest;

readonly class RawRequest
{
  function __construct(
    public Method $method,
    public string $path,
    public array $get = [],
    public array $post = [],
    public array $files = [],
    public array $session = [],
    public array $cookie = [],
  ) {
  }

  static function fromGlobals()
  {
    return new self(
      Method::from($_SERVER['REQUEST_METHOD']),
      $_SERVER['PATH_INFO'] ?? '/',
      $_GET,
      $_POST,
      $_FILES,
      $_SESSION,
      $_COOKIE
    );
  }
}
