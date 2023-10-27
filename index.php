<?php
require_once 'vendor/autoload.php';

use App\Currency;
use App\FreeCurrencyAPI;
use App\CurrencyCollection;
use App\FastForexAPI;

$input = readline("Enter conversion: ");
$targetCurrencyIsoCode = readline("Enter currency to convert to: ");

[$amount, $baseCurrencyIsoCode] = explode(' ', $input);

$conversionFreeCurrency = new FreeCurrencyAPI();
$conversionFastForex = new FastForexAPI();

$targetCurrencies = new CurrencyCollection([$targetCurrencyIsoCode]);

$resultsFreeCurrency = $conversionFreeCurrency->exchange(
    new Currency($baseCurrencyIsoCode),
    $targetCurrencies,
    $amount);

foreach ($resultsFreeCurrency as $isoCode => $value){
    echo "{$isoCode} -> {$value}" . PHP_EOL;
}

$resultsFastForex = $conversionFastForex->exchange(
    $baseCurrencyIsoCode,
    $targetCurrencies,
    $amount);

if (isset($resultsFastForex[$targetCurrencyIsoCode])) {
    echo "FastForex: {$targetCurrencyIsoCode} -> {$resultsFastForex[$targetCurrencyIsoCode]}" . PHP_EOL;
} else {
    echo "FastForex: Target currency not found in the results." . PHP_EOL;
}
