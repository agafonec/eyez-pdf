<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders_summaries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id');
            $table->timestamp('summary_date')->default(now());
            $table->bigInteger('orders_count')->default(0);
            $table->float('orders_total')->default(0);
            $table->integer('walk_in_count')->default(0);
            $table->integer('walk_in_rate')->default(0);
            $table->timestamp('last_updated')->default(now());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders_summaries');
    }
};
