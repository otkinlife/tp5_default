<?php
/**
 * @desc 搜索服务.
 * @author: kcjia
 * @time 2018/1/30
 */

namespace app\common\extend;


use Elasticsearch\ClientBuilder;
use think\Config;

class ElasticSearch extends Common
{
    protected $client;

    public function __construct()
    {
        //初始化客户端
        $config = Config::get('elastic_search');
        $this->client = ClientBuilder::create()
            ->setRetries(3)
            ->setSSLVerification(false)
            ->setHosts($config['host'])
            ->build();
    }

    /**
     * @desc 创建索引数据
     * @note 如果没有该索引则创建
     * @param string $index 索引名
     * @param string $type 类型名
     * @param string $id 文档id
     * @param array $body 实体数据
     * @return array|bool
     * @author kcjia
     * @time 2018/1/30
     */
    public function index($index, $type, $id, $body)
    {
        if (!is_array($body)) {
            $this->setRMessage('body必须为数组');
            return false;
        }
        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id,
            'body' => $body
        ];
        $result = $this->client->index($params);
        return $result;
    }

    /**
     * @desc 仅仅创建索引
     * @param $index string 索引名
     * @param $shards int 分片数
     * @param $replicas int 备份数
     * @return array
     * @author kcjia
     * @time 2018/1/31
     */
    public function createIndex($index, $shards = 5, $replicas = 1)
    {
        $params = [
            'index' => $index,
            'body' => [
                'settings' => [
                    'number_of_shards' => $shards,
                    'number_of_replicas' => $replicas
                ]
            ]
        ];
        $result = $this->client->indices()->create($params);
        return $result;
    }

    /**
     * @desc 获取记录
     * @note 可以单独获取索引/文档
     * @note 不能获取类型($type和$id要么都有值,要么都为空)
     * @param string $index 索引名
     * @param string $type 类型名
     * @param string $id 文档id
     * @return array
     * @author kcjia
     * @time 2018/1/31
     */
    public function get($index, $type = '', $id = '')
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id
        ];
        $params = array_filter($params);
        if (empty($type) && empty($id)) {
            $result = $this->client->indices()->get($params);
        } else if (!empty($type) && !empty($id)) {
            $result = $this->client->get($params);
        } else {
            $this->setRMessage('type和id必须都有值或者都为空');
            $result = false;
        }
        return $result;
    }

    /**
     * @desc 更新文档信息
     * @param string $index
     * @param string $type
     * @param string $id
     * @param array $body
     * @return array|bool
     * @author kcjia
     * @time 2018/2/2
     */
    public function update($index, $type, $id, $body)
    {
        if (!is_array($body)) {
            $this->setRMessage('body必须为数组');
            return false;
        }
        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id,
            'body' => $body
        ];
        $result = $this->client->update($params);
        return $result;
    }

    /**
     * @desc 删除索引/文档
     * @note 可以单独删除索引/文档
     * @note 不能直接删除类型($type和$id要么都有值,要么都为空)
     * @param string $index 索引名
     * @param string $type 类型名
     * @param string $id 文档id
     * @return array
     * @author kcjia
     * @time 2018/2/2
     */
    public function delete($index, $type = '', $id = '')
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id
        ];
        $params = array_filter($params);
        if (empty($type) || empty($id)) {
            $result = $this->client->indices()->delete($params);
        } else if (!empty($type) && !empty($id)) {
            $result = $this->client->delete($params);
        } else {
            $this->setRMessage('type和id必须都有值或者都为空');
            $result = false;
        }
        return $result;
    }

    /**
     * @desc
     * @param string $keywords 搜索关键词数组
     * @param string $index 索引名 ''或者 '_all' 代表所有
     * @param string $type 类型名
     * @param int $from offset
     * @param int $size size
     * @param array $sort 排序数组
     * @return array
     * @author kcjia
     * @time 2018/2/2
     */
    public function search($keywords = [], $index = '', $type = '', $from = 0, $size = 10, $sort = [])
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'from' => $from,
            'size' => $size,
            'sort' => $sort,
        ];
        foreach ($keywords as $key => $value) {
            $params['body']['query']['match'][key($keywords)] = $keywords[key($keywords)];
        }
        $result = $this->client->search($params);
        return $result;
    }
}