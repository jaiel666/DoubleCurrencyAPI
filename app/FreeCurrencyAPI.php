<?php


namespace App;

use GuzzleHttp\Client;

class FreeCurrencyAPI
{
    private Client $client;

    private const API_KEY = 'fca_live_WkOz6IXHCSZxykwss34F4xPVuJnwvf0eSKrlmdKc';
    private const API_URL = 'https://api.freecurrencyapi.com/v1/latest';

    public function __construct()
    {
        $certificatePath = 'C:\Users\turis\PhpstormProjects/cacert.pem';

        $this->client = new Client([
            'verify' => $certificatePath,
        ]);
    }

    public function exchange(Currency $baseCurrency, CurrencyCollection $currencies, float $amount): array
    {
        $url = $this->getUrl($baseCurrency, $currencies);

        $result = $this->client->get($url);
        $result = (string)$result->getBody();
        $result = json_decode($result, true);

        $results = [];
        foreach ($result['data'] as $isoCode => $rate) {
            $results[$isoCode] = $rate * $amount;
        }
        return $results;
    }

    private function getUrl(Currency $baseCurrency, CurrencyCollection $currencies): string
    {

        $params = [
            'apikey' => self::API_KEY,
            'currencies' => implode(',', $currencies->getIsoCodes()),
            'base_currency' => $baseCurrency->getIsoCode(),
        ];
        return self::API_URL . '?' . http_build_query($params);
    }
}
