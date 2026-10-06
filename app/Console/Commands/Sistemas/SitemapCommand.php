<?php

namespace App\Console\Commands\Sistemas;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;

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
        SitemapGenerator::create('https://message-business.gijac.com')
            ->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generado correctamente en public/sitemap.xml');
    }
}
