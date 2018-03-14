<?php declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Bling\NotaFiscal\Bling;
use Bling\NotaFiscal\Http\Client;

class BlingTest extends TestCase
{
    public function testInitialization(): void
    {
        $bling = new Bling('test');

        $this->assertInstanceOf(Bling::class, $bling);
    }

    public function testResponseType(): void
    {
        $response = file_get_contents('samples/notafiscal.xml');
        $mockClient = $this->getMockBuilder(Client::class)
                           ->disableOriginalConstructor()
                           ->getMock();
        $mockClient->expects($this->any())
                   ->method('request')
                   ->will($this->returnValue($response));

        $bling = new Bling('test');
        $reflection = new \ReflectionClass($bling);
        $reflectionProperty = $reflection->getProperty('httpClient');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($bling, $mockClient);

        $resp = $bling->getNotaFiscal('18099', '001');

        $this->assertInternalType('array', $resp);
    }
}