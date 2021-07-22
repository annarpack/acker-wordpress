<?php

namespace AckerWines;

class MyAuctionsApi implements IAckerAuctionInvoiceService
{
    use UserTraits;

    function getInvoices(string $cust_id) {

        $key = "prog-invoices-$cust_id";

        $db_cache = new DbCache();
        $result = $db_cache->getValue($key);

        if ($result === NULL) {
            $prog_db = new AuctionProgData();

            $results = $prog_db->getInvoices($cust_id);

            $data = array();
            foreach ($results as $paddle) {
                array_push($data, $paddle->toArray());
            }

            $result = ApiRequestResult::formatResultAsJson($data);
            $db_cache->setValue($key, $result);
        }

        return $result;

    }

    function getPaddles(string $cust_id)
    {
        $prog_db = new AuctionProgData();

        $results = $prog_db->getPaddles($cust_id);

        return json_encode(array('data' => $results));
    }

    function getInvoicePdf(array $paddles)
    {
        // TODO: Implement getInvoicePdf() method.
    }
}
