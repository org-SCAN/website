<?php

namespace App\Logging;

use Elastic\Elasticsearch\ClientBuilder;
use Monolog\Handler\ElasticsearchHandler;
use Monolog\Logger;

class CreateElasticsearchLogger
{
    /**
     * Crée une instance personnalisée de Monolog.
     *
     * @param  array  $config
     * @return Logger
     */
    public function __invoke(
        array $config
    ) {
        $hosts = [
            env('ELASTICSEARCH_HOST',
                'http://elasticsearch:9200'),
        ];

        $client = ClientBuilder::create()->setHosts($hosts)->build();

        $options = [
            'index' => 'laravel-logs',
            // Elasticsearch Index
            'type' => '_doc',
        ];

        $handler = new ElasticsearchHandler($client,
            $options);

        return new Logger('elasticsearch',
            [$handler]);
    }
}
