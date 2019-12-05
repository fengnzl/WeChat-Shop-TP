<?php
/**
 * Created by PhpStorm.
 * User: lullabies
 * Date: 2019/11/30
 * Time: 11:27
 */

namespace app\lib\exception;


use Exception;
use think\exception\Handle;
use think\Log;


class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $errorCode;
    public function render(Exception $e)
    {
        if($e instanceof BaseException){// 如果是自定义抛出错误
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        }else{// 系统或代码错误
            if(config('app_debug')){// 是否开启调试模式
                return parent::render($e);
            }else{
                $this->code = 500;
                $this->msg ='服务器发生错误，就不告诉你！';
                $this->errorCode = 999;
               // 将服务器产生的错误保存到日志中
                $this->recordLog($e);
            }
        }
        // 获取当前请求的url
        $url = request()->url();
        $result =[
            'msg'=>$this->msg,
            'error_code'=>$this->errorCode,
            'request_url'=>$url
        ];
        return json($result, $this->code);
    }

    public function recordLog(Exception $e){
        Log::init([
            // 日志记录方式，内置 file socket 支持扩展
            'type'  => 'file',
            // 日志保存目录
            'path'  => LOG_PATH,
            // 日志记录级别
            'level' => ['error'],
            // 开启日志写如功能
        ]);
        $data = [
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'message' => $this->getMessage($e),
            'code'    => $this->getCode($e),
        ];
        $log = "[{$data['code']}]{$data['message']}[{$data['file']}:{$data['line']}]";
        Log::record($log,'error');
    }
}