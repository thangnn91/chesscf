<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MQHelper
 *
 * @author ONECONDUCK
 */

namespace App\Services;

use Stomp\Stomp;
use Illuminate\Support\Facades\Log;

class MQHelper {

    //put your code here
    private $Stomp;
    private $broker = 'tcp://192.168.1.12:61613';
    private $queue = '/topic/sms';

    public function __construct() {
         $this->Stomp = new Stomp($this->broker);
    }

    public function send($msg) {
        if (!$this->Stomp->isConnected()) {        
            try {
                $this->Stomp->connect('admin', '223334444');
            } catch (StompException $ex) {
                 Log::channel('daily')->debug($ex->getMessage());
            }
            //$this->Stomp->connect();
        }
        $this->Stomp->setReadTimeout(5);
        $this->Stomp->send($this->queue, $msg);
    }

    public function read() {
        if (!$this->Stomp->isConnected('admin', 'admin')) {
            $this->Stomp->connect();
        }
        $this->Stomp->subscribe($this->queue, array('ack' => 'client', 'activemq.prefetchSize' => 1));
        echo($this->Stomp->hasFrameToRead());
        exit;
        if ($this->Stomp->hasFrameToRead()) {
            echo('co de doc');
            $frame = $this->Stomp->readFrame();
            $this->Stomp->ack($frame);
        }

        $this->Stomp->disconnect();
    }

}
