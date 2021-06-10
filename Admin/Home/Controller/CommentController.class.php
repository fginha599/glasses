<?php
namespace Home\Controller;
use Think\Controller;
class CommentController extends Controller {
	/**
	*	评论管理
	**/
    public function ComList(){
        $this->display("Comment/ComList");
    }
}