<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
class KsController extends Controller
{
    public function jiekou()
    {
//        echo urlencode("http://1809zhaokai.comcto.com/weixin/welcome");http%3A%2F%2F1809zhaokai.comcto.com%2Fweixin%2Fwelcome
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".env('WX_APPID')."&redirect_uri=http%3A%2F%2F1809zhaokai.comcto.com%2Fweixin%2Fwelcome&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
        header("Refresh:0;url=".$url);
    }
    //跳转业面
    public function welcome()
    {
        //获取网页授权
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".env('WX_APPID')."&secret=".env('WX_APPSECRET')."&code=".$_GET['code']."&grant_type=authorization_code";
        $data = json_decode(file_get_contents($url),true);
//        dd($data);
//        获取网页access_token和用户的openid
        $access_token = $data['access_token'];
        $openid = $data['openid'];
        //拉去用户的信息
        $url1 = "https://api.weixin.qq.com/sns/userinfo?access_token=".get_wx_access()."&openid=".$openid."&lang=zh_CN";
        $userinfo = json_decode(file_get_contents($url1),true);

        //给用户打标签

        //调用接口
        $url_d = "https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token=".get_wx_access();
        //拼接参数
        $bq_data =[
            "openid_list"=>[
                $openid,
            ],
            "tagid"=>100
        ];
        $bq_data_json = json_encode($bq_data);
        $client = new Client();
        $respon = $client->request("POST",$url_d,[
            "body"=>$bq_data_json
        ]);
        $shuju = json_decode($respon->getBody(),true);
        if($shuju['errcode']==0&&$shuju['errmsg']=="ok"){
            echo "<h3>给用户打标签成功</h3>";
        }
    }
    public function create_bq()
    {
        //创建标签
        //调用接口
        $url_a = "https://api.weixin.qq.com/cgi-bin/tags/create?access_token=".get_wx_access();
        //拼接参数
        $data = [
            "tag"=>
                [
                    "name"=>'1809b'
                ]
        ];
        $data = json_encode($data);
//        dd($data);
        $client = new Client();
        $respon = $client->request("POST",$url_a,[
            "body"=>$data
        ]);
        $jie = json_decode($respon->getBody(),true);
        dd($jie);
    }
}