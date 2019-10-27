<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PartenaireControllerTest extends WebTestCase
{
    public function testaddPartenaire()
    {
        $client = static::createClient([],[
            'PHP_AUTH_USER'=>'yahya@',
            'PHP_AUTH_PW'=>'01234'
         ]);

        $client->request('POST', '/api/partenaire',[],[],
        ['CONTENT TYPE'=>'application/json'],
        '{
            "nomentreprise":"Tigocash",
            "ninea":"Gth12548",
            "adresse":"Thiaroye",
            "email":"tigocash@wari.com",
            "telephone":771253698,
            "raisonsocial":"je suis particulier et je gere une boutique",
            "username":"Asta@",
            "password":1230,
            "nom":"babs",
            "prenom":"Ababacar",
            "profile":"user"
        }
        ');

        $rep=$client->getResponse();
        var_dump($rep);
        $this->assertSame(201,$client->getResponse()->getStatusCode());
    }
}
