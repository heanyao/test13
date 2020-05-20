<?php
namespace app\index\controller;

class Myform extends Common
{

    private $obj;

    public function _initialize()
    {
       $this->obj = Model('Myform');

    }

    public function index()
    {
 
        //本周曝光最多
        $blacklist=$this->obj->get_black_list();
        // dump($blacklist);die;

        $this->assign(array(
            'blacklist'=>$blacklist,
            ));
        return view();
    }


    
}
