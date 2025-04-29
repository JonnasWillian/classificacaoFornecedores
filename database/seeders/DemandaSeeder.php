<?php

namespace Database\Seeders;

use App\Models\Demanda;
use Illuminate\Database\Seeder;

class DemandaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        for ($i = 1; $i <= 20; $i++) {
            Demanda::create([
                'descricao' => 'Descrição da demanda ' . $i,
            ]);
        }
    }
}


if (!function_exists('array_random')) {
    function array_random($array) {
        return $array[array_rand($array)];
    }
}