<?php
namespace App\Tests\Util;

use App\Utils\BirthdayCakeCalculator;
use PHPUnit\Framework\TestCase;

class CalculateCakeTest extends TestCase
{
    public function testSingleBirthdayCalculation()
    {
        $calculator = new BirthdayCakeCalculator();
        $calculator->year = 2021;
        $result = $calculator->calculateCakes(array('Alex,1995-07-19'));
        // Check Alex
        $alex_expect_date = '2021-07-20';
        $this->assertEquals($alex_expect_date, $result[$alex_expect_date]['date']);
        // Check the date Alex included in the cake
        $this->assertEquals('Alex', $result[$alex_expect_date]['staff']);
        // Check Alex gets a small cake
        $this->assertEquals('1', $result[$alex_expect_date]['small_cakes']);
        // Check if Alex gets a big cake
        $this->assertEquals('0', $result[$alex_expect_date]['large_cakes']);
    }

    public function testMultipleBirthdayCalculation()
    {
        $calculator = new BirthdayCakeCalculator();
        $calculator->year = 2022;
        $result = $calculator->calculateCakes(
            array('Alex,1995-07-19', 'John,1990-03-01', 'Alice,1945-06-03')
        );
        // Check Alex
        $alex_expect_date = '2022-07-20';
        $this->assertEquals($alex_expect_date, $result[$alex_expect_date]['date']);
        $this->assertEquals('Alex', $result[$alex_expect_date]['staff']);
        $this->assertEquals('1', $result[$alex_expect_date]['small_cakes']);
        $this->assertEquals('0', $result[$alex_expect_date]['large_cakes']);

        // Check John
        $john_expect_date = '2022-03-02';
        $this->assertEquals($john_expect_date, $result[$john_expect_date]['date']);
        $this->assertEquals('John', $result[$john_expect_date]['staff']);
        $this->assertEquals('1', $result[$john_expect_date]['small_cakes']);
        $this->assertEquals('0', $result[$john_expect_date]['large_cakes']);

        // Check Alice
        $alice_expect_date = '2022-06-06';
        $this->assertEquals($alice_expect_date, $result[$alice_expect_date]['date']);
        $this->assertEquals('Alice', $result[$alice_expect_date]['staff']);
        $this->assertEquals('1', $result[$alice_expect_date]['small_cakes']);
        $this->assertEquals('0', $result[$alice_expect_date]['large_cakes']);
    }

    public function testMultipleCombinationBirthdayCalculation()
    {
        $calculator = new BirthdayCakeCalculator();
        $calculator->year = 2022;
        $result = $calculator->calculateCakes(
            array('Alex,1995-04-15', 'Dave,1995-04-15', 'John,1990-04-16')
        );
        $expect_date = '2022-04-19';
        // Check Alex
        $this->assertEquals($expect_date, $result[$expect_date]['date']);
        $this->assertStringContainsString('Alex', $result[$expect_date]['staff']);
        // Check Dave
        $this->assertStringContainsString('Dave', $result[$expect_date]['staff']);
        // Check John
        $this->assertStringContainsString('John', $result[$expect_date]['staff']);
        $this->assertEquals('0', $result[$expect_date]['small_cakes']);
        $this->assertEquals('1', $result[$expect_date]['large_cakes']);
    }

    public function testMultipleCombinationAndCakeFreeDayBirthdayCalculation()
    {
        $calculator = new BirthdayCakeCalculator();
        $calculator->year = 2022;
        $result = $calculator->calculateCakes(
            array('Alex,1995-04-19', 'Dave,1995-04-20', 'John,1990-04-21')
        );
        $dave_alex_expect_date = '2022-04-21';
        // Check Alex and Dave
        $this->assertEquals($dave_alex_expect_date, $result[$dave_alex_expect_date]['date']);
        $this->assertStringContainsString('Alex', $result[$dave_alex_expect_date]['staff']);
        $this->assertStringContainsString('Dave', $result[$dave_alex_expect_date]['staff']);
        $this->assertEquals('0', $result[$dave_alex_expect_date]['small_cakes']);
        $this->assertEquals('1', $result[$dave_alex_expect_date]['large_cakes']);
        // Check John
        $john_expect_date = '2022-04-23';
        $this->assertStringContainsString('John', $result[$john_expect_date]['staff']);
        $this->assertEquals('1', $result[$john_expect_date]['small_cakes']);
        $this->assertEquals('0', $result[$john_expect_date]['large_cakes']);
    }
}