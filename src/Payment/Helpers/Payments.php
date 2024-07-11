<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\Helpers;

use Qvickly\Api\Payment\RequestDataObjects\Data;

/**
 * @param int $method
 * @param string $email
 * @param string|null $pno
 * @return Data
 */
function examplePayment(int $method, string $email, string|null $pno = null): Data
{
    return new Data(
        [
            "PaymentData" => [
                "method" => $method,
                "paymentplanid" => "",
                "currency" => "SEK",
                "language" => "sv",
                "country" => "SE",
                "autoactivate" => "0",
                "orderid" => "P" . date("Ymd") . "-" . rand(1000, 9999) . "-" . rand(1000, 9999),
                "logo" => "Logo2.jpg",
                "accepturl" => "https://www.example.com/accept",
                "cancelurl" => "https://www.example.com/cancel",
                "returnmethod" => "POST",
                "callbackurl" => "https://www.example.com/callback",
                "bankid" => "true",
            ],
            "PaymentInfo" => [
                "paymentdate" => date("Y-m-d"),
                "paymentterms" => "14",
                "yourreference" => "Purchaser X",
                "ourreference" => "Seller Y",
                "projectname" => "Project Z",
                "deliverymethod" => "Post",
                "deliveryterms" => "FOB",
                "autocredit" => "false"
            ],
            "Card" => [
                "promptname" => "",
                "recurring" => "",
                "recurringnr" => ""
            ],
            "Customer" => [
                "nr" => "12",
                "pno" => $pno ?? "550101-1018",
                "Billing" => [
                    "firstname" => "Testperson",
                    "lastname" => "Approved",
                    "company" => "Company",
                    "street" => "Teststreet",
                    "street2" => "Street2",
                    "zip" => "12345",
                    "city" => "Testcity",
                    "country" => "SE",
                    "phone" => "0712-345678",
                    "email" => $email
                ],
                "Shipping" => [
                    "firstname" => "Testperson",
                    "lastname" => "Approved",
                    "company" => "Company",
                    "street" => "Teststreet",
                    "street2" => "Shipping Street2",
                    "zip" => "12345",
                    "city" => "Testcity",
                    "country" => "SE",
                    "phone" => "0711-345678"
                ]
            ],
            "Articles" => [
                [
                    "artnr" => "A123",
                    "title" => "Article 1",
                    "quantity" => "2",
                    "aprice" => "1234",
                    "discount" => "0",
                    "withouttax" => "2468",
                    "taxrate" => "25"
                ],
                [
                    "artnr" => "B456",
                    "title" => "Article 2",
                    "quantity" => "3.5",
                    "aprice" => "56780",
                    "discount" => "10",
                    "withouttax" => "178857",
                    "taxrate" => "25"
                ]
            ],
            "Cart" => [
                "Handling" => [
                    "withouttax" => "1000",
                    "taxrate" => "25"
                ],
                "Shipping" => [
                    "withouttax" => "3000",
                    "taxrate" => "25"
                ],
                "Total" => [
                    "withouttax" => "185325",
                    "tax" => "46331",
                    "rounding" => "44",
                    "withtax" => "231700"
                ]
            ]
        ]
    );
}

function exampleCheckout()
{
    return new Data(
        [
            "CheckoutData" => [
                "terms" => "http://qvickly.io/terms",
                "privacyPolicy" => "http://qvickly.io/privacy-policy",
                "companyview" => "false",
                "showPhoneOnDelivery" => "false",
                "redirectOnSuccess" => "true"
            ],
            "PaymentData" => [
                "currency" => "SEK",
                "language" => "sv",
                "country" => "SE",
                "autoactivate" => "0",
                "orderid" => "P123456789-" . date("Ymd") . "-" . rand(1000, 9999),
                "logo" => "Logo2.jpg",
                "accepturl" => "https://www.example.com/completedpayment",
                "cancelurl" => "https://www.example.com/failedpayment",
                "returnmethod" => "GET",
                "callbackurl" => "https://www.example.com/callback.php"
            ],
            "PaymentInfo" => [
                "paymentdate" => date('Y-m-d'),
                "yourreference" => "Purchaser X",
                "ourreference" => "Seller Y",
                "projectname" => "Project Z",
                "deliverymethod" => "Post",
                "deliveryterms" => "FOB",
                "autocredit" => "false"
            ],
            "Articles" => [
                [
                    "artnr" => "A123",
                    "title" => "Article 1",
                    "quantity" => "2",
                    "aprice" => "1234",
                    "discount" => "0",
                    "withouttax" => "2468",
                    "taxrate" => "25"
                ],
                [
                    "artnr" => "B456",
                    "title" => "Article 2",
                    "quantity" => "3.5",
                    "aprice" => "56780",
                    "discount" => "10",
                    "withouttax" => "178857",
                    "taxrate" => "25"
                ]
            ],
            "Cart" => [
                "Handling" => [
                    "withouttax" => "0",
                    "taxrate" => "25"
                ],
                "Shipping" => [
                    "withouttax" => "3000",
                    "taxrate" => "25"
                ],
                "Total" => [
                    "withouttax" => "184325",
                    "tax" => "46081",
                    "rounding" => "-6",
                    "withtax" => "230400"
                ]
            ]
        ]
    );
}