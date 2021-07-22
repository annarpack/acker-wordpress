<?php


namespace AckerWines\Api;


interface IAuctionProgApi
{
    function getInvoices(string $apc_id);

    function getInvoiceBalance(string $apc_id);

    function getInvoiceDetails(string $order_id, string $apc_id);

    function getInvoicePdf(string $paddle_id, string $apc_id);

    function getTotalBalance(string $apc_id);

    function getBids(int $apc_id, int $auction_id, string $from_date, string $to_date);

    function getConsignorSales(int $apc_id, string $from_date);
}
