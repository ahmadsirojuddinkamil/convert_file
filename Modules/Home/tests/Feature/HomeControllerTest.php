<?php

namespace Modules\Home\tests\Feature;

use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    public function test_home_page_success_displayed(): void
    {
        $response = $this->get('/');
        $response->assertViewHas('comments');
        $comments = $response->original->getData()['comments'];
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $comments);
        $response->assertStatus(200);
    }
}
