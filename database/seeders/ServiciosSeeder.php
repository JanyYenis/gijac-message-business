<?php

namespace Database\Seeders;

use App\Models\Servicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiciosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $servicios = [
            ['nombre' => 'Respuestas automáticas', 'slug' => 'respuestas.automaticas'],
            ['nombre' => 'Soporte vía ticket', 'slug' => 'soporte.ticket'],
            ['nombre' => 'Soporte VIP vía WhatsApp', 'slug' => 'soporte.vip.whatsapp'],
            ['nombre' => 'Acceso a API', 'slug' => 'api'],
            ['nombre' => 'Chatbots Conversacionales con IA', 'slug' => 'chatbots.ia'],
            ['nombre' => 'Chatbots Avanzados', 'slug' => 'chatbots.avanzados'],
            ['nombre' => 'Integración con Flows de WhatsApp', 'slug' => 'flows.whatsapp'],
        ];

        foreach ($servicios as $servicio) {
            Servicio::create($servicio);
        }
    }
}
