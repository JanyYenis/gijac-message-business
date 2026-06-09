<?php

namespace App\Console\Commands;

use App\Models\ConfiguracionMeta;
use App\Models\Plantilla;
use App\Models\PlantillaComponente;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SincronizarPlantillasCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sincronizar:plantillas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para consultar todas las platillas de los usuarios en META y agregarlos en la plataforma.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->sincronizar();
    }

    public function sincronizar()
    {
        $configuraciones = ConfiguracionMeta::where('estado', ConfiguracionMeta::ACTIVO)
            ->get();

        foreach ($configuraciones as $config) {
            $url = "https://graph.facebook.com/{$config->version}/{$config->waba_id}/message_templates";
            $metodo = 'GET';
            $response = consultaBase($url, $metodo, $config->token);
            $obj = json_decode($response);

            $templates = $obj?->data ?? [];
            // dd($templates);
            foreach ($templates as $tpl) {
                // Guardar la plantilla principal
                $template = Plantilla::updateOrCreate([
                    'id' => $tpl->id,
                    'cod_config' => $config->id,
                ],[
                    'name' => $tpl->name,
                    'language' => $tpl->language,
                    'status' => $tpl?->status ? Plantilla::VALIDAR_VALOR_ESTADO[$tpl->status] : Plantilla::PENDIENTE,
                    'category' => $tpl?->category ? Plantilla::VALIDAR_VALOR_CATEGORIA[$tpl->category] : Plantilla::MARKETING,
                    'sub_category' => $tpl->sub_category ?? null,
                    'parameter_format' => $tpl?->parameter_format ? Plantilla::VALIDAR_VALOR_FORMATO_PARAMETRO[$tpl?->parameter_format] : Plantilla::POSIONAL,
                ]);

                // Guardar componentes
                foreach ($tpl->components as $comp) {
                    PlantillaComponente::updateOrCreate([
                        'plantilla_id' => $template->id,
                        'type' => $comp?->type ? PlantillaComponente::VALIDAR_TIPO[$comp?->type] : PlantillaComponente::BODY,
                        'format' => property_exists($comp, 'format') && $comp?->format && isset($comp?->format) ? PlantillaComponente::VALIDAR_FORMATO[$comp?->format] : null,
                    ], [
                        'text' => $comp->text ?? null,
                        'buttons' => isset($comp->buttons) ? json_encode($comp->buttons) : null,
                        'example' => isset($comp->example) ? json_encode($comp->example) : null,
                    ]);
                }
            }
        }

        $this->info('✅ Plantillas sincronizadas correctamente');
    }
}
