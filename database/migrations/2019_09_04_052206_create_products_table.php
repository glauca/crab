<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedTinyInteger('type')->nullable();
            $table->string('title');
            $table->string('summary')->nullable();
            $table->unsignedDecimal('price')->default(0);
            $table->unsignedDecimal('market_price')->default(0);
            $table->json('spec')->nullable()->comment('规格');
            $table->json('attr')->nullable()->comment('属性');
            $table->text('content')->nullable();
            $table->enum('status', ['draft', 'online', 'offline'])->default('draft');
            $table->unsignedSmallInteger('rank')->default(65535)->comment('排序');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
