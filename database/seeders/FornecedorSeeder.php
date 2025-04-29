<?php

namespace Database\Seeders;

use App\Models\Fornecedor;
use Illuminate\Database\Seeder;

class FornecedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fornecedores = [
            [
                'razao_social' => 'Empresa A Ltda',
                'cep' => '01001000'
            ],
            [
                'razao_social' => 'Empresa B S.A.',
                'cep' => '20010000'
            ],
            [
                'razao_social' => 'Empresa C & Cia',
                'cep' => '30110000'
            ],
            [
                'razao_social' => 'Empresa D Tecnologia',
                'cep' => '80010000'
            ],
            [
                'razao_social' => 'Empresa E Construções',
                'cep' => '90010000'
            ],
        ];

        foreach ($fornecedores as $fornecedor) {
            Fornecedor::create($fornecedor);
        }
    }
}