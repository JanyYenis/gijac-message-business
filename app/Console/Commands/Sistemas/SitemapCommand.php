<?php

namespace App\Console\Commands\Sistemas;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class SitemapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para generar el sitemap del sitio web';

    /**
     * Execute the console command.
     *
     * @return int
     */
     public function handle()
    {
        $base = 'https://message-business.gijac.com';

        $sitemap = Sitemap::create();

        // Páginas principales
        $paginas = [
            ['/',                       1.0, Url::CHANGE_FREQUENCY_WEEKLY],
            ['/precios',                0.9, Url::CHANGE_FREQUENCY_MONTHLY],
            ['/recursos',               0.8, Url::CHANGE_FREQUENCY_WEEKLY],
            ['/preguntas-frecuentes',   0.7, Url::CHANGE_FREQUENCY_MONTHLY],
            ['/contactarnos',           0.6, Url::CHANGE_FREQUENCY_YEARLY],
            ['/politicas-de-privacidad', 0.3, Url::CHANGE_FREQUENCY_YEARLY],
            ['/terminos-y-condiciones', 0.3, Url::CHANGE_FREQUENCY_YEARLY],
            ['/eliminacion-de-datos',   0.3, Url::CHANGE_FREQUENCY_YEARLY],
        ];

        foreach ($paginas as [$ruta, $prioridad, $frecuencia]) {
            $sitemap->add(
                Url::create($base . $ruta)
                    ->setPriority($prioridad)
                    ->setChangeFrequency($frecuencia)
            );
        }

        // Artículos (ajusta la fecha de cada uno)
        $articulos = [
            '/api-whatsapp-business-importancia-empresas'    => '2026-09-24',
            '/enviar-mensajes-masivos-whatsapp-sin-bloqueo'  => '2026-10-01',
        ];

        foreach ($articulos as $ruta => $fecha) {
            $sitemap->add(
                Url::create($base . $ruta)
                    ->setLastModificationDate(Carbon::parse($fecha))
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generado correctamente en public/sitemap.xml');
    }
}
