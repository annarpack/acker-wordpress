<?php


namespace AckerWines\Test;

use AckerWines\Api\DbCache;
use AckerWines\Api\AuctionProgData;

require dirname(__FILE__) . '/TestBase.php';

class DbCacheTest extends TestBase
{
    public function testDbCacheInsert() {
        $db = new DbCache();
        $key = uniqid();
        $value = '{"data":[{"contactId":10180,"totalBalanceDue":-81212.9,"totalBalanceDueLocal":2918591.1,"currencyCodeLocal":"HKD","currencySymbolLocal":"HK$"}]}';

        $db->setValue($key, $value);

        $the_value = $db->getValue($key);
        $this->assertEquals($value, $the_value);

        $db->remove($key);
        $the_value = $db->getValue($key);
        $this->assertNull($the_value);
    }

//    public function testSaveFile()
//    {
//        $db = new AuctionProgData();
//        $db_cache = new DbCache();
//
//        $result = $db->getInvoicePdf('371738');
//        $temp = tempnam(sys_get_temp_dir(), 'ProgFile');
//        file_put_contents($temp, $result);
//
//        $result = $db_cache->saveFile($temp, $temp);
//
//        $this->assertNotFalse($result);
//    }
//
//    public function testSaveAndGetFile()
//    {
//        $db = new AuctionProgData();
//        $db_cache = new DbCache();
//
//        $result = $db->getInvoicePdf('371738');
//        $temp = tempnam(sys_get_temp_dir(), 'ProgFile');
//        file_put_contents($temp, $result);
//
//        $result = $db_cache->saveFile($temp, $temp);
//
//        $this->assertNotFalse($result);
//
//        $saved = $db_cache->getFile($temp);
//
//        $this->assertNotNull($saved);
//        $this->assertEquals('application/pdf', $saved['file_type']);
//        $this->assertNotNull($saved['file']);
//    }
}
