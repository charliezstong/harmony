# Woohoo Labs. Harmony

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]
[![Gitter][ico-gitter]][link-gitter]

**Woohoo Labs. Harmony is a flexible micro-framework developed for PHP applications.**

Our aim was to create an invisible, easily extensible, but first of all, extremely flexible framework for your
quality application. We wanted to give you total control via
[PSR-7](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md) and
[Container-Interop](https://github.com/container-interop/container-interop/blob/master/docs/ContainerInterface.md).

## Table of Contents

* [Introduction](#introduction)
* [Install](#install)
* [Basic Usage](#basic-usage)
* [Advanced Usage](#advanced-usage)
* [Examples](#examples)
* [Versioning](#versioning)
* [Change Log](#change-log)
* [Contributing](#contributing)
* [Credits](#credits)
* [License](#license)

## Introduction

#### Rationale

This post summarizes the best why Harmony was born: http://www.catonmat.net/blog/frameworks-dont-make-sense/ 

#### Features

- Extreme flexibility through middleware
- Full control over HTTP requests and responses via PSR-7
- Support for any IoC Containers via Container-Interop
- Totally object-oriented workflow

#### What's different?

There are a lot very similar middleware dispatcher libraries out there. To name a few:
[Zend-Stratigility](https://github.com/zendframework/zend-stratigility/),
[Slim Framework 3](http://www.slimframework.com/docs/concepts/middleware.html) or [Relay](http://relayphp.com/).
So what is the purpose of yet another library with the same functionality?

We believe Harmony is superior to the others in two key things:

- It is the simplest of all. Although simplicity is subjective, one thing is for sure: Harmony offers the least
functionality which is minimally needed. It doesn't have capabilities which are not required really.
That's why Harmony fits in a single class and its implementation doesn't even took 300 lines.

- Starting from version 3, Harmony natively supports the concept of [Conditions](#defining-conditions) which is a unique
feature for middleware dispatchers. This eases a major weakness of the middleware-oriented approach which is being able
to invoke middleware conditionally.

#### Use Cases of Woohoo Labs. Harmony

Harmony won't suit the needs of all projects and teams. Firstly, this framework works best
for advanced teams. So less experienced teams should probably choose a less lenient framework with more features
in order to speed up development in its initial phase. Harmony's flexibility is the most advantageous if your
software is a long-term, strategic project. That's why legacy applications can also profit from Harmony because it
eases gradual refactoring.

#### Concepts

Woohoo Labs. Harmony is built upon two main concepts: middleware which promote separation of concerns and
common interfaces allowing you to band your favourite tools together!

Middleware - that are [described in detail by Igor Wiedler](https://igor.io/2013/02/02/http-kernel-middlewares.html) -
make it possible to take hands on the course of action of the request-response lifecycle: you can authenticate before
routing, do some logging after the response has been sent, or you can even dispatch multiple routes in one
request if you want. These can be achieved because everything in Harmony is a middleware, so the framework itself only
consists of cc. 300 lines of code. And that's why there is no framework-wide configuration (only middleware can
be configured). Basically it only depends on your imagination and needs what you do with Harmony.

But middleware must work in cooperation (especially the router and the dispatcher are tightly coupled to each other).
That's why it is also important to provide common interfaces for the distinct components of the framework.

Naturally, we decided to use [PSR-7](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md)
for modelling the HTTP request and response. In order to facilitate the usage of different IoC Containers, we
adapted the [Container-Interop standard interface](https://github.com/container-interop/container-interop)
(which is supported by various containers off-the-shelf).

#### Available Middleware

Woohoo Labs. Harmony's middleware interface design is based on the "request, response, next" style advocated
by such prominent developers as [Matthew Weier O'Phinney](https://mwop.net/) (you can read more on the topic
[in his blog post](https://mwop.net/blog/2015-01-08-on-http-middleware-and-psr-7.html)). That's why
Harmony's middleware are compatible with middleware built for
[Zend-Stratigility](https://github.com/zendframework/zend-stratigility/),
[Slim Framework 3](http://www.slimframework.com/) or [Relay](http://relayphp.com/).

Furthermore, you can find various other middleware available for Harmony:

- [Woohoo Labs. Yin-Middleware](https://github.com/woohoolabs/yin-middleware): A bunch of middleware to integrate
[Woohoo Labs. Yin](https://github.com/woohoolabs/harmony) - the elegant JSON API framework - into Harmony.
- [PSR-7 Middlewares](https://github.com/oscarotero/psr7-middlewares): A collection of PSR-7 middleware
- [MiniUrl](https://github.com/mtymek/MiniUrl): A simple URL shortener, which can be used as a free, open-source
replacement for bit.ly's core functionality: creating short links and redirecting users.

## Install

The steps of this process are quite straightforward. The only thing you need is [Composer](http://getcomposer.org).

#### Add Harmony to your composer.json:

To install this library, run the command below and you will get the latest version:

```bash
$ composer require woohoolabs/harmony
```

#### Require the necessary dependencies:

If you want to use the default middleware then you have to ask for the following dependencies too:

```bash
$ composer require nikic/fast-route:^1.0.0
$ composer require zendframework/zend-diactoros:^2.3.0
```

## Basic Usage

#### Define Your Endpoints:

The following example applies only if you use the
[default dispatcher middleware](https://github.com/woohoolabs/harmony/blob/master/src/Middleware/DispatcherMiddleware.php).
There are two important things to notice here: first, each endpoint receives a `Psr\Http\Message\ServerRequestInterface`
and a `Psr\Http\Message\ResponseInterface` object and they are expected to manipulate and return the latter.
Second, you are not forced to only use classes for the endpoints, it is possible to define other callables too (see
below in the routing section).

```php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserController
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function getUsers(ServerRequestInterface $request, ResponseInterface $response)
    {
        $users = ["Steve", "Arnie", "Jason", "Bud"];
        $response->getBody()->write(json_encode($users));
        
        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
     public function updateUser(ServerRequestInterface $request, ResponseInterface $response)
     {
        $userId = $request->getAttribute("id");
        $userData = $request->getParsedBody();

        // Updating user...
        
        return $response;
     }
}
```

#### Define Your Routes:

The following example applies only if you use the
[default router middleware](https://github.com/woohoolabs/harmony/blob/master/src/Middleware/FastRouteMiddleware.php)
which is based on [FastRoute](https://github.com/nikic/FastRoute), the library of Nikita Popov. We chose to use this
library because of its performance and elegance. You can read more about it
[in Nikita's blog](http://nikic.github.io/2014/02/18/Fast-request-routing-using-regular-expressions.html).

Let's add three routes to FastRoute:

```php
$router = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute("GET", "/me", function (ServerRequestInterface $request, ResponseInterface $response) {
        $response->getBody()->write("Welcome to the real world!");
        
        return $response;
    });
    
    $r->addRoute("GET", "/users", [\App\Controllers\UserController::class, "getUsers"]);
    $r->addRoute("POST", "/users/{id}", [\App\Controllers\UserController::class, "updateUser"]);
};
```

#### Finally, Launch The Framework:

```php
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\FastRouteMiddleware;
use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;
use WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;

$harmony = new Harmony(ServerRequestFactory::fromGlobals(), new Response());
$harmony
    ->addMiddleware(new FastRouteMiddleware($router))
    ->addMiddleware(new DispatcherMiddleware())
    ->addFinalMiddleware(new DiactorosResponderMiddleware(new SapiEmitter()));

$harmony();
```

You have to register all the following middleware in order for the framework to function properly:
- `FastRouteMiddleware` takes care of routing (`$router`  was configured in the previous step)
- `DispatcherMiddleware` dispatches a controller which belongs to the request's current route
- `DiactorosResponderMiddleware` sends the response to the ether via
[Zend Diactoros](https://github.com/zendframework/zend-diactoros)

Note that there is a second optional argument of `Harmony::addMiddleware()` and `Harmony::addFinalMiddleware()` with which
you can define the ID of a middleware (doing so is necessary if you want to call `Harmony::getMiddleware()` somewhere
in your code).

Furthermore, the middleware attached via `Harmony::addFinalMiddleware()` will always be executed after the normal ones,
just before the `Harmony` object gets destructed. In the following example, we want to emit the HTTP response by
`DiactorosResponderMiddleware` as the very last step.

Of course, it is completely up to you how you add additional middleware or how you replace them with your own
implementations. When you'd like to go live, just call `$harmony()`!

## Advanced Usage

#### Using invokable controllers

Most of the time, you will define your route handlers (~controller actions) as regular callables like it
was seen in the section about the default router:

```php
$r->addRoute("GET", "/users/me", [\App\Controllers\UserController::class, "getMe"]);
```

But nowadays, there is an increasing popularity of controllers containing only one action. To do so, it is a general
practice to implement the `__invoke()` magic method. In former versions of Harmony, if you wanted to apply this pattern,
you had to define the example route above the following way (at least if you used the default router and dispatcher):
  
```php
$r->addRoute("GET", "/users/me", [\App\Controllers\GetMe::class, "__invoke"]);
```

As of Harmony 2.1.0, your route definition can be simplified to:

```php
$r->addRoute("GET", "/users/me", \App\Controllers\GetMe::class);
```

Note: If you use other router or dispatcher than the default ones, please make sure whether the feature is
available for you.

If you are interested in how you could benefit from invokable controllers in the context of the
Action-Domain-Responder pattern, you can find an insightful description in
[Paul M. Jones' blog post](http://paul-m-jones.com/archives/6006).

#### Using Your Favourite DI Container with Harmony

The motivation of creating Woohoo Labs. Harmony was to become able to change every single aspect
of the framework. That's why you can use such a DI Container you want.

For this purpose, we chose
the [Container-Interop standard](https://github.com/container-interop/container-interop)
(it is PSR-11 now) to be the common interface for DI Containers in the built-in `DispatcherMiddleware`.

It's also important to know that the `DispatcherMiddleware` uses the `BasicContainer` by default. It's nothing more
than a very silly DIC which tries to create objects based on their class name (so calling 
`$basicContainer->get(Foo::class)` would create a new `Foo` instance).

But if you provide an argument to the middleware's constructor, you can use your favourite Container-Interop compliant
DIC too. Let's have a look at an example where one would like to swap `BasicContainer` with the awesome
[PHP-DI](http://php-di.org):

```php
$container = new \DI\Container();
$harmony->addMiddleware("dispatcher", new DispatcherMiddleware($container));
```

#### Creating Custom Middleware

It's not a big deal to add a new middleware to your stack. For a basic scenario, you can use anonymous functions.
Let's say you would like to log all the requests:

```php
$middleware = function(ServerRequestInterace $request, ResponseInterface $response, callable $next) {
    // Logging

    return $next();
}
```

And then you have to attach the middleware to Harmony:

```php
$harmony->addMiddleware("logging", $middleware);
```

**A middleware must return a `ResponseInterface` instance in any cases**, but the most important thing it can do is to
call `$next()` to invoke the next middleware when its function was accomplished. Failing to call this method results
in the interruption of the framework's operation (of course the final middleware will still be executed)!

But what to do if you want to pass a manipulated request or response to the next middleware? Then, you should call
`$next($request, $response)`. This way, the following middleware will receive the modified request or response.
Calling `$next(null, $response)` will pass the original request and the possibly changed response to the next
middleware!

If you need more sophistication, you can use an invokable class as a middleware too. For example let's create an
authentication middleware:

```php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class AuthenticationMiddleware
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        if ($request->getHeader("x-api-key") !== [$this->apiKey]) {
            return $response->withStatusCode(401);
        }
        
        return $next();
    }
}
```

Then 

```php
$harmony->addMiddleware("authentication", new AuthenticationMiddleware("123"));
```

As you can see, the constructor receives the API Key, while the `__invoke()` method is responsible for performing the
authentication.

Instead of `callable`, you can also typehint the `$next` argument against `Harmony` according to the
[`MiddlewareInterface`](https://github.com/woohoolabs/harmony/blob/master/src/Middleware/MiddlewareInterface.php).
By implementing this interface, you can use some specific features of Harmony (like `Harmony::getMiddleware()`) but lose
the ability to reuse your middleware in other frameworks.

Again: **a middleware must return a `ResponseInterface` instance in any cases**, but the most important thing it can do
is to call `$next()` to invoke the next middleware when its function was accomplished. Failing to call this method
results in the interruption of the framework's operation (of course the final middleware will still be executed)!
That's why we only invoke `$next()` in this example when the authentication was successful.

Very important to notice that when authentication is unsuccessful, no other middleware will be executed (as `$next()`
is not called), so possibly only the final middleware will be invoked afterwards. As you want to pass a modified
response with status code 412 to the final middleware, you must return the response (as seen in the prior example)
in order to inform the framework from the changed response.

### Defining Conditions

Non-trivial applications often need some kind of branching during the execution of their middleware pipeline. A possible
use-case is when they want to perform authentication only for some of their endpoints or when they want to check for a
CSRF token if the request method is `POST`.

With Harmony v2 too, these conditions were easy to handle:

```php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class CsrfMiddleware
{
    /**
     * @var MyFavoriteCsrfValidatorLibrary
     */
    protected $csrfValidator;
    
    public function __construct(MyFavoriteCsrfValidatorLibrary $csrfValidator)
    {
        $this->csrfValidator = $csrfValidator;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        if ($request->getMethod() === "POST" && $this->csrfValidator->validate($request) === false) {
            return $response->withStatusCode(400);
        }
        
        return $next();
    }
}
```

And attach it to Harmony:

```php
$harmony->addMiddleware(new CsrfMiddleware(new MyFavoriteCsrfValidatorLibrary()));
```

You only had to check the request method inside the middleware and the problem was solved. The downside of doing this is
that `CsrfMiddleware` and all its dependencies are instantiated for each request although the validation itself is not
necessary at all (e.g. for `GET` requests)!

In Harmony v3, you are able to use conditions in order to optimize the number of objects created. In this case you can
utilize the built-in `HttpMethodCondition` which looks like the following:

```php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HttpMethodCondition implements ConditionInterface
{
    protected $methods = [];

    /**
     * @param array $methods
     */
    public function __construct(array $methods)
    {
        $this->methods = $methods;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return bool
     */
    public function evaluate(ServerRequestInterface $request, ResponseInterface $response)
    {
        return in_array($request->getMethod(), $this->methods) === true;
    }
}
```

And add it to Harmony:

```php
$harmony->addCondition(
    new HttpMethodCondition(["POST"]),
    function (Harmony $harmony) {
        $harmony->addMiddleware(new CsrfMiddleware(new MyFavoriteCsrfValidatorLibrary()));
    }
);
```

This way, `CsrfMiddleware` will only be instantiated when `HttpMethodCondition` evaluates to `true`. Furthermore,
you are able to attach more middleware to Harmony (even final middleware) in the anonymous function. These
middleware will be executed together, as if they were part of a containing middleware.

## Examples

Have a look at the [examples directory](https://github.com/woohoolabs/harmony/blob/master/examples/) for a really basic
application structure. Don't forget to run `composer install` first in Harmony's root directory if you want to try it out!

## Versioning

This library follows [SemVer v2.0.0](http://semver.org/).

## Change Log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Máté Kocsis][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/woohoolabs/harmony.svg
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg
[ico-travis]: https://img.shields.io/travis/woohoolabs/harmony/master.svg
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/woohoolabs/harmony.svg
[ico-code-quality]: https://img.shields.io/scrutinizer/g/woohoolabs/harmony.svg
[ico-downloads]: https://img.shields.io/packagist/dt/woohoolabs/harmony.svg
[ico-gitter]: https://badges.gitter.im/woohoolabs/harmony.svg

[link-packagist]: https://packagist.org/packages/woohoolabs/harmony
[link-travis]: https://travis-ci.org/woohoolabs/harmony
[link-scrutinizer]: https://scrutinizer-ci.com/g/woohoolabs/harmony/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/woohoolabs/harmony
[link-downloads]: https://packagist.org/packages/woohoolabs/harmony
[link-gitter]: https://gitter.im/woohoolabs/harmony?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge
[link-author]: https://github.com/kocsismate
[link-contributors]: ../../contributors
