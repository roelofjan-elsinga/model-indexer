<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Mocks\EmptyConfigMock;
use Tests\Mocks\SearchConfigMock;

class ConfigurationTest extends TestCase
{
    public function testAnEmptyConfigHasNoCore()
    {
        $mock = new EmptyConfigMock();

        $client = $mock->getClient();

        $this->assertNull($client->getEndpoint('localhost')->getCore());
    }

    public function testTheConfigReturnsTheRightCore()
    {
        $mock = new SearchConfigMock();

        $client = $mock->getClient();

        $this->assertSame('search', $client->getEndpoint('localhost')->getCore());
    }
}
