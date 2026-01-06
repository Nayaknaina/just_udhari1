<?php
class PngRenderer {
    public function render($bars) {
        $barWidth = 2;
        $barHeight = 60;
        $width = count($bars) * $barWidth;

        $image = imagecreate($width, $barHeight);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);

        foreach ($bars as $i => $bar) {
            if ($bar) {
                imagefilledrectangle($image, $i * $barWidth, 0, ($i + 1) * $barWidth - 1, $barHeight, $black);
            }
        }

        ob_start();
        imagepng($image);
        $imgData = ob_get_clean();
        imagedestroy($image);

        return $imgData;
    }
}
