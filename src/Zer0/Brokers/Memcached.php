<?php

namespace Zer0\Brokers;

use RedisClient\RedisClient;
use Zer0\Config\Interfaces\ConfigInterface;
use Zer0\Drivers\Redis\RedisDebug;
use Zer0\Drivers\Redis\Tracy\BarPanel;
use Zer0\Model\Exceptions\UnsupportedActionException;

/**
 * Class Memcached
 * @package Zer0\Brokers
 */
class Memcached extends Base
{
    /**
     * @param ConfigInterface $config
     * @return \Memcached
     * @throws UnsupportedActionException
     */
    public function instantiate(ConfigInterface $config)
    {
        $attrs = $config->toArray();
        $memcached = new \Memcached();
        $memcached->addServers($attrs['servers']);

        $tracy = $this->app->factory('Tracy');
        if ($tracy !== null) {
            $memcached = new RedisDebug($memcached);
            $tracy->addPanel(new BarPanel($memcached));
            $this->app->factory('HTTP')->on('endRequest', function () use ($memcached) {
                $memcached->resetQueryLog();
            });
        }

        return $memcached;
    }

    /**
     * @param string $name
     * @param bool $caching
     * @return RedisClient
     */
    public function get(string $name = '', bool $caching = true)
    {
        return parent::get($name, $caching);
    }
}
