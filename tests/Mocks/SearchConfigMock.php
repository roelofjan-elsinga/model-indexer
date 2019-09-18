<?php

namespace Tests\Mocks;

use Solarium\Client;
use Tubber\Indexer\Contracts\SolrConfigInterface;

class SearchConfigMock implements SolrConfigInterface
{

    /**
     * Get the client for this Solr interaction
     *
     * @return Client
     */
    public function getClient(): Client
    {
        $client = new Client();

        $client->getEndpoint('localhost')->setCore('search');

        return $client;
    }

    /**
     * Reload the core that's provided in $this->getClient
     *
     * @return void
     */
    public function reloadCollection(): void
    {
        // TODO: Implement reloadCollection() method.
    }
}
