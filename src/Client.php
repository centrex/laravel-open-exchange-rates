<?php

declare(strict_types = 1);

namespace Centrex\LaravelOpenExchangeRates;

use Centrex\LaravelOpenExchangeRates\Exceptions\OpenExchangeRatesResponseException;
use Centrex\LaravelOpenExchangeRates\Models\ExchangeRate;

class Client
{
    /**
     * Base URI of API service.
     */
    const BASE_URI = 'https://openexchangerates.org/api/';

    /**
     * Set base currency.
     */
    public function currency($currencyCode): self
    {
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
        $uri = sprintf(self::BASE_URI . 'latest.json?app_id=%s&symbols=%s', config('laravel-open-exchange-rates.app_id'), $symbols);

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
        $uri = sprintf(self::BASE_URI . 'historical/%s.json?app_id=%s&symbols=%s', $date, config('laravel-open-exchange-rates.app_id'), $symbols);

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
     * The requested date in YYYY-MM-DD format (required).
     *
     * @param  string  $startDate
     * @param  string  $endDate
     * @param  string  $symbols
     */
    public function timeSeries($startDate, $endDate, $symbols = ''): array
    {
        $uri = sprintf(self::BASE_URI . 'timeseries.json?app_id=%s&start=%s&end=%s&symbols=%s', config('laravel-open-exchange-rates.app_id'), $startDate, $endDate, $symbols);

        return $this->sendRequest($uri);
    }

    /**
     * Convert any money value from one currency to another.
     *
     * @param  string  $value
     * @param  string  $from
     * @param  string  $to
     */
    public function convert($value, $from, $to): array
    {
        $uri = sprintf(self::BASE_URI . 'convert/%s/%s/%s?app_id=%s', $value, $from, $to, config('laravel-open-exchange-rates.app_id'));

        return $this->sendRequest($uri);
    }

    /**
     * Get historical Open, High Low, Close (OHLC) and Average exchange rates for a given time period,
     * ranging from 1 month to 1 minute, where available.
     *
     * @param  string  $startTime  , Format: "YYYY-MM-DDThh:mm:00Z".
     * @param  string  $period,  Allowed periods are: 1m, 5m, 15m, 30m, 1h, 12h, 1d, 1w, and 1mo
     * @param  string  $symbols
     */
    public function ohlc($startTime, $period, $symbols = ''): array
    {
        $uri = sprintf(self::BASE_URI . 'ohlc.json?app_id=%s&start_time=%s&periods=%s&symbols=%s', config('laravel-open-exchange-rates.app_id'), $startTime, $period, $symbols);

        return $this->sendRequest($uri);
    }

    /**
     * Fetch latest rates and persist them to the local database.
     * Returns the number of currencies synced.
     */
    public function syncLatest(string $symbols = ''): int
    {
        $response  = $this->latest($symbols);
        $base      = strtoupper($response['base'] ?? config('laravel-open-exchange-rates.default_base_currency', 'USD'));
        $rates     = $response['rates'] ?? [];

        ExchangeRate::upsertRates($rates, $base, now());

        return count($rates);
    }

    /**
     * Get basic plan information and usage statistics for your App ID
     *
     * @param  string  $prettyprint
     */
    public function usage($prettyprint = '1'): array
    {
        $uri = sprintf(self::BASE_URI . 'usage.json?app_id=%s', config('laravel-open-exchange-rates.app_id'));

        return $this->sendRequest($uri);
    }

    /**
     * Send request to API
     *
     *
     * @throws OpenExchangeRatesResponseException
     */
    private function sendRequest(string $uri): array
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $uri,
            CURLOPT_RETURNTRANSFER => true,
        ]);
        $result = curl_exec($ch);

        if ($result === false) {
            $error = curl_error($ch);
            curl_close($ch);

            throw new OpenExchangeRatesResponseException('cURL request failed: ' . $error);
        }

        curl_close($ch);

        $response = json_decode((string) $result, true);

        if (isset($response['error']) && $response['error'] === true) {
            throw new OpenExchangeRatesResponseException("Status: {$response['status']}, message: {$response['message']}");
        }

        return $response;
    }
}
