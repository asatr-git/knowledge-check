<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        print 'wergf';
        $response = $this->get('http://commonmind3/elements');
        
        $response->assertStatus(200);

        $response->assertSeeText('test11', $escaped = true);
    }
}
