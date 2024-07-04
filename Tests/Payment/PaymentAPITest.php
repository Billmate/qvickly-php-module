<?php
declare(strict_types=1);

namespace Payment;

require __DIR__ . '/../../vendor/autoload.php';


use PHPUnit\Framework\TestCase;
use Qvickly\Api\Payment;
use Qvickly\Api\Enums\ReturnDataType;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

class PaymentAPITest extends TestCase
{
    public function testObject()
    {
        $this->assertIsObject(new Payment\PaymentAPI($_ENV['EID'], $_ENV['SECRET']));
    }

    public function testWithoutCredentials()
    {
        $api = new Payment\PaymentAPI("", "");
        $response = $api->getAddress([ "pno" => "550101-1018", "country" => "SE" ]);
        $this->assertArrayHasKey('code', $response);
        $this->assertEquals(9011, $response['code']);
    }
    public function testGetAddress()
    {
        $api = new Payment\PaymentAPI($_ENV['EID'], $_ENV['SECRET'], onlyReturnData: false);
        $response = $api->getAddress([ "pno" => "550101-1018", "country" => "SE" ]);
        $this->assertIsArray($response);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('firstname', $response['data']);
        $this->assertIsString($response['data']['firstname']);
        $this->assertEquals('Testperson', $response['data']['firstname']);
    }

    public function testGetAddressDataResponse()
    {
        $api = new Payment\PaymentAPI($_ENV['EID'], $_ENV['SECRET'], onlyReturnData: true, returnData: ReturnDataType::OBJECT);
        $response = $api->getAddress([ "pno" => "550101-1018", "country" => "SE" ]);
        $this->assertIsArray($response);
        $this->assertEquals('Testperson', $response['firstname']);
    }

    public function testGetAddressRawResponse()
    {
        $api = new Payment\PaymentAPI($_ENV['EID'], $_ENV['SECRET'], onlyReturnData: true, returnData: ReturnDataType::RAW);
        $response = $api->getAddress([ "pno" => "550101-1018", "country" => "SE" ]);
        $this->assertIsObject($response);
        $this->assertEquals('Testperson', $response->firstname);
    }
}
