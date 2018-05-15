<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/26
 * Time: 20:07
 */

namespace app\lib\exception;


use Exception;
use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{
    // http状态码
    private $code;
    // 错误码描述
    private $msg;
    // 错误码
    private $errorCode;

    // 重写render()方法
    public function render(Exception $e)
    {
        // 判断异常类型（客户端引起的异常，还是服务器引起的异常）
        if($e instanceof BaseException){
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        } else {
            // 调试模式是否开启（在开发时，开启，客户端将显示tp自带的调试页面）
            if(config("app_debug")){
                return parent::render($e);
            } else {
                $this->code = 500;
                $this->msg = "服务器内部错误";
                $this->errorCode = 999;
                $this->recordErrorLog($e);
            }
        }

        $request = Request::instance();
        $result = [
            "msg" => $this->msg,
            "error_code" => $this->errorCode,
            "request_url" => $request->url()
        ];
        return json($result, $this->code);
    }

    /**
     * 调用日志类对异常进行记录
     * @param Exception $e 异常对象
     */
    private function recordErrorLog(Exception $e)
    {
        // 初始化日志类
        Log::init([
           "type" => "File",
           "path" => LOG_PATH,
           "level" => ['error']
        ]);
        // 进行日志记录
        Log::record($e->getMessage(), 'error');
    }
}