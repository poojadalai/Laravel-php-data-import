<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('address')->nullable();            
            $table->boolean('checked')->nullable()->default(false);
            $table->longText('description')->nullable();
            $table->string('interest')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('email')->nullable();
            $table->bigInteger('account')->nullable();
            $table->Integer('credit_card')->unsigned()->nullable();   
            $table->foreign('credit_card')->references('id')->on('credit_cards');
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
        Schema::dropIfExists('customers');
    }
}
