<?php


namespace AckerWines\Api;


class AuctionContact
{
    private $id = 0;
    private $totalBalanceDue = 0.00;
    private $totalBalanceDueLocal = 0.00;
    private $currencyCodeLocal = '';
    private $currencySymbolLocal = '';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return AuctionContact
     */
    public function setId(int $id): AuctionContact
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return float
     */
    public function getTotalBalanceDue(): float
    {
        return $this->totalBalanceDue;
    }

    /**
     * @param float $totalBalanceDue
     * @return AuctionContact
     */
    public function setTotalBalanceDue(float $totalBalanceDue): AuctionContact
    {
        $this->totalBalanceDue = $totalBalanceDue;
        return $this;
    }

    /**
     * @return float
     */
    public function getTotalBalanceDueLocal(): float
    {
        return $this->totalBalanceDueLocal;
    }

    /**
     * @param float $totalBalanceDueLocal
     * @return AuctionContact
     */
    public function setTotalBalanceDueLocal(float $totalBalanceDueLocal): AuctionContact
    {
        $this->totalBalanceDueLocal = $totalBalanceDueLocal;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencyCodeLocal(): string
    {
        return $this->currencyCodeLocal;
    }

    /**
     * @param string $currencyCodeLocal
     * @return AuctionContact
     */
    public function setCurrencyCodeLocal(string $currencyCodeLocal): AuctionContact
    {
        $this->currencyCodeLocal = $currencyCodeLocal;
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
     * @return AuctionContact
     */
    public function setCurrencySymbolLocal(string $currencySymbolLocal): AuctionContact
    {
        $this->currencySymbolLocal = $currencySymbolLocal;
        return $this;
    }

    public function toArray() {
        return array(
            'contactId' => $this->id,
            'totalBalanceDue' => $this->totalBalanceDue,
            'totalBalanceDueLocal' => $this->totalBalanceDueLocal,
            'currencyCodeLocal' => $this->currencyCodeLocal,
            'currencySymbolLocal' => $this->currencySymbolLocal
        );
    }

}
