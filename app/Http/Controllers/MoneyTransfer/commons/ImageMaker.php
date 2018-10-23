<?php
	namespace App\Http\Controllers\MoneyTransfer\commons;

	use Illuminate\Http\Request;
	use App\Http\Controllers\Controller;
	class ImageMaker extends Controller
	{
		public function base64ToImage($pathToStore,$imageName)
		{
		   	$imageData='';
			$file_data = $imageName; 
			$file_name = uniqid().'.png'; //generating unique file name; 
			@list($type, $file_data) = explode(';', $file_data);
			@list(, $file_data) = explode(',', $file_data); 
			if($file_data!=""){ 
		   		$image = public_path() . "\\".$pathToStore."\\" . $file_name;
		   		$imageData="\\".$pathToStore."\\" . $file_name;
		        file_put_contents($image,base64_decode($file_data)); 
		    } 
		    return $imageData;
		}
		public function deleteFile($filename)
		{
	        $filepath = public_path().$filename;
	        $fileDelete=\File::delete($filepath);
	        return $fileDelete;
		}
	}
?>