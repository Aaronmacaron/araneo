<?php

namespace App\Console\Commands;

use App\Jobs\AraneoPageImportJob;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Console\Command;

class ImportCommand extends Command
{
    protected $signature = 'araneo:import {endpoint}';
    protected $description = 'Import proxies from another Araneo instance.';
    protected $guzzle;

    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;

        parent::__construct();
    }

    public function handle()
    {
        $endpoint = $this->argument('endpoint');
        $uri = sprintf('%s/api/proxies', $endpoint);

        $this->info(sprintf('Importing from instance %s.', $uri));

        $request = $this->guzzle->get($uri, [
            RequestOptions::QUERY => [
                'page' => 1,
            ],
        ]);

        $meta = json_decode($request->getBody());

        $this->info(sprintf('Found %s proxies and %s pages to import.', $meta->total, $meta->last_page));

        $bar = $this->output->createProgressBar($meta->last_page);

        foreach (range(1, $meta->last_page) as $page) {
            dispatch(new AraneoPageImportJob($uri, $page));

            $bar->advance();
        }

        $bar->finish();
    }
}
