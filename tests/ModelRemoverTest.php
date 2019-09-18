<?php

namespace Tests;

use Solarium\Client;
use Solarium\Core\Client\Endpoint;
use Solarium\QueryType\Update\Query\Query as UpdateQuery;
use Tests\Mocks\EmptyConfigMock;
use Tests\Mocks\IndexableMock;
use Tests\Mocks\UnIndexableMock;
use Tubber\Indexer\Contracts\SolrConfigInterface;
use Tubber\Indexer\Exceptions\NoCoreFoundException;
use Tubber\Indexer\ModelRemover;

class ModelRemoverTest extends \PHPUnit\Framework\TestCase
{
    public function testExceptionIsThrownWhenConfigurationHasEmptyCore()
    {
        $this->expectException(NoCoreFoundException::class);

        ModelRemover::forModels([new IndexableMock], new EmptyConfigMock)->perform();
    }

    public function testRemovingIsSuccessful()
    {
        $documents = [new IndexableMock, new UnIndexableMock];

        // Mock the endpoint
        $endpoint = new Endpoint();
        $endpoint->setCore('search');

        // Mock the updater
        $updater = $this->createMock(UpdateQuery::class);
        $updater->expects($this->exactly(2))
            ->method('addDeleteQuery');

        // Mock the Solr client
        $client = $this->createMock(Client::class);
        $client->method('getPlugin')->with('bufferedadd')->willReturnReference($buffer);
        $client->method('getEndpoint')->willReturnReference($endpoint);
        $client->method('createUpdate')->willReturnReference($updater);

        // Mock the config
        $config_mock = $this->createMock(SolrConfigInterface::class);
        $config_mock->method('getClient')->willReturnReference($client);

        ModelRemover::forModels($documents, $config_mock)->perform();
    }
}
