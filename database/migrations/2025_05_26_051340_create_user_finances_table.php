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
        Schema::create('user_finances', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('keterangan'); // Contoh: "makan", "freelance"
            $table->text('deskripsi')->nullable(); // Bisa dikosongkan jika tidak detail
            $table->integer('nominal');
            $table->string('no_hp');
            $table->enum('jenis', ['pemasukan', 'pengeluaran']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_finances');
    }
};
