<?php

namespace Tests\Mocks;

use Solarium\QueryType\Update\Query\Document\Document;
use Tubber\Indexer\Contracts\IndexableInterface;

class UnIndexableMock implements IndexableInterface
{

    /**
     * Get an array of documents that need to be indexed for this object
     *
     * @return array|Document[]
     */
    public function indexingDocuments(): array
    {
        // This returns an array, thus satisfying the return type of the interface, but they're not Documents

        return [
            [
                'id' => 1,
                'name' => 'indexing document'
            ],
            [
                'id' => 2,
                'name' => 'updating document'
            ],
        ];
    }

    /**
     * Mark this object as indexed
     *
     * @return void
     */
    public function markAsIndexed(): void
    {
        // TODO: Implement markAsIndexed() method.
    }
}