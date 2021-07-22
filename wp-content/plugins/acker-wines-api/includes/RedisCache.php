<?php


namespace AckerWines\Api;

class RedisCache implements IRedisCache
{
    private $config;

    /**
     * @return RedisConfig
     */
    public function getConfig():RedisConfig
    {
        return $this->config;
    }

    /**
     * @param RedisConfig $config
     * @return RedisCache
     */
    public function setConfig(RedisConfig $config)
    {
        $this->config = $config;
        return $this;
    }

    function GetByKey($key)
    {
        // TODO: Implement GetByKey() method.
    }

    function Put($key, $value)
    {
        // TODO: Implement Put() method.
    }
}
