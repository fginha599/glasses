<?php
namespace Home\Controller;
use Think\Controller;
class NewsController extends CommonController {
     
     //浏览新闻信息
    public function index(){
       
       //判断是否有关键字传值
       if($key = $_GET['key'])
       {
          $map['title'] = array('like','%'.$key.'%');
       }
       //判断是否有时间值传递
       if($_GET['start'] && $_GET['end'])
       {
          
          $start = strtotime($_GET['start']." 00:00:00");
          $end = strtotime($_GET['end']." 23:59:59");
          // var_dump($start);die;
          // var_dump($end);die;
          $map['addtime'] = array(array('EGT',$start),array('ELT',$end),'AND');
          
       }
       //判断是否有分类值传递
       if($cid = $_GET['cid'])
       {
          $map['cid'] = array('EQ',$cid);
       }
       //复合查询
        
        // $where['_complex'] = $map;
        
        //实例化新闻信息操作对象
       $mod = M("news");
       $count = $mod->where($map)->count();// 查询满足要求的总记录数
       
       $Page = new \Think\Page($count,2);// 实例化分页类 传入总记录数和每页显示的记录数(25)

       $Page -> setConfig('header','共%TOTAL_ROW%条');
       $Page -> setConfig('first','首页');
       $Page -> setConfig('last','共%TOTAL_PAGE%页');
       $Page -> setConfig('prev','上一页');
       $Page -> setConfig('next','下一页');
       $Page -> setConfig('link','indexpagenumb');//pagenumb 会替换成页码
       $Page -> setConfig('theme','%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
       
       $show = $Page->show();// 分页显示输出
       // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
       $list=M('news')->join('news_cate cate ON news.cid=cate.id')->field("news.id,news.cid,news.title,news.pic,news.author,news.addtime,news.keywords,news.abs,cate.name")->order('news.addtime desc')->limit($Page->firstRow.','.$Page->listRows)->where($map)->select();
       // var_dump($list);
       // $list = $mod->limit($Page->firstRow.','.$Page->listRows)->select();
       //放置模板中
       
       $this->assign("list",$list);
       $this->assign('page',$show);// 赋值分页输出
       //查询分类
        $mod = M('news_cate');
        $news_cate = $mod -> select();
        $this -> assign('news_cate',$news_cate);
       //加载模板输出
       $this->display("index");
    }

    //加载添加表单
    public function add(){
        $mod = M('news_cate');
        $news_cate = $mod -> select();
        $this -> assign('news_cate',$news_cate);
       $this->display("add"); 
    }

    //执行信息添加
    public function insert(){
        //实例化
        $mod = M("news");
        //自动验证
        $rules = array(
              
         array('content','/\S+/','新闻内容必须填写！',1,'regex',1),// 新闻内容必须
         array('title','/\S+/','标题必须填写！',1,'regex',1),// 新闻内容必须
         array('author','/\S+/','文章作者必须填写！',1,'regex',1),// 新闻内容必须
         array('keywords','/\S+/','关键字必须填写！',1,'regex',1),// 新闻内容必须
         array('abs','/\S+/','文章描述必须填写！',1,'regex',1),// 新闻内容必须
         
        );  
       

        // var_dump(!$mod->validate($rules)->create());die;
        if (!$mod->validate($rules)->create()){
             // 如果创建失败 表示验证没有通过 输出错误提示信息
             $this->error($mod->getError());
        }

        //判断有没有文件上传
        if($_FILES['pic']['error'] == 0)
        {
          //文件上传
          $upload = new \Think\Upload();// 实例化上传类
          $upload->maxSize   =     3145728 ;// 设置附件上传大小
          $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
          $upload->rootPath = './Public/Uploads/'; // 设置附件上传根目录
          // 上传单个文件 
          $info   =   $upload->uploadOne($_FILES['pic']);
          if(!$info) {// 上传错误提示错误信息
              $this->error($upload->getError());
          }else{// 上传成功 获取上传文件信息
               $mod->pic = $info['savepath'].$info['savename'];
          }
        }

        //获取添加时间
        $mod->addtime = time();
        //执行添加
        $m = $mod->add();
        //判断并输出对应的提示信息
        if($m>0){
           $this->success("添加成功！",U("News/add"));
        }else{
           $this->error("添加失败！");
        }
    }

    //加载编辑表单
    public function edit(){

        //获取要编辑的信息
        $mod = M('news_cate');
        $news_cate = $mod -> select();
        $this -> assign('news_cate',$news_cate);

        $mod =M("news"); 
        $ob = $mod->find($_GET['id']);
        // var_dump($ob);die;
        $ob['content'] = html_entity_decode($ob['content']);

        //放置模板中
        $this->assign("v",$ob);
        //加载编辑模板
        $this->display("edit");
    }

    //执行信息修改
    public function update(){
        //实例化
        $mod =M("news");
        //自动验证
        $rules = array(
              
           array('content','/\S+/','新闻内容必须填写！',1,'regex',1),// 新闻内容必须
           array('title','/\S+/','标题必须填写！',1,'regex',1),// 新闻内容必须
           array('author','/\S+/','文章作者必须填写！',1,'regex',1),// 新闻内容必须
           array('keywords','/\S+/','关键字必须填写！',1,'regex',1),// 新闻内容必须
           array('abs','/\S+/','文章描述必须填写！',1,'regex',1),// 新闻内容必须
           
        );  
       

        // var_dump(!$mod->validate($rules)->create());die;
        if (!$mod->validate($rules)->create()){
             // 如果创建失败 表示验证没有通过 输出错误提示信息
             $this->error($mod->getError());
        }

        //判断有没有文件上传
        if($_FILES['pic']['error'] == 0)
        {
          //文件上传
          $upload = new \Think\Upload();// 实例化上传类
          $upload->maxSize   =     3145728 ;// 设置附件上传大小
          $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
          $upload->rootPath = './Public/Uploads/'; // 设置附件上传根目录
          // 上传单个文件 
          $info   =   $upload->uploadOne($_FILES['pic']);
          if(!$info) {// 上传错误提示错误信息
              $this->error($upload->getError());
          }else{// 上传成功 获取上传文件信息
               $mod->pic = $info['savepath'].$info['savename'];
          }
        }

        //获取原图片地址
        $pic = $mod->getFieldById($_GET['id'],'pic');
        //判断是否有该文件
        if(file_exists("./Public/Uploads/".$pic))
        {
          //删除图片
            unlink("./Public/Uploads/".$pic);
        }
        

        //执行修改
        $m = $mod->save();
        //判断并输出对应的提示信息
        if($m!==false){
           $this->success("修改成功！",U("News/index"));
        }else{
           $this->error("修改失败！");
        }
    }

    //执行信息删除
    public function del(){
       //实例化
       $mod =M("news");
       //获取原图片地址
            $pic = $mod->getFieldById($_GET['id'],'pic');
       //判断是否有该文件
            dump(unlink("/Public/Uploads/".$pic));die;
            if(file_exists("./Public/Uploads/".$pic))
            {     
              
              //删除图片
                unlink("/Public/Uploads/".$pic);
            }
       //执行删除
       // $m = $mod->delete($_GET['id']+0);
       //判断并输出对应的提示信息
       if($m>0){
            

           $this->success("删除成功！",U("News/index"));
       }else{
           $this->error("删除失败！");
       }
    }
}