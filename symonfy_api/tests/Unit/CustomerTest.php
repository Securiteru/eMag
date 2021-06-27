<?php


namespace App\Tests;


use App\Entity\Customer;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->customer=new Customer();
    }

    public function testGetEmail(){
        $value="testEmail@provider.com";
        $entity_value=$this->customer->setEmail($value);
        $get_value=$this->customer->getEmail();

        self::assertInstanceOf(Customer::class,$entity_value);

        self::assertEquals($value, $get_value);
    }





}