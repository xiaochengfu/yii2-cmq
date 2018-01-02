<?php

namespace xiaochengfu\cmq\controllers;

use xiaochengfu\cmq\component\QueueBase;
use xiaochengfu\kafka\component\Producer;
use yii\console\Controller;

/**
 * RabbitMQ producer functionality
 * @package mikemadisonweb\rabbitmq\controllers
 */
class ProducerController extends Controller
{

    /**
     * Description:  命令行投递消息
     * Author: hp <xcf-hp@foxmail.com>
     * Updater:
     * @param array $msg
     */
    public function actionPublish($msg){
        $producer = new QueueBase();
        $result = $producer->send($msg);
        if($result['status']){
            echo date('Y-m-d H:i:s')." send success," . PHP_EOL;
        }else{
            echo date('Y-m-d H:i:s')." send fail" . PHP_EOL;
        };
     }
}