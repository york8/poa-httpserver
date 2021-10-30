<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2021/10/23 22:23
 */

namespace Poa\Http\Server;

use Poa\Middleware\MiddlewareSystemInterface;

/**
 * Http 服务器接口定义
 */
interface HttpServerInterface
{
    /**
     * 启动运行 Http 服务器
     */
    public function start();

    /**
     * 停止运行 Http 服务器
     */
    public function stop();

    /**
     * 将 HTTP 请求委托给中间件系统 $application 进行处理
     * @param MiddlewareSystemInterface $application HTTP 服务器接收完整的数据后将请求委托给中间件系统 $application 进行处理
     */
    public function delegateRequest(MiddlewareSystemInterface $application);
}
