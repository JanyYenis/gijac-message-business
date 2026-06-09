<?php

namespace Database\Seeders;

use App\Models\Periodo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periodos = [
            [
                'nombre' => 'Mensual',
                'codigo' => 'monthly',
                'multiplicador' => 1,
                'descuento' => 0.00,
            ],
            [
                'nombre' => 'Trimestral',
                'codigo' => 'quarterly',
                'multiplicador' => 3,
                'descuento' => 0.05, // 5%
            ],
            // [
            //     'nombre' => 'Semestral',
            //     'codigo' => 'SEMESTRAL',
            //     'multiplicador' => 6,
            //     'descuento' => 0.10, // 10%
            // ],
            [
                'nombre' => 'Anual',
                'codigo' => 'yearly',
                'multiplicador' => 12,
                'descuento' => 0.20, // 20%
            ],
        ];

        foreach ($periodos as $periodo) {
            Periodo::updateOrCreate([
                'codigo' => $periodo['codigo']
            ], $periodo);
        }
    }
}
