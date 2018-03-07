<?php
namespace app\common\behavior;

use app\common\auth\BaseAuth;
use think\Log;
use think\Request;
use think\Response;
use think\response\Redirect;

class ApiEndBehavior
{

    public function run(Response $response)
    {
        $request = Request::instance();
        $requestUrl = strtolower(sprintf('/%s/%s/%s',$request->module(),$request->controller(),$request->action()));
        if ($response instanceof Redirect) {
            return $response;
        }

        $content = $response->getData();
        if (is_string($content)) return false;              //模板页面未加日志
        $output = isset($content['output']) ? $content['output'] : 'json';
        $content['code'] = isset($content['code']) ? $content['code'] : E_OK;
        $code = $content['code'];
        $apiCode = config('api_code');
        if (isset($content['code']))  unset($content['code']) ;
        $data = array(
            'code' => $code,
            'success' => $code === E_OK ? true : false,
            'message' => isset($content['message']) ? $content['message'] : $apiCode[$code],
            'data' => isset($content['data']) ? $content['data'] : '',
        );
        $this->logEvent($requestUrl,$data);
        if ($output == 'arr') {
            //return $data;
        } else {
            $response->data($data);
        }
    }

    public function logEvent($requestUrl,$data)
    {
        $user = BaseAuth::getCurrentUser();
        $userStr = '游客';
        if ($user) $userStr = sprintf('%s(ID:%s)',$user['nickname'],$user['id']);

        $apiEventName = config('apiEventName');
        $eventName = isset($apiEventName[$requestUrl]) ? $apiEventName[$requestUrl] : $requestUrl;
        if (isset($_GET['_logTitle'])) {
            $eventName .= $_GET['_logTitle'];
        }
        $logData = sprintf('[ INFO ][user:%s][event:%s][code:%s][message:%s]',
            $userStr,$eventName,$data['code'],$data['message'],$data['success']);
        if ($data['code'] != E_OK) {
            $requesData = array(
                'GET' => $_GET,
                'POST' => $_POST,
                'FILE' => $_FILES,
                'COOKIE' => $_COOKIE
            );
            $logData = str_replace('[ INFO ]','[ ERROR ]',$logData);
            $logData .= sprintf('[error:%s]',json_encode($requesData));
        }
        Log::info($logData);
        return true;
    }
}