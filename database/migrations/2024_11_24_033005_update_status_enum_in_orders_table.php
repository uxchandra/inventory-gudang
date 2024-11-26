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
        // Ubah enum status di tabel orders
        DB::statement("ALTER TABLE orders MODIFY status ENUM('menunggu_konfirmasi', 'diterima', 'ditolak', 'selesai')");
    }

    public function down()
    {
        // Kembalikan ke sebelumnya
        DB::statement("ALTER TABLE orders MODIFY status ENUM('menunggu_konfirmasi', 'diterima', 'ditolak')");
    }
};
