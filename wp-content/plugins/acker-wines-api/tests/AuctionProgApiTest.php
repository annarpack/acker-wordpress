<?php

namespace AckerWines\Test;

use AckerWines\Api\AuctionProgData;
use AckerWines\Api\AuctionProgApi;

require dirname(__FILE__) . '/TestBase.php';

class AuctionProgApiTest extends TestBase
{
    public function testApcIdLookup() {
        $apcid = NULL;

        $api = new AuctionProgApi();
        $apcid = $api->getApcId();

        $this->assertNotNull($apcid);
        $this->assertEquals('10180', $apcid);
    }

    public function testInvoiceHeaderData() {
        $api = new AuctionProgApi();
        $apcid = $api->getApcId();

        $db = new AuctionProgData();
        $data = $db->getInvoices($apcid);

        $this->assertTrue(sizeof($data) > 0, "invoice header contains no data");
    }

    public function testInvoiceDetailData() {
        $api = new AuctionProgApi();
        $apcid = $api->getApcId();

        $db = new AuctionProgData();
        $data = $db->getInvoiceDetails($apcid);

        $this->assertTrue(sizeof($data) > 0, "invoice details contains no data");
    }

    public function testGetInvoiceApi() {
        $api = new AuctionProgApi();
        $json_data = $api->getInvoices($api->getApcId());
        $this->assertNotNull($json_data);
    }

    public function testInvoicePdf() {
        $api = new AuctionProgApi();
        $paddleid = '';
        $data = $api->getInvoices($paddleid, $api->getApcId());
        $this->assertNotNull($data);
    }

    public function testTotalBalance() {
        $api = new AuctionProgApi();
        $json_data = $api->getTotalBalance($api->getApcId());
        $this->assertNotNull($json_data);
    }
}
