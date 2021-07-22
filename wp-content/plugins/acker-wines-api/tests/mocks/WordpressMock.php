<?php

namespace AckerWines\Test\Mock;

class WordpressMock
{

    /**
     * WordpressMock constructor.
     */
    public function __construct()
    {
        include_once dirname(__FILE__) . '/wp-functions.php';
    }
}
