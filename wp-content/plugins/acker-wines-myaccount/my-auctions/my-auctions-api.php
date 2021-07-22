<?php
/**
 * This file defines ajax endpoints for My Auctions API.
 */

$includes_path = dirname( __FILE__ ) . '/../includes/';

require_once $includes_path . 'IDataProvider.php';
require_once $includes_path . 'IAckerAuctionInvoiceService.php';
require_once $includes_path . 'IRedisCache.php';
require_once $includes_path . 'Aw_Data.php';
require_once $includes_path . 'UserTraits.php';
require_once $includes_path . 'ApiHelper.php';
require_once $includes_path . 'ApiRequestResult.php';
require_once $includes_path . 'AuctionProgData.php';
require_once $includes_path . 'MyAuctionsApi.php';
require_once $includes_path . 'Paddle.php';
require_once $includes_path . 'RedisCache.php';
require_once $includes_path . 'RedisConfig.php';
require_once $includes_path . 'DbCache.php';

AckerWines\ApiHelper::registerAjaxEndpoint('aw_my_auctions_paddles', true);
function aw_my_auctions_index()
{
    $api = new AckerWines\MyAuctionsApi();

    $results = $api->getPaddles($api->getApcId());

    return AckerWines\ApiRequestResult::formatResultAsJson($results);
}

AckerWines\ApiHelper::registerAjaxEndpoint('aw_my_auction_invoices', false);
function aw_my_auction_invoices()
{
    $api = new AckerWines\MyAuctionsApi();
    echo $api->getInvoices($api->getApcId());
    wp_die();
}

AckerWines\ApiHelper::registerAjaxEndpoint('aw_my_auction_invoice_pdf', true);
function aw_my_auction_invoice_pdf()
{
    $paddle_id = intval($_POST['paddle_id']);

    //TODO: implement invoice pdf
}
