<?php


namespace Tests\Mocks;

use Solarium\QueryType\Update\Query\Document;
use Tubber\Indexer\Contracts\IndexableInterface;

class GeneratorDocumentMocks implements IndexableInterface
{

    /**
     * Get an array of documents that need to be indexed for this object
     *
     * @return \Generator
     */
    public function indexingDocuments()
    {
        yield new Document([
            'id' => 1,
            'name' => 'indexing document'
        ]);

        yield new Document([
            'id' => 2,
            'name' => 'updating document'
        ]);
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

    /**
     * Get the query to delete this object from the given core
     *
     * @param string $search_core
     * @return string
     */
    public function getDeleteQueryFor(string $search_core): string
    {
        return "id: 1";
    }
}
