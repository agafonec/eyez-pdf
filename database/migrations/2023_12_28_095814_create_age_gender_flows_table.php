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
        Schema::create('age_gender_flows', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id');
            $table->enum('gender', ['male', 'female']);
            $table->bigInteger('age_group_id');
            $table->bigInteger('people_count');
            $table->timestamp('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('age_gender_flows');
    }
};
