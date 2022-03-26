<?php
namespace Home\Controller;

use Think\Controller;
class UserController extends BaseController {
	
    /**
     * 用户注册
     * @return [type] [description]
     */
    //注册接口
    public function sign(){

    	// 校验用户名不为空
        if(!$_POST['username'] ){
        	$return_data = array();
        	$return_data['error_code'] = 1;
        	$return_data['msg'] = '参数不足: username';
        	var_dump($_POST);

        	$this->ajaxReturn($return_data);
        }

        // 校验手机号不为空
        if(!$_POST['phone']){
        	$return_data = array();
        	$return_data['error_code'] = 1;
        	$return_data['msg'] = '参数不足: phone';

        	$this->ajaxReturn($return_data);
        }

        // 校验密码不为空
        if(!$_POST['password']){
        	$return_data = array();
        	$return_data['error_code'] = 1;
        	$return_data['msg'] = '参数不足: password';

        	$this->ajaxReturn($return_data);
        }

        // 校验密码重复
        if(!$_POST['password_again']){
        	$return_data = array();
        	$return_data['error_code'] = 1;
        	$return_data['msg'] = '参数不足: password_again';

        	$this->ajaxReturn($return_data);
        }

		// 校验密码是否不一致
        if($_POST['password'] != $_POST['password_again'] ){
        	$return_data = array();
        	$return_data['error_code'] = 2;
        	$return_data['msg'] = '参数不足: 密码不一致';

        	$this->ajaxReturn($return_data);
        }
        
        $User = M('user');

        // 校验手机号是否已存在
        $where = array();
        $where['phone'] = $_POST['phone']; 

        $user = $User->where($where)->find();

        if($user){

			$return_data = array();
        	$return_data['error_code'] = 3;
        	$return_data['msg'] = '手机号已被注册';

        	$this->ajaxReturn($return_data);
        }
        else{
        	// 如果用户尚未注册，则注册
        	// 构建插入的数据
        	$data = array();
        	$data['username'] = $_POST['username']; // 用户名
        	$data['phone'] = $_POST['phone'];
        	$data['password'] = md5($_POST['password']);
        	$data['face_url'] = $_POST['face_url'];

    		$this->ajaxReturn($return_data);

        	//插入数据
        	$result = $User->add($data);
        	//添加成功后，返回数据id

        	if($result){
				$return_data = array();
        		$return_data['error_code'] =0;
        		$return_data['msg'] = '注册成功';
	    		$return_data['data']['user_id'] = $result;
	    		$return_data['data']['username'] = $_POST['username'];
	    		$return_data['data']['phone'] = $_POST['phone'];
	    		$return_data['data']['face_url'] = $_POST['face_url'];

	    		$this->ajaxReturn($return_data);
        	}
        	else{
        		//插入数据之星失败
        		$return_data = array();
        		$return_data['error_code'] =4;
        		$return_data['msg'] = '注册失败';
				$this->ajaxReturn($return_data);
        	}

        }
	}

    /**
     * 用户登陆
     * @return [type] [description]
     *      
     * */
    
    public function login(){

    	//校验是否参数存在
    	if(!$_POST['phone']){

    		$return_data = array();
    		$return_data['error_code'] = 1;
    		$return_data['msg'] = '参数不足: phone';

    		$this->ajaxReturn($return_data);

    	}

        // 校验密码不为空
        if(!$_POST['password']){
        	$return_data = array();
        	$return_data['error_code'] = 1;
        	$return_data['msg'] = '参数不足: password';

        	$this->ajaxReturn($return_data);
        }


        // 查询用户
        $User = M('User');

        $where = array();
        $where['phone'] = $_POST['phone'];

        $user = $User->where($where)->find();

        if($user){
        	// 如果查询到用户的手机号
        	// dump($user);
        	if(md5($_POST['password']) != $user['password']){
        		// 密码不一致
        		$return_data = array();
        		$return_data['error_code'] = 3;
        		$return_data['msg'] = '密码或手机号不正确，请重新输入';

        		$this->ajaxReturn($return_data);

        	}
        	else{
        		// 如果密码一致
        		$return_data = array();
        		$return_data['error_code'] = 0;
        		$return_data['msg'] = '登录成功';

        		$return_data['data']['user_id'] = $user['id'];
        		$return_data['data']['username'] = $user['username'];
        		$return_data['data']['phone'] = $user['phone'];
        		$return_data['data']['face_url'] = $user['face_url'];

        		$this->ajaxReturn($return_data);
        	}

        }
        else{
        	$return_data = array();
        	$return_data['error_code'] = 2;
        	$return_data['msg'] = '不存在该手机号用户，请注册';

        	$this->ajaxReturn($return_data);
        }

    	dump($_POST);
    }

}


