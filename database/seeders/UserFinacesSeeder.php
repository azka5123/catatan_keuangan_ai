<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserFinaces;
use Faker\Factory as Faker;
use Illuminate\Support\Carbon;

class UserFinacesSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $keteranganList = ['makan', 'minum', 'perlengkapan mandi', 'transportasi', 'hiburan'];
        $deskripsiList = [
            'membeli nasi goreng',
            'minum kopi latte',
            'beli sabun dan shampoo',
            'naik ojek online',
            'nonton bioskop'
        ];

        foreach (range(1, 200) as $i) {
            $jenis = $faker->randomElement(['pemasukan', 'pengeluaran']);
            $today = Carbon::now()->subDays(rand(0, 30));

            UserFinaces::create([
                'tanggal' => $today->format('Y-m-d'),
                'keterangan' => $faker->randomElement($keteranganList),
                'deskripsi' => $faker->randomElement($deskripsiList),
                'nominal' => $faker->numberBetween(10000, 100000),
                'no_hp' => '6285215043495',
                'jenis' => $jenis,
                'created_at' => $today,
                'updated_at' => $today,
            ]);
        }
    }
}
