<?php

namespace Tubber\Indexer;

use Solarium\Core\Plugin\PluginInterface;
use Solarium\Plugin\BufferedAdd\BufferedAdd;
use Solarium\QueryType\Update\Query\Document;
use Tubber\Indexer\Contracts\IndexableInterface;
use Tubber\Indexer\Contracts\SolrConfigInterface;
use Tubber\Indexer\Exceptions\NoCoreFoundException;

class ModelIndexer
{
    /**@var array|IndexableInterface[]*/
    private $models;

    /**@var SolrConfigInterface*/
    private $config;

    /**@var BufferedAdd*/
    private $buffer;

    /**
     * ModelIndexer constructor.
     *
     * @param array $models
     * @param SolrConfigInterface $config
     * @throws NoCoreFoundException
     */
    private function __construct(array $models, SolrConfigInterface $config)
    {
        $this->models = $models;

        $this->config = $config;

        $this->assertConfigHasCore();

        $this->buffer = $this->createBuffer();
    }

    /**
     * Set the collection of models that need to be indexed
     *
     * @param array $models
     * @param SolrConfigInterface $config
     * @return ModelIndexer
     * @throws NoCoreFoundException
     */
    public static function forModels(array $models, SolrConfigInterface $config): ModelIndexer
    {
        return new static($models, $config);
    }

    /**
     * Perform the indexing for the models in this class
     *
     * @throws \InvalidArgumentException
     */
    public function perform(): void
    {
        $this->buffer->setBufferSize(2500);

        foreach ($this->models as $model) {

            $this->assertDocumentsAreValid(
                $documents = $model->indexingDocuments()
            );

            $this->buffer->addDocuments($documents);

            $model->markAsIndexed();

        }

        $this->buffer->flush(true, 10000);

        $this->config->reloadCollection();
    }

    /**
     * Create a new buffer instance
     *
     * @return PluginInterface
     */
    private function createBuffer(): PluginInterface
    {
        return $this->config->getClient()->getPlugin('bufferedadd');
    }

    /**
     * Throw an exception if no search core was provided in the Config
     *
     * @throws NoCoreFoundException
     */
    private function assertConfigHasCore(): void
    {
        if(is_null($this->config->getClient()->getEndpoint('localhost')->getCore())) {
            throw new NoCoreFoundException;
        }
    }

    /**
     * Throw an exception if at least one of the provided documents isn't actually a Document
     *
     * @param array $documents
     * @throws \InvalidArgumentException
     */
    private function assertDocumentsAreValid(array $documents): void
    {
        foreach($documents as $document) {

            if(! $document instanceof Document) {
                throw new \InvalidArgumentException(
                    "One or more indexing documents aren't of type: Solarium\QueryType\Update\Query\Document"
                );
            }

        }
    }
}
