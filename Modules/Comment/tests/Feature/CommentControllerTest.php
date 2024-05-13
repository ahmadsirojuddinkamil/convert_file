<?php

namespace Modules\Comment\tests\Feature;

use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    public function test_create_comment_success(): void
    {
        $response = $this->post('/create-comment', [
            '_token' => csrf_token(),
            'uuid' => Uuid::uuid4()->toString(),
            'name' => 'kamil',
            'comment' => 'success convert file',
            'star' => 5,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Comment anda berhasil dibuat!', session('success'));
    }

    public function test_create_comment_failed_because_form_is_empty(): void
    {
        $response = $this->post('/create-comment', [
            '_token' => csrf_token(),
            'uuid' => Uuid::uuid4()->toString(),
            'name' => '',
            'comment' => '',
            'star' => 5,
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }
}
