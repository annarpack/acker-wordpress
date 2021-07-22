<?php


namespace AckerWines\Api;

$dir_prefix = dirname(__FILE__) . '/../../../mu-plugins/acker-wines-shared/conf/';
include_once $dir_prefix . 'config.php';
include_once $dir_prefix . 'common.php';
include_once $dir_prefix . 'db.php';

class AuctionProgData extends Aw_Data implements IDataProvider
{
    /**
     * AuctionProgIData constructor.
     * @param null $conn
     */
    public function __construct($conn = NULL)
    {
        parent::__construct($conn);
    }

    public function open()
    {
        $this->conn = sqlsrv_connect(ACKER_WINES_PROG_DB_HOST, $this->connection_options);
        if (!$this->conn) {
            error_log("Database Connection Failed!", 0);
        }
    }

    function getInvoices(string $apc_id)
    {

        if (!$this->conn)
            $this->open();

        $sql = "exec uspInvoiceBalance_WA ?";

        $params = array(
            array(
                $apc_id,
                SQLSRV_PARAM_IN,
                SQLSRV_PHPTYPE_STRING(SQLSRV_ENC_CHAR),
                SQLSRV_SQLTYPE_VARCHAR(1000),
            )
        );

        $results = sqlsrv_query($this->conn, $sql, $params);

        $data = array();
        while ($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
            $paddle = new Paddle();
            $paddle->setNumber(ApiHelper::null2EmptyString(ApiHelper::getDataValue($row, 'PaddleNo')))
                ->setId(ApiHelper::null2ZeroInt(ApiHelper::getDataValue($row, 'PaddleID')))
                ->setAuctionDate(ApiHelper::null2MinDate(ApiHelper::getDataValue($row, 'SaleDate')))
                ->setAuctionId(ApiHelper::null2ZeroInt(ApiHelper::getDataValue($row, 'AuctionID')))
                ->setAuctionNumber(ApiHelper::null2EmptyString(ApiHelper::getDataValue($row, 'AuctionNoSuffix')))
                ->setSaleDate(ApiHelper::null2MinDate(ApiHelper::getDataValue($row, 'SaleDate')))
                ->setTotalAmount(ApiHelper::null2ZeroDecimal(ApiHelper::getDataValue($row, 'Total')))
                ->setTotalAmountLocal(ApiHelper::null2ZeroDecimal(ApiHelper::getDataValue($row, 'LclTotal')))
                ->setPaidAmount(ApiHelper::null2ZeroDecimal(ApiHelper::getDataValue($row, 'PaddlePayTotalAmt')))
                ->setPaidAmountLocal(ApiHelper::null2ZeroDecimal(ApiHelper::getDataValue($row, 'LclPaddlePayTotalAmt')))
                ->setBalanceDue(ApiHelper::null2ZeroDecimal(ApiHelper::getDataValue($row, 'BalanceDue')))
                ->setBalanceDueLocal(ApiHelper::null2ZeroDecimal(ApiHelper::getDataValue($row, 'LclBalanceDue')))
                ->setCurrencyCodeLocal(ApiHelper::null2EmptyString(ApiHelper::getDataValue($row, 'LclCurrencyCode')))
                ->setCurrencySymbolLocal(ApiHelper::null2EmptyString(ApiHelper::getDataValue($row, 'LclCurrencySymbol')))
                ->setDueDate(ApiHelper::null2MinDate(ApiHelper::getDataValue($row, 'DueDate')))
                ->setSiteCode(ApiHelper::null2EmptyString(ApiHelper::getDataValue($row, 'SiteCode')))
                ->setInterestAmount(ApiHelper::null2ZeroDecimal(ApiHelper::getDataValue($row, 'InterestAmt')))
                ->setInterestAmountLocal(ApiHelper::null2ZeroDecimal(ApiHelper::getDataValue($row, 'LclInterestAmt')))
                ->setNextInterestDate(ApiHelper::null2MinDate(ApiHelper::getDataValue($row, 'NextInterestDt')));

            array_push($data, $paddle);
        }

        $result = sqlsrv_next_result($results);
        if ($result) {
            // nothing to do, using only the first result set for now
        }

        return $data;
    }

    function getInvoiceSummary(string $paddle_id) {

        if (!$this->conn)
            $this->open();

        $sql = "exec uspInvoiceData ?";

        $params = array(
            array(
                $paddle_id,
                SQLSRV_PARAM_IN,
                SQLSRV_PHPTYPE_STRING(SQLSRV_ENC_CHAR),
                SQLSRV_SQLTYPE_VARCHAR(1000),
            )
        );

        $stmt = sqlsrv_query($this->conn, $sql, $params);

        // DataSet 1 - Invoice Summary
        $data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        sqlsrv_free_stmt( $stmt);

        return $data;

    }

