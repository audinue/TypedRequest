<?php

namespace TypedRequest;

enum From : string
{
  case Subpath = 'subpath';
  case Get = 'get';
  case Post = 'post';
  case Files = 'files';
  case Session = 'session';
  case Cookie = 'cookie';
}
