<?php


namespace AckerWines\Api;


interface IRedisCache
{
    function GetByKey($key);
    function Put($key, $value);
}
