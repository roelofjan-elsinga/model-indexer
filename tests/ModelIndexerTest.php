<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Solarium\Client;
use Solarium\Core\Client\Endpoint;
use Solarium\Plugin\BufferedAdd\BufferedAdd;
use Tests\Mocks\EmptyConfigMock;
use Tests\Mocks\GeneratorDocumentMocks;
use Tests\Mocks\IndexableMock;
use Tests\Mocks\SearchConfigMock;
use Tests\Mocks\UnIndexableMock;
use Tubber\Indexer\Contracts\SolrConfigInterface;
use Tubber\Indexer\ModelIndexer;
use Tubber\Indexer\Exceptions\NoCoreFoundException;

class ModelIndexerTest extends TestCase
{
    public function testExceptionIsThrownWhenIndexingDocumentsDoNotContainDocuments()
    {
        $this->expectException(\InvalidArgumentException::class);

        ModelIndexer::forModels([new UnIndexableMock], new SearchConfigMock)->perform();
    }

    public function testExceptionIsThrownWhenConfigurationHasEmptyCore()
    {
        $this->expectException(NoCoreFoundException::class);

        ModelIndexer::forModels([new IndexableMock], new EmptyConfigMock)->perform();
    }

    public function testIndexingIsSuccessful()
    {
        $documents = [new IndexableMock];

        // Mock the buffer
        $buffer = $this->createMock(BufferedAdd::class);

        $buffer
            ->expects($this->atLeast(2))
            ->method('addDocument');

        $buffer->expects($this->once())->method('flush');

        // Mock the endpoint
        $endpoint = new Endpoint();
        $endpoint->setCore('search');

        // Mock the Solr client
        $client = $this->createMock(Client::class);
        $client->method('getPlugin')->with('bufferedadd')->willReturnReference($buffer);
        $client->method('getEndpoint')->willReturnReference($endpoint);

        // Mock the config
        $config_mock = $this->createMock(SolrConfigInterface::class);
        $config_mock->method('getClient')->willReturnReference($client);

        ModelIndexer::forModels($documents, $config_mock)->perform();
    }

    public function testIndexingIsSuccessfulForAGenerator()
    {
        $documents = [new GeneratorDocumentMocks];

        // Mock the buffer
        $buffer = $this->createMock(BufferedAdd::class);

        $buffer
            ->expects($this->atLeast(2))
            ->method('addDocument');

        $buffer->expects($this->once())->method('flush');

        // Mock the endpoint
        $endpoint = new Endpoint();
        $endpoint->setCore('search');

        // Mock the Solr client
        $client = $this->createMock(Client::class);
        $client->method('getPlugin')->with('bufferedadd')->willReturnReference($buffer);
        $client->method('getEndpoint')->willReturnReference($endpoint);

        // Mock the config
        $config_mock = $this->createMock(SolrConfigInterface::class);
        $config_mock->method('getClient')->willReturnReference($client);

        ModelIndexer::forModels($documents, $config_mock)->perform();
    }

    public function testBufferSizeCanBeAdjusted()
    {
        $indexer = ModelIndexer::forModels([new IndexableMock], new SearchConfigMock);

        $this->assertEquals(100, $indexer->getBufferSize());

        $indexer->setBufferSize(1000);

        $this->assertEquals(1000, $indexer->getBufferSize());
    }

    public function testCommitWithinCanBeAdjusted()
    {
        $indexer = ModelIndexer::forModels([new IndexableMock], new SearchConfigMock);

        $this->assertEquals(10000, $indexer->getCommitWithin());

        $indexer->setCommitWithin(5000);

        $this->assertEquals(5000, $indexer->getCommitWithin());
    }
}
