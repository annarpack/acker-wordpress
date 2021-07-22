<?php


namespace AckerWines;


interface IRedisCache
{
    function GetByKey($key);
    function Put($key, $value);
}
