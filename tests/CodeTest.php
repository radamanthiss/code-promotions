<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CodeTest extends TestCase
{
    /** @test */
    public function testgetCode()
    {
        $this->get("code/2");
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            ['data' =>
                
                [
                    'id',
                    'code',
                    'starts_on',
                    'ends_on',
                    'coupon_type',
                    'state',
                    'quantity_travel'
                ]
                
                
            ]
            
        );
    }
    
}
