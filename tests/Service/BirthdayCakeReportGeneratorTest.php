<?php
namespace App\Tests\Service;

use App\Service\BirthdayCakeReportGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BirthdayCakeReportGeneratorTest extends KernelTestCase
{
    public function testCalculate()
    {
        self::bootKernel();
        $container = static::getContainer();
        $BirthdayCakeReportGenerator = $container->get(BirthdayCakeReportGenerator::class);
        $BirthdayCakeReportGenerator->generateBirthdayCakeReport(array('Alex,1995-07-19'));
        $file_path = $BirthdayCakeReportGenerator->getReportPath();
        $this->assertEquals(1, file_exists($file_path));
        $csvFile = file($file_path);
        $data = [];
        foreach ($csvFile as $line) {
            $data[] = str_getcsv($line);
        }
        $this->assertContains('Alex', $data[1]);
        $this->assertContains('2022-07-20', $data[1]);
    }
}