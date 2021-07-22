<?php

namespace AckerWines;

$dir_prefix =  dirname(__FILE__) . '/../../../mu-plugins/acker-wines-shared/conf/';
include_once $dir_prefix . 'config.php';
include_once $dir_prefix . 'common.php';
include_once $dir_prefix . 'db.php';

class DbCache
{
    public function setValue(string $key, string $value) {
        $mysql_conn = aw_mysqlDbConnect();

        $sql = <<<SQL
insert into aw_prog_cache(`key`, `value`) values('$key', '$value');
SQL;

        try {
            aw_mysqlExecuteStatement($mysql_conn, $sql);
        } catch(\Exception $e) {
            aw_logMessage($e->getMessage());
            print $e->getMessage();
        }
    }

    public function getValue(string $key) {
        $mysql_conn = aw_mysqlDbConnect();

        $sql = <<<SQL
select `value` from aw_prog_cache where `key` ='$key';
SQL;

        $value = NULL;

        try {
            $value = aw_mysqlGetValue($mysql_conn, $sql);
        } catch(\Exception $e) {
            aw_logMessage($e->getMessage());
        }

        return $value;
    }

    public function remove($key) {
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
}
