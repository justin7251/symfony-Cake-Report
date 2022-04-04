<?php

namespace App\Command;

use App\Service\BirthdayCakeReportGenerator;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateBirthdayCakeCommand extends Command
{
    protected static $defaultName = 'generateBirthdayCakeReport';
    private $reportGenerator;
    private $projectDir;

    public function __construct(
        $projectDir,
        BirthdayCakeReportGenerator $reportGenerator
    )
    {
        $this->projectDir = $projectDir;
        $this->reportGenerator = $reportGenerator;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Generates Birthday Cake Report')
            ->addArgument('file_name', InputArgument::OPTIONAL, 'File Name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $fileName = $input->getArgument('file_name');
        $birthdayFilePath = $this->projectDir . '/public/birthday_data/' . $fileName . '.txt';

        if (file_exists($birthdayFilePath)){
            $birthdays = explode("\n", file_get_contents($birthdayFilePath));
            $this->reportGenerator->generateBirthdayCakeReport($birthdays);
            $filepath = $this->reportGenerator->getReportPath();
            $io->success("Birthday Cake report saved to $filepath");
        } else {
            $io->error('FILE ' . $birthdayFilePath . ' FAILED TO LOAD');
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}
