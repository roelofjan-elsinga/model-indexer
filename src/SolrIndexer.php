<?php

namespace Tubber\Indexer;

use Solarium\Core\Plugin\PluginInterface;
use Solarium\Plugin\BufferedAdd\BufferedAdd;
use Tubber\Indexer\Contracts\IndexableInterface;
use Tubber\Indexer\Contracts\SolrConfigInterface;

class SolrIndexer
{
    /**@var array|IndexableInterface[]*/
    private $models;

    /**@var SolrConfigInterface*/
    private $config;

    /**@var BufferedAdd*/
    private $buffer;

    private function __construct(array $models, SolrConfigInterface $config)
    {
        $this->models = $models;

        $this->config = $config;

        $this->buffer = $this->createBuffer();
    }

    /**
     * Set the collection of models that need to be indexed
     *
     * @param array $models
     * @param SolrConfigInterface $config
     * @return SolrIndexer
     */
    public static function forModels(array $models, SolrConfigInterface $config): SolrIndexer
    {
        return new static($models, $config);
    }

    /**
     * Perform the indexing for the models in this class
     */
    public function perform(): void
    {
        $this->buffer->setBufferSize(2500);

        foreach ($this->models as $model) {

            $documents = $model->indexingDocuments();

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
}
