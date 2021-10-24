<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2021/10/23 22:22
 */

namespace Poa\Http\Server;

use Poa\Middleware\MiddlewareSystemInterface;
use Poa\Middleware\MiddlewareSystemTrait;

/**
 * HTTP 应用程序，中间件使用的上下文对象必须是 HttpContext 类的实例
 */
class HttpApplication implements MiddlewareSystemInterface
{
    const KEY_HANDLER = '@http_server_handler';

    use MiddlewareSystemTrait;

    /**
     * HTTP 应用服务器执行的具体业务逻辑，挂载在 Application 上的中间件（路由器等）需要把最后的可执行结果
     * 存放到对应的上下文变量名 HttpApplication::KEY_HANDLER 上，必须是一个可执行对象，或者为 null 表示不需要执行
     *
     * @param HttpContext $context HTTP 应用程序的上下文对象
     * @inheritDoc
     */
    public function handle(HttpContext $context)
    {
        $hander = $context[self::KEY_HANDLER] ?? null;
        if (is_callable($hander)) $hander($context);
    }
}
