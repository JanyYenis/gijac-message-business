<?php

namespace App\Console\Commands;

use App\Models\WhatsappAccount;
use App\Models\Plantilla;
use App\Models\PlantillaComponente;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class SincronizarPlantillasCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sincronizar:plantillas {waba_id}';

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
        $waba_id = $this->argument('waba_id');
        $this->sincronizar($waba_id);
    }

    public function sincronizar(string $waba_id)
    {
        $configuraciones = WhatsappAccount::where('estado', WhatsappAccount::CONECTADO)
            ->where('waba_id', $waba_id)
            ->get();
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $plantillas_activas = [];
        foreach ($configuraciones as $config) {
            $token = Crypt::decrypt($config->access_token);
            $url = "https://graph.facebook.com/{$version}/{$config->waba_id}/message_templates";
            $metodo = 'GET';
            $response = consultaBase($url, $metodo, $token);
            $obj = json_decode($response);

            $templates = $obj?->data ?? [];
            foreach ($templates as $tpl) {
                $plantillas_activas[] = $tpl->id;
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
                        ], [
                        'format' => property_exists($comp, 'format') && $comp?->format && isset($comp?->format) ? PlantillaComponente::VALIDAR_FORMATO[$comp?->format] : PlantillaComponente::N_A,
                        'text' => $comp->text ?? null,
                        'buttons' => isset($comp->buttons) ? json_encode($comp->buttons) : null,
                        'example' => isset($comp->example) ? json_encode($comp->example) : null,
                    ]);
                }
            }

            $plantillas_eliminar = Plantilla::whereNotIn('id', $plantillas_activas)
                ->where('cod_config', $config->id)
                ->get();
            foreach ($plantillas_eliminar as $plantilla) {
                $plantilla->update(['status' => Plantilla::ELIMINADO]);
            }
        }

        $this->info('✅ Plantillas sincronizadas correctamente');
    }
}
