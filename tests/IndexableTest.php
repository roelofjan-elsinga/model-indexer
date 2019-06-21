<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Solarium\QueryType\Update\Query\Document;
use Tests\Mocks\IndexableMock;

class IndexableTest extends TestCase
{

    public function testIndexableObjectContainsDocuments()
    {
        $mock = new IndexableMock();

        $documents = $mock->indexingDocuments();

        $this->assertEquals(get_class($documents[0]), Document::class);
    }

}