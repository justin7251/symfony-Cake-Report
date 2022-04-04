<?php

namespace App\Service;

use App\Utils\BirthdayCakeCalculator;

class BirthdayCakeReportGenerator
{
    private $reportPath = null;
    private $projectDir;
    private $birthdayCakeCalculator;

    public function __construct($projectDir, BirthdayCakeCalculator $birthdayCakeCalculator)
    {
        $this->projectDir = $projectDir . '\public\report';
        $this->birthdayCakeCalculator = $birthdayCakeCalculator;
    }

    public function generateBirthdayCakeReport(array $birthdays)
    {
        $birthdayReport = $this->getBirthdayReport($birthdays);
        $this->saveReport($birthdayReport);
    }

    private function getBirthdayReport(array $birthdays)
    {
        $this->birthdayCakeCalculator->year = date('Y');
        return $this->birthdayCakeCalculator->calculateCakes($birthdays);
    }

    private function saveReport(array $birthdayReport)
    {
        $header = array(
            'date' => 'Date',
            'small_cakes' => 'Number of Small Cakes',
            'large_cakes' => 'Number of Large Cakes',
            'staff' => 'Names of people getting cake'
        );
        $this->reportPath = $this->projectDir . '\BirthdayReport.csv';
        $f = fopen($this->reportPath, 'wa+'); // Configure fopen to create, open, and write data.
        fputcsv($f, $header);
        foreach ($birthdayReport as $row) {
            fputcsv($f, $row);
        }
        fclose($f);
    }

    public function getReportPath()
    {
        return $this->reportPath;
    }
}