    function getInvoiceDetails(string $paddle_id)
    {

        if (!$this->conn)
            $this->open();

        $sql = "exec uspInvoiceData ?";

        $params = array(
            array(
                $paddle_id,
                SQLSRV_PARAM_IN,
                SQLSRV_PHPTYPE_STRING(SQLSRV_ENC_CHAR),
                SQLSRV_SQLTYPE_VARCHAR(1000),
            )
        );

        $stmt = sqlsrv_query($this->conn, $sql, $params);

        $data = array();

        $contact_id = 0;

        // DataSet 1 - Invoice Summary
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $contact_id = ApiHelper::null2ZeroInt(ApiHelper::getDataValue($row, 'ContactID'));
            break; // we only need the ID of the first row
        }

        $next_result = sqlsrv_next_result($stmt); // Dataset 2 - Lots

        if (!$next_result) {
            return $data;
        }

        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $lot = new Lot();
            $lot->setPaddleId(ApiHelper::null2ZeroInt(ApiHelper::getDataValue($row, 'PaddleID')))
                ->setLotNumber(ApiHelper::null2EmptyString(ApiHelper::getDataValue($row, 'Lot')))
                ->setQuantity(ApiHelper::null2ZeroInt(ApiHelper::getDataValue($row, 'Quantity')))
                ->setFormat(ApiHelper::null2EmptyString(ApiHelper::getDataValue($row, 'BottleName')))
                ->setLotNumberSeq(ApiHelper::null2ZeroDecimal(ApiHelper::getDataValue($row, 'LotNoDec')))
                ->setSeq(ApiHelper::null2ZeroInt(ApiHelper::getDataValue($row, 'Seq')))
                ->setWinningBid(ApiHelper::null2ZeroDecimal(ApiHelper::getDataValue($row, 'WinningBidDft')))
                ->setIsOwc(ApiHelper::null2EmptyString(ApiHelper::getDataValue($row, 'OwcFlag') === '*' ? true : false))
                ->setBottles(ApiHelper::null2EmptyString(ApiHelper::getDataValue($row, 'Bottles')))
                ->setVintage(ApiHelper::null2EmptyString(ApiHelper::getDataValue($row, 'Vintage')))
                ->setWineName(ApiHelper::null2EmptyString(ApiHelper::getDataValue($row, 'WineName')))
                ->setProducerLocation(ApiHelper::null2EmptyString(ApiHelper::getDataValue($row, 'ProducerLocation')))
                ->setProducer(ApiHelper::null2EmptyString(ApiHelper::getDataValue($row, 'Producer')))
                ->setDesignation(ApiHelper::null2EmptyString(ApiHelper::getDataValue($row, 'Designation')))
                ->setAppelation(ApiHelper::null2EmptyString(ApiHelper::getDataValue($row, 'Appellation')))
                ->setCurrencySymbol(ApiHelper::null2EmptyString(ApiHelper::getDataValue($row, 'CurrencySymbolDft')))
                ->setContactId($contact_id);

