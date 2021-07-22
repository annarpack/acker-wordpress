<?php

namespace AckerWines;

class ApiRequestResult
{
    public static function formatResultAsJson($data){
        return json_encode(array('data' => $data));
    }
}
