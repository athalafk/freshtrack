<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['masuk', 'keluar', 'tambah', 'edit', 'hapus']);
            $table->string('item');
            $table->integer('stock');
            $table->string('actor');
            $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};