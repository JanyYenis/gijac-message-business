<?php

namespace App\Console\Commands;

use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CancelarDemoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancelar:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para consultar y eliminar el demo segun la fecha de creacion de la cuenta';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->eliminarDemo();
    }

    public function eliminarDemo()
    {
        $usuarios = Usuario::where('demo', 1)
            ->where('created_at', '<', Carbon::now()->subDays(15))
            ->get();

        foreach ($usuarios as $usuario) {
            $usuario->update(['demo' => 0]);
        }
    }
}
