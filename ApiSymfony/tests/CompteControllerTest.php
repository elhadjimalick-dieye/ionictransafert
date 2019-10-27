<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CompteControllerTest extends WebTestCase
{
    public function testSomething()
    {
        $client = static::createClient([],[
            'PHP_AUTH_USER'=>'yahya@',
            'PHP_AUTH_PW'=>'01234'
         ]);

        $client->request('POST', '/api/compte',[],[],
        ['CONTENT TYPE'=>'application/json'],
        '{
            "montant":180000,
            "partenaire":3,
            "user":9,
            "solde":200000
        }
        ');

        $rep=$client->getResponse();
        var_dump($rep);
        $this->assertSame(201,$client->getResponse()->getStatusCode());
    }
}
