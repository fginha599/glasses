<?php
namespace Home\Controller;
use Think\Controller;
class AdminController extends Controller {
	/**
	*	管理员列表
	**/
    public function AdminList(){
        $this->display("admin/adminList");
    }
}