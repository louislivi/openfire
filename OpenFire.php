<?php
/**
 * openfire类
 *
 * 链接openfire 处理各种操作
 * @author      子山<574747417@qq.com>
 * @version     1.0
 * @since       1.0
 */

/**
 * OpenFire操作类
 * Class OpenFire
 *
 */
class OpenFire{

    static private $base_url = 'http://127.0.0.1:9090';
    private $username = 'admin';
    private $password = 'admin';
    static private $server_domain = 'i-f5tpijbs';
    private static $_instance = null;
    static private $ch = '';

    /**
     * OpenFire constructor.
     * @param $username string 管理员用户名
     * @param $password string 管理员密码
     * @param string $url string openfire 地址
     */
    private function __construct($username ='',$password='',$url='')
    {
        if ($url){
            self::$base_url = $url;
        }
        if ($username){
            $this->username = $username;
        }
        if ($password){
            $this->password = $password;
        }
        self::$ch = curl_init();
        curl_setopt(self::$ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt(self::$ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt(self::$ch, CURLOPT_USERPWD, "$this->username:$this->password");
    }

    /**
     * 实例化类
     *
     * @param $username string 管理员用户名
     * @param $password string 管理员密码
     * @param string $url string openfire 地址
     * @return null|OpenFire
     */
    static public function getInstance($username='',$password='',$url='') {
        if (is_null ( self::$_instance ) || isset ( self::$_instance )) {
            self::$_instance = new self ($username,$password,$url);
        }
        return self::$_instance;
    }

    /**
     * 执行操作
     *
     * @param $action_name string 请求操作名称
     * @param $data array 需要传输的数据
     */
    static public function run($action_name,$data)
    {
        curl_setopt(self::$ch,CURLOPT_URL,self::$base_url."/plugins/restapi/v1/".$action_name);
        self::set_post_data($data);
        return self::get_result_status();
    }

    /**
     * 获取用户jid
     * @param $uid integer 用户id
     * @return string
     */
    static public function  get_jid($uid)
    {
        return $uid."@".self::$server_domain;
    }



    /**
     * 设置json参数
     * @param $arr array 参数数组
     */
    static private function set_post_data($arr)
    {
        $data_string =  json_encode($arr);
        curl_setopt(self::$ch, CURLOPT_POSTFIELDS,$data_string);
        curl_setopt(self::$ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ));
    }

    /**
     * 获取返回值
     *
     * @return bool
     */
    static private function get_result_status()
    {
        curl_exec(self::$ch);
        $status = curl_getinfo(self::$ch,CURLINFO_HTTP_CODE);
        if ($status < 300 && $status >= 200){
            return true;
        }else{
            return false;
        }
    }

    private function __clone()
    {
        // 禁用克隆
    }
}