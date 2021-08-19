<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->string('no_trans', 20)->nullable();
            $table->timestamp('tanggal');
            $table->integer('divisi');
            $table->integer('total_buah');

            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');

            $table->foreignId('deleted_by')
                ->nullable()
                ->constrained('users')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
}
