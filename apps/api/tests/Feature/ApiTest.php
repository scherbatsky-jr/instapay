<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRootEndPoint()
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        $data = $response->original;

        $this->assertEquals('SiamCarDeal-Services API', $data['title']);
        $this->assertEquals(
            config('app.version').'+'.config('app.build'),
            $data['version']
        );
    }
}
