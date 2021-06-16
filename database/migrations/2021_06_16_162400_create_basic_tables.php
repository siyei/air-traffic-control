<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasicTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if( !Schema::hasTable('acs') ){
            Schema::create('acs', function (Blueprint $table) {
                $table->id();
                $table->enum('type', ['passenger', 'cargo', 'vip', 'emergency']);
                $table->enum('size', ['large', 'small']);
                $table->timestamps();
            });
        }

        if( !Schema::hasTable('system') ){
            Schema::create('system', function (Blueprint $table) {
                $table->id();
                $table->string('status', 25);
                $table->timestamps();
            });
        }

        if( !Schema::hasTable('queues') ){
            Schema::create('queues', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('ac_id')->nullable();
                $table->foreign('ac_id')->references('id')->on('acs');
                $table->tinyInteger('priority')->default(1);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('queues');
        Schema::dropIfExists('acs');
        Schema::dropIfExists('system');
    }
}
