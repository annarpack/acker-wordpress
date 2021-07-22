<?php


namespace AckerWines;

$dir_prefix =  dirname(__FILE__) . '/../../../mu-plugins/acker-wines-shared/conf/';
include_once $dir_prefix . 'config.php';
include_once $dir_prefix . 'common.php';
include_once $dir_prefix . 'db.php';

class AuctionProgData extends Aw_Data implements IDataProvider
{
    public $conn;
    public $connection_options;

    /**
     * AuctionProgIData constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    public function open() {
        $this->conn = sqlsrv_connect(getenv('ACKER_WINES_PROG_DB_HOST'), $this->connection_options);
    }

    private function init() {
        $this->connection_options = array(
            "Database" => getenv('ACKER_WINES_PROG_DB_NAME'),
            "UID" => getenv('ACKER_WINES_PROG_DB_USER'),
            "PWD" => getenv('ACKER_WINES_PROG_DB_PASSWORD'),
            "TraceOn" => true,
            "TraceFile" => '/var/log/odbc.log'
        );
    }

    function getInvoices(string $cust_id) {

        if (!$this->conn)
            $this->open();

        $sql = "exec uspInvoiceBalance_WA ?";

        $results = sqlsrv_query($this->conn, $sql, array(array($cust_id, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING(SQLSRV_ENC_CHAR),SQLSRV_SQLTYPE_VARCHAR(1000))));

        $data = array();

        while ($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
            $paddle = new Paddle();
            $paddle->setNumber($row['PaddleID'])
                ->setId($row['PaddleNo'])
                ->setAuctionDate($row['SaleDate'])
                ->setAuctionId($row['AuctionID'])
                ->setAuctionNumber($row['AuctionNoMonth'])
                ->setSaleDate($row['SaleDate'])
                ->setTotalAmount($row['BalanceDue'])
                ->setTotalAmountLocal($row['LclBalanceDue'])
                ->setCurrencyCodeLocal(is_null($row['LclCurrencyCode']) ? '' : $row['LclCurrencyCode'])
                ->setCurrencySymbolLocal(is_null($row['LclCurrencySymbol']) ? '' : $row['LclCurrencySymbol']);
            array_push($data, $paddle);
        }

        $result = sqlsrv_next_result($results);
        if ($result) {
            // nothing to do, using only the first result set for now
        }

        return $data;
    }

    function getPaddles($paddles) {

        if (!$this->conn) init();

        $sql = "exec uspInvoiceData_WA ?";

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

    function cleanup() {
        sqlsrv_close($this->conn);
    }

    function __destruct()
    {
        $this->cleanup();
    }

}
