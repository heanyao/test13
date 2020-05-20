<?php
namespace app\api\model;
use think\Model;
use think\Db;
// use app\index\model\Cate;

class Cases extends Model
{
    
    public function getmsg($datas){

        $map['a.case_id']  = $datas['case_id'];
        $map['a.is_delete']  = 0;
        $map['a.pid']  = 0;
        $order='a.ding_sum desc,a.id desc';
        if($datas['support']===1)
            {
                $order='a.id desc';
            }

        $data=db('case_comments')->where($map)
        ->field('a.id,a.netizen_id,a.pid,a.msg,a.comment_pics,a.create_time,a.ding_sum,b.name,b.head_img_url,b.is_shang')
        ->alias('a')
        ->join('bk_user b','a.netizen_id=b.id')
        ->order($order)
        ->paginate($datas['num']);

        $total= $data->total();
        // dump($aaaa);die;
        $leavemsg = [];
        
        if($data){
            $leavemsg = $this->get_Children_Class(0,$data);     // 递归调用 查询这六条根目录的 所有子分类
            $leavemsg = $this->makeTree($leavemsg);     //  转成有子类格式  children
        }
        $datas_all['total_num'] = $total;
        $datas_all['total_page']= ceil($total / $datas['num']);
        $datas_all['leavemsg'] = $leavemsg;
        return $datas_all; 
    }

    //利用递归将所有的无限级评论都遍历出来
    public function get_Children_Class($pid=0,$childResult="",&$arr=array()){           //  arr 必须要引用
        if($childResult){
            // dump($childResult);die;
            foreach($childResult as $row){
                
                $arr[] = $row;  

         $map['a.pid']  = $row["id"];
         $map['a.is_delete']  = 0;
         $childResult=db('case_comments')->where($map)
        ->field('a.id,a.netizen_id,a.pid,a.msg,a.comment_pics,a.create_time,a.ding_sum,b.name,b.head_img_url,b.is_shang')
        ->alias('a')->join('bk_user b','a.netizen_id=b.id')
        ->order('a.ding_sum desc')
        ->select();
                if($childResult){ 
                    $this->get_Children_Class($row["id"],$childResult,$arr);
                }
            }
        }
        return $arr;
    }


    /**
     * 无限分类，结构化 children
    */
    public function makeTree($data){
        // dump($data);die;
        foreach ($data as $row) {
            $datas[$row["id"]]=$row;
        }
        $tree = [];
        if(isset($datas)){
        foreach ($datas as $id=>$area){
            if(isset($datas[$area["pid"]])){
                $datas[$area["pid"]]["children"][] = &$datas[$id];
             } else {
                $tree[] = &$datas[$area["id"]];
             }
         }            
        }

        return $tree;
    }

   //图片上传处理
    public function upload(){
        $files = request()->file('image');
        // dump($files);die;
        if(!$files){
            return '';
        }
        $imageStr = '';
        foreach($files as $file){
            // 移动到框架应用根目录/public/uploadscmt/ 目录下
            $info = $file->validate(['size'=>156780,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploadscmt');
            if($info) {
                $imageStr .= DS . 'uploads'. DS .$info->getSaveName() . ',';
            } else {
                // 上传失败获取错误信息
                // $file->getError();
            }    
        }
        return rtrim($imageStr, ',');
    }


}
