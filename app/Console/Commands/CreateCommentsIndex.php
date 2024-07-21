<?php

namespace App\Console\Commands;


use Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;

class CreateCommentsIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:create-comments-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the comments index in Elasticsearch';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = ClientBuilder::create()->setHosts([env('ELASTICSEARCH_HOST')])->build();

        $params = [
            'index' => 'comments',
            'body' => [
                'mappings' => [
                    'properties' => [
                        'user_name' => ['type' => 'text'],
                        'email' => ['type' => 'keyword'],
                        'home_page' => ['type' => 'text'],
                        'text' => ['type' => 'text'],
                        'created_at' => ['type' => 'date'],
                    ],
                ],
            ],
        ];

        $response = $client->indices()->create($params);

        $this->info('Comments index created.');
    }
}
