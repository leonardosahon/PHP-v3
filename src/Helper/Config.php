<?php

namespace Flutterwave\Helper;

use Flutterwave\Contract\ConfigInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Unirest\Request;

class Config implements ConfigInterface {

    const PUBLIC_KEY = 'PUBLIC_KEY';
    const SECRET_KEY = 'SECRET_KEY';
    const ENCRYPTION_KEY = 'ENCRYPTION_KEY';
    const VERSION = 'v3';
    const BASE_URL = 'https://api.flutterwave.com/'.self::VERSION;
    const DEFAULT_PREFIX = 'FW|PHP';
    const LOG_FILE_NAME = 'flutterwave-php.log';
    /**
     * @var string
     */
    private $secret;
    /**
     * @var string
     */
    private string $public;

    private static ?Config $instance = null;
    /**
     * @var string
     */
    private string $env;
    /**
     * @var Logger
     */
    protected Logger $logger;
    private Request $http;
    private string $enc;

    private function __construct(string $secretKey, string $publicKey, string $encryptKey, string $env)
    {
        $this->secret = $secretKey;
        $this->public = $publicKey;
        $this->enc = $encryptKey;
        $this->env = $env;

        $this->http = new Request();
        $log = new Logger('Flutterwave/PHP');
        $this->logger = $log;
        $log->pushHandler(new RotatingFileHandler(self::LOG_FILE_NAME, 90, Logger::DEBUG));
    }

    public function getHttp(): Request
    {
        return $this->http ?? new Request();
    }

    public static function setUp(string $secretKey, string $publicKey, string $enc, string $env): self
    {

        if(\is_null(self::$instance))
        {
            return new Config($secretKey, $publicKey, $enc, $env);
        }

        return self::$instance;
    }

    public function getLoggerInstance(): LoggerInterface
    {
        return $this->logger;;
    }

    public function getEncryptkey(): string
    {
        return $this->enc;
    }

    public function getPublicKey():string
    {
        return $this->public;
    }

    public static function getBaseUrl():string
    {
        return self::BASE_URL;
    }

    public function getSecretKey():string
    {
        return $this->secret;
    }
    
    public function getEnv():string
    {
        return $this->env;
    }

    public static function getDefaultTransactionPrefix():string
    {
        return self::DEFAULT_PREFIX ;
    }
}