<?php
namespace xiaochengfu\cmq;

use xiaochengfu\cmq\component\QueueBase;
use yii\base\Component;

class Module extends Component
{
    /**
     * Description:  投递消息
     * Author: hp <xcf-hp@foxmail.com>
     * Updater:
     * @param array $msg
     * @return bool
     */
    public function poll($msg = []){
        $producer = new QueueBase();
        return $producer->send($msg);
    }

    /**
     * Description:  删除消息
     * Author: hp <xcf-hp@foxmail.com>
     * Updater:
     * @param $receiptHandle
     */
    public function delete($receiptHandle){
        $producer = new QueueBase();
        $producer->delete($receiptHandle);
    }
}
