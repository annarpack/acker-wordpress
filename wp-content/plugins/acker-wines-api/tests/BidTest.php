<?php


namespace AckerWines\Test;

use AckerWines\Api\AuctionProgApi;
use AckerWines\Api\AuctionProgData;

require dirname(__FILE__) . '/TestBase.php';

class BidTest extends TestBase
{
    public function testGetBids() {
        $api = new AuctionProgApi();
        $apcid = $api->getApcId();

        $db = new AuctionProgData();
        $data = $db->getBids($apcid, '2019-01-01', '2019-09-01');

        $this->assertTrue(sizeof($data) > 0, "invoice header contains no data");
    }
}
