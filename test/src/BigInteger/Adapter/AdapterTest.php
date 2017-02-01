<?php


namespace Alvtek\OpenIdConnectTest\BigInteger\Adapter;

use Alvtek\OpenIdConnect\BigInteger;
use Alvtek\OpenIdConnect\BigInteger\Adapter\GMPAdapter;
use Alvtek\OpenIdConnect\BigInteger\Adapter\BCMathAdapter;
use Alvtek\OpenIdConnect\BigInteger\AdapterInterface;
use PHPUnit\Framework\TestCase;

class AdapterTest extends TestCase
{
    /** @var AdapterInterface[] */
    protected $adapters;
    
    public function setup()
    {
        $this->adapters = [new GMPAdapter, new BCMathAdapter()];
    }
    
    public function testSmallNumberConversion()
    {
        $bigInt = $this->getMockBuilder(BigInteger::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $bigInt->expects($this->any())
            ->method('toBytes')
            ->willReturn(file_get_contents(TEST_ASSETS . DIRECTORY_SEPARATOR . 'byte_stream_small'));
        
        foreach ($this->adapters as $adapter) {
            $this->assertEquals('a1efde', $adapter->toHex($bigInt));
            $this->assertEquals('10612702', $adapter->toDecimal($bigInt));
        }
    }
    
    public function testMediumNumberConversion()
    {
        $bigInt = $this->getMockBuilder(BigInteger::class)
            ->disableOriginalConstructor()
            ->getMock();
    
        $bigInt->expects($this->any())
            ->method('toBytes')
            ->willReturn(file_get_contents(TEST_ASSETS . DIRECTORY_SEPARATOR . 'byte_stream_medium'));
        
        foreach ($this->adapters as $adapter) {
            $this->assertEquals('ae1fffcda2e3af62513abbc413ac359931aabcdda41950a52512512a', $adapter->toHex($bigInt));
        }
    }
}
