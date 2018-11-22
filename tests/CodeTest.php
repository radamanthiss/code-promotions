<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CodeTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCode()
    {
        $this->get('code/2');
        
        $this->assertEquals(
            $this->app->version(), $this->response->getContent()
            );
    }
}
