<?php

namespace App\Logging;

use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Monolog\Handler\RotatingFileHandler;

class CustomLogNames
{
    /**
     * Customize the given logger instance.
     *
     * @param  \Illuminate\Log\Logger  $logger
     * @return void
     */
    protected $request;

    public function __construct(Request $request = null) {
        $this->request = $request;
    }
    
    public function __invoke($logger) {
        if ($this->request) {
            foreach ($logger->getHandlers() as $handler) {
                if ($handler instanceof RotatingFileHandler) {
                    $handler->pushProcessor(function ($record) {
                        $record['extra']['ip'] =$this->request->user()->username ?? 'guest';
                        $record['extra']['ip'] =$this->request->getClientIp();
                        $record['extra']['path'] = $this->request->getPathInfo() . '|param= ' . $this->request->getQueryString();
                        return $record;
                    });
                    $handler->setFilenameFormat("{filename}-{date}", 'Y-m-d');
                }
            }
        }
    }
}