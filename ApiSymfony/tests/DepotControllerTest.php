<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DepotControllerTest extends WebTestCase
{
    public function testaddDepot()
    {
        $client = static::createClient([],[
            'PHP_AUTH_USER'=>'liklik',
            'PHP_AUTH_PW'=>'123'
         ]);

        $client->request('POST', '/api/depot',[],[],
        ['CONTENT TYPE'=>'application/json'],
        '{
            "montant":100000,
            "compte":3
        }
        ');

        $rep=$client->getResponse();
        var_dump($rep);
        $this->assertSame(201,$client->getResponse()->getStatusCode());
    }
}
