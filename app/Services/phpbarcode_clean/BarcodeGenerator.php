<?php
class BarcodeGenerator {
    public function getBarcode($text, $type = 'code128') {
        $renderer = new PngRenderer();
        $barcode = new Barcode();
        $barcode->setType(new TypeCode128());
        return $renderer->render($barcode->getBars($text));
    }
}
