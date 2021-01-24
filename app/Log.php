<?php

namespace App;

use Config;
use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Simple wrapper of Monolog Logger
 *
 * @author Alessandro Afloarei <alessandro.afloarei@gmail.com>
 *
 */
class Log
{
    protected static $instance;

    /**
     * Method to return the Monolog instance
     *
     * @return \Monolog\Logger
    */
    public static function getLogger()
    {
        if (! self::$instance) {
            self::configureInstance();
        }

        return self::$instance;
    }

    /**
     * Configure Monolog.
     *
     * @return Logger
    */
    protected static function configureInstance()
    {
        $dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'logs';

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $logger = new Logger('Mainlogger');
        $handler = new StreamHandler($dir . DIRECTORY_SEPARATOR . 'app.log', Config::LOG_LEVEL);

        $dateFormat = "Y-n-j, H:i:s";
        $output = "%datetime% > %level_name% > %message% %context% %extra%\n";
        $formatter = new LineFormatter($output, $dateFormat, false, true);

        $handler->setFormatter($formatter);
        $logger->pushHandler($handler);

        self::$instance = $logger;
    }

    public static function debug($message, array $context = [])
    {
        self::getLogger()->debug($message, $context);
    }

    public static function info($message, array $context = [])
    {
        self::getLogger()->info($message, $context);
    }

    public static function notice($message, array $context = [])
    {
        self::getLogger()->notice($message, $context);
    }

    public static function warning($message, array $context = [])
    {
        self::getLogger()->warning($message, $context);
    }

    public static function error($message, array $context = [])
    {
        self::getLogger()->error($message, $context);
    }

    public static function critical($message, array $context = [])
    {
        self::getLogger()->critical($message, $context);
    }

    public static function alert($message, array $context = [])
    {
        self::getLogger()->alert($message, $context);
    }

    public static function emergency($message, array $context = [])
    {
        self::getLogger()->emergency($message, $context);
    }
}
