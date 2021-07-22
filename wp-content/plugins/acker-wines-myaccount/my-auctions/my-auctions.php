<?php

const AW_MY_AUCTIONS_ENDPOINT = 'my-auctions';

function aw_my_auctions_init()
{
    aw_shared_plugin_init();
    add_rewrite_endpoint(AW_MY_AUCTIONS_ENDPOINT, EP_ROOT | EP_PAGES);
    wp_enqueue_script('aw-jquery-ui-js');
    wp_enqueue_script('aw-jquery-dataTables-js');
    wp_enqueue_style('aw-jquery-ui-css-1.12.1');
    wp_enqueue_style('aw-jquery-dataTables-css');
    wp_enqueue_script('aw-what-input');
    wp_enqueue_script('aw-ajax-common');

    global $aw_shared_plugin_path;
    wp_register_style('aw-multistep-css', $aw_shared_plugin_path . 'breadcrumbs-and-multistep-indicator/css/style.css');
    wp_enqueue_style('aw-multistep-css');

    require_once( AW_ACCOUNT_DIR . 'my-auctions/my-auctions-api.php');
}
add_action('init', 'aw_my_auctions_init');

function aw_my_auctions_enqueue_scripts() {
    wp_enqueue_script('my-auctions-ajax', get_template_directory_uri() . '');
}
add_action('wp_enqueue_scripts', 'aw_my_auctions_enqueue_scripts');

