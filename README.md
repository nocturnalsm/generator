# Code Number Generator for Laravel
A Laravel package to generate code numbers, such as product numbers, transaction numbers, etc.

## Usage

Use this procedure to generate number 

``NumberGenerator::generate($format, $params);``

### Parameters

#### $format
A string with a number of curly brackets in it where codes will be generated.

Available formats are:

##### **{d:_formatdate_}**

You can replace _formatdate_ with any PHP Date Format.

##### **{r:_randomnumber_}**

This will generate random number with digits in range of the _randomnumber_ string length.

For example: with randomnumber 0000 (you can replace 0 with any character you prefer) a random number ranged from 1000 to 9999 will be generated.

##### **{i:_increment_}**

This will generate increments with length of _increment_. You can only include this format once. If there are more than one, only the last occurence will be generated.

For example: {i:0000} will generate 0001, 0002, and so on.

#### $params

You can also specify a string in curly bracket other than the above formats. The string will be replaced by values from this parameter. This will come in handy if you have formats you want to generate your own.

For example:

```
$params = Array("number" => 100);
return NumberGenerator::generate("APP:{number}", $params);

// this will return APP:100

```

### Example Code

```
use NocturnalSm\NumberGenerator\NumberGenerator;

public function generateNumber()
{
    $format = "EX.{d:dmY}.{store}.{i:0000}";
    return NumberGenerator::generate($format, ["store" => "APX");
}
```

## Installation

Use Composer to install this package. Execute script below inside your laravel app directory

``composer require nocturnalsm\numbergenerator``

Increment format require a table to hold the data. Publish the migration file by executing the script below

``php artisan vendor:publish --tag=migrations --provider=NocturnalSm\NumberGenerator\NumberGeneratorProvider``

To use the package, include the NumberGenerator class inside your script

``use NocturnalSm\NumberGenerator\NumberGenerator``

## License

This package has MIT License







