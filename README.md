# Solr indexer

This package makes it simple to index large amounts of data to Solr.

## Installation

You can include this package through Composer using:

```bash
composer require tubber/model-indexer
```

## Usage

To be able to index any documents, you first need a class that implements the ``IndexableInterface``:

```php
use Solarium\QueryType\Update\Query\Document;

class IndexableModel implements \Tubber\Indexer\Contracts\IndexableInterface
{

    /**
     * Get an array of documents that need to be indexed for this object
     *
     * @return array|Document[]
     */
    public function indexingDocuments(): array
    {
        return [
            new Document([
                'id' => 1,
                'name' => 'document 1'
            ]),
            new Document([
                'id' => 2,
                'name' => 'document 2'
            ]),
        ];
    }

    /**
     * Mark this object as indexed
     *
     * @return void
     */
    public function markAsIndexed(): void
    {
        return; // Perform some kind of action to indicate this object has been indexed
    }
}
```

This class is now ready to be indexed. However, you will also need to provide the 
ModelIndexer with some kind of configuration, so it knows where to send your documents. 
All you need to do is create a new class that implements SolrConfigInterface:

```php
use Solarium\Client;
use Tubber\Indexer\Contracts\SolrConfigInterface;

class SearchConfig implements SolrConfigInterface
{

    /**
     * Get the client for this Solr interaction
     *
     * @return Client
     */
    public function getClient(): Client
    {
        $client = new Client();

        $client
            ->getEndpoint('your_solarium_endpoint_name')
            ->setCore('your_core_name');

        return $client;
    }

    /**
     * Reload the core that's provided in $this->getClient
     *
     * @return void
     */
    public function reloadCollection(): void
    {
        // You can implement your own way of reloading the collection
        // This method is call after indexing has finished
    }
}
```

Now you can index your documents by running the ModelIndexer ``perform()`` method:

```php

/**@var array|Document[] $documents*/
$documents = [
    new IndexableModel()
];

ModelIndexer::forModels($documents, new SearchConfig)->perform();
```

## Available methods

```php
/**
 * Set the collection of models that need to be indexed
 *
 * @param array $models
 * @param SolrConfigInterface $config
 * @return ModelIndexer
 * @throws NoCoreFoundException
 */
public static function forModels(array $models, SolrConfigInterface $config): ModelIndexer;
```

```php
/**
 * Perform the indexing for the models in this class
 *
 * @throws \InvalidArgumentException
 */
public function perform(): void;
```

## Testing

You can run the included tests by running ``./vendor/bin/phpunit`` in your terminal.