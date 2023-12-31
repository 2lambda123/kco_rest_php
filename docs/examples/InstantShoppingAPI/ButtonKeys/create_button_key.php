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
 * Create a button key based on setup options.
 */

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';

// X-Example: POST /instantshopping/v1/buttons

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

try {
    $buttonsApi = new Klarna\Rest\InstantShopping\ButtonKeys($connector);
    $data = [
        'merchant_name' => 'John Doe',
        'merchant_urls' => [
          'place_order' => 'https://example.com/place-callback',
          'push' => 'https://example.com/push-callback',
          'confirmation' => 'https://example.com/confirmation-callback',
          'terms' => 'https://example.com/terms-callback',
          'notification' => 'https://example.com/notification-callback',
          'update' => 'https://example.com/update-callback',
        ],
        'purchase_currency' => 'EUR',
        'purchase_country' => 'DE',
        'billing_countries' => ["UK", "DE", "SE"],
        'shipping_countries' => ["UK", "DE", "SE"],
        'locale' => 'en-US',
        'order_amount' => 50000,
        'order_tax_amount' => 0,
        'order_lines' => [
          [
            'name' => 'Red T-Shirt',
            'type' => 'physical',
            'reference' => '19-402-USA',
            'quantity' => 5,
            'quantity_unit' => 'pcs',
            'tax_rate' => 0,
            'total_amount' => 50000,
            'total_discount_amount' => 0,
            'total_tax_amount' => 0,
            'unit_price' => 10000,
            'product_url' => 'https://www.estore.com/products/f2a8d7e34',
            'image_url' => 'https://www.exampleobjects.com/logo.png',
            'product_identifiers' =>
            [
              'category_path' => 'Electronics Store > Computers & Tablets > Desktops',
              'global_trade_item_number' => '735858293167',
              'manufacturer_part_number' => 'BOXNUC5CPYH',
              'brand' => 'Intel',
            ],
          ],
        ],
        'shipping_options' => [
          [
            'id' => 'my-shipping-id',
            'name' => 'Pickup Store',
            'description' => 'My custom description',
            'promo' => 'string',
            'price' => 10,
            'tax_amount' => 0,
            'tax_rate' => 0,
            'preselected' => true,
            'shipping_method' => 'PICKUPSTORE',
          ],
        ],
    ];
    $button = $buttonsApi->create($data);

    echo 'Button has been successfully created' . PHP_EOL;
    print_r($button);
} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}

// /X-Example: POST /instantshopping/v1/buttons
