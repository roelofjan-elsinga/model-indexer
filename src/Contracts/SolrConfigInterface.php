<?php

namespace Tubber\Indexer\Contracts;

interface SolrConfigInterface
{

    /**
     * Get the client for this Solr interaction
     *
     * @return \Solarium\Client
     */
    public function getClient(): \Solarium\Client;

    /**
     * Reload the core that's provided in $this->getClient
     *
     * @return void
     */
    public function reloadCollection(): void;
}
