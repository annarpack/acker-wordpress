<?php


namespace AckerWines\Api;


class AuctionProgApi implements IAuctionProgApi
{
    use UserTraits;

    function getInvoices(string $apc_id)
    {
        $key = md5("prog-invoices-$apc_id");

        $db_cache = new DbCache();
        $result = $db_cache->getValue($key);

        if ($result === NULL) {
            $prog_db = new AuctionProgData();

            $results = $prog_db->getInvoices($apc_id);
            $lots = $prog_db->getInvoiceDetails($apc_id);

            $data = array();
            foreach ($results as $paddle) {
                $lots_array = array();
                foreach ($lots as $lot) {
                    if ($lot->getPaddleId() === $paddle->getId()) {
                        array_push($lots_array, $lot->toArray());
                    }
                }
                $paddle->setLots($lots_array);
                array_push($data, $paddle->toArray());
            }

            $result = ApiRequestResult::formatResultAsJson($data);
            $db_cache->setValue($key, $result);
        }

        return $result;
    }

    function getInvoiceBalance(string $apc_id)
    {
        // TODO: Implement getInvoiceBalance() method.
        return NULL;
    }

    function getInvoiceDetails(string $paddle_id, string $apc_id)
    {
        $key = md5("prog-invoice-details-$paddle_id");

        $db_cache = new DbCache();
        $result = $db_cache->getValue($key);

        if (!$result) {
            $prog_db = new AuctionProgData();

            $results = $prog_db->getInvoiceDetails($paddle_id, $apc_id);

            $data = array();
            foreach ($results as $lot) {
                array_push($data, $lot->toArray());
            }

            $result = ApiRequestResult::formatResultAsJson($data);
            $db_cache->setValue($key, $result);
        }

        return $result;
    }

    function getInvoicePdf(string $paddle_id, string $apc_id)
    {
        $key = md5("auction-invoice-$paddle_id");

        $prog_db = new AuctionProgData();

        // Validate request: Is the current logged in user the owner of $paddle_id?
        $is_valid = false;
        $invoice = $prog_db->getInvoiceSummary($paddle_id);
        $contact_id = ApiHelper::null2ZeroInt($invoice['ContactID']);
        $apc_id = ApiHelper::null2ZeroInt($apc_id);

        if ( isset($invoice) && is_array($invoice) && count($invoice) > 0 &&
            $contact_id  && $apc_id && $contact_id == $apc_id) {
            $is_valid = true;
        }

        if ( !$is_valid ) {
            return null;
        }

        $db_cache = new DbCache();
        $file = $db_cache->getFile($key);
        $temp_file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "AckerWinesAuctionInvoice-{$paddle_id}" . '.pdf';

        if ($file === NULL) {
            $data = $prog_db->getInvoicePdf($paddle_id);
            file_put_contents($temp_file, $data);
            $db_cache->saveFile($key, $temp_file);
        } else {
            file_put_contents($temp_file, $file);
        }

        return $temp_file;
    }

    function getTotalBalance(string $apc_id)
    {
        $key = md5("prog-balance-{$apc_id}");

        $db_cache = new DbCache();
        $result = $db_cache->getValue($key);

        if ($result === NULL) {
            $prog_db = new AuctionProgData();

            $results = $prog_db->getTotalBalance($apc_id);

            $data = array();
            foreach ($results as $contact) {
                array_push($data, $contact->toArray());
            }

            $result = ApiRequestResult::formatResultAsJson($data);
            $db_cache->setValue($key, $result);
        }

        return $result;
    }

    function getBids(int $apc_id, int $auction_id = null, string $from_date = null, string $to_date = null)
    {
        $auction_key = $auction_id ? $auction_id : 'all';

        if (!$from_date || strlen(trim($from_date)) < 1)
            $from_date = ApiHelper::AW_MINDATE;

        if (!$to_date || strlen(trim($to_date)) < 1)
            $to_date = ApiHelper::AW_MAXDATE;

        $key = md5("prog-bids-$apc_id-$auction_key-$from_date-$to_date");

        $db_cache = new DbCache();
        $result = $db_cache->getValue($key);

        if ($result === NULL) {
            $prog_db = new AuctionProgData();

						$results = $prog_db->getBids($apc_id, $auction_id, $from_date, $to_date);
            //$results = $prog_db->getBids($apc_id, null, $from_date, $to_date);

            $auctions = array();
            foreach ($results as $bid) {
							// if ( !$auction_id || $bid->getAuctionId() === $auction_id ) {
							// 		 array_push($auctions, $bid->toArray());
							//  }
                $lot_details = $bid->toArray();
                $auction_no = $bid->getAuctionId();
								$auction_name = $bid->getAuctionNoSuffix();
                $year = $bid->getSaleDate()->format('Y');
								$sale_date = $bid->getSaleDate();
								$date = date_format($sale_date, 'Y-m-d');
                $lot = $bid->getLot();
								$lot_details['row_id'] = $auction_name . '-' . $lot_details['lot'];
								$auctions['years'][$year]['auctions'][$date]['lots'][$lot] = $lot_details;
								$auctions_arr = $auctions['years'][$year]['auctions'][$date];
								$auctions_arr = rsort($auctions_arr);
            }
						foreach($auctions['years'] as $year => $auction_data){
							krsort($auctions['years'][$year]['auctions']);
						}
						//krsort($auctions);

            $result = ApiRequestResult::formatResultAsJson($auctions);
						//$result = ApiRequestResult::formatResultAsJson($auctions);
            $db_cache->setValue($key, $result);
        }
        $db_cache->setValue($key, $result);
        return $result;
    }

    function getConsignorSales(int $apc_id, string $from_date)
    {
        if (!$from_date || strlen(trim($from_date)) < 1){
            $from_date = ApiHelper::AW_MINDATE;}

        $key = md5("consignor-sales-$apc_id-$from_date");

        $db_cache = new DbCache();
        $result = $db_cache->getValue($key); // for production
				//$result = NULL; // only for local

        if ($result === NULL) {
            $prog_db = new AuctionProgData();
            $results = $prog_db->getConsignorSales($apc_id, $from_date);

            $result = ApiRequestResult::formatResultAsJson($results);
            $db_cache->setValue($key, $result);
        }

        return $result;
    }
}
