<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

class ReceiptService
{
    public function generate(array $values): string
    {
        $temporaryFile = tempnam(sys_get_temp_dir(), 'rhu_receipt_');
        if ($temporaryFile === false) {
            throw new \RuntimeException('Unable to create a temporary receipt file.');
        }
        $escaping = Settings::isOutputEscapingEnabled();
        Settings::setOutputEscapingEnabled(true);

        try {
            $template = new TemplateProcessor(resource_path('templates/official-receipt.docx'));
            $template->setValues($values);
            $template->saveAs($temporaryFile);
            $word = IOFactory::load($temporaryFile);

            ob_start();
            try {
                IOFactory::createWriter($word, 'HTML')->save('php://output');
                $html = ob_get_contents();
            } finally {
                ob_end_clean();
            }

            $path = 'receipts/'.Str::uuid().'.pdf';
            if (! Storage::disk('public')->put($path, Pdf::loadHTML($html)->output())) {
                throw new \RuntimeException('Unable to save the receipt PDF.');
            }

            return $path;
        } finally {
            Settings::setOutputEscapingEnabled($escaping);
            if (is_file($temporaryFile)) {
                unlink($temporaryFile);
            }
        }
    }
}
