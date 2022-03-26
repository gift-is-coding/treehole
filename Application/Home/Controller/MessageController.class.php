<?php
namespace Home\Controller;

use Think\Controller;
class MessageController extends BaseController {
	
    /**
     * 发布新的树洞
     * @return [type] [description]
     */
    public function publish_new_message(){
        // 校验参数是否存在
        if(!$_POST['user_id']){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足: user_id';

            $this->ajaxReturn($return_data);
        }
        if(!$_POST['username']){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足: usename';

            $this->ajaxReturn($return_data);
        }
        if(!$_POST['face_url']){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足: face_url';

            $this->ajaxReturn($return_data);
        }
        if(!$_POST['content']){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足: content';

            $this->ajaxReturn($return_data);
        }

        $Message = M('Message');

        // 设置要插入的数据
        $data = array();
        $data['user_id'] = $_POST['user_id'];
        $data['username'] = $_POST['username'];
        $data['face_url'] = $_POST['face_url'];
        $data['content'] = $_POST['content'];
        $data['total_likes'] = 0;
        $data['send_timestamp'] = time();

        // 插入数据
        $result = $Message->add($data);

        if($result){

            $return_data = array();
            $return_data['error_code'] = 0;
            $return_data['msg'] = '数据添加成功';

            $this->ajaxReturn($return_data);
        }
        else{
            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '数据添加失败';

            $this->ajaxReturn($return_data);
        }
        // dump($_POST);


    }

    /**
     * 查看所有树洞
     * @return [type] [description]
     */
    public function get_all_messages(){
        // 实例化数据表
        $Message = M('Message');

        // 获取所有树洞
        $all_messages = $Message->order('id desc')->select();

        // 将时间戳转化为2022-04-02 12:22:00 （感觉前端控制更好）
        foreach($all_messages as $key => $message){
            $all_messages[$key]['send_timestamp'] = date('Y-m-d H:i:s', $message['send_timestamp']);
        }

        $return_data = array();
        $return_data['error_code'] = 0;
        $return_data['msg'] = '数据获取成功';
        $return_data['data'] = $all_messages;

        $this->ajaxReturn($all_messages);

        // dump($all_messages);
    }

    /**
     * 查看某个人所有树洞
     * @return [type] [description]
     */
    public function get_one_user_all_messages(){
        // 校验user_id是否存在
        if(!$_POST['user_id']){

            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = 'user_id不存在';

            $this->ajaxReturn($return_data);
        }
        else{
        // 实例化数据表
        $Message = M('Message');
        $where['user_id'] = $_POST['user_id'];

        // 获取特定user_id的树洞
        $one_user_all_messages = $Message->order('id desc')->where($where)->select();

        // 将时间戳转化为2022-04-02 12:22:00 （感觉前端控制更好）
        foreach($one_user_all_messages as $key => $message){
            $one_user_all_messages[$key]['send_timestamp'] = date('Y-m-d H:i:s', $message['send_timestamp']);
        }

        $return_data = array();
        $return_data['error_code'] = 0;
        $return_data['msg'] = '数据获取成功';
        $return_data['data'] = $one_user_all_messages;

        $this->ajaxReturn($one_user_all_messages);
        // dump($one_user_all_messages);
        }

        

        // dump($all_messages);
    }

    /**
     * 查看点赞数
     * @return [type] [description]
     */
    public function do_like(){
        // 校验参数
        if(!$_POST['message_id']){

            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '缺少message_id';

            $this->ajaxReturn($return_data);
        }

        if(!$_POST['user_id']){

            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '缺失user_id';

            $this->ajaxReturn($return_data);
        }
        // dump($return_data);

        $Message = M('Message');
        // 查询条件
        $where = array();
        $where['id'] = $_POST['message_id'];

        $message = $Message->where($where)->find();

        //判断树洞是否存在
        if(!$message){

            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '指定的树洞不存在';

            $this->ajaxReturn($return_data);
        }

        // dump($message);

        // like+1
        $data = array();
        $data['total_likes'] = $message['total_likes']+1;

        // 构造要保存的条件
        $where = array();
        $where['id'] = $_POST['message_id'];
        $result = $Message->where($where)->save($data);

        //判断是否保存成功
        if($result) {

            $return_data = array();
            $return_data['error_code'] = 0;
            $return_data['msg'] = '数据保存成功';
            $return_data['data']['message_id'] = $_POST['message_id'];
            $return_data['data']['total_likes'] = $data['total_likes'];


            $this->ajaxReturn($return_data);
        }
        else{
            $return_data = array();
            $return_data['error_code'] = 3;
            $return_data['msg'] = '数据保存失败';

            $this->ajaxReturn($return_data);
        }
    }


    /**
     * 删除message
     * @return [type] [description]
     */
    public function delete(){
        // 校验参数
        if(!$_POST['user_id']){

            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '缺少user_id';

            $this->ajaxReturn($return_data);
        }



        //判断树洞是否存在

        $Message = M('Message');
        // 查询条件
        $where = array();
        $where['id'] = $_POST['message_id'];
        $where['user_id'] = $_POST['user_id'];

        $message = $Message->where($where)->find();
        // dump($message);


        if(!$message){

            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '指定的树洞不存在';

            $this->ajaxReturn($return_data);
        }

        // delete message


        $result = $Message->where($where)->delete();
        dump($result);
        //判断是否保存成功
        if($result) {

            $return_data = array();
            $return_data['error_code'] = 0;
            $return_data['msg'] = '数据删除成功';
            $return_data['data']['message_id'] = $_POST['message_id'];

            $this->ajaxReturn($return_data);
        }
        else{
            $return_data = array();
            $return_data['error_code'] = 3;
            $return_data['msg'] = '数据删除失败';

            $this->ajaxReturn($return_data);
        }

        
    }


}