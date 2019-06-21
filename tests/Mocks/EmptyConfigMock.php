<?php

namespace Tests\Mocks;


use Solarium\Client;
use Tubber\Indexer\Contracts\SolrConfigInterface;

class EmptyConfigMock implements SolrConfigInterface
{

    /**
     * Get the client for this Solr interaction
     *
     * @return \Solarium\Client
     */
    public function getClient(): Client
    {
        return new Client();
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