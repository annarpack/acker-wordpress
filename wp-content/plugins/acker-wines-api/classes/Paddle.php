<?php


namespace AckerWines\Api;


use DateTime;

class Paddle
{
    /**
     * @var int
     * Unique identifier for paddle
     */
    private $id = 0;
    /**
     * @var string
     * Paddle # for Auction. This is what bidders sees.
     */
    private $number = '';
    /**
     * @var string
     * Auction #, Plus suffix for web sales
     */
    private $auctionNumber = '';
    /**
     * @var int
     * Unique identifier for auction
     */
    private $auctionId = 0;
    /**
     * @var string
     * Date of the auction which is usually the same as Sale Date
     */
    private $auctionDate = '';
    /**
     * @var string
     * Date of Sale
     */
    private $saleDate = '';
    /**
     * @var float
     * USD balance due
     */
    private $totalAmount = 0.00;
    /**
     * @var float
     * Balance Due in currency for non-US auctions
     */
    private $totalAmountLocal = 0.00;
    /**
     * @var float
     * USD Interest Amount
     */
    private $interestAmount = 0.00;
    /**
     * @var float
     * Interest Amount in currency for non-US auctions
     */
    private $interestAmountLocal = 0.00;
    /**
     * @var float
     * Paid Amount in USD
     */
    private $paidAmount = 0.00;
    /**
     * @var float
     * Paid Amount in currency for non-US auctions
     */
    private $paidAmountLocal = 0.00;
    /**
     * @var float
     * Balance Due Amount in USD
     */
    private $balanceDue = 0.00;
    /**
     * @var float
     * Balance Due Amount in currency for non-US auctions
     */
    private $balanceDueLocal = 0.00;
    /**
     * @var string
     * ISO currency code for non-US auctions
     */
    private $currencyCodeLocal = '';
    /**
     * @var string
     * ISO currency symbol for non-US auctions
     */
    private $currencySymbolLocal = '';
    /**
     * @var string
     * Same as Date of Sale
     */
    private $dueDate = '';
    /**
     * @var string
     * Location of the Auction (NY, HK, etc.)
     */
    private $siteCode = '';
    /**
     * @var DateTime
     * Next Date for interest to be compounded. NULL if balance <= 0 or interest not charged for paddle
     */
    private $nextInterestDate = '';
    /**
     * @var bool
     * true = Bidder is/was also a consignor at any time
     */
    private $isConsignor = false;
    /**
     * @var bool
     * true = Bidder is also a consignor in same sale
     */
    private $isSaleConsignor = false;
    /**
     * @var array
     * Lots collection
     */
    private $lots;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Paddle
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     * @return Paddle
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuctionNumber()
    {
        return $this->auctionNumber;
    }

