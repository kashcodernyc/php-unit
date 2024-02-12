<?php
namespace TDD\Test;
require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use TDD\Receipt;

class ReceiptTest extends TestCase{
    private $Receipt;
    public function setUp(): void{
        $this->Formatter = $this->getMockBuilder('TDD\Formatter')
            ->onlyMethods(['currencyAmt'])
            ->getMock();
        $this->Formatter->expects($this->any())
            ->method('currencyAmt')
            ->with($this->anything())
            ->will($this->returnArgument((0)));
        $this->Receipt = new Receipt($this->Formatter);
    }

    public function tearDown(): void{
        unset($this->Receipt);
    }

    public function provideSubTotal(){
        return [
            [[1,2,5,8], 16],
            [[1,2,5,2], 10],
            [[1,2,8], 11]
        ];
    }
    /**
     * @dataProvider provideSubTotal
     */
    public function testSubTotal($items, $expected){
        $coupon = null;
        $output = $this->Receipt->subtotal($items, $coupon);
        $this->assertEquals(
            $expected,
            $output,
            "the total should equal to {$expected}"
        );
    }

    public function testSubTotalAndCoupon(){
        $input = [0,2,5,8];
        $coupon = 0.20;
        $output = $this->Receipt->subtotal($input, $coupon);
        $this->assertEquals(
            12,
            $output,
            'the total should equal to 12'
        );
    }

    public function testSubTotalException(){
        $input = [0,2,5,8];
        $coupon = 1.20;
        $this->expectException('BadMethodCallException');
        $this->Receipt->subtotal($input, $coupon);
    }


    public function testPostTaxTotal() {
        $items = [1,2,5,8];
        $tax = 0.20;
        $coupon = null;
        $Receipt = $this->getMockBuilder('TDD\Receipt')
            ->onlyMethods(['subtotal', 'tax'])
            ->setConstructorArgs([$this->Formatter])
            ->getMock();
        $Receipt->expects($this->once())
            ->method('subtotal')
            ->with($items, $coupon)
            ->willReturn(10.00);
        $Receipt->expects($this->once())
            ->method('tax')
            ->with(10.00)
            ->willReturn(1.00);
        $result = $Receipt->postTaxTotal([1,2,5,8], null);
        $this->assertEquals(11.00, $result);
    }

    public function testTax(){
        $inputAmount = 10.00;
        $this->Receipt->tax = 0.10;
        $output = $this->Receipt->tax($inputAmount);
        $this->assertEquals(
            1.00,
            $output,
            'The tax calculation should equal 1.00'
        );
    }
}