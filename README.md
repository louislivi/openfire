# openfire
php连接openfire 执行聊天室,用户 的增删查改
#### 1.到http://www.igniterealtime.org/projects/openfire/plugins.jsp下载一个插件REST API。
这个插件的作用就是允许程序设计师通过http管理openfire的用户。
部署以后默认REST API是没有开启的，你需要到后台开启并且设置验证码，为了确保安全你也许还要设置一个安全的ip。
![image.png](http://upload-images.jianshu.io/upload_images/6411787-66a47dc1ddeaa3c4.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)
服务器部署完成。


#### 2.PHP代码连接OpenFire服务器。
我这边举个简单的例子 例如添加用户。
首先查看REST API 文档(http://www.igniterealtime.org/projects/openfire/plugins/restapi/readme.html)
点击
Create a user
Examples
[JSON Examples](http://www.igniterealtime.org/projects/openfire/plugins/restapi/readme.html#json-examples)
然后
![image.png](http://upload-images.jianshu.io/upload_images/6411787-c4e5f45515712edc.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

随后实例化PHP类Openfire(https://github.com/louislivi/openfire)
```
/**
* register_data 为上图json 必填参数 ,如需其他参数请自行添加。
* return $result bool 返回成功失败
*/
$register_data['username'] =  'testuser';
$register_data['password'] =  'password';
$conn = \OpenFire::getInstance(); //实例化类 
//$username ='',$password='',$url='' 实例化可选参数 
//默认值:用户名(admin),密码(admin),openfire服务器地址 (http://127.0.0.1:9090)
//需要jid可使用方法$conn::get_jid()快捷获取jid,
//需先修改类中的(`static private $server_domain = '设置自己服务器的domain值';`)
//可在openfire 服务器信息中查看。
$result = $conn::run('users',$register_data); // 'users'是v1/后面的操作名 ,$register_data 需要传递的参数
```
###### 最后再说两句
操作名v1/ 后面有斜线时 也得加上斜线
如:[http://example.org:9090/plugins/restapi/v1/chatrooms/global](http://example.org:9090/plugins/restapi/v1/chatrooms/global) 操作名为: chatrooms/global
`$conn::run('chatrooms/global',$post_data); `
