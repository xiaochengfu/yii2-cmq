# yii2-cmq
对接腾讯cmq的消息队列

### 配置
在common/config/params.php 添加如下参数：
```
<?php
return [
    'cmq'=>[
       'secretId'=>'您的腾讯id',
       'secretKey'=>'您的key',
       'endpoint'=>'http://cmq-queue-bj.api.qcloud.com',//地区域名
       'queueName'=>'queue-v1',//队列名称
       'processName'=>'cmq-master',//进程名称
       'callback'=>'common\\components\\cmq\\Consumer'//消费业务回调处理名字空间
    ]
];
```
在console/config/ain.php中添加如下参数：
```
  'controllerMap' => [
        'cmq-consumer'=>\xiaochengfu\cmq\controllers\ConsumerController::class,
        'cmq-producer'=>\xiaochengfu\cmq\controllers\ProducerController::class,
    ],
```


### 启动消费进程

```
./yii cmq-consumer/start
```

### 发布消息

```
Yii::$app->mq->poll(['1'=>'我收到了','2'=>'我成功了'])
```
### 删除消息

```
Yii::$app->mq->delete($receiptHandle)
```

### 回调处理参考配置

目录结构：`common/components/cmq/Consumer.php`

#### 注意：execute方法不可变更

Consumer.php内容如下：
```
<?php
/**
 * Name: cmq回调处理类
 * Author: hp <xcf-hp@foxmail.com>
 * Date: 2017-11-22 18:17
 * Description: adf.php.
 */
namespace common\components\cmq;

use yii\base\Exception;

class Consumer
{

    /**
     * Description:  异步回调执行体
     * Author: hp <xcf-hp@foxmail.com>
     * Updater:
     * @return int
     */
    public function execute($msg)
    {
        try{
              $data = unserialize($msg['msgBody']);
              var_dump($msg);
        }catch (Exception $e){
            var_dump($e->getMessage());
        }

    }
}
```