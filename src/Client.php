<?php

declare(strict_types = 1);

namespace Centrex\LaravelOpenExchangeRates;

use Centrex\LaravelOpenExchangeRates\Exceptions\OpenExchangeRatesResponseException;

class Client
{
    /**
     * Base URI of API service.
     */
    const BASE_URI = 'https://openexchangerates.org/api/';

    /**
     * The base currency set in the configuration file.
     *
     * @var mixed
     */
    private $baseCurrency;

    /**
     * Client constructor.
     */
    public function __construct()
    {
        $this->baseCurrency = config('loer.default_base_currency');
    }

    /**
     * Set base currency.
     */
    public function currency($currencyCode): self
    {
        $this->baseCurrency = $currencyCode;

        return $this;
    }

    /**
     * Limit results to specific currencies (comma-separated list of 3-letter codes).
     * For example: ?symbols=USD,RUB,AWG
     *
     * @param  string  $symbols
     */
    public function latest($symbols = ''): array
    {
        $uri = sprintf(self::BASE_URI . 'latest.json?app_id=%s&symbols=%s', config('loer.app_id'), $symbols);

        return $this->sendRequest($uri);
    }

    /**
     * The requested date in YYYY-MM-DD format (required).
     *
     * @param  string  $date
     * @param  string  $symbols
     */
    public function historical($date, $symbols = ''): array
    {
        $uri = sprintf(self::BASE_URI . 'historical/%s.json?app_id=%s&symbols=%s', $date, config('loer.app_id'), $symbols);

        return $this->sendRequest($uri);
    }

    /**
     * This list will always mirror the currencies available in the latest rates (given as their 3-letter codes).
     *
     * @param  string  $showAlternative
     * @param  string  $onlyAlternative
     * @param  string  $prettyprint
     */
    public function currencies($showAlternative = '0', $onlyAlternative = '0', $prettyprint = '1'): array
    {
        $uri = sprintf(self::BASE_URI . 'currencies.json?show_alternative=%s&only_alternative=%s&prettyprint=%s', $showAlternative, $onlyAlternative, $prettyprint);

        return $this->sendRequest($uri);
    }

    /**
     * Get basic plan information and usage statistics for your App ID
     *
     * @param  string  $prettyprint
     */
    public function usage($prettyprint = '1'): array
    {
        $uri = sprintf(self::BASE_URI . 'usage.json?app_id=%s', config('loer.app_id'));

        return $this->sendRequest($uri);
    }

    /**
     * Send request to API
     *
     * @param  string  $uri
     *
     * @throws OpenExchangeRatesResponseException
     */
    private function sendRequest($uri): array
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $uri,
            CURLOPT_RETURNTRANSFER => true,
        ]);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if (isset($response['error']) && $response['error'] == true) {
            throw new OpenExchangeRatesResponseException("Status: {$response['status']}, message: {$response['message']}");
        }

        return $response;
    }
}
