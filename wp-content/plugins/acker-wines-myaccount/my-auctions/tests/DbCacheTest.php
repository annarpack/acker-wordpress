<?php


namespace AckerWines\Test;

use AckerWines\DbCache;

require dirname(__FILE__) . '/MyAuctionsTestBase.php';

class DbCacheTest extends MyAuctionsTestBase
{
    public function testDbCacheInsert() {
        $db = new DbCache();
        $key = uniqid();
        $value = uniqid();

        $db->setValue($key, $value);

        $the_value = $db->getValue($key);
        $this->assertEquals($value, $the_value);

        $db->remove($key);
        $the_value = $db->getValue($key);
        $this->assertNull($the_value);
    }
}
