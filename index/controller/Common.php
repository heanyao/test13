<?php
namespace app\index\controller;
use think\Controller;

class Common extends Controller {

	public function _initialize() {

		//当前位置
		
		$res = session('userinfo');
		//未读消息数量
		if($res){
			$res['unread_num'] = db('messages')->where(['to_uid' => $res['id'], 'status' => 2])->count();
			session('userinfo', $res);
		}
		

	}

	// 判断是否登录
	function checkLoginTp5() {
		$userId = session('userinfo.id');
		if (empty($userId)) {
			$this->error('请先登录', url('index/user/c_login'));
		}
	}

}
