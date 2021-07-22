<?php


namespace AckerWines;


class Paddle
{
    private $id = 0;
    private $number = '';
    private $auctionNumber = '';
    private $auctionId = 0;
    private $auctionDate = '';
    private $saleDate = '';
    private $totalAmount = 0.00;
    private $totalAmountLocal = 0.00;
    private $currencyCodeLocal = '';
    private $currencySymbolLocal = '';

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
            'auctionDate' => $this->auctionDate,
            'saleDate' => $this->saleDate,
            'totalAmount' => $this->totalAmount,
            'totalAmountLocal' => $this->totalAmountLocal,
            'currencyCodeLocal' => $this->currencyCodeLocal,
            'currencySymbolLocal' => $this->currencySymbolLocal,
        );
    }

}
