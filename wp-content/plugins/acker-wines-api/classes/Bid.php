<?php


namespace AckerWines\Api;


class Bid
{

    private $auctionId = 0;
    private $auctionNoSuffix = '';
    private $saleDate = '';
		private $year = '';
    private $lotId = 0;
    private $lot = '';
    private $lotNoDec = 0;
    private $quantity = 0;
    private $format = '';
    private $vintage = '';
    private $wineName = '';
    private $designation = '';
    private $producer = '';
    private $appellation = '';
    private $wineType = '';
    private $bidAmount = 0;
    private $bidAmountLocal = 0;
    private $currencySymbolLocal = '';
    private $result = '';
    private $winningBid = 0;
    private $winningBidLocal = 0;
    private $isMixedLot = false;

    /**
     * @return int
     */
    public function getAuctionId(): int
    {
        return $this->auctionId;
    }

    /**
     * @param int $auctionId
     * @return Bid
     */
    public function setAuctionId(int $auctionId): Bid
    {
        $this->auctionId = $auctionId;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuctionNoSuffix(): string
    {
        return $this->auctionNoSuffix;
    }

    /**
     * @param string $auctionNoSuffix
     * @return Bid
     */
    public function setAuctionNoSuffix(string $auctionNoSuffix): Bid
    {
        $this->auctionNoSuffix = $auctionNoSuffix;
        return $this;
    }

    /**
     * @return string
     */
    public function getSaleDate(): \DateTime
    {
        return $this->saleDate;
    }

    /**
     * @param mixed $saleDate
     * @return Bid
     */
    public function setSaleDate(\DateTime $saleDate): Bid
    {
        $this->saleDate = $saleDate;
        return $this;
    }

		/**
		 * @return string
		 */
		public function getFullYear(): \DateTime
		{
				return $this->$year;
		}

		/**
		 * @param mixed $year
		 * @return Bid
		 */
		public function setFullYear(\DateTime $saleDate): Bid
		{
			$saleDate = $this->saleDate;
			$year = $saleDate->format('Y');
			$this->year = $year;
			return $this;
		}

    /**
     * @return int
     */
    public function getLotId(): int
    {
        return $this->lotId;
    }

    /**
     * @param int $lotId
     * @return Bid
     */
    public function setLotId(int $lotId): Bid
    {
        $this->lotId = $lotId;
        return $this;
    }

    /**
     * @return string
     */
    public function getLot(): string
    {
        return $this->lot;
    }

    /**
     * @param string $lot
     * @return Bid
     */
    public function setLot(string $lot): Bid
    {
        $this->lot = $lot;
        return $this;
    }

    /**
     * @return int
     */
    public function getLotNoDec(): int
    {
        return $this->lotNoDec;
    }

    /**
     * @param int $lotNoDec
     * @return Bid
     */
    public function setLotNoDec(int $lotNoDec): Bid
    {
        $this->lotNoDec = $lotNoDec;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return Bid
     */
    public function setQuantity(int $quantity): Bid
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @return Bid
     */
    public function setFormat(string $format): Bid
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return string
     */
    public function getVintage(): string
    {
        return $this->vintage;
    }

    /**
     * @param string $vintage
     * @return Bid
     */
    public function setVintage(string $vintage): Bid
    {
        $this->vintage = $vintage;
        return $this;
    }

    /**
     * @return string
     */
    public function getWineName(): string
    {
        return $this->wineName;
    }

    /**
     * @param string $wineName
     * @return Bid
     */
    public function setWineName(string $wineName): Bid
    {
        $this->wineName = $wineName;
        return $this;
    }

    /**
     * @return string
     */
    public function getDesignation(): string
    {
        return $this->designation;
    }

    /**
     * @param string $designation
     * @return Bid
     */
    public function setDesignation(string $designation): Bid
    {
        $this->designation = $designation;
        return $this;
    }

    /**
     * @return string
     */
    public function getProducer(): string
    {
        return $this->producer;
    }

    /**
     * @param string $producer
     * @return Bid
     */
    public function setProducer(string $producer): Bid
    {
        $this->producer = $producer;
        return $this;
    }

    /**
     * @return string
     */
    public function getAppellation(): string
    {
        return $this->appellation;
    }

    /**
     * @param string $appellation
     * @return Bid
     */
    public function setAppellation(string $appellation): Bid
    {
        $this->appellation = $appellation;
        return $this;
    }

    /**
     * @return string
     */
    public function getWineType(): string
    {
        return $this->wineType;
    }

    /**
     * @param string $wineType
     * @return Bid
     */
    public function setWineType(string $wineType): Bid
    {
        $this->wineType = $wineType;
        return $this;
    }

    /**
     * @return int
     */
    public function getBidAmount(): int
    {
        return $this->bidAmount;
    }

    /**
     * @param int $bidAmount
     * @return Bid
     */
    public function setBidAmount(int $bidAmount): Bid
    {
        $this->bidAmount = number_format($bidAmount, 2, '.', ',');
        return $this;
    }

    /**
     * @return int
     */
    public function getBidAmountLocal(): int
    {
        return $this->bidAmountLocal;
    }

    /**
     * @param int $bidAmountLocal
     * @return Bid
     */
    public function setBidAmountLocal(int $bidAmountLocal): Bid
    {
        $this->bidAmountLocal = number_format($bidAmountLocal, 2, '.', ',');
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencySymbolLocal(): string
    {
        return $this->currencySymbolLocal;
    }

    /**
     * @param string $currencySymbolLocal
     * @return Bid
     */
    public function setCurrencySymbolLocal(string $currencySymbolLocal): Bid
    {
        $this->currencySymbolLocal = $currencySymbolLocal;
        return $this;
    }

    /**
     * @return string
     */
    public function getResult(): string
    {
        return $this->result;
    }

		// public function buildResult(string $result): Bid{
		// 	$auction_no = $this->auctionNoSuffix;
		// 	$year = $this->saleDate->date;
		// 	$lot_id = $this->lotId;
		//
		//
		// }
    /**
     * @param string $result
     * @return Bid
     */
    public function setResult(string $result): Bid
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @return int
     */
    public function getWinningBid(): int
    {
        return $this->winningBid;
    }

    /**
     * @param int $winningBid
     * @return Bid
     */
    public function setWinningBid(int $winningBid): Bid
    {
        $this->winningBid = $winningBid;
        return $this;
    }

    /**
     * @return int
     */
    public function getWinningBidLocal(): int
    {
        return $this->winningBidLocal;
    }

    /**
     * @param int $winningBidLocal
     * @return Bid
     */
    public function setWinningBidLocal(int $winningBidLocal): Bid
    {
        $this->winningBidLocal = $winningBidLocal;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMixedLot(): bool
    {
        return $this->isMixedLot;
    }

    /**
     * @param bool $isMixedLot
     * @return Bid
     */
    public function setIsMixedLot(bool $isMixedLot): Bid
    {
        $this->isMixedLot = $isMixedLot;
        return $this;
    }

    public function toArray() {
        return array(
            'auctionId' => $this->auctionId,
            'auctionNoSuffix' => $this->auctionNoSuffix,
            'saleDate' => $this->saleDate,
						'year' => $this->year,
            'lotId' => $this->lotId,
            'lot' => $this->lot,
            'lotNoDec' => $this->lotNoDec,
            'quantity' => $this->quantity,
            'format' => $this->format,
            'vintage' => $this->vintage,
            'wineName' => $this->wineName,
            'designation' => $this->designation,
            'producer' => $this->producer,
            'appellation' => $this->appellation,
            'wineType' => $this->wineType,
            'bidAmount' => $this->bidAmount,
            'bidAmountLocal' => $this->bidAmountLocal,
            'currencySymbolLocal' => $this->currencySymbolLocal,
            'result' => $this->result,
            'winningBid' => $this->winningBid,
            'winningBidLocal' => $this->winningBidLocal,
            'isMixedLot' => $this->isMixedLot
        );
    }
}
