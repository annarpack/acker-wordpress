<?php


namespace AckerWines\Test;

use AckerWines\Api\AuctionProgData;

require dirname(__FILE__) . '/TestBase.php';

class AuctionProgDataTest extends TestBase
{
    public function testDbConnection() {
        $db = new AuctionProgData();
        $db->open();
        $this->assertNotEquals(FALSE, $db->conn);
    }
}
