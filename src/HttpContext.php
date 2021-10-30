<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2021/10/23 17:19
 * @noinspection PhpUnused
 */

namespace Poa\Http\Server;

use Poa\Middleware\ContextInterface;
use Poa\Middleware\ContextTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * 基于中间件上下文接口实现的 HTTP 服务器的 请求处理上下文
 */
class HttpContext implements ContextInterface, LoggerAwareInterface
{
    use ContextTrait;

    /** @var ServerRequestInterface */
    public ServerRequestInterface $request;
    /** @var ResponseInterface */
    public ResponseInterface $response;

    /** @var LoggerInterface 日志处理对象 */
    public LoggerInterface $logger;

    public function __construct(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->logger = new NullLogger();
    }

    /**
     * 获取请求的 主机
     * @return string
     */
    public function getHost(): string
    {
        return $this->request->getUri()->getHost();
    }

    /**
     * 获取请求的 端口
     * @return int|null
     */
    public function getPort(): ?int
    {
        return $this->request->getUri()->getPort();
    }

    /**
     * 获取请求的 HTTP 方法
     * @return string
     */
    public function getMethod(): string
    {
        return $this->request->getMethod();
    }

    /**
     * 获取请求的 路径
     * @return string
     */
    public function getPath(): string
    {
        return $this->request->getUri()->getPath();
    }

    /**
     * 获取请求头
     * @param string $name
     * @return string|string[]|null
     */
    public function getHeader(string $name)
    {
        $values = $this->request->getHeader($name);
        if (empty($values)) return null;
        else if (count($values) < 2) return $values[0];
        else return $values;
    }

    /**
     * 设置响应头
     * @param string $name 响应头名称
     * @param string|string[]|null $value 响应头对应的值，可以是字符串或字符串数组，null 表示删除对应的响应头
     * @return self
     */
    public function setHeader(string $name, $value): self
    {
        if (is_null($value)) $this->response = $this->response->withoutHeader($name);
        else $this->response = $this->response->withHeader($name, $value);
        return $this;
    }

    /**
     * 批量设置响应头内容
     * @param string[]|string[][] $headers
     * @return self
     */
    public function setHeaders(array $headers): self
    {
        foreach ($headers as $name => $value) {
            $this->setHeader($name, $value);
        }
        return $this;
    }

    /**
     * 获取请求中的所有查询参数
     * @return array
     */
    public function getQueryParams(): array
    {
        return $this->request->getQueryParams();
    }

    /**
     * 获取指定请求的查询参数
     * @param $name
     * @return mixed
     */
    public function getQueryParam($name)
    {
        return $this->getQueryParams()[$name] ?? null;
    }

    /**
     * 获取解析后的请求体内容
     * @return array|object|null
     */
    public function getParsedBody()
    {
        return $this->request->getParsedBody();
    }

    /**
     * 获取或设置响应码，不传递任何参数表示获取当前的响应码
     * @param ?int $code 待设置的响应码
     * @param ?string $reasonPhrase 响应码说明
     * @return self|int
     */
    public function statusCode(int $code = null, string $reasonPhrase = null)
    {
        if (func_num_args() < 1) {
            return $this->response->getStatusCode();
        }
        $this->response = $this->response->withStatus($code, $reasonPhrase);
        return $this;
    }

    /**
     * 清空响应输出流
     * @return self
     */
    public function clear(): self
    {
        $rsp = $this->response;
        $body = $rsp->getBody();
        if ($body->isSeekable()) {
            $body->rewind();
            $body->write("\0\0");
            $body->rewind();
        }
        return $this;
    }

    /**
     * 发送响应内容
     * @param string|StreamInterface $content 内容
     * @return self
     */
    public function send($content): self
    {
        if (is_string($content)) {
            $this->clear();
            $this->response->getBody()->write($content);
        } else if ($content instanceof StreamInterface) {
            $this->response = $this->response->withBody($content);
        }
        return $this;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
