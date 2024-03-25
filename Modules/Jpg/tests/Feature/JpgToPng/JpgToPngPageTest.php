<?php

namespace Modules\Jpg\tests\Feature\JpgToPng;

use Tests\TestCase;

class JpgToPngPageTest extends TestCase
{
    public function test_page_jpg_to_png_success_displayed(): void
    {
        $response = $this->get('/jpg-to-png');

        $response->assertViewHas('title');
        $response->assertSeeText('File Convert - Jpg To Png');

        $response->assertViewHas('typeConvert');
        $response->assertSeeText('JPG to PNG Converter');

        $response->assertStatus(200);
    }
}
