<?php

namespace Tests\Functional;

class TicketsTest extends BaseTestCase
{
    public function testIndex() {
        $response = $this->runApp('GET', '/tickets');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('チケット一覧', (string)$response->getBody());
    }
}
