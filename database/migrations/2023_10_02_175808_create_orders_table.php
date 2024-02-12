<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user'); // Внешний ключ для клиента
            $table->unsignedBigInteger('product'); // Внешний ключ для товара
            $table->timestamp('order_time');
            $table->string('order_number')->unique()->default(DB::raw('UUID()'));
            $table->integer('quantity');
            $table->decimal('order_total', 65);
            $table->string('status');
            $table->timestamps();

            // Определение внешних ключей
            $table->foreign('user')->references('id')->on('users');
            $table->foreign('product')->references('id')->on('products');
        });
    }

};
