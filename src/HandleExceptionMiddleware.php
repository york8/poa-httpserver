<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2021/10/24 16:21
 */

namespace Poa\Http\Server;

use Poa\Middleware\ContextInterface;
use Poa\Middleware\MiddlewareInterface;

/**
 * 放在围洋葱圈模型外围用来进行异常捕获和处理的中间件
 */
class HandleExceptionMiddleware implements MiddlewareInterface
{
    /**
     *
     * @param HttpContext $context
     * @inheritDoc
     */
    public function __invoke(ContextInterface $context)
    {
        try {
            yield;
        } catch (\Throwable $e) {
            $context->logger->error($e->getMessage(), ['exception' => $e]);
            $context->statusCode(500)
                    ->send($e->getMessage());
        }
    }
}
