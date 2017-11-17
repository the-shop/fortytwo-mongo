<?php

namespace Framework\Mongo\Test;

use Framework\Base\Test\UnitTest;
use Framework\Mongo\MongoAdapter;
use MongoDB\Client;

class MongoAdapterTest extends UnitTest
{
    public function testGetClient()
    {
        $adapter = new MongoAdapter();
        $mongoClient = new Client();
        // Set the client
        $adapter->setClient($mongoClient);

        $this->assertEquals($mongoClient, $adapter->getClient());
    }
}
