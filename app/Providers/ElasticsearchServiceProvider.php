<?php

namespace App\Providers;

use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class ElasticsearchServiceProvider extends ServiceProvider
{
public function register()
{
$this->app->singleton('Elasticsearch\Client', function ($app) {
return ClientBuilder::create()->setHosts([env('ELASTICSEARCH_HOST')])->build();
});
}

public function boot()
{
//
}
}
