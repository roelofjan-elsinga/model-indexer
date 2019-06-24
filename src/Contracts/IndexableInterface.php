<?php

namespace Tubber\Indexer\Contracts;

use Solarium\QueryType\Update\Query\Document;

interface IndexableInterface
{
    /**
     * Get an array of documents that need to be indexed for this object
     *
     * @return array|Document[]
     */
    public function indexingDocuments(): array;

    /**
     * Mark this object as indexed
     *
     * @return void
     */
    public function markAsIndexed(): void;
}
