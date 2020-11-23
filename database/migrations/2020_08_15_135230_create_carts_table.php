<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->uuid('id');//هو عبارة عن نص يمكنني استخدامه ك id
            $table->unsignedBigInteger('user_id')->nullable(); //قمنى بجعل اليوزر نل من اجل اي شخص يمكنه اضافة منتج دون تسجيل الدخول
            $table->unsignedBigInteger('product_id');
            $table->unsignedSmallInteger('quntity')->default(0);
            $table->float('price');

            $table->primary(['id' , 'product_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
