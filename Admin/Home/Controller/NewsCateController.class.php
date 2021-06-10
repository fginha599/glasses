<?php
namespace Home\Controller;
use Think\Controller;
class NewsCateController extends CommonController {
   
    //浏览新闻分类信息
    public function index(){
       //实例化新闻分类信息操作对象
       $mod = M("news_cate");
       $count      = $mod->count();// 查询满足要求的总记录数
       $Page       = new \Think\Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数(25)

       $Page -> setConfig('header','共%TOTAL_ROW%条');
       $Page -> setConfig('first','首页');
       $Page -> setConfig('last','共%TOTAL_PAGE%页');
       $Page -> setConfig('prev','上一页');
       $Page -> setConfig('next','下一页');
       $Page -> setConfig('link','indexpagenumb');//pagenumb 会替换成页码
       $Page -> setConfig('theme','%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');

       $show       = $Page->show();// 分页显示输出
       // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
       $list = $mod->limit($Page->firstRow.','.$Page->listRows)->select();

       
       //放置模板中
       $this->assign("list",$list);
       $this->assign('page',$show);// 赋值分页输出
       //加载模板输出
       $this->display("index");
    }

    //加载添加表单
    public function add(){
       $this->display("add"); 
    }

    //执行信息添加
    public function insert(){
        //实例化
        $mod = M("news_cate");
        //自动验证
        $rules = array(
              
         array('name','require','分类名必须填写！'), // 分类名必须
         array('name','','分类名称已经存在！',1,'unique',1), // 验证分类名是否已经存在
         array('keyword','require','关键字必须填写！'), // 关键字必须   
        );  
       


        if (!$mod->validate($rules)->create()){
             // 如果创建失败 表示验证没有通过 输出错误提示信息
             $this->error($mod->getError());
        }
         
        //执行添加
        $m = $mod->add();
        //判断并输出对应的提示信息
        if($m>0){
           $this->success("添加成功！",U("NewsCate/add"));
        }else{
           $this->error("添加失败！");
        }
    }

    //加载编辑表单
    public function edit(){
        //获取要编辑的信息
        $mod =M("news_cate"); 
        $ob = $mod->find($_GET['id']);
        //放置模板中
        $this->assign("v",$ob);
        //加载编辑模板
        $this->display("edit");
    }

    //执行信息修改
    public function update(){
        //实例化
        $mod =M("news_cate");
        //自动验证
        $rules = array(
              
         array('name','require','分类名必须填写！'), // 分类名必须
         array('name','','分类名称已经存在！',1,'unique',2), // 验证分类名是否已经存在 
         array('keyword','require','关键字必须填写！'), // 关键字必须  
        );  
       


        if (!$mod->validate($rules)->create()){
             // 如果创建失败 表示验证没有通过 输出错误提示信息
             
             $this->error($mod->getError());
        }
         
        //执行修改
        $m = $mod->save();
        //判断并输出对应的提示信息
        if($m!==false){
           $this->success("修改成功！",U("NewsCate/index"));
        }else{
           $this->error("修改失败！");
        }
    }

    //执行信息删除
    public function del(){
       //实例化
       $mod = M('news');
       //查询分类下是否有文章
       
       $num = $mod->where('cid='.$_GET['id'])->count();
       
       if($num)
       {
          $this->error("该分类下有新闻，删除失败！请先删除该分类下的新闻！");
       }
       //实例化
       $mod = M("news_cate");
       // echo $_GET['id'];die;
       //执行删除
       $m = $mod->delete($_GET['id']+0);
       //判断并输出对应的提示信息
       if($m>0){
           $this->success("删除成功！",U("NewsCate/index"));
       }else{
           $this->error("删除失败！");
       }
    }
}