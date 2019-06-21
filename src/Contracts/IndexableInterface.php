<?php

namespace Tubber\Indexer\Contracts;

interface IndexableInterface
{
    /**
     * Get an array of documents that need to be indexed for this object
     *
     * @return array
     */
    public function indexingDocuments(): array;

    /**
     * Mark this object as indexed
     *
     * @return void
     */
    public function markAsIndexed(): void;
}
