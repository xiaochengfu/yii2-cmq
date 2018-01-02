<?php
namespace xiaochengfu\cmq\component;
require_once '../cmq/cmq_api.php';
require_once CMQAPI_ROOT_PATH . '/account.php';
require_once CMQAPI_ROOT_PATH . '/queue.php';
require_once CMQAPI_ROOT_PATH . '/cmq_exception.php';

/*
 * CMQ_V1.0.2 PHP Demo
 * 
 *  1 Account类对象不是线程安全的，如果多线程使用，需要每个线程单独初始化Account类对象
 *  2 Topic与Queue使用不同的endpoint, 因此需要需要分别初始化Account
 *  3 创建订阅的时候，需要设置订阅的属性，订阅属性参见SubscriptionMeta的定义
 */


class QueueBase
{
    private $secretId;
    private $secretKey;
    private $endpoint;
    private $my_queue;
    private $queue_name;

    public function __construct()
    {
        $config = \Yii::$app->params['cmq'];
        $this->secretId = $config['secretId'];
        $this->secretKey = $config['secretKey'];
        $this->endpoint = $config['endpoint'];
        $this->queue_name = $config['queue_name'];
        $my_account = new \Account($this->endpoint, $this->secretId, $this->secretKey);

        $this->my_queue = $my_account->get_queue($this->queue_name);
    }

    public function create(){
        $queue_meta = new \QueueMeta();
        $queue_meta->queueName = $this->queue_name;
        $queue_meta->pollingWaitSeconds = 10;
        $queue_meta->visibilityTimeout = 10;
        $queue_meta->maxMsgSize = 1024;
        $queue_meta->msgRetentionSeconds = 3600;
        $this->my_queue->create($queue_meta);
    }

    public function send($msg_body)
    {
        try {
            $msg_body = (array)$msg_body;
            $msg = new \Message(serialize($msg_body));
            $re_msg = $this->my_queue->send_message($msg);
            return [
                'status'=>true,
                'data'=>[
                    'message_id'=>$re_msg->msgId,
                    'message_body'=>$msg_body
                ]
            ];
        } catch (\CMQExceptionBase $e) {
            return [
                'status'=>false,
                'message'=>$e,
            ];
        }
    }

    public function receive(){
        $this->setProcessName($this->queue_name);
        while (true) {
            try{
                $recv_msg = $this->my_queue->receive_message(3);
                $array_msg = json_decode($recv_msg,true);
                if(!isset($array_msg['code'])){
                    $array_msg['code'] = 0;
                    echo "Receive Message Succeed! " . $recv_msg . "\n";
                    //接收成功后，删除消息
                    $this->my_queue->delete_message($array_msg['receiptHandle']);
                }else{
                    echo "No Message Waitting ..." . "\n";
                }
            }catch (\Exception $e) {
                if($e->getCode() == 700){
                    echo "No Message Waitting ..." . "\n";
                    break;
                }
            }
        }
    }

    public function delete($receiptHandle){
        $this->my_queue->delete_message($receiptHandle);
    }

    public function setProcessName($name){
        if (function_exists('cli_set_process_title')) {
            cli_set_process_title($name);
        } else {
            trigger_error(__METHOD__. " failed.require cli_set_process_title.");
        }
    }
}
