<?php

namespace Modules\Png\tests\Feature\PngToPdf;

use Tests\TestCase;

class PngToPdfPageTest extends TestCase
{
    public function test_page_png_to_pdf_success_displayed(): void
    {
        $response = $this->get('/png-to-pdf');

        $response->assertViewHas('title');
        $response->assertSeeText('File Convert - Png To Pdf');

        $response->assertViewHas('typeConvert');
        $response->assertSeeText('PNG to PDF Converter');

        $response->assertStatus(200);
    }
}