            array_push($data, $lot);
        }

        return $data;
    }

    function getTotalBalance(string $apc_id)
    {
        if (!$this->conn)
            $this->open();

        $sql = "exec uspInvoiceBalance_WA ?";

        $results = sqlsrv_query($this->conn, $sql, array(array($apc_id, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING(SQLSRV_ENC_CHAR), SQLSRV_SQLTYPE_VARCHAR(1000))));

        $data = array();

        $result = sqlsrv_next_result($results);
        if ($result) {
            while ($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
                $contact = new AuctionContact();
                $contact->setId($row['ContactID'])
                    ->setTotalBalanceDue(ApiHelper::null2ZeroDecimal(ApiHelper::getDataValue($row, 'TotalBalanceDue')))
                    ->setTotalBalanceDueLocal(ApiHelper::null2ZeroDecimal(ApiHelper::getDataValue($row, 'LclTotalBalanceDue')))
                    ->setCurrencyCodeLocal(ApiHelper::null2EmptyString(ApiHelper::getDataValue($row, 'LclCurrencyCode')))
                    ->setCurrencySymbolLocal(ApiHelper::null2EmptyString(ApiHelper::getDataValue($row, 'LclCurrencySymbol')));
                array_push($data, $contact);
            }
        }

        return $data;
    }

    function getPaddles($paddles)
    {

        if (!$this->conn) init();

        $sql = "exec uspInvoiceData ?";

        $results = sqlsrv_query($this->conn, $sql, array($paddles));

        $data = array();

        while ($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
            $paddle = new Paddle();
            $paddle->setNumber($row['PaddleID'])
                ->setId($row['PaddleNo'])
                ->setAuctionDate($row['AuctionDate'])
                ->setSaleDate($row['SaleDate'])
                ->setTotalAmount($row['Total'])
                ->setTotalAmountLocal($row['LclTotal']);
            array_push($data, $paddle);
        }

        $result = sqlsrv_next_result($results);
        if ($result) {
            // nothing to do
        }

        return $data;
    }

    function getInvoicePdf($paddle_id)
    {
        if (!$this->conn)
            $this->open();

        $sql = "exec dbo.uspInvoiceFile_WA ?";
        $params = array(
            array(
                $paddle_id,
                SQLSRV_PARAM_IN,
                SQLSRV_PHPTYPE_STRING(SQLSRV_ENC_CHAR),
                SQLSRV_SQLTYPE_VARCHAR(1000)
            )
        );

        $stmt = sqlsrv_query($this->conn, $sql, $params);

        if ($stmt === false) {
            aw_logMessage("Error in statement execution.  " . print_r(sqlsrv_errors(), true));
        }

        $data = '';

        if (sqlsrv_fetch($stmt)) {
            $file = sqlsrv_get_field($stmt, 0, SQLSRV_PHPTYPE_STREAM(SQLSRV_ENC_BINARY));
            $data = stream_get_contents($file);
        }

        sqlsrv_free_stmt($stmt);

        return $data;
    }

    function getBids($apc_id, $auction_id, $from_date, $to_date)
    {
				$data = array();
        try {
            $from_date = new \DateTime($from_date);
            $to_date = new \DateTime($to_date);
        } catch (\Exception $e) {
            error_log("Error while trying to convert DateTime. " . $e->getMessage(), 0);
        }

        if (!$this->conn){
            $this->open();

        $params = array(
            array(
                $apc_id,
                SQLSRV_PARAM_IN
            ),
            array(
                $auction_id,
                SQLSRV_PARAM_IN
            ),
            array(
                $from_date,
                SQLSRV_PARAM_IN,
                SQLSRV_PHPTYPE_DATETIME,
                SQLSRV_SQLTYPE_DATETIME
            ),
            array(
                $to_date,
                SQLSRV_PARAM_IN,
                SQLSRV_PHPTYPE_DATETIME,
                SQLSRV_SQLTYPE_DATETIME
            )
        );

        $sql = "exec dbo.uspBidResults_WA ?, ?, ?, ?";
				if($this->conn){
	        $results = sqlsrv_query($this->conn, $sql, $params);

	        while ($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
	            $bid = new Bid();
	            $isMixedLot = ApiHelper::null2Bool($row['MixedLot']);
	            $bid->setAuctionId(ApiHelper::null2ZeroInt($row['AuctionID']))
	                ->setAuctionNoSuffix(ApiHelper::null2EmptyString($row['AuctionNoSuffix']))
	                ->setSaleDate(ApiHelper::null2MinDate($row['SaleDate']))
									->setFullYeaR(ApiHelper::null2MinDate($row['SaleDate']))
	                ->setLotId(ApiHelper::null2ZeroInt($row['LotID']))
	                ->setLot(ApiHelper::null2EmptyString($row['Lot']))
	                ->setLotNoDec(ApiHelper::null2ZeroDecimal($row['LotNoDec']))
	                ->setQuantity(ApiHelper::null2ZeroInt($row['Quantity']))
	                ->setFormat( $isMixedLot ? '' : ApiHelper::null2EmptyString($row['BottleName']) )
	                ->setVintage( $isMixedLot ? '' : ApiHelper::null2EmptyString($row['Vintage']) )
	                ->setWineName( $isMixedLot ? 'Mixed lot' : ApiHelper::null2EmptyString($row['WineName']) )
	                ->setDesignation( $isMixedLot ? '' : ApiHelper::null2EmptyString($row['Designation']) )
	                ->setProducer( $isMixedLot ? '' : ApiHelper::null2EmptyString($row['Producer']) )
	                ->setAppellation( $isMixedLot ? '' : ApiHelper::null2EmptyString($row['Appellation']) )
	                ->setWineType( $isMixedLot ? '' : ApiHelper::null2EmptyString($row['WineType']) )
	                ->setBidAmount(ApiHelper::null2ZeroInt($row['BidAmount']))
	                ->setBidAmountLocal(ApiHelper::null2ZeroInt($row['LclBidAmount']))
	                ->setCurrencySymbolLocal(ApiHelper::null2EmptyString($row['CurrSymbol']))
	                ->setResult(ApiHelper::null2EmptyString($row['Result']))
	                ->setWinningBid(ApiHelper::null2ZeroDecimal($row['WinningBid']))
	                ->setWinningBidLocal(ApiHelper::null2ZeroDecimal($row['LclWinningBid']))
	                ->setIsMixedLot($isMixedLot);

	            array_push($data, $bid);
	        }
				}
			}

        return $data;
    }

    function getConsignorSales($apc_id, $from_date)
    {
			if($from_date != null){
	      try {
	          $from_date = new \DateTime($from_date);
	      } catch (\Exception $e) {
	          error_log("Error while trying to convert DateTime. " . $e->getMessage(), 0);
	      }
			}
			else { $from_date = null; }

        if (!$this->conn)
            $this->open();

        $params = array(
            array(
                $apc_id,
                SQLSRV_PARAM_IN
            ),
            array(
                $from_date,
                SQLSRV_PARAM_IN,
                SQLSRV_PHPTYPE_DATETIME,
                SQLSRV_SQLTYPE_DATETIME
            )
        );

        $sql = "exec dbo.uspConsignorSaleInfo_WA ?, ?";

        $results = sqlsrv_query($this->conn, $sql, $params);

        $data = array();

        while ($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
            $sale = (object)$row;
            array_push($data, $sale);
        }

        return $data;
    }

    function cleanup()
    {
        sqlsrv_close($this->conn);
    }

    function __destruct()
    {
        $this->cleanup();
    }

}
