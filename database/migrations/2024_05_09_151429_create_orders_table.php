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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_date');
            $table->string('delivery_date');
            $table->string('day');
            $table->string('delivery_number');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('telephone_number');
            $table->string('address');
            $table->string('zip_code');
            $table->string('would');
            $table->string('floor_number');
            $table->json('items')->nullable();
            $table->json('other')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
