CakeReport
====
The project uses Symfony 5.4.4 as the framework. It allows users to use the command line to select files from the directory (.\public\birthday_data) to select files.
It reads through the text file and gets the birthdays of all employees. Then it calculates which employee will get a birthday cake.
Finally, it generates a birthday report in csv format.

Install
--------
# Install all required packages
1. composer install

# Run the following command in the folder directory to generate cake report
# You can add a new text file to (.\public\birthday_data)
# Then replace sample1 with the name of the file you want to run
1. symfony console generateBirthdayCakeReport sample1

Testing
--------
# Run the following command in the folder directory to perform unit test checks
1. symfony php bin/phpunit tests/Feature/ReadTxtBirthdayCommandTest.php
2. symfony php bin/phpunit tests/Util/CalculateCakeTest.php
3. symfony php bin/phpunit tests/Service/BirthdayCakeReportGeneratorTest.php

