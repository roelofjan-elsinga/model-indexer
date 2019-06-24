<?php

namespace Tubber\Indexer;

use Tubber\Indexer\Contracts\IndexableInterface;
use Tubber\Indexer\Contracts\SolrConfigInterface;
use Tubber\Indexer\Exceptions\NoCoreFoundException;

class ModelRemover
{

    /**@var array|IndexableInterface[]*/
    private $models;

    /**@var SolrConfigInterface*/
    private $config;

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
    }

    /**
     * Set the collection of models that need to be indexed
     *
     * @param array|IndexableInterface[] $models
     * @param SolrConfigInterface $config
     * @return ModelIndexer
     * @throws NoCoreFoundException
     */
    public static function forModels(array $models, SolrConfigInterface $config): ModelRemover
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
        $updater = $this->config->getClient()->createUpdate();

        foreach ($this->models as $model) {

            $updater->addDeleteQuery(

                $model->getDeleteQueryFor(

                    $this->config->getClient()->getEndpoint()->getCore()

                )

            );

        }

        $this->config->getClient()->update($updater);

        $this->config->reloadCollection();
    }

    /**
     * Throw an exception if no search core was provided in the Config
     *
     * @throws NoCoreFoundException
     */
    private function assertConfigHasCore(): void
    {
        if(is_null($this->config->getClient()->getEndpoint()->getCore())) {
            throw new NoCoreFoundException;
        }
    }

}