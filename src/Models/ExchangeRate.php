<?php

declare(strict_types = 1);

namespace Centrex\LaravelOpenExchangeRates\Models;

use Illuminate\Database\Eloquent\{Builder, Model};

class ExchangeRate extends Model
{
    protected $fillable = ['base', 'currency', 'rate', 'fetched_at'];

    protected $casts = [
        'rate'       => 'decimal:8',
        'fetched_at' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('laravel-open-exchange-rates.db_connection', config('database.default')));
        $this->setTable(config('laravel-open-exchange-rates.table_name', 'oer_exchange_rates'));
    }

    public static function latestFor(string $currency, string $base = 'USD'): ?self
    {
        return static::query()
            ->where('currency', strtoupper($currency))
            ->where('base', strtoupper($base))
            ->latest('fetched_at')
            ->first();
    }

    public static function upsertRates(array $rates, string $base, \DateTimeInterface $fetchedAt): void
    {
        $rows = [];
        $now = now();

        foreach ($rates as $currency => $rate) {
            $rows[] = [
                'base'       => strtoupper($base),
                'currency'   => strtoupper($currency),
                'rate'       => (string) $rate,
                'fetched_at' => $fetchedAt->format('Y-m-d H:i:s'),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (empty($rows)) {
            return;
        }

        $instance = new static();
        $instance->getConnection()
            ->table($instance->getTable())
            ->upsert($rows, ['base', 'currency'], ['rate', 'fetched_at', 'updated_at']);
    }

    public function convertFrom(float $amount): float
    {
        if ((float) $this->rate == 0.0) {
            return 0.0;
        }

        return round($amount / (float) $this->rate, 8);
    }

    public function convertTo(float $amount): float
    {
        return round($amount * (float) $this->rate, 8);
    }

    public function scopeForBase(Builder $query, string $base = 'USD'): Builder
    {
        return $query->where('base', strtoupper($base));
    }

    public function scopeCurrency(Builder $query, string $currency): Builder
    {
        return $query->where('currency', strtoupper($currency));
    }
}
