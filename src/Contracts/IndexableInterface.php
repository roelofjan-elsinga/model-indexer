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

    /**
     * Get the query to delete this object from the given core
     *
     * @param string $search_core
     * @return string
     */
    public function getDeleteQueryFor(string $search_core): string;
}
