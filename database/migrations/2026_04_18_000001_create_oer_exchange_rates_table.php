<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function getConnection(): ?string
    {
        return config('laravel-open-exchange-rates.db_connection', config('database.default'));
    }

    public function up(): void
    {
        $table = config('laravel-open-exchange-rates.table_name', 'oer_exchange_rates');

        Schema::connection($this->getConnection())->create($table, function (Blueprint $table): void {
            $table->id();
            $table->string('base', 3)->default('USD');
            $table->string('currency', 3);
            $table->decimal('rate', 18, 8);
            $table->timestamp('fetched_at');
            $table->timestamps();

            $table->unique(['base', 'currency']);
            $table->index('fetched_at');
        });
    }

    public function down(): void
    {
        $table = config('laravel-open-exchange-rates.table_name', 'oer_exchange_rates');

        Schema::connection($this->getConnection())->dropIfExists($table);
    }
};
