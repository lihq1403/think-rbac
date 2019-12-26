<?php
declare (strict_types = 1);

namespace Lihq1403\ThinkRbac\protected_traits;

/**
 * API 响应配置
 * Trait ApiResponseTrait
 * @package app\common\traits
 */
trait ApiResponseTrait
{
    /**
     * 返回提示语
     * @var string
     */
    protected $message = 'system error';

    /**
     * 状态码
     * 200 成功
     * 400 请求异常
     * 401 用户未登录
     * 403 无权请求
     * 404 不存在的资源
     * 405 请求方式不允许
     * 422 表单校验错误
     * 429 请求频率过高
     * 500 系统错误
     * @var string
     */
    protected $code = 500;

    /**
     * 数据内容
     * @var array
     */
    protected $data = [];

    /**
     * 返回数据类型
     * json | jsonp | exit
     * @var string
     */
    protected $method = 'json';

    /**
     * 200 成功操作
     * @param string $message
     * @param array $data
     * @return \think\response\Json|\think\response\Jsonp
     */
    public function successResponse(string $message = 'success', $data = [])
    {
        return $this->setCode(200)->setMessage($message)->setData($data)->response();
    }

    /**
     * 400 请求异常通用错误
     * @param string $message
     * @param array $data
     * @return \think\response\Json|\think\response\Jsonp
     */
    public function errorResponse(string $message = 'error', $data = [])
    {
        return $this->setCode(400)->setMessage($message)->setData($data)->response();
    }

    /**
     * 404 查询为空
     * @param string $message
     * @param array $data
     * @return \think\response\Json|\think\response\Jsonp
     */
    public function emptyResponse(string $message = 'error', $data = [])
    {
        return $this->setCode(404)->setMessage($message)->setData($data)->response();
    }

    /**
     * 422 表单校验错误
     * @param string $message
     * @param array $data
     * @return \think\response\Json|\think\response\Jsonp
     */
    public function errorParamResponse(string $message = 'error', $data = [])
    {
        return $this->setCode(422)->setMessage($message)->setData($data)->response();
    }

    /**
     * 自定义code返回
     * @param int $code
     * @param string $message
     * @param array $data
     * @return \think\response\Json|\think\response\Jsonp
     */
    public function customCodeResponse(int $code, string $message = 'error', $data = [])
    {
        return $this->setCode($code)->setMessage($message)->setData($data)->response();
    }

    /**
     * exit方式返回数据
     * @param int $code
     * @param string $message
     * @param array $data
     * @return \think\response\Json|\think\response\Jsonp
     */
    public function exitResponse(int $code = 500, $message = 'error', $data = [])
    {
        return $this->setMethod('exit')->setCode($code)->setMessage($message)->setData($data)->response();
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message)
    {
        $this->message = lang($message);
        return $this;
    }

    /**
     * @param int $code
     * @return $this
     */
    public function setCode(int $code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param string $method
     * @return $this
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * 响应
     * @return \think\response\Json|\think\response\Jsonp
     */
    public function response()
    {
        $response = [
            'message' => $this->message,
            'code' => $this->code,
            'data' => $this->data
        ];

        switch ($this->method) {
            case 'jsonp':
                return jsonp($response);
            case 'exit':
                exit(json_encode($response, JSON_UNESCAPED_UNICODE));
            default:
                return json($response);
        }
    }
}