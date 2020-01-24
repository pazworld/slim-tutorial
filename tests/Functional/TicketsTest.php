<?php

namespace Tests\Functional;

class TicketsTest extends BaseTestCase
{
    public function testIndex() {
        $response = $this->runApp('GET', '/tickets');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('チケット一覧', (string)$response->getBody());
    }
    
    public function testStore()
    {
        $response = $this->runApp('POST', '/tickets', ['subject' => 'テストチケット']);

        $id = $this->container['db']->lastInsertId();
        $stmt = $this->container['db']->query('SELECT * FROM tickets WHERE id = ' . $id);
        $ticket = $stmt->fetch();

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/tickets', (string)$response->getHeaderLine('Location'));
        $this->assertEquals('テストチケット', $ticket['subject']);
    }
}
