<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Jenis;
use App\Models\Satuan;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Role::create([
            'id'        => 4,
            'role'      => 'admin gudangg',
            'deskripsi'     => 'Admin gudang memiliki akses untuk mengelola barang masuk dan barang keluar'
        ]);

    }
}
