<?php

header("Content-type:image/png");
include '../phpqrcode/phpqrcode.php';

function scerweima($url=''){
    $value = $url;         //二维码内容
  
    $errorCorrectionLevel = 'L';  //容错级别
  
    $matrixPointSize = 5;      //生成图片大小
  
    //生成二维码图片
  //  ob_start();
     $QR = QRcode::png($value,false,$errorCorrectionLevel, $matrixPointSize, 2);
   // $imageString = base64_encode(ob_get_contents());
	//关闭缓冲区
   // ob_end_clean();
   //return "data:image/jpg;base64,".$imageString;
 }
 scerweima("11");
 //$base64_img= scerweima("11");
 //echo '<img src="'.$base64_img.'" />';

/*
$img = 'F:/workspaces/1.jpg';
$base64_img = base64EncodeImage($img);
function base64EncodeImage ($image_file) {
   $base64_image = '';
   $image_info = getimagesize($image_file);
   $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
   $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
   return $base64_image;
}


echo '<img src="'.$base64_img.'" />';
*/
?>