<?php

namespace Tubber\Indexer\Contracts;

use Solarium\Client;

interface SolrConfigInterface
{

    /**
     * Get the client for this Solr interaction
     *
     * @return Client
     */
    public function getClient(): Client;

    /**
     * Reload the core that's provided in $this->getClient
     *
     * @return void
     */
    public function reloadCollection(): void;
}
