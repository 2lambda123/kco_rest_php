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
 * Distribute link to the HPP session.
 */

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';

// X-Example: POST /hpp/v1/sessions/{session_id}/distribution

/**
 * Follow the link to get your credentials: https://github.com/klarna/kco_rest_php/#api-credentials
 *
 * Make sure that your credentials belong to the right endpoint. If you have credentials for the US Playground,
 * such credentials will not work for the EU Playground and you will get 401 Unauthorized exception.
 */
$merchantId = getenv('USERNAME') ?: 'K123456_abcd12345';
$sharedSecret = getenv('PASSWORD') ?: 'sharedSecret';
$sessionId = getenv('SESSION_ID') ?: 'sessionId';

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
    $data = [
        "contact_information" => [
            "email" => "test@example.com",
            "phone" => "07000212345",
            "phone_country" => "SE"
        ],
        "method" => "sms",
        "template" => "INSTORE_PURCHASE"
    ];

    $session = new Klarna\Rest\HostedPaymentPage\Sessions($connector, $sessionId);
    $session->distributeLink($data);

    echo 'The session link has been distributed';
} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}

// /X-Example: POST /hpp/v1/sessions/{session_id}/distribution
