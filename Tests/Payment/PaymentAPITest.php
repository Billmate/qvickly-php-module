<?php
declare(strict_types=1);

namespace Payment;

require __DIR__ . '/../../vendor/autoload.php';


use PHPUnit\Framework\TestCase;
use Qvickly\Api\Payment;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

class PaymentAPITest extends TestCase
{
    public function testTrue()
    {
        $this->assertTrue(true);
    }

    public function testObject()
    {
        $this->assertIsObject(new Payment\PaymentAPI($_ENV['EID'], $_ENV['SECRET']));
    }

    public function testGetAddress()
    {
        $api = new Payment\PaymentAPI($_ENV['EID'], $_ENV['SECRET'], onlyReturnData: false);
        $response = $api->getAddress([ "pno" => "550101-1018", "country" => "SE" ]);
        $this->assertIsObject($response);
        $this->assertObjectHasProperty('data', $response);
        $this->assertObjectHasProperty('firstname', $response->data);
        $this->assertIsString($response->data->firstname);
        $this->assertEquals('Testperson', $response->data->firstname);
    }
}
