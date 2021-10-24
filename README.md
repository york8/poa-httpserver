# poa-middleware

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Total Downloads][ico-downloads]][link-downloads]

POA框架的中间件组件，使用PHP的Generator实现洋葱圈模型

## 作者

- [York](https://github.com/york8)

## 安装

```json
{
  "require": {
    "poa/httpserver": "~0.1"
  }
}
```

```bash
composer install
```

## 示例

```php
use Poa\Http\Router\Router;
use Poa\Http\Server\HandleExceptionMiddleware;
use Poa\Http\Server\HttpApplication;
use Poa\Http\Server\HttpContext;
use Poa\Http\Server\HttpServerInterface;
use Poa\Middleware\ElapsedTimeMiddleware;
use Poa\Middleware\MiddlewareSystemInterface;

class FpmHttpServer implements HttpServerInterface
{
    protected MiddlewareSystemInterface $application;

    public function start()
    {
        $context = new HttpContext(null, null);
        $app = $this->application;
        $app($context);
    }

    public function stop()
    {
        // do nothing
    }

    public function delegateRequest(MiddlewareSystemInterface $application)
    {
        $this->application = $application;
    }
}

$app = new HttpApplication();
$app->use(new HandleExceptionMiddleware())
    ->use(new ElapsedTimeMiddleware())
    ->use(new Router());

$server = new FpmHttpServer();
$server->delegateRequest($app);
$server->start();

```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/poa/httpserver.svg?style=flat-square

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square

[ico-downloads]: https://img.shields.io/packagist/dt/poa/httpserver.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/poa/httpserver

[link-downloads]: https://packagist.org/packages/poa/httpserver
