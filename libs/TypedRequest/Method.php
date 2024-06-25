<?php

namespace TypedRequest;

enum Method: string
{
  case Get    = 'GET';
  case Post   = 'POST';
  case Put    = 'PUT';
  case Patch  = 'PATCH';
  case Delete = 'DELETE';
}
