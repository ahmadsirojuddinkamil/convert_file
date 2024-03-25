<?php

namespace Modules\Pdf\tests\Feature\PdfToPng;

use Tests\TestCase;

class PdfToPngPageTest extends TestCase
{
    public function test_page_pdf_to_png_success_displayed(): void
    {
        $response = $this->get('/pdf-to-png');

        $response->assertViewHas('title');
        $response->assertSeeText('File Convert - Pdf To Png');

        $response->assertViewHas('typeConvert');
        $response->assertSeeText('PDF to PNG Converter');

        $response->assertStatus(200);
    }
}
