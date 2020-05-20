<?php

namespace app\api\controller;
use think\Request;

header('content-type:application/json;charset=utf-8');

class Cases extends Common
{

    private $obj;
    private $uid;

    public function _initialize()
    {
       $this->obj = Model('Cases');
       // $this->uid = (int)session('userinfo.id'); //上线再打开
       $this->uid = 1;
    }
 
    //所有评论获取
    public function getmessages()
    {
        //加载渲染时的评论数据
        if (Request::instance()->isPost()){
            $datas['case_id']=input('post.case_id');  
            $datas['support']=(int)input('post.support');

                if (!isset($datas['num'])) {
                    $datas['num'] = 10;
                }

                if (!isset($datas['support'])) {
                    $datas['support'] = '';
                }
                // dump($datas);die;

            $res=$this->obj->getmsg($datas);
        };

        if ($res == null) {
            $this->returnMsg(400, '暂无数据！');
        } 
            //响应数据给客户端
            $this->returnMsg(200, '操作成功！', $res);
    }


    //一级评论提交
    public function leavemessages()
    {
 
        //POST提交时提交的评论数据
        if (Request::instance()->isPost()){
            // $this->checkLogin();//上线再打开
            $datas['case_id']=input('post.case_id'); 
            $datas['msg']=input('post.msg');   
            $datas['netizen_id']= $this->uid;  


            $datas['create_time'] = time();
            //图片上传
            $datas['comment_pics']=$this->obj->upload();

            // dump($datas);die;

            //2. 往数据库插入文章信息
            $res = db('case_comments')->insertGetId($datas);   
        }

        if ($res == null) {
            $this->returnMsg(400, '暂无数据！');
        } 
            //响应数据给客户端
            $this->returnMsg(200, '操作成功！', $res);
    }


    //二级及以上的评论提交
    public function subcomments()
    {
         
        //POST提交时提交的评论数据
        if (Request::instance()->isPost()){
            // $this->checkLogin();//上线再打开
            $postdata['pid']= (int)input('post.commentid');
            $postdata['case_id']= (int)input('post.case_id');
            $postdata['msg']= input('post.msg');
            $postdata['netizen_id']= $this->uid;     
            $postdata['create_time'] = time();

            //图片上传
            $datas['comment_pics']=$this->obj->upload();

            //2. 往数据库插入文章信息
            $res = db('case_comments')->insertGetId($postdata);   

            }
 
        if ($res == null) {
            $this->returnMsg(400, '暂无数据！');
        } 
            //响应数据给客户端
            $this->returnMsg(200, '操作成功！', $res);
    }

}
