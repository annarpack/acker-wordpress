<?php

namespace AckerWines\Test;

use AckerWines\Api\Paddle;

require dirname(__FILE__) . '/TestBase.php';

class PaddleTest extends TestBase
{
    public function testPaddleDateTime(){
        $testDateTimeObj = array(
            "date" => "2019-06-19 00:00:00.000000",
            "timezone_type" => 3,
            "timezone" => "America/New_York"
        );
        
        $testDate = date_create("2019-06-19T00:00:00.000000");
        $paddle = new Paddle();
        $formatDate = $paddle->formatDate($testDate);
        $this->assertRegExp('/^((19|20)\d{2})-((0|1|2)\d{1})-((0|1)\d{1})/', $formatDate);

    }

    // public function testPaddleData(){
    //     $testPaddleObj = array(
    //         "id" => 3544,
    //         "paddleNumber" => 356133,
    //         "auctionNumber" => "194A",
    //         "auctionId" => 348,
    //         "auctionDate" => array(
    //             "date" => "2019-05-11 00:00:00.000000",
    //             "timezone_type" => 3,
    //             "timezone" => "America/New_York"
    //         ),
    //         "saleDate" => array(
    //             "date" => "2019-05-11 00:00:00.000000",
    //             "timezone_type" => 3,
    //             "timezone" => "America/New_York"
    //         ),
    //         "totalAmount" => "2420.0000",
    //         "totalAmountLocal" => "19360.0000",
    //         "currencyCodeLocal" => "HKD",
    //         "currencySymbolLocal" => "HK$"
    //     );
    //     //echo '<p>' . var_dump($testPaddleObj) . '</p>';

    //     $PaddleID = 3544;
    //     $PaddleNo = 356133;
    //     $AuctionNumber = "194A";
    //     $AuctionID = 348;
    //     $TotalAmount = "2420.0000";
    //     $TotalAmountLocal = "19360.0000";
    //     $CurrencyCodeLocal = "HKD";
    //     $CurrencySymbolLocal = "HK$";

    //     $AuctionDate = $testDateTimeObj;
    //     $SaleDate = $testDateTimeObj;




    //     $paddle = new Paddle();
    //     $paddle->setNumber($PaddleID)
    //             ->setId($PaddleNo)
    //             ->setAuctionDate($AuctionDate)
    //             ->setAuctionId($AuctionID)
    //             ->setAuctionNumber($AuctionNumber)
    //             ->setSaleDate($SaleDate)
    //             ->setTotalAmount($TotalAmount)
    //             ->setTotalAmountLocal($TotalAmountLocal)
    //             ->setCurrencyCodeLocal($CurrencyCodeLocal)
    //             ->setCurrencySymbolLocal($CurrencySymbolLocal);
        
    //     //echo var_dump($paddle);
    //     $paddleData = $paddle->toArray();
    //     echo var_dump($paddleData);

        
    // }

}
