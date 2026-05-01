<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsService;

class FetchNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch news from the Steam API and save them to the database';

    protected $newsService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(NewsService $newsService)
    {
        parent::__construct();
        $this->newsService = $newsService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Iniciando sincronización de noticias desde Steam API...');
        
        $appId = '3405340'; // El AppID proporcionado por el usuario
        
        $newCount = $this->newsService->fetchSteamNews($appId);

        $this->info("Sincronización completada. Se han añadido {$newCount} nuevas noticias.");
        
        return 0;
    }
}
