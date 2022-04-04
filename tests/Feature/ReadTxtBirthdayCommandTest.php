<?php

namespace App\Tests\feature;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ReadTxtBirthdayCommandTest extends KernelTestCase
{

    /** @test */
    public function the_read_txt_birthday_command_behaves_correctly()
    {
        //setup
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        //Command
        $command = $application->find('generateBirthdayCakeReport');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'file_name' => 'sample1'
        ]);
        //make assering
        $commandTester->assertCommandIsSuccessful();
        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Birthday Cake report saved', $output);
    }
}