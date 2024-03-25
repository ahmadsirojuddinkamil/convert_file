<?php

namespace Modules\Jpg\tests\Feature\JpgToPdf;

use Tests\TestCase;

class JpgToPdfPageTest extends TestCase
{
    public function test_page_jpg_to_pdf_success_displayed(): void
    {
        $response = $this->get('/jpg-to-pdf');

        $response->assertViewHas('title');
        $response->assertSeeText('File Convert - Jpg To Pdf');

        $response->assertViewHas('typeConvert');
        $response->assertSeeText('JPG to PDF Converter');

        $response->assertStatus(200);
    }
}
