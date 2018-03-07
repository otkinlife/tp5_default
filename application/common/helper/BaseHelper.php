<?php
/**
 * @desc 工具类
 * @author kcjia
 * @time 2018/1/25
 */
namespace app\common\helper;

class BaseHelper
{

    /**
     * @desc CURL请求方法
     * @param string $method (POST/GET/PUT/DELETE/PATCH/JSON)
     * @param string $url 请求地址
     * @param array $params 请求参数
     * @param array $conditions 设置条件
     * @return array|mixed|string
     * @author kcjia
     * @time 2018/1/25
     */
    static public function httpRequest($method, $url, $params = array(), $conditions = array())
    {
        $conditions['userAgent'] = isset($conditions['userAgent']) ? $conditions['userAgent'] : '';
        $conditions['connectTimeout'] = isset($conditions['connectTimeout']) ? $conditions['connectTimeout'] : 10;
        $conditions['timeout'] = isset($conditions['timeout']) ? $conditions['timeout'] : 10;

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, $conditions['userAgent']);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $conditions['connectTimeout']);
        curl_setopt($curl, CURLOPT_TIMEOUT, $conditions['timeout']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        } elseif ($method == 'PUT') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        } elseif ($method == 'DELETE') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        } elseif ($method == 'PATCH') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        } else if ($method == 'JSON') {
            $json = json_encode($params);
            $conditions['headers'][] = 'Content-Type: application/json; charset=utf-8';
            $conditions['headers'][] = 'Content-Length:' . strlen($json);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        } else {
            if (!empty($params)) {
                $url = $url . (strpos($url, '?') ? '&' : '?') . http_build_query($params);
            }
        }
        if (!empty($conditions['port'])) {
            curl_setopt($curl, CURLOPT_PORT, $conditions['port']);
        }
        if (!empty($conditions['headers'])) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $conditions['headers']);
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);

        $response = curl_exec($curl);
        $curlinfo = curl_getinfo($curl);
        $header = substr($response, 0, $curlinfo['header_size']);
        $body = substr($response, $curlinfo['header_size']);
        curl_close($curl);

        if (empty($curlinfo['namelookup_time'])) {
            return array();
        }

        if (isset($conditions['contentType']) && $conditions['contentType'] == 'plain') {
            return $body;
        }

        $body = json_decode($body, true);

        return $body;
    }

    /**
     * @desc 获取所有控制器名称
     */
    static public function getController($module)
    {
        if (empty($module)) {
            return null;
        }
        $module_path = APP_PATH . '/' . $module . '/controller/';  //控制器路径
        if (!is_dir($module_path)) {
            return null;
        }
        $files = [];
        $module_path .= '/*.php';
        $ary_files = glob($module_path);
        foreach ($ary_files as $file) {
            if (is_dir($file)) {
                continue;
            } else {
                $file = basename($file, ".php");
                $files[] = "app\\{$module}\\controller\\{$file}";
            }
        }
        return $files;
    }

    /**
     * @desc 加密解密方法
     * @param mixed $string 需要加密/解密的数据
     * @param string $operation 加密(E)/解密(D) 操作类型
     * @param string $key 加密/解密key
     * @return mixed|string
     */
    static public function encrypt($string, $operation, $key = 'kls8in1e')
    {
        if (is_array($string)) {
            $string = serialize($string);
        }
        $key = md5($key);
        $keyLength = strlen($key);
        if ($operation == 'D') {
            $string = base64_decode($string);
        } else {
            $string = substr(md5($string . $key), 0, 8) . $string;
        }
        $stringLength = strlen($string);
        $rndKey = $box = array();
        $result = '';
        for ($i = 0; $i <= 255; $i++) {
            $rndKey[$i] = ord($key[$i % $keyLength]);
            $box[$i] = $i;
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndKey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $stringLength; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'D') {
            if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
                return unserialize(substr($result, 8));
            } else {
                return '';
            }
        } else {
            return str_replace('=', '', base64_encode($result));
        }
    }
}