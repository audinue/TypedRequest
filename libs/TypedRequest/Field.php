<?php

namespace TypedRequest;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
readonly class Field
{
  function __construct(
    public From $from,
    public ?string $name = null
  ) {
  }
}
