<?php

namespace Tests\Mocks;

use Solarium\QueryType\Update\Query\Document;

class IndexableMock implements \Tubber\Indexer\Contracts\IndexableInterface
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
                'name' => 'indexing document'
            ]),
            new Document([
                'id' => 2,
                'name' => 'updating document'
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