<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TransactionControllerTest extends WebTestCase
{
    public function testaddTransaction()
    {
       $client = static::createClient([],[
            'PHP_AUTH_USER'=>'yahya@',
            'PHP_AUTH_PW'=>'01234'
         ]);

        $client->request('POST', '/api/transaction',[],[],
        ['CONTENT TYPE'=>'application/json'],
        '{
            "envoie":180000,
            "retrait":3,
            "envoie":9,
            "user":2
        }
        ');

        $rep=$client->getResponse();
        var_dump($rep);
        $this->assertSame(201,$client->getResponse()->getStatusCode());
    
    }
}
