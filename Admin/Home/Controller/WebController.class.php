<?php
namespace Home\Controller;
use Think\Controller;
class WebController extends CommonController {
     
   //加载编辑表单
    public function edit(){

        //获取要编辑的信息
        

        $mod =M("web"); 
        $ob = $mod->find();
       
        //放置模板中
        $this->assign("v",$ob);
        //加载编辑模板
        $this->display("edit");
    }

    //执行信息修改
    public function update(){
        
        //实例化
        $mod =M("web");

        if($mod->getById(1) == null)
        {
            //自动验证
          $rules = array(

             array('webname','/\S+/','网站名称必须填写！',1,'regex'),// 网站配置内容必须
             array('keywords','/\S+/','关键字必须填写！',1,'regex'),// 网站配置内容必须
             array('description','/\S+/','网站描述必须填写！',1,'regex'),// 网站配置内容必须
             array('address','/\S+/','公司地址必须填写！',1,'regex'),// 网站配置内容必须
             array('phone','/\S+/','联系电话必须填写！',1,'regex'),// 网站配置内容必须
             array('email','/\S+/','Email必须填写！',1,'regex'),// 网站配置内容必须
             array('bnum','/\S+/','底部版权信息必须填写！',1,'regex'),// 网站配置内容必须
             array('copyright','/\S+/','备案号必须填写！',1,'regex'),// 网站配置内容必须
             array('email','email','Email格式错误！',1),// 网站配置内容必须
           
          );  
         

          if (!$mod->validate($rules)->create()){
               // 如果创建失败 表示验证没有通过 输出错误提示信息
               
               $this->error($mod->getError());
          }
          
          //执行添加
          $m = $mod->add();
          
          //判断并输出对应的提示信息
          if($m!==false){
             $this->success("添加成功！",U("Web/edit"));
          }else{
             $this->error("添加失败！");
          }
        }else
        {
          $rules = array(
           
             array('webname','/\S+/','网站名称必须填写！',1,'regex'),// 网站配置内容必须
             array('keywords','/\S+/','关键字必须填写！',1,'regex'),// 网站配置内容必须
             array('description','/\S+/','网站描述必须填写！',1,'regex'),// 网站配置内容必须
             array('address','/\S+/','公司地址必须填写！',1,'regex'),// 网站配置内容必须
             array('phone','/\S+/','联系电话必须填写！',1,'regex'),// 网站配置内容必须
             array('email','/\S+/','Email必须填写！',1,'regex'),// 网站配置内容必须
             array('bnum','/\S+/','底部版权信息必须填写！',1,'regex'),// 网站配置内容必须
             array('copyright','/\S+/','备案号必须填写！',1,'regex'),// 网站配置内容必须
             array('email','email','Email格式错误！',1),// 网站配置内容必须
           
          ); 

          if (!$mod->validate($rules)->create()){
               // 如果创建失败 表示验证没有通过 输出错误提示信息
               $this->error($mod->getError());
          }
          
          //执行修改
          $m = $mod->save();
          
          
          //判断并输出对应的提示信息
          if($m!==false){

             $this->success("修改成功！",U("Web/edit"));
          }else{
             $this->error("修改失败！");
          }
        }
        
    }

}