function aw_my_auctions_content()
{

    ?>
    <div class="row">
        <div class="columns small-12">

            <div class="panel">
                <h4>Get a Free Appraisal Now!</h4>
                It's easy to get a free appraisal. Just click start and upload your wine list.
                <p><a href="#" class="button large success">Start</a></p>
            </div>
            <div>
                <h4>Appraisal Tracker</h4>
                <p>
                    Thanks for submitting your wine for appraisal. You can always check here to find the status of your
                    appraisal.
                </p>
                <nav>
                    <ol class="cd-breadcrumb triangle">
                        <li class="current"><a href="#0">Uploaded</a></li>
                        <li><a href="#0">Received</a></li>
                        <li><a href="#0">Appraisal Complete</a></li>
                    </ol>
                </nav>
            </div>

        </div>
    </div>

    <div class="row expanded">

        <div class="columns small-12">
            <ul class="accordion" data-accordion>
                <li class="accordion-navigation">
                    <a href="#option1">#1 Table with side nav menu</a>
                    <div id="option1" class="content active">
                        <div class="row expanded">
                            <div class="columns small-6 medium-10">
                                <div class="ui segment">
                                    <!--<table class="ui sortable selectable very compact table">
                                        <thead>
                                        <tr>
                                            <th>Sale #</th>
                                            <th>Sale Date</th>
                                            <th>Lot #</th>
                                            <th>Bid Amt (USD)</th>
                                            <th>Outbid</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>195</td>
                                            <td>June 19, 2019</td>
                                            <td>7</td>
                                            <td>$100.00</td>
                                            <td>Yes</td>
                                        </tr>
                                        <tr>
                                            <td>196A</td>
                                            <td>June 30, 2019</td>
                                            <td>81</td>
                                            <td>$3,500.00</td>
                                            <td>No</td>
                                        </tr>
                                        <tr>
                                            <td>196A</td>
                                            <td>June 30, 2019</td>
                                            <td>763</td>
                                            <td>$500.00</td>
                                            <td>No</td>
                                        </tr>
                                        <tr>
                                            <td>196A</td>
                                            <td>June 30, 2019</td>
                                            <td>1015</td>
                                            <td>$124,000.00</td>
                                            <td>No</td>
                                        </tr>
                                        <tr>
                                            <td>196A</td>
                                            <td>June 30, 2019</td>
                                            <td>707</td>
                                            <td>$16,600.00</td>
                                            <td>No</td>
                                        </tr>
                                        <tr>
                                            <td>196A</td>
                                            <td>June 30, 2019</td>
                                            <td>1319</td>
                                            <td>$15,200.00</td>
                                            <td>No</td>
                                        </tr>
                                        <tr>
                                            <td>197W</td>
                                            <td>July 1-14, 2019</td>
                                            <td>6003</td>
                                            <td>$100.00</td>
                                            <td>No</td>
                                        </tr>
                                        <tr>
                                            <td>197W</td>
                                            <td>July 1-14, 2019</td>
                                            <td>6987</td>
                                            <td>$100.00</td>
                                            <td>No</td>
                                        </tr>
                                        <tr>
                                            <td>197W</td>
                                            <td>July 1-14, 2019</td>
                                            <td>8911</td>
                                            <td>$100.00</td>
                                            <td>No</td>
                                        </tr>
                                        </tbody>
                                    </table>-->

                                    <table id="auctions-table" class="ui sortable selectable very compact table" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Paddle ID</th>
                                                <th>Paddle #</th>
                                                <th>Auction #</th>
                                                <th>Auction ID</th>
                                                <th>Auction Date</th>
                                                <th>Sale Date</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <div class="columns small-6 medium-2">
                                <ul class="vertical menu">
                                    <li>
                                        <span class="strong">Bids</span>
                                        <ul class="nested vertical menu">
                                            <li class="active"><a href="#">Active bids</a></li>
                                            <li><a href="#">Bid history</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <span class="strong">Sold</span>
                                        <ul class="nested vertical menu">
                                            <li><a href="#">My Sold Wines</a></li>
                                            <li><a href="#">Appraisals</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </li>
                <li class="accordion-navigation">
                    <a href="#option2">#2 Table with tab navigation with sub nav</a>
                    <div id="option2" class="content">

                        <ul class="tabs" data-tab>
                            <li class="tab-title active"><a href="#panel1">Bids</a></li>
                            <li class="tab-title"><a href="#panel2">Sold</a></li>
                            <li class="tab-title"><a href="#panel3">Documents</a></li>
                            <li class="tab-title"><a href="#panel4">Support</a></li>
                        </ul>
                        <div class="tabs-content">
                            <div class="content active" id="panel1">
                                <div class="panel">
                                    <dl class="sub-nav">
                                        <dd class="active"><a href="#">Active bids</a></dd>
                                        <dd><a href="#">Bid history</a></dd>
                                    </dl>
                                </div>
                                <div class="columns small-6 medium-12">
                                    <div class="ui segment">
                                        <table class="ui sortable selectable very compact table">
                                            <thead>
                                            <tr>
                                                <th>Sale #</th>
                                                <th>Sale Date</th>
                                                <th>Lot #</th>
                                                <th>Bid Amt (USD)</th>
                                                <th>Outbid</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>195</td>
                                                <td>June 19, 2019</td>
                                                <td>7</td>
                                                <td>$100.00</td>
                                                <td>Yes</td>
                                            </tr>
                                            <tr>
                                                <td>196A</td>
                                                <td>June 30, 2019</td>
                                                <td>81</td>
                                                <td>$3,500.00</td>
                                                <td>No</td>
                                            </tr>
                                            <tr>
                                                <td>196A</td>
                                                <td>June 30, 2019</td>
                                                <td>763</td>
                                                <td>$500.00</td>
                                                <td>No</td>
                                            </tr>
                                            <tr>
                                                <td>196A</td>
                                                <td>June 30, 2019</td>
                                                <td>1015</td>
                                                <td>$124,000.00</td>
                                                <td>No</td>
                                            </tr>
                                            <tr>
                                                <td>196A</td>
                                                <td>June 30, 2019</td>
                                                <td>707</td>
                                                <td>$16,600.00</td>
                                                <td>No</td>
                                            </tr>
                                            <tr>
                                                <td>196A</td>
                                                <td>June 30, 2019</td>
                                                <td>1319</td>
                                                <td>$15,200.00</td>
                                                <td>No</td>
                                            </tr>
                                            <tr>
                                                <td>197W</td>
                                                <td>July 1-14, 2019</td>
                                                <td>6003</td>
                                                <td>$100.00</td>
                                                <td>No</td>
                                            </tr>
                                            <tr>
                                                <td>197W</td>
                                                <td>July 1-14, 2019</td>
                                                <td>6987</td>
                                                <td>$100.00</td>
                                                <td>No</td>
                                            </tr>
                                            <tr>
                                                <td>197W</td>
                                                <td>July 1-14, 2019</td>
                                                <td>8911</td>
                                                <td>$100.00</td>
                                                <td>No</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="content" id="panel2">
                                <div class="panel">
                                    <dl class="sub-nav">
                                        <dd class="active"><a href="#">My Sold Wines</a></dd>
                                        <dd><a href="#">Appraisals</a></dd>
                                    </dl>
                                </div>
                                <div class="columns small-6 medium-12">
                                    <ul class="accordion" data-accordion>
                                        <li class="accordion-navigation">
                                            <a href="#panel1a">2019</a>
                                            <div id="panel1a" class="content active">
                                                Panel 1. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                                                do
                                                eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
                                                minim
                                                veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                                                commodo consequat.
                                            </div>
                                        </li>
                                        <li class="accordion-navigation">
                                            <a href="#panel2a">2018</a>
                                            <div id="panel2a" class="content">
                                                Panel 2. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                                                do
                                                eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
                                                minim
                                                veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                                                commodo consequat.
                                            </div>
                                        </li>
                                        <li class="accordion-navigation">
                                            <a href="#panel3a">2017</a>
                                            <div id="panel3a" class="content">
                                                Panel 3. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                                                do
                                                eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
                                                minim
                                                veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                                                commodo consequat.
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="content" id="panel3">
                                <p>This is the third panel of the basic tab example. This is the third panel of the
                                    basic
                                    tab example.</p>
                            </div>
                            <div class="content" id="panel4">
                                <p>This is the fourth panel of the basic tab example. This is the fourth panel of the
                                    basic
                                    tab example.</p>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>

        </div>

        <!-- tabular menu
        <div class="ui top attached tabular menu">
            <a class="active item" data-tab="bids">Bids</a>
            <a class="item" data-tab="sold">Sold</a>
        </div>
        <div class="ui bottom attached active tab segment" data-tab="bids">
            <div class="ui grid">
                <div class="four wide column">
                    <div class="ui vertical menu">
                        <div class="item">
                            <div class="header">Bids</div>
                            <div class="menu">
                                <a class="item">Enterprise</a>
                                <a class="item">Consumer</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="twelve wide stretched column">
                    <div class="ui segment">
                        <table class="ui sortable selectable very compact table">
                            <thead>
                            <tr>
                                <th>Sale #</th>
                                <th>Sale Date</th>
                                <th>Lot #</th>
                                <th>Bid Amt (USD)</th>
                                <th>Outbid</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>195</td>
                                <td>June 19, 2019</td>
                                <td>7</td>
                                <td>$100.00</td>
                                <td>Yes</td>
                            </tr>
                            <tr>
                                <td>196A</td>
                                <td>June 30, 2019</td>
                                <td>81</td>
                                <td>$3,500.00</td>
                                <td>No</td>
                            </tr>
                            <tr>
                                <td>196A</td>
                                <td>June 30, 2019</td>
                                <td>763</td>
                                <td>$500.00</td>
                                <td>No</td>
                            </tr>
                            <tr>
                                <td>196A</td>
                                <td>June 30, 2019</td>
                                <td>1015</td>
                                <td>$124,000.00</td>
                                <td>No</td>
                            </tr>
                            <tr>
                                <td>196A</td>
                                <td>June 30, 2019</td>
                                <td>707</td>
                                <td>$16,600.00</td>
                                <td>No</td>
                            </tr>
                            <tr>
                                <td>196A</td>
                                <td>June 30, 2019</td>
                                <td>1319</td>
                                <td>$15,200.00</td>
                                <td>No</td>
                            </tr>
                            <tr>
                                <td>197W</td>
                                <td>July 1-14, 2019</td>
                                <td>6003</td>
                                <td>$100.00</td>
                                <td>No</td>
                            </tr>
                            <tr>
                                <td>197W</td>
                                <td>July 1-14, 2019</td>
                                <td>6987</td>
                                <td>$100.00</td>
                                <td>No</td>
                            </tr>
                            <tr>
                                <td>197W</td>
                                <td>July 1-14, 2019</td>
                                <td>8911</td>
                                <td>$100.00</td>
                                <td>No</td>js/app.js
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="ui bottom attached tab segment" data-tab="sold">
            <div class="ui grid">
                <div class="four wide column">
                    <div class="ui vertical fluid tabular menu">
                        <a class="active item">
                            My Sold Wine
                        </a>
                        <a class="item">
                            Appraisals
                        </a>
                    </div>
                </div>
                <div class="twelve wide stretched column">
                    <div class="ui segment">
                        <table class="ui very compact table">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Notes</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>John</td>
                                <td>Approved</td>
                                <td>None</td>
                            </tr>
                            <tr>
                                <td>Jamie</td>
                                <td>Approved</td>
                                <td>Requires call</td>
                            </tr>
                            <tr>
                                <td>Jill</td>
                                <td>Denied</td>
                                <td>None</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> --end tabular menu -->

        <div id="spinner"></div>
        
        <script>
            const $ = jQuery;
            
            $(document).ready(function ($) {

                $(document).foundation();

                let spinDiv = document.getElementById('spinner');

                spinDiv.style.display = 'block';

                acker.api.getProgInvoiceHeaders(function (response, success, xhr) {
                    console.log("got the auction data " + JSON.stringify(response));

                    $("#auctions-table").DataTable( {
                        "data": response.data,
                        "columns": [
                            {"data": "paddleId"},
                            {"data": "paddleNumber"},
                            {"data": "auctionNumber"},
                            {"data": "auctionId"},
                            {"data": "auctionDate.date"},
                            {"data": "saleDate.date"},
                            {"data": "totalAmount"}
                        ]
                    });

                    spinDiv.style.display = 'none';
                });

            });
        </script>
        <style>
            .menu .active > a {
                background: #ae374b;
                color: #fefefe;
            }
            #spinner {
                display: none;
                width: 40px;
                height: 40px;
                position: absolute;
                top: 50%;
                left: 50%;
                margin-top: -20px;
                margin-left: -20px;
                border: solid 3px;
                border-color: #555 #fff;
                -webkit-border-radius: 1000px;
                border-radius: 1000px;
                -webkit-animation-name: rotate;
                -webkit-animation-duration: 1.5s;
                -webkit-animation-iteration-count: infinite;
                -webkit-animation-timing-function: linear;
                -moz-animation-name: rotate;
                -moz-animation-duration: 1.5s;
                -moz-animation-iteration-count: infinite;
                -moz-animation-timing-function: linear;
                -o-animation-name: rotate;
                -o-animation-duration: 1.5s;
                -o-animation-iteration-count: infinite;
                -o-animation-timing-function: linear;
                animation-name: rotate;
                animation-duration: 1.5s;
                animation-iteration-count: infinite;
                animation-timing-function: linear;
            }
        </style>
    <?php
}

add_action('woocommerce_account_' . AW_MY_AUCTIONS_ENDPOINT . '_endpoint', 'aw_my_auctions_content');
