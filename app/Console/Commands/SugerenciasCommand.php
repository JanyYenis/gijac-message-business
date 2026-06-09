<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SugerenciasCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sugerencias:agente';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para consultar las recomendaciones del agente';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->iniciar();
    }

    public function iniciar()
    {
        $preguntas = [
            1 => 'Puedes darme una pequeña recomendacion de cuales son los mejores dias de la semana para hacer un envio de una campaña por whatsapp, la respuesta debe ser de no mas de 20 palabras',
            2 => 'Puedes darme una pequeña recomendacion de cuales son las mejores horas del dia para hacer un envio de una campaña por whatsapp, la respuesta debe ser de no mas de 20 palabras',
            3 => 'Puedes darme una pequeña recomendacion de cuales son los mejores tipos de mensajes para hacer un envio de una campaña por whatsapp (Imagenes, documentos, videos), la respuesta debe ser de no mas de 20 palabras',
            4 => 'Dame 3 recomendaciones para mejor la comunicacion por el canal de WhatsApp con mis clientes, cada recomendacion no debe tener mas de 20 palabras y deben estar separadas por (-) y solo con (-) al inicio (nota: sin salto de lineas',
        ];

        $datosChat['phone_number'] = '110450798716118';
        foreach ($preguntas as $index => $pregunta) {
            $datosChat['question'] = $pregunta;

            $response = Http::timeout(60)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post('http://agente-meta.gijac.com:8000/chat', $datosChat);

            // Manejar la respuesta
            if ($response->successful()) {
                $mensajeResponse = json_decode($response->body(), true); // Muestra la respuesta

                $mensajeFinal = str_replace('\n', '', $mensajeResponse);


            }
        }
    }
}
