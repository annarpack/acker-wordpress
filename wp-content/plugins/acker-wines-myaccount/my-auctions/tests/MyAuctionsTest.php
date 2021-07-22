<?php

namespace AckerWines\Test;

use AckerWines\AuctionProgData;
use AckerWines\MyAuctionsApi;

require dirname(__FILE__) . '/MyAuctionsTestBase.php';

class MyAuctionsTest extends MyAuctionsTestBase
{
    public function testApcIdLookup() {
        $apcid = NULL;

        $api = new MyAuctionsApi();
        $apcid = $api->getApcId();

        $this->assertNotNull($apcid);
        $this->assertEquals('10180', $apcid);
    }

    public function testInvoiceHeaderData() {
        $api = new MyAuctionsApi();
        $apcid = $api->getApcId();

        $db = new AuctionProgData();
        $data = $db->getInvoices($apcid);

        $this->assertTrue(sizeof($data) > 0, "invoice header contains no data");
    }

    public function testGetInvoiceApi() {
        $api = new MyAuctionsApi();
        $json_data = $api->getInvoices($api->getApcId());
        $this->assertNotNull($json_data);
    }
}
