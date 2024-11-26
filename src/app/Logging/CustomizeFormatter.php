<?php

namespace App\Logging;

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class CustomizeFormatter
{
    /**
     * Personnalise le formatteur pour le canal de logs.
     *
     * @param  Logger  $logger
     * @return void
     */
    public function __invoke(
        $logger
    ) {
        foreach ($logger->getHandlers() as $handler) {
            if ($handler instanceof RotatingFileHandler) {
                $handler->setFormatter(new JsonFormatter());
            }
        }
    }
}
