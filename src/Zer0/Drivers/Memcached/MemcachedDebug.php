<?php

namespace Zer0\Drivers\Memcached;

use MemcachedClient\MemcachedClient;
use Zer0\Drivers\Traits\QueryLog;

/**
 * Class MemcachedDebug
 * @package Zer0\Drivers\Memcached
 */
class MemcachedDebug
{
    use QueryLog;

    /**
     * @var null|array
     */
    protected $transaction;

    /**
     * @var MemcachedClient
     */
    protected $memcached;

    /**
     * MemcachedDebug constructor.
     * @param MemcachedClient $memcached
     */
    public function __construct(MemcachedClient $memcached)
    {
        $this->memcached = $memcached;
        $this->queryLogging = true;
    }

    /**
     * @param $method
     * @param $args
     * @return
     */
    public function __call($method, $args)
    {
        try {
            $t0 = microtime(true);
            return $ret = $this->memcached->$method(...$args);
        } finally {
            $this->queryLog[] = [
                'query' => strtoupper($method) . ' '
                    . substr(json_encode($args, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), 1, -1),
                'time' => microtime(true) - $t0,
                'trace' => new \Exception,
            ];
        }
    }
}
