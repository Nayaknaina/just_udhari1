<?php
class Barcode {
    protected $type;

    public function setType($type) {
        $this->type = $type;
    }

    public function getBars($text) {
        return $this->type->encode($text);
    }
}
