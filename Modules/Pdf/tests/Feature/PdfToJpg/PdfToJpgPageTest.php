<?php

namespace Modules\Pdf\tests\Feature\PdfToJpg;

use Tests\TestCase;

class PdfToJpgPageTest extends TestCase
{
    public function test_page_pdf_to_jpg_success_displayed(): void
    {
        $response = $this->get('/pdf-to-jpg');

        $response->assertViewHas('title');
        $response->assertSeeText('File Convert - Pdf To Jpg');

        $response->assertViewHas('typeConvert');
        $response->assertSeeText('PDF to JPG Converter');

        $response->assertStatus(200);
    }
}
