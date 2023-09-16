<?php

namespace Tests\Support;

use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;

trait TestClient
{
    use MakesHttpRequests;

    protected function graphql(string $query)
    {
        return $this->post(
            '/graphql',
            [
                'query' => $query,
            ]
        );
    }
}
