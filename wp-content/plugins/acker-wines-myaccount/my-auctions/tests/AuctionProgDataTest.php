<?php


namespace AckerWines\Test;

use AckerWines\AuctionProgData;
use AckerWines\MyAuctionsApi;

require dirname(__FILE__) . '/MyAuctionsTestBase.php';

class AuctionProgDataTest extends MyAuctionsTestBase
{
    public function testDbConnection() {
        $db = new AuctionProgData();
        $db->open();
        $this->assertNotEquals(FALSE, $db->conn);
    }
}
