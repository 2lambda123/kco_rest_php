<?php
/**
 * Copyright 2019 Klarna AB
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Create a checkout order.
 */

require_once dirname(__DIR__) . '/../../vendor/autoload.php';

// X-Example: POST /checkout/v3/orders

/**
 * Follow the link to get your credentials: https://github.com/klarna/kco_rest_php/#api-credentials
 *
 * Make sure that your credentials belong to the right endpoint. If you have credentials for the US Playground,
 * such credentials will not work for the EU Playground and you will get 401 Unauthorized exception.
 */
$merchantId = getenv('USERNAME') ?: 'K123456_abcd12345';
$sharedSecret = getenv('PASSWORD') ?: 'sharedSecret';

/*
EU_BASE_URL = 'https://api.klarna.com'
EU_TEST_BASE_URL = 'https://api.playground.klarna.com'
NA_BASE_URL = 'https://api-na.klarna.com'
NA_TEST_BASE_URL = 'https://api-na.playground.klarna.com'
*/
$apiEndpoint = Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL;

$connector = Klarna\Rest\Transport\GuzzleConnector::create(
    $merchantId,
    $sharedSecret,
    $apiEndpoint
);

$order = [
    "purchase_country" => "gb",
    "purchase_currency" => "gbp",
    "locale" => "en-gb",
    "order_amount" => 10000,
    "order_tax_amount" => 2000,
    "order_lines" => [
        [
            "type" => "physical",
            "reference" => "123050",
            "name" => "Tomatoes",
            "quantity" => 10,
            "quantity_unit" => "kg",
            "unit_price" => 600,
            "tax_rate" => 2500,
            "total_amount" => 6000,
            "total_tax_amount" => 1200
        ],
        [
            "type" => "physical",
            "reference" => "543670",
            "name" => "Bananas",
            "quantity" => 1,
            "quantity_unit" => "bag",
            "unit_price" => 5000,
            "tax_rate" => 2500,
            "total_amount" => 4000,
            "total_discount_amount" => 1000,
            "total_tax_amount" => 800
        ]
    ],
    "merchant_urls" => [
        "terms" => "https://www.example.com/terms.html",
        "cancellation_terms" => "https://www.example.com/terms/cancellation.html",
        "checkout" => "https://www.example.com/checkout.html",
        "confirmation" => "https://www.example.com/confirmation.html",

        // Callbacks
        "push" => "https://www.example.com/api/push",
        "validation" => "https://www.example.com/api/validation",
        "shipping_option_update" => "https://www.example.com/api/shipment",
        "address_update" => "https://www.example.com/api/address",
        "notification" => "https://www.example.com/api/pending",
        "country_change" => "https://www.example.com/api/country"
    ]
];

try {
    $checkout = new Klarna\Rest\Checkout\Order($connector);
    $checkout->create($order);

    // Store checkout order id
    $orderId = $checkout->getId();

    // Get some data if needed
    echo <<<ORDER
         OrderID: $checkout[order_id]
    Order status: $checkout[status]
    HTML snippet: $checkout[html_snippet]
ORDER;
} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}

// /X-Example: POST /checkout/v3/orders