    /**
     * @param mixed $auctionNumber
     * @return Paddle
     */
    public function setAuctionNumber($auctionNumber)
    {
        $this->auctionNumber = $auctionNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuctionId()
    {
        return $this->auctionId;
    }

    /**
     * @param mixed $auctionId
     * @return Paddle
     */
    public function setAuctionId($auctionId)
    {
        $this->auctionId = $auctionId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuctionDate()
    {
        return $this->auctionDate;
    }

    /**
     * @param mixed $auctionDate
     * @return Paddle
     */
    public function setAuctionDate($auctionDate)
    {
        $this->auctionDate = $auctionDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSaleDate()
    {
        return $this->saleDate;
    }

    /**
     * @param mixed $saleDate
     * @return Paddle
     */
    public function setSaleDate($saleDate)
    {
        $this->saleDate = $saleDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * @param mixed $totalAmount
     * @return Paddle
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalAmountLocal()
    {
        return $this->totalAmountLocal;
    }

    /**
     * @param mixed $totalAmountLocal
     * @return Paddle
     */
    public function setTotalAmountLocal($totalAmountLocal)
    {
        $this->totalAmountLocal = $totalAmountLocal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrencyCodeLocal(): string
    {
        return $this->currencyCodeLocal;
    }

    /**
     * @param string $currencyCodeLocal
     * @return Paddle
     */
    public function setCurrencyCodeLocal(string $currencyCodeLocal): Paddle
    {
        $this->currencyCodeLocal = $currencyCodeLocal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrencySymbolLocal(): string
    {
        return $this->currencySymbolLocal;
    }

    /**
     * @param string $currencySymbolLocal
     * @return Paddle
     */
    public function setCurrencySymbolLocal(string $currencySymbolLocal): Paddle
    {
        $this->currencySymbolLocal = $currencySymbolLocal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * @param mixed $dueDate
     * @return Paddle
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getSiteCode(): string
    {
        return $this->siteCode;
    }

    /**
     * @param string $siteCode
     * @return Paddle
     */
    public function setSiteCode(string $siteCode)
    {
        $this->siteCode = $siteCode;
        return $this;
    }

    /**
     * @return float
     */
    public function getInterestAmount(): float
    {
        return $this->interestAmount;
    }

    /**
     * @param float $interestAmount
     * @return Paddle
     */
    public function setInterestAmount(float $interestAmount)
    {
        $this->interestAmount = $interestAmount;
        return $this;
    }

    /**
     * @return float
     */
    public function getInterestAmountLocal(): float
    {
        return $this->interestAmountLocal;
    }

    /**
     * @param float $interestAmountLocal
     * @return Paddle
     */
    public function setInterestAmountLocal(float $interestAmountLocal): Paddle
    {
        $this->interestAmountLocal = $interestAmountLocal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNextInterestDate()
    {
        if ( $this->nextInterestDate === ApiHelper::getMinDate() ||
            $this->formatDate($this->nextInterestDate) === '1970-01-01') {
            return null;
        }

        return $this->nextInterestDate;
    }

    /**
     * @param DateTime $nextInterestDate
     * @return Paddle
     */
    public function setNextInterestDate(DateTime $nextInterestDate): Paddle
    {
        $this->nextInterestDate = $nextInterestDate;
        return $this;
    }

    /**
     * @return float
     */
    public function getPaidAmount(): float
    {
        return $this->paidAmount;
    }

    /**
     * @param float $paidAmount
     * @return Paddle
     */
    public function setPaidAmount(float $paidAmount): Paddle
    {
        $this->paidAmount = $paidAmount;
        return $this;
    }

    /**
     * @return float
     */
    public function getPaidAmountLocal(): float
    {
        return $this->paidAmountLocal;
    }

    /**
     * @param float $paidAmountLocal
     * @return Paddle
     */
    public function setPaidAmountLocal(float $paidAmountLocal): Paddle
    {
        $this->paidAmountLocal = $paidAmountLocal;
        return $this;
    }

    /**
     * @return float
     */
    public function getBalanceDue(): float
    {
        return $this->balanceDue;
    }

    /**
     * @param float $balanceDue
     * @return Paddle
     */
    public function setBalanceDue(float $balanceDue): Paddle
    {
        $this->balanceDue = $balanceDue;
        return $this;
    }

    /**
     * @return float
     */
    public function getBalanceDueLocal(): float
    {
        return $this->balanceDueLocal;
    }

    /**
     * @param float $balanceDueLocal
     * @return Paddle
     */
    public function setBalanceDueLocal(float $balanceDueLocal): Paddle
    {
        $this->balanceDueLocal = $balanceDueLocal;
        return $this;
    }

    /**
     * @return bool
     */
    public function isConsignor(): bool
    {
        return $this->isConsignor;
    }

    /**
     * @param bool $isConsignor
     * @return Paddle
     */
    public function setIsConsignor(bool $isConsignor): Paddle
    {
        $this->isConsignor = $isConsignor;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSaleConsignor(): bool
    {
        return $this->isSaleConsignor;
    }

    /**
     * @param bool $isSaleConsignor
     * @return Paddle
     */
    public function setIsSaleConsignor(bool $isSaleConsignor): Paddle
    {
        $this->isSaleConsignor = $isSaleConsignor;
        return $this;
    }

    /**
     * @return array
     */
    public function getLots(): array
    {
        return $this->lots;
    }

    /**
     * @param array $lots
     * @return Paddle
     */
    public function setLots(array $lots): Paddle
    {
        $this->lots = $lots;
        return $this;
    }

    /**
     * Paddle constructor.
     * @param null $id
     * @param null $number
     */
    public function __construct($id=NULL, $number=NULL)
    {
        $this->setId($id);
        $this->setNumber($number);
    }

    public function toArray() {
        return array(
            'paddleId' => $this->id,
            'paddleNumber' => $this->number,
            'auctionNumber' => $this->auctionNumber,
            'auctionId' => $this->auctionId,
            'auctionDate' => $this->formatDate($this->auctionDate),
            'saleDate' => $this->formatDate($this->saleDate),
            'totalAmount' => $this->totalAmount,
            'totalAmountLocal' => $this->totalAmountLocal,
            'interestAmount' => $this->interestAmount,
            'interestAmountLocal' => $this->interestAmountLocal,
            'paidAmount' => $this->paidAmount,
            'paidAmountLocal' => $this->paidAmountLocal,
            'balanceDue' => $this->balanceDue,
            'balanceDueLocal' => $this->balanceDueLocal,
            'currencyCodeLocal' => $this->currencyCodeLocal,
            'currencySymbolLocal' => $this->currencySymbolLocal,
            'dueDate' => $this->formatDate($this->dueDate),
            'siteCode' => $this->siteCode,
            'nextInterestDate' => $this->formatDate($this->getNextInterestDate()),
            'isConsignor' => $this->isConsignor(),
            'isSaleConsignor' => $this->isSaleConsignor(),
            'lots' => $this->lots,
        );
    }

    /**
     * @param $datetime
     * @return mixed
     */
    public function formatDate($datetime)
    {
        if (!$datetime)
            return null;

        $formatted = date_format($datetime, 'Y-m-d');

        if ($formatted === FALSE) {
            return $datetime;
        }

        return $formatted;
    }

}
