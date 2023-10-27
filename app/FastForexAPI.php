<?php
namespace App;

use GuzzleHttp\Client;

class FastForexAPI
{
    private Client $client;

    private const API_KEY = 'fa66b05249-eb6107914e-s36o4g';
    private const API_URL = 'https://api.fastforex.io/fetch-all?api_key=fa66b05249-eb6107914e-s36o4g';

    public function __construct()
    {
        $certificatePath = 'C:\Users\turis\PhpstormProjects/cacert.pem';

        $this->client = new Client([
            'verify' => $certificatePath,
        ]);
    }

    public function exchange(string $baseCurrencyIsoCode, CurrencyCollection $currencies, float $amount): array
    {
        $url = $this->getUrl($baseCurrencyIsoCode, $currencies);

        $result = $this->client->get($url);
        $result = (string) $result->getBody();
        $result = json_decode($result, true);

        $results = [];
        foreach ($result['results'] as $isoCode => $rate) {
            $results[$isoCode] = $rate * $amount;
        }

        return $results;
    }

    private function getUrl(string $baseCurrency): string
    {
        return self::API_URL . "&from=$baseCurrency";
    }
}