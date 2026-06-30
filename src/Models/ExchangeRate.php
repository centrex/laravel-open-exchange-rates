<?php

declare(strict_types = 1);

namespace Centrex\LaravelOpenExchangeRates\Models;

use Centrex\LaravelOpenExchangeRates\Concerns\AddTablePrefix;
use DateTimeInterface;
use Illuminate\Database\Eloquent\{Builder, Model};

class ExchangeRate extends Model
{
    use AddTablePrefix;

    protected function getTableSuffix(): string
    {
        return 'exchange_rates';
    }

    protected $fillable = ['date', 'base', 'rates', 'fetched_at'];

    protected $casts = [
        'date'       => 'date:Y-m-d',
        'rates'      => 'array',
        'fetched_at' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('laravel-open-exchange-rates.db_connection', config('database.default')));
    }

    /**
     * Latest stored snapshot (any date) that contains a rate for $currency.
     */
    public static function latestFor(string $currency, string $base = 'USD'): ?self
    {
        $currency = strtoupper($currency);

        return static::query()
            ->where('base', strtoupper($base))
            ->latest('date')
            ->cursor()
            ->first(fn (self $row): bool => array_key_exists($currency, $row->rates ?? []));
    }

    /**
     * Most recent stored snapshot on or before $date that contains a rate for $currency.
     */
    public static function asOf(string $currency, string $base, DateTimeInterface|string $date): ?self
    {
        $currency = strtoupper($currency);
        $cutoff = $date instanceof DateTimeInterface ? $date->format('Y-m-d') : $date;

        return static::query()
            ->where('base', strtoupper($base))
            ->whereDate('date', '<=', $cutoff)
            ->orderByDesc('date')
            ->cursor()
            ->first(fn (self $row): bool => array_key_exists($currency, $row->rates ?? []));
    }

    /**
     * Store (or merge into) the daily snapshot for $base on $date, keyed by currency.
     * Existing currencies for that day are preserved unless overwritten by $rates.
     */
    public static function upsertRates(array $rates, string $base, DateTimeInterface $fetchedAt, ?string $date = null): void
    {
        if ($rates === []) {
            return;
        }

        $base = strtoupper($base);
        $date ??= $fetchedAt->format('Y-m-d');

        $normalized = [];

        foreach ($rates as $currency => $rate) {
            $normalized[strtoupper((string) $currency)] = (float) $rate;
        }

        $existing = static::query()->where('base', $base)->whereDate('date', $date)->first();
        $merged = $existing ? array_merge($existing->rates ?? [], $normalized) : $normalized;

        static::query()->updateOrCreate(
            ['base' => $base, 'date' => $date],
            ['rates' => $merged, 'fetched_at' => $fetchedAt->format('Y-m-d H:i:s')],
        );
    }

    public function rateFor(string $currency): ?float
    {
        $rate = ($this->rates ?? [])[strtoupper($currency)] ?? null;

        return $rate === null ? null : (float) $rate;
    }

    public function convertFrom(float $amount, string $currency): float
    {
        $rate = $this->rateFor($currency);

        if ($rate === null || $rate == 0.0) {
            return 0.0;
        }

        return round($amount / $rate, 8);
    }

    public function convertTo(float $amount, string $currency): float
    {
        $rate = $this->rateFor($currency);

        if ($rate === null) {
            return 0.0;
        }

        return round($amount * $rate, 8);
    }

    public function scopeForBase(Builder $query, string $base = 'USD'): Builder
    {
        return $query->where('base', strtoupper($base));
    }

    public function scopeOnDate(Builder $query, DateTimeInterface|string $date): Builder
    {
        return $query->whereDate('date', $date instanceof DateTimeInterface ? $date->format('Y-m-d') : $date);
    }
}
