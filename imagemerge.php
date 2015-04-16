<?php
$testImages = new ImageMerger();
$testImages->mergeBarcodeImages('photo.png', 1000, 1000, 'barcode.png', 75, 75, 10, 210, 'Text Stuff', 150, 25, 5);

class ImageMerger	{

	private $imageURL,
			$imageW,
			$imageH,
			$imageBarcode,
			$barcodeW,
			$barcodeH,
			$horizontalLoc,
			$verticalLoc,
			$text,
			$textHorizontal,
			$textVertical,
			$textSize;
			
	public function ImageMerger()	{
	}
			
	private function resize_image($file, $w, $h, $crop=FALSE) {
		list($width, $height) = getimagesize($file);
		$r = $width / $height;
		if ($crop) {
			if ($width > $height) {
				$width = ceil($width-($width*abs($r-$w/$h)));
			} else {
				$height = ceil($height-($height*abs($r-$w/$h)));
			}
			$newwidth = $w;
			$newheight = $h;
		} else {
			if ($w/$h > $r) {
				$newwidth = $h*$r;
				$newheight = $h;
			} else {
				$newheight = $w/$r;
				$newwidth = $w;
			}
		}
		$src = imagecreatefrompng($file);
		$dst = imagecreatetruecolor($newwidth, $newheight);
		imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		return $dst;
	}
			
	public function mergeBarcodeImages($URL, $iW, $iH, $barcode, $bW, $bH, $hLoc, $vLoc, $txt, $txtH, $txtV, $txtSize)	{
		$imageURL = $URL;
		$imageW = $iW;
		$imageH = $iH;
		$imageBarcode = $barcode;
		$barcodeW = $bW;
		$barcodeH = $bH;
		$horizontalLoc = $hLoc;
		$verticalLoc = $vLoc;
		$text = $txt;
		$textHorizontal = $txtH;
		$textVertical = $txtV;
		$textSize = $txtSize;
	
		$image = $this->resize_image($imageURL, $imageW, $imageH);
		$barcode = $this->resize_image($imageBarcode, $barcodeW, $barcodeH);

		imagestring($image, $textSize, $textHorizontal, $textVertical, $text, 0x0000FF);
		imagecopymerge($image, $barcode, $horizontalLoc, $verticalLoc, 0, 0, imagesx($barcode), imagesy($barcode), 100);

		imagepng($image, 'barcoded_image.png');
		imagedestroy($image);
	}
}
?>
