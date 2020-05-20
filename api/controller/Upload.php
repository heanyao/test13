<?php
namespace app\api\controller;
use app\api\model\Upload as apiUpload;
class Upload extends Common
{

    // public function index(){

    //     return view();
    // }

// //视频封面图片的上传
//     public function uploadimg()
//     {
//          // 获取表单上传文件 例如上传了001.jpg
//         $file = request()->file('file');
// 		$info = $file->validate(['ext' => 'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads',true,false);
// 		if ($info) {
// 			$msg = [
// 				'code' => 200,
// 				'data' => [
// 					'ext' => $info->getExtension(),
// 					'filename' => $info->getFilename(),
// 					'savefile' => $info->getSaveName()
// 				],
// 			];
// 		} else {
// 			echo $file->getError();
// 			exit;
// 		}

// 		// $addfile = new apiUploadvideo();
// 		// $file = $addfile->uploadOne($file);
// 		// echo $file;

// 		//

// 		return json_encode($msg, JSON_UNESCAPED_UNICODE);
//     }

//房源图片的上传
    public function upload()
    {
        // 获取表单上传文件 例如上传了001.jpg
        dump('how are you');die;
        $file = request()->file('file');
        $addfile = new apiUpload();
        $file = $addfile->uploadOne($file);
        $msg=[
            'code'=>200,
            'data'=>$file
        ];
        return json_encode($msg,JSON_UNESCAPED_UNICODE);
    }



}
