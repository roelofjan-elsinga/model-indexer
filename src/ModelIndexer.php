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

    /**@var int*/
    private $buffer_size;

    /**@var int*/
    private $commit_within;

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

        $this->buffer_size = 100;

        $this->commit_within = 10000;
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
        $this->buffer->setBufferSize($this->buffer_size);

        foreach ($this->models as $model) {

            $this->assertDocumentsAreValid(
                $documents = $model->indexingDocuments()
            );

            $this->buffer->addDocuments($documents);

            $model->markAsIndexed();

        }

        $this->buffer->flush(true, $this->commit_within);

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

    /**
     * Set the buffer size
     *
     * @param int $buffer_size
     * @return ModelIndexer
     */
    public function setBufferSize(int $buffer_size = 100): ModelIndexer
    {
        $this->buffer_size = $buffer_size;
        return $this;
    }

    /**
     * Get the buffer size
     *
     * @return int
     */
    public function getBufferSize(): int
    {
        return $this->buffer_size;
    }

    /**
     * Set the commit within value
     *
     * @param int $commit_within
     * @return ModelIndexer
     */
    public function setCommitWithin(int $commit_within = 10000): ModelIndexer
    {
        $this->commit_within = $commit_within;
        return $this;
    }

    /**
     * Get the commit within value
     *
     * @return int
     */
    public function getCommitWithin(): int
    {
        return $this->commit_within;
    }
}
