#api-doc

### 使用方法
####1、安装扩展
```
composer require weiwei/laravel-apidoc
```

####2、注册服务提供者

```
    添加 ApiDocServiceProvider到config/app.php 的providers 中

    如：
    'providers' => [
         /*
         * Application Service Providers...
         */
         .......
        Weiwei\LaravelApiDoc\ApiDocServiceProvider::class
    ]
```
####3、发布前端资源文件
```
    php artisan vendor:publish
```
####4、在相关接口类中增加注释参数
方法如下：返回参数支持数组及多维数组
```
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @title 测试demo
 * @description 接口说明
 * @header name:key require:1 default: desc:秘钥(区别设置)
 * @param name:public type:int require:1 default:1 other: desc:公共参数(区别设置)
 */
class DemoController extends Controller
{
    /**
     * @title 测试demo接口
     * @description 接口说明
     * @author 开发者
     * @url /api/demo
     * @method GET
     *
     * @header name:device require:1 default: desc:设备号
     *
     * @param name:id type:int require:1 default:1 other: desc:唯一ID
     *
     * @return name:名称
     * @return mobile:手机号
     * @return list_messages:消息列表@
     * @list_messages message_id:消息ID content:消息内容
     * @return object:对象信息@!
     * @object attribute1:对象属性1 attribute2:对象属性2
     * @return array:数组值#
     * @return list_user:用户列表@
     * @list_user name:名称 mobile:手机号 list_follow:关注列表@
     * @list_follow user_id:用户id name:名称
     */
    public function index(Request $request)
    {
        //接口代码
        $device = $request->header('device');
        echo json_encode(["code"=>200, "message"=>"success", "data"=>['device'=>$device]]);
    }
}
```
####4、在浏览器访问http://你的域名/doc 查看接口文档

####5、预览
![](https://static.oschina.net/uploads/img/201704/17101409_tAgD.png)
![](https://static.oschina.net/uploads/img/201704/17101348_XuUz.png)
![](https://static.oschina.net/uploads/img/201704/17101306_KePe.png)

###更多支持
- QQ群663447446