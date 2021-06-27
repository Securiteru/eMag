<?php


namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\DatabasePrimer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class CustomerTest extends ApiTestCase
{

    public function testGetCustomers(){

        $this->client=self::createClient();
        $this->client->request(Request::METHOD_GET,'/api/customers');
        $response=$this->client->getResponse()->getStatusCode();
        var_dump($response);
//        dd($this->client->getResponse());
    }
}