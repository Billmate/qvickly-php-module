<?php
declare(strict_types=1);

namespace Payment\RequestDataObjects;

use Qvickly\Api\Payment\RequestDataObjects\Credentials;
use PHPUnit\Framework\TestCase;

class CredentialsTest extends TestCase
{
    public function testCredentials()
    {
        $credentials = new Credentials([
            'id' => 123456,
            'hash' => '123']);
        $this->assertEquals($credentials->validate(), true);
    }

    public function testSettingDefaultValues()
    {
        $credentials = new Credentials();
        $credentials->id = 123456;
        $credentials->hash = '123';
        $credentials->setDefaultValues();
        $this->assertEquals($credentials->client, 'Qvickly');
    }
}
