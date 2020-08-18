<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number',50);
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('product_code',100);
            $table->integer('base_price');
            $table->integer('sell_price');
            $table->integer('qty');
            $table->integer('total_base_price');
            $table->integer('total_sell_price');

            $table->foreign('product_code')->references('product_code')->on('products')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
