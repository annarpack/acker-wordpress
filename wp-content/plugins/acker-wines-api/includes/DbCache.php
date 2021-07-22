<?php

namespace AckerWines\Api;

$dir_prefix =  dirname(__FILE__) . '/../../../mu-plugins/acker-wines-shared/conf/';
include_once $dir_prefix . 'config.php';
include_once $dir_prefix . 'common.php';
include_once $dir_prefix . 'db.php';

class DbCache
{
    private $cache_enabled = false;

    function __construct() {
        if ( defined(ACKER_WINES_PROG_DB_CACHE_ENABLED) ) {
            $this->cache_enabled = strtolower(ACKER_WINES_PROG_DB_CACHE_ENABLED) === 'true' ? true : false;
        }
    }

    public function setValue(string $key, string $value) {

        if ( !$this->cache_enabled ) return;

        $mysql_conn = aw_mysqlDbConnect();
        $encoded_value = base64_encode($value);
        $sql = <<<SQL
insert into aw_prog_cache(`key`, `value`) values('$key', '$encoded_value)');
SQL;

        try {
            aw_mysqlExecuteStatement($mysql_conn, $sql);
        } catch(\Exception $e) {
            aw_logMessage($e->getMessage());
            print $e->getMessage();
        }
    }

    public function getValue(string $key) {

        if ( !$this->cache_enabled ) return null;

        $mysql_conn = aw_mysqlDbConnect();

        $sql = <<<SQL
select `value` from aw_prog_cache where `key` ='$key';
SQL;

        $value = NULL;

        try {
            $value = aw_mysqlGetValue($mysql_conn, $sql);
            if ($value) {
                $value = base64_decode($value);
            }
        } catch(\Exception $e) {
            aw_logMessage($e->getMessage());
        }

        return $value;
    }

    public function remove($key) {

        if ( !$this->cache_enabled ) return;

        $mysql_conn = aw_mysqlDbConnect();

        $sql = <<<SQL
delete from aw_prog_cache where `key` ='$key';
SQL;

        try {
            aw_mysqlExecuteStatement($mysql_conn, $sql);
        } catch(\Exception $e) {
            aw_logMessage($e->getMessage());
        }
    }

    public function saveFile($name, $file) {

        if ( !$this->cache_enabled ) return false;

        $mysql_conn = aw_mysqlDbConnect();

        $file_data = \mysqli_real_escape_string($mysql_conn, file_get_contents($file));
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($file_info, $file);
        $file_size = filesize($file);

        $sql = <<<SQL
INSERT INTO aw_prog_files (`name`, `file`, `file_type`, `file_size`) 
VALUES ('$name', '$file_data', '$file_type', $file_size);
SQL;

        $result = false;

        try {
            $result = $mysql_conn->query($sql);
        } catch(\Exception $e) {
            error_log($e->getMessage(), 0);
        }

        return $result;
    }

    public function getFile($name) {

        if ( !$this->cache_enabled ) return null;

        $mysql_conn = aw_mysqlDbConnect();

        $sql = <<<SQL
select `name`, `file`, `file_type`, `file_size` from aw_prog_files where `name` ='$name';
SQL;

        $file = NULL;

        try {
            $result = aw_mysqlGetArray($mysql_conn, $sql);
            if ( $result && count($result) > 0 ) {
                $file = $result[0];
            }
        } catch(\Exception $e) {
            aw_logMessage($e->getMessage());
        }

        return $file;
    }
}
