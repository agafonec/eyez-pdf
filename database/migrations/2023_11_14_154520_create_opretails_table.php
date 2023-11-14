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
        Schema::create('opretails', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('username');
            $table->string('password');
            $table->string('secret_key');
            $table->string('_akey');
            $table->string('_aid');
            $table->bigInteger('enterpriseId');
            $table->string('orgId');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opretails');
    }
};
