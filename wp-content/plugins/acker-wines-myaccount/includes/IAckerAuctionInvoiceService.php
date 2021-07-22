<?php


namespace AckerWines;


interface IAckerAuctionInvoiceService
{
    function getInvoices(string $cust_id);
    function getPaddles(string $cust_id);
    function getInvoicePdf(array $paddles);
}
