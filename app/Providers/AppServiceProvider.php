<?php

namespace App\Providers;

use App\Articles\ArticlesRepository;
use App\Articles\ElasticsearchRepository;
use App\Articles\EloquentRepository;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind(
        //     ArticlesRepository::class,
        //     EloquentRepository::class
        // );

        $this->app->bind(ArticlesRepository::class, function ($app) {
            // This is useful in case we want to turn-off our
            // search cluster or when deploying the search
            // to a live, running application at first.
            if (!config('services.search.enabled')) {
                return new EloquentRepository();
            }

            return new ElasticsearchRepository(
                $app->make(Client::class)
            );
        });

        $this->bindSearchClient();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts($app['config']->get('services.search.hosts'))
                ->build();
        });
    }
}
