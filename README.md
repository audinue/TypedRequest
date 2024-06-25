# TypedRequest

```php
#[Request(Method::Post, '/login')]
readonly class LoginRequest
{
  function __construct(
    #[Field(From::Post)]
    public string $username,
    #[Field(From::Post)]
    public string $password,
  ) {
  }
}

$request = TypedRequest::parse(LoginRequest::class);
if ($request) {
  // ...
}

// Mock it up
$request = TypedRequest::parse(
  LoginRequest::class,
  new RawRequest(
    Method::Post,
    '/login',
    post: [
      'username' => 'admin',
      'password' => 'admin',
    ]
  )
);

$response = (new TypedRouter())
  ->case(LoginRequest::class, function ($req) {
    return 'Login';
  })
  ->default(function ($req) {
    return 'Not found';
  })
  ->route();
```
