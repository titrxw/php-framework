<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2017/8/27
 * Time: 20:58
 */
namespace blog\controller;
use blog\lib\Web;
use Elasticsearch\ClientBuilder;

class Es extends Web
{
    private $client;

    protected function afterInit () 
    {
      echo 1;
      $this->client = ClientBuilder::create()->build();
    }

    public function addApi()
    {
        $params = [
          'index' => 'blog',
          'type' => 'user',
          'id' => '1',
          'body' => [ 
            'name' => '希拉里和韩国',
            'id' => 12
          ]
      ];
      
      $response = $this->client->index($params);
      var_dump( $response);
    }

    public function add1Api()
    {
        $params = [
          'index' => 'blog',
          'type' => 'user',
          'id' => '2',
          'body' => [ 
            'name' => '闺蜜＂崔顺实被韩检方传唤 韩总统府促彻查真相',
            'id' => 122
          ]
      ];
      
      $response = $this->client->index($params);
      var_dump( $response);
    }

    public function add2Api()
    {
        $params = [
          'index' => 'blog',
          'type' => 'user',
          'id' => '3',
          'body' => [ 
            'name' => '韩举行＂护国训练＂ 青瓦台:决不许国家安全出问题',
            'id' => 122
          ]
      ];
      
      $response = $this->client->index($params);
      var_dump( $response);
    }

    public function add3Api()
    {
        $params = [
          'index' => 'blog',
          'type' => 'user',
          'id' => '4',
          'body' => [ 
            'name' => '媒体称FBI已经取得搜查令 检视希拉里电邮',
            'id' => 122
          ]
      ];
      
      $response = $this->client->index($params);
      var_dump( $response);
    }

    public function add4Api()
    {
        $params = [
          'index' => 'blog',
          'type' => 'user',
          'id' => '5',
          'body' => [ 
            'name' => '村上春树获安徒生奖 演讲中谈及欧洲排外问题',
            'id' => 122
          ]
      ];
      
      $response = $this->client->index($params);
      var_dump( $response);
    }

    public function add5Api()
    {
        $params = [
          'index' => 'blog',
          'type' => 'user',
          'id' => '6',
          'body' => [ 
            'name' => '希拉里团队炮轰FBI 参院民主党领袖批其“违法',
            'id' => 122
          ]
      ];
      
      $response = $this->client->index($params);
      var_dump( $response);
    }

    public function getApi()
    {
      $params = [
        'index' => 'blog',
          'type' => 'user',
          'id' => '1'
      ];
      
      $response = $this->client->get($params);
      print_r($response);
    }

    public function searchApi()
    {
      $params = [
          'index' => 'blog',
          'type' => 'user',
          'body' => [
              'query' => [
                  'match' => [
                      'name' => '希拉里和韩国'
                  ]
              ]
          ]
      ];
      $response = $this->client->search($params);
      print_r($response);
    }
}