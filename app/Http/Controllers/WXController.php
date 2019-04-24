<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Goods;
class WXController extends Controller
{
    public function get_vaild(){
        echo $_GET['echostr'];
    }
    public function post_vaild()
    {
        //获取传过来的值
        $content = file_get_contents("php://input");
        $res = simplexml_load_string($content);
//        dd($res);
        $time = date('Y-m-d H:i:s',time());
        $str = $time.$content."\n";
        // 写入日志
        file_put_contents("logs/wx_event.log",$str,FILE_APPEND);

        //用户的openid
        $oid = $res->FromUserName;
        // 公众号id
        $gzhid = $res->ToUserName;

        if($res->MsgType=='text'){
            if($res->Content=='最新商品'){
                $goodsinfo = Goods::first();
                echo "<xml><ToUserName><![CDATA[$oid]]></ToUserName><FromUserName><![CDATA[$gzhid]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[news]]></MsgType><ArticleCount>1</ArticleCount><Articles><item><Title><![CDATA[".$goodsinfo->goods_name."]]></Title><Description><![CDATA[iphone不好用了，能支持国产了！]]></Description><PicUrl><![CDATA[]]></PicUrl><Url><![CDATA[]]></Url></item></Articles></xml>";
//                echo "<xml><ToUserName><![CDATA[$oid]]></ToUserName><FromUserName><![CDATA[$gzhid]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[请稍等，马上查出来！！]]></Content></xml>";
            }else{
                echo "<xml><ToUserName><![CDATA[$oid]]></ToUserName><FromUserName><![CDATA[$gzhid]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[暂无有效信息！！]]></Content></xml>";
            }
        }
    }
}