<?php

namespace AckerWines\Api;

$dir_prefix =  dirname(__FILE__) . '/../../../mu-plugins/acker-wines-shared/conf/';
include_once $dir_prefix . 'config.php';
include_once $dir_prefix . 'common.php';
include_once $dir_prefix . 'db.php';

class Aw_Data
{
    public $conn;
    public $connection_options;

    public function __construct($conn = NULL)
    {
        if ($conn) {
            $this->connection_options = $conn;
        } else {
            $this->init();
        }
    }

    private function init() {
        $this->connection_options = array(
            "Database" => ACKER_WINES_PROG_DB_NAME,
            "UID" => ACKER_WINES_PROG_DB_USER,
            "PWD" => ACKER_WINES_PROG_DB_PASSWORD,
            "TraceOn" => true,
            "TraceFile" => '/var/log/odbc.log'
        );
    }

}
