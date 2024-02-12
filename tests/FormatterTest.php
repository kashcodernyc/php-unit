<?php
namespace TDD\Test;
require __DIR__ . '/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use TDD\Formatter;

class FormatterTest extends TestCase {
    private $Formatter;
    public function setUp(): void{
        $this->Formatter = new Formatter();
    }

    public function tearDown(): void{
        unset($this->Formatter);
    }
    /**
     * @dataProvider provideCurrencyAmt
     */

     public function testCurrencyAmt($input, $expected, $msg){
        $this->assertSame(
            $expected,
            $this->Formatter->currencyAmt($input),
            $msg
        );
    }

    public function provideCurrencyAmt(){
        return [
            [1,1.00, '1 should be transformed into 1.00'],
            [1.1, 1.10, '1.1 should be transformed into 1.10'],
            [1.11, 1.11, '1.11 should stay as 1.11'],
            [1.111, 1.11, '1.111 should be transformed into 1.11']
        ];
    }
}