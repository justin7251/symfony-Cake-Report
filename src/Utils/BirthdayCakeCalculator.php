<?php

namespace App\Utils;

class BirthdayCakeCalculator
{
    private $cakeDays;
    public $year;

    private $week = array(
        'Fri' => 3,
        'Sat' => 3,
        'Sun' => 2
    );

    public function calculateCakes(array $birthdays)
    {
        if (count($birthdays) < 1) {
            return array();
        }
        return $this->calcualte($birthdays);
    }

    private function calcualte($birthdays)
    {
        foreach ($birthdays as $birthday) {
            $this->getCakes($birthday);
        }
        $this->combineCakes();
        $this->checkCakeFreeDay();
        $all_cake_day = array();
        if (count($this->cakeDays) > 0) {
            foreach ($this->cakeDays as $date => $report) {
                $all_cake_day[$date] = array(
                    'date' => $date,
                    'small_cakes' => $report['small_cakes'],
                    'large_cakes' => $report['large_cakes'],
                    'staff' => implode(', ', $report['staff'])
                );
            }
        }
        return $all_cake_day;
    }

    private function getCakes($birthday)
    {
        $birthday = explode(',', $birthday);
        $staff_name = $birthday[0];
        $this_year_birthday = substr_replace(str_replace("\r", '', $birthday[1]), $this->year, 0, 4);
        $cakeDay = $this->getNextWorkingDay($this_year_birthday);
        if (isset($this->cakeDays[$cakeDay]) && is_array($this->cakeDays[$cakeDay])) {
            array_push($this->cakeDays[$cakeDay]['staff'], $staff_name);
            $this->cakeDays[$cakeDay] = array(
                'staff' => $this->cakeDays[$cakeDay]['staff'],
                'small_cakes' => $this->cakeDays[$cakeDay]['small_cakes'] + 1,
                'large_cakes' => 0
            );
        } else {
            $this->cakeDays[$cakeDay] = array(
                'staff' => array($staff_name),
                'small_cakes' => 1,
                'large_cakes' => 0
            );
        }
    }

    private function combineCakes()
    {
        if (count($this->cakeDays) < 1) {
            return;
        }
        $skip = array();
        foreach ($this->cakeDays as $date => $data) {
            $tomorrow = date('Y-m-d', strtotime('+1 day', strtotime($date)));
            $cakeOnTomorrow = (array_key_exists($tomorrow, $this->cakeDays) ? $tomorrow : '');
            if (array_key_exists($date, $skip)) {
                continue;
            }
            if ($cakeOnTomorrow) {
                foreach ($data['staff'] as $staff) {
                    $this->cakeDays[$cakeOnTomorrow]['staff'][] = $staff;
                }
                $this->cakeDays[$cakeOnTomorrow] = array(
                    'staff' => $this->cakeDays[$cakeOnTomorrow]['staff'],
                    'small_cakes' => 0,
                    'large_cakes' => 1
                );
                $skip[$cakeOnTomorrow] = 1;
                unset($this->cakeDays[$date]);
            }
        }
    }

    private function checkCakeFreeDay()
    {
        $skip = array();
        foreach ($this->cakeDays as $date => $data) {
            if (array_key_exists($date, $skip)) {
                continue;
            }
            $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($date)));
            $cakeOnYesterday = (array_key_exists($yesterday, $this->cakeDays) ? $yesterday : '');
            $tomorrow = date('Y-m-d', strtotime('+1 day', strtotime($date)));
            if ($cakeOnYesterday) {
                foreach ($data['staff'] as $staff) {
                    $this->cakeDays[$tomorrow]['staff'][] = $staff;
                }
                $this->cakeDays[$tomorrow] = array(
                    'staff' => $this->cakeDays[$tomorrow]['staff'],
                    'small_cakes' => count($this->cakeDays[$tomorrow]['staff']),
                    'large_cakes' => 0
                );
                $skip[$tomorrow] = 1;
                unset($this->cakeDays[$date]);
            }
        }
    }

    private function getNextWorkingDay($this_year_birthday)
    {
        $add_days = 0;
        $bank_holiday = $this->getBankHoliday();
        if (array_key_exists($this_year_birthday, $bank_holiday)) {
            $add_days += $bank_holiday[$this_year_birthday];
        }
        $day = date("D", strtotime('+' . $add_days . ' day', strtotime($this_year_birthday)));
        if (array_key_exists($day, $this->week)) {
            $add_days += $this->week[$day];
        } else {
            $add_days += 1;
        }
        return date("Y-m-d", strtotime('+' . $add_days . ' day', strtotime($this_year_birthday)));
    }

    private function getBankHoliday()
    {
        // Christmas Day, Boxing Day and New Year’s Day.
        return array($this->year . '-12-25' => 2, $this->year . '-12-26' => 1, ($this->year + 1) . '-01-01' => 1);
    }
}