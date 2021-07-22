<?php

namespace AckerWines\Test;

use AckerWines\Test\Mock\WordpressMock;
use PHPUnit\Framework\TestCase;

class MyAuctionsTestBase extends TestCase
{
    public $WP_MOCK;

    static function setUpBeforeClass() : void {
        require dirname(__FILE__) . '/mocks/WordpressMock.php';
        require dirname(__FILE__) . '/../my-auctions-api.php';
        require dirname(__FILE__) . '/load-test-classes.php';

        include_once dirname(__FILE__) . '/../../../../../wp-config-dev.php';
    }

    function setUp() : void {
        $this->WP_MOCK = new WordpressMock();
    }

}
