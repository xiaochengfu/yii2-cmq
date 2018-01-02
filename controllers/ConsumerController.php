<?php

namespace xiaochengfu\cmq\controllers;


use xiaochengfu\cmq\component\QueueBase;
use yii\console\Controller;

/**
 * RabbitMQ consumer functionality
 * @package mikemadisonweb\rabbitmq\controllers
 */
class ConsumerController extends Controller
{
    /**
     * Description:  启动消费进程
     * Author: hp <xcf-hp@foxmail.com>
     * Updater:
     */
    public function actionStart(){
        $setting = \Yii::$app->params['cmq'];
        $base = new QueueBase();
        //设置进程名称
        $base->setProcessName(isset($setting['processName'])?$setting['processName']:'cmq-master');
        echo "php-kafka start success process name ".$setting['processName']."\n";
        echo "Waiting for partition assignment... (make take some time when\n";
        echo "quickly re-joining the group after leaving it.)\n";
        echo "---------------------------cmq等待消费...-----------------------------------\n";
        $base->receive();
    }
}