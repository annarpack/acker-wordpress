<?php


namespace AckerWines\Api;


class Lot
{
    // Private Fields
    /**
     * @var int
     * Paddle ID
     * Unique ID of customer
     */
    private $paddle_id = 0;
    /**
     * @var string
     * Lot Number
     * Lot identifier
     */
    private $lot_number = '';
    /**
     * @var float
     * Lot Number Sequence
     * use to sort lots, as Lot is varchar
     */
    private $lot_number_seq = 0.00;
    /**
     * @var int
     * Sequence
     * Used for 2nd sort after Lot Number Sequence
     */
    private $seq = 0;
    /**
     * @var float
     * Winning bid in currency for auction
     */
    private $winning_bid = 0.00;
    /**
     * @var bool
     * Is OWC
     * Indicator for OWC, OCB or OGB packaging
     */
    private $is_owc = false;
    /**
     * @var string
     * Bottles
     * Quantity / Size concatenated & pluralized as needed
     */
    private $bottles = '';
    /**
     * @var string
     * Vintage
     */
    private $vintage = '';
    /**
     * @var string
     * Wine Name
     */
    private $wine_name = '';
    /**
     * @var string
     * Producer Location
     */
    private $producer_location = '';
    /**
     * @var string
     * Currency Symbol
     * ISO currency symbol for auction currency
     */
    private $currency_symbol = '';
    /**
     * @var int
     * Quantity
     */
    private $quantity = 0;
    /**
     * @var string
     * Format
     * Type of bottle
     */
    private $format = '';
    /**
     * @var string
     * Bottle Code
     * Code for Type of Bottle
     */
    private $bottle_code = '';
    /**
     * @var string
     * Appellation
     * Wine Appellation
     */
    private $appelation = '';
    /**
     * @var string
     * Wine Designation
     */
    private $designation = '';
    /**
     * @var string
     * Producer
     * Wine Producer
     */
    private $producer = '';
    /**
     * @var string
     * Region Description
     * Region description related to wine type
     */
    private $region_description = '';
    /**
     * @var string
     * Region Code
     * Type of Wine
     */
    private $region_code = '';
    /**
     * @var int
     * Contact ID
     * Contact ID from Auction Prog
     */
    private $contact_id = 0;

    // Getters and Setters
    /**
     * @return int
     */
    public function getPaddleId(): int
    {
        return $this->paddle_id;
    }

    /**
     * @param int $paddle_id
     * @return Lot
     */
    public function setPaddleId(int $paddle_id): Lot
    {
        $this->paddle_id = $paddle_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getLotNumber(): string
    {
        return $this->lot_number;
    }

    /**
     * @param string $lot_number
     * @return Lot
     */
    public function setLotNumber(string $lot_number): Lot
    {
        $this->lot_number = $lot_number;
        return $this;
    }

    /**
     * @return float
     */
    public function getLotNumberSeq(): float
    {
        return $this->lot_number_seq;
    }

    /**
     * @param float $lot_number_seq
     * @return Lot
     */
    public function setLotNumberSeq(float $lot_number_seq): Lot
    {
        $this->lot_number_seq = $lot_number_seq;
        return $this;
    }

    /**
     * @return int
     */
    public function getSeq(): int
    {
        return $this->seq;
    }

    /**
     * @param int $seq
     * @return Lot
     */
    public function setSeq(int $seq): Lot
    {
        $this->seq = $seq;
        return $this;
    }

    /**
     * @return float
     */
    public function getWinningBid(): float
    {
        return $this->winning_bid;
    }

    /**
     * @param float $winning_bid
     * @return Lot
     */
    public function setWinningBid(float $winning_bid): Lot
    {
        $this->winning_bid = $winning_bid;
        return $this;
    }

    /**
     * @return bool
     */
    public function isIsOwc(): bool
    {
        return $this->is_owc;
    }

    /**
     * @param bool $is_owc
     * @return Lot
     */
    public function setIsOwc(bool $is_owc): Lot
    {
        $this->is_owc = $is_owc;
        return $this;
    }

    /**
     * @return string
     */
    public function getBottles(): string
    {
        return $this->bottles;
    }

    /**
     * @param string $bottles
     * @return Lot
     */
    public function setBottles(string $bottles): Lot
    {
        $this->bottles = $bottles;
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
     * @return Lot
     */
    public function setVintage(string $vintage): Lot
    {
        $this->vintage = $vintage;
        return $this;
    }

    /**
     * @return string
     */
    public function getWineName(): string
    {
        return $this->wine_name;
    }

    /**
     * @param string $wine_name
     * @return Lot
     */
    public function setWineName(string $wine_name): Lot
    {
        $this->wine_name = $wine_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getProducerLocation(): string
    {
        return $this->producer_location;
    }

    /**
     * @param string $producer_location
     * @return Lot
     */
    public function setProducerLocation(string $producer_location): Lot
    {
        $this->producer_location = $producer_location;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencySymbol(): string
    {
        return $this->currency_symbol;
    }

    /**
     * @param string $currency_symbol
     * @return Lot
     */
    public function setCurrencySymbol(string $currency_symbol): Lot
    {
        $this->currency_symbol = $currency_symbol;
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
     * @return Lot
     */
    public function setQuantity(int $quantity): Lot
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
     * @return Lot
     */
    public function setFormat(string $format): Lot
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return string
     */
    public function getBottleCode(): string
    {
        return $this->bottle_code;
    }

    /**
     * @param string $bottle_code
     * @return Lot
     */
    public function setBottleCode(string $bottle_code): Lot
    {
        $this->bottle_code = $bottle_code;
        return $this;
    }

    /**
     * @return string
     */
    public function getAppelation(): string
    {
        return $this->appelation;
    }

    /**
     * @param string $appelation
     * @return Lot
     */
    public function setAppelation(string $appelation): Lot
    {
        $this->appelation = $appelation;
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
     * @return Lot
     */
    public function setDesignation(string $designation): Lot
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
     * @return Lot
     */
    public function setProducer(string $producer): Lot
    {
        $this->producer = $producer;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegionDescription(): string
    {
        return $this->region_description;
    }

    /**
     * @param string $region_description
     * @return Lot
     */
    public function setRegionDescription(string $region_description): Lot
    {
        $this->region_description = $region_description;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegionCode(): string
    {
        return $this->region_code;
    }

    /**
     * @param string $region_code
     * @return Lot
     */
    public function setRegionCode(string $region_code): Lot
    {
        $this->region_code = $region_code;
        return $this;
    }

    /**
     * @return int
     */
    public function getContactId(): int
    {
        return $this->contact_id;
    }

    /**
     * @param int $contact_id
     * @return Lot
     */
    public function setContactId(int $contact_id): Lot
    {
        $this->contact_id = $contact_id;
        return $this;
    }

    public function toArray() {
        return array(
            'paddleId' => $this->paddle_id,
            'lotNumber' => $this->lot_number,
            'quantity' => $this->quantity,
            'format' => $this->format,
            'winningBid' => $this->winning_bid,
            'isOWC' => $this->is_owc,
            'vintage' => $this->vintage,
            'wineName' => $this->wine_name,
            'producer' => $this->producer,
            'region_code' => $this->region_code,
            'region_description' => $this->region_description,
            'designation' => $this->designation,
            'appellation' => $this->appelation,
            'currencySymbol' => $this->currency_symbol,
        );
    }
}
