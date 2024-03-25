<?php

namespace Modules\Png\tests\Feature\PngToJpg;

use Tests\TestCase;

class PngToJpgPageTest extends TestCase
{
    public function test_page_jpg_to_png_success_displayed(): void
    {
        $response = $this->get('/png-to-jpg');

        $response->assertViewHas('title');
        $response->assertSeeText('File Convert - Png To Jpg');

        $response->assertViewHas('typeConvert');
        $response->assertSeeText('PNG to JPG Converter');

        $response->assertStatus(200);
    }
}
