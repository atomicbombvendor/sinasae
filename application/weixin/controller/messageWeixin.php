<?php
/**
 * Created by PhpStorm.
 * User: atomic
 * Date: 5/26/2018
 * Time: 18:55
 */

function responseMsg_param($postStr){

    if(!empty($postStr)){
        $this->logger("Receive \r\n".$postStr);
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $RX_TYPE = strtolower(trim($postObj->MsgType));

        //Msg type dividend
        switch ($RX_TYPE){
            case "event":
                $result = $this->receiveEvent($postObj);
                break;
            case "text":
                $result = $this->receiveText($postObj);
                break;
            case "image":
                $result = $this->receiveImage($postObj);
                break;
            case "location":
                $result = $this->receiveLocation($postObj);
                break;
            case "voice":
                $result = $this->receiveVoice($postObj);
                break;
            case "video":
            case "shortvideo":
                $result = $this->receiveShortVideo($postObj);
                break;
            case "link":
                $result = $this->receiveLink($postObj);
                break;
            default:
                $result = "unknown msg type: ".$RX_TYPE;
                break;
        }
        $this->logger("Return \r\n".$result);
        echo $result;
    }else{
        echo "";
        exit;
    }
}

function receiveEvent($object){
    $content = "";
    switch ($object->Event){
        case "subscribe":
            $content = "欢迎关注读书患不多 \n请回复一下关键字：文本 表情 单图文 多图文 音乐 \n 请按住说话 或 点击 + 再分别发送一下内容：语音 图片 小视频 我的收藏 位置";
            break;
        case "unsubscribe":
            $content = "取消关注";
            break;
        default:
            $content = "receive a new event: ".$object->Event;
            break;
    }

    if(is_array($content)){
        $result = $this->transmitNews($object, $content);
    }else{
        $result = $this->transmitText($object, $content);
    }
    return $result;
}

function receiveText($object){
    $keyword = trim($object->Content);

    switch ($keyword) {
        case "文本":
            $content = "这是个文本消息";
            break;
        case "单图文":
            $content = array();
            $content[] = array("Title" => "百度",
                "Description" => "百度一下",
                "PicUrl" => "https://www.baidu.com/img/bd_logo1.png",
                "Url" => "www.baidu.com");
            break;
        case "表情":
            $content = "微笑：/::) \n 乒乓：/:oo \n 中国：" . $this->bytes_to_emoji(0x1F1E8) . $this->bytes_to_emoji(0x1F1F3) . "\n仙人掌：" . $this->bytes_to_emoji(0x1F335);
            break;
        case strstr($keyword, "图文") || strstr($keyword, "多图文"):
            $content = array(); //数组下面包含了别名数组，也就是 二维数组
            $content[] = array("Title" => "百度",
                "Description" => "百度一下", "PicUrl" => "https://www.baidu.com/img/bd_logo1.png",
                "Url" => "www.baidu.com");
            $content[] = array("Title" => "谷歌",
                "Description" => "Google", "PicUrl" => "https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png",
                "Url" => "www.google.com");
            $content[] = array("Title" => "BING",
                "Description" => "必应搜索", "PicUrl" => "https://img.25pp.com/uploadfile/app/icon/20161205/1480897804657521.jpg",
                "Url" => "www.bing.com");
            break;
        case "音乐":
            $content = array("Title" => "我第一个喜欢的女孩",
                "Description" => "歌手：凯瑟喵",
                "MusicUrl" => "http://fs.open.kugou.com/56421207aee2f346f44cbfde4596ab49/5aeed833/G052/M09/11/08/1IYBAFa58QKAa45fACsrmd-dtis592.mp3",
                "HQMusicUrl" => "http://fs.open.kugou.com/56421207aee2f346f44cbfde4596ab49/5aeed833/G052/M09/11/08/1IYBAFa58QKAa45fACsrmd-dtis592.mp3");
            break;
        case strstr($keyword, "天气"):
            $city = str_replace('天气', '', $keyword);
            include("weather.php");
            $content = getWeatherInfo($city);
            if (is_string($content) && strstr(strtolower($content), 'no result')) {
                $result = $this->transmitText($object, "城市错误");
            } else {
                $result = $this->transmitNews($object, $content);
            }
            return $result;
        case "笑话":
            include("getJokeInfo.php");
//                $content = getJokeInfo();
            $content = "Not Ready";
            $result = $this->transmitText($object, $content);
            return $result; # return会跳过下面的处理流程
        case "预约":
            $content = "http://xiaobaili.applinzi.com/orders/order.php";
            $result = $this->transmitText($object, $content);
            return $result; # return会跳过下面的处理流程
        case "微站":
            $content = "http://xiaobaili.applinzi.com/MicSite/index.html";
            $result = $this->transmitText($object, $content);
            return $result; # return会跳过下面的处理流程
        default:
            #region 其他回复
            if ($keyword == "时间" || $keyword == "time") {//回复时间
                $content = date('y-m-d h:i:s', time()) . "\nOpenId:" . $object->FromUserName . "\n";
            } else if ($keyword == "?" || $keyword == "？") {
                $content = "欢迎关注读书患不多 \n请回复一下关键字：时间 or time\n文本\n表情\n单图文\n多图文\n音乐\n天气 地点\n请按住说话 或 点击 + 再分别发送一下内容：语音 图片 小视频 我的收藏 位置等";                   #endregion
            } else { //回复接收的内容
                $content = $keyword;
            }
            break;
    }

    if (is_array($content)) {
        if (isset($content[0])) { //isset 检测变量是否设置
            $result = $this->transmitNews($object, $content);
        } else if (isset($content['MusicUrl'])) {
            $result = $this->transmitMusic($object, $content);
        }
    }else {
        $result = $this->transmitText($object, $content);
    }
    return $result;
}

function receiveImage($object){
    $content = array("MediaId"=>$object->MediaId);
    $result = $this->transmitImage($object, $content);
    return $result;
}

function receiveLocation($object){
    $content = "你发送的位置，经度为：".$object->Location_Y."; 纬度为：".$object->Location_X."缩放为：".$object->Scale."; 位置为："
        .$object->Label;
    $result = $this->transmitText($object, $content);
    return $result;
}

function receiveVoice($object){
    if(isset($object->Recognition) && !empty($object->Recognition)){
        $content = " 你刚才说的是： ".$object->Recognition;
        $result = $this->transmitText($object, $content);
    }else{
        $content = array("MediaId"=>$object->MediaId);
        $result = $this->transmitVoice($object, $content);
    }
    return $result;
}

/**
 * 回复视频消息
 */
function receiveShortVideo($object){
    //回复视频消息
    $content = array("MediaId"=>$object->MediaId, "ThumbMediaId"=>$object->ThumbMediaId, "Title"=>"", "Description"=>"");
    $result = $this->transmitVideo($object, $content);;
    return $result;
}

function receiveLink($object){
    $content = " 你发送的链接，标题为：".$object->Title."；内容为:".$object->Description."; 链接地址为：".$object->Url;
    $result = $this->transmitText($object, $content);
    return $result;
}

function transmitText($object, $content){
    if (!isset($content) || empty($content)){
        return "";
    }

    $textTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName> <FromUserName><![CDATA[%s]]></FromUserName> <CreateTime>%s</CreateTime> <MsgType><![CDATA[text]]></MsgType> <Content><![CDATA[%s]]></Content></xml>";

    $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
    return $result;
}

/*
 * 回复图文消息
 */
function transmitNews($object, $newArray){
    if(!is_array($newArray))
        return "";

    $itemTpl = "    <item><Title><![CDATA[%s]]></Title> <Description><![CDATA[%s]]></Description> <PicUrl><![CDATA[%s]]></PicUrl> <Url><![CDATA[%s]]></Url></item>";

    $item_str = "";
    foreach($newArray as $item){
        $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
    }

    $newsTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName> <FromUserName><![CDATA[%s]]></FromUserName> <CreateTime>%s</CreateTime> <MsgType><![CDATA[news]]></MsgType> <Content><![CDATA[]]></Content> <ArticleCount>%s</ArticleCount> <Articles>$item_str</Articles></xml>";
    $result = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($newArray));
    return $result;
}

/**
 * 回复音乐消息
 */
function transmitMusic($object, $musicArray){
    if (!is_array($musicArray)){
        return "";
    }

    $itemTpl = "    <Music><Title><![CDATA[%s]]></Title> <Description><![CDATA[%s]]></Description> <MusicUrl><![CDATA[%s]]></MusicUrl> <HQMusicUrl><![CDATA[%s]]></HQMusicUrl></Music> ";

    $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);

    $textTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName> <FromUserName><![CDATA[%s]]></FromUserName> <CreateTime>%s</CreateTime> <MsgType><![CDATA[music]]></MsgType>$item_str</xml>";

    $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
    return $result;
}

/*
 * 回复图片消息
 */
function transmitImage($object, $imageArray){
    $itemTpl = "<Image><MediaId><![CDATA[%s]]></MediaId></Image>";
    $item_str = sprintf($itemTpl, $imageArray["MediaId"]);

    $textTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName> <FromUserName><![CDATA[%s]]></FromUserName> <CreateTime>%s</CreateTime> <MsgType><![CDATA[image]]></MsgType>$item_str</xml>";

    $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
    return $result;
}

/*
 * 回复语音消息
 */
function transmitVoice($object, $voiceArray){
    $itemTpl = "<Voice><MediaId><![CDATA[%s]]></MediaId></Voice>";

    $item_str = sprintf($itemTpl, $voiceArray['MediaId']);

    $textTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName> <FromUserName><![CDATA[%s]]></FromUserName> <CreateTime>%s</CreateTime> <MsgType><![CDATA[voice]]></MsgType>$item_str</xml>";

    $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
    return $result;
}

/*
 * 回复视频消息
 */
function transmitVideo($object, $videoArray)
{
    $itemTpl = "<Video><MediaId><![CDATA[%s]]></MediaId> <ThumbMediaId><![CDATA[%s]]></ThumbMediaId> <Title><![CDATA[%s]]></Title> <Description><![CDATA[%s]]></Description></Video>";

    $item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['ThumbMediaId'], $videoArray['Title'], $videoArray['Description']);

    $textTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName> <FromUserName><![CDATA[%s]]></FromUserName> <CreateTime>%s</CreateTime> <MsgType><![CDATA[video]]></MsgType>$item_str</xml>";

    $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
    return $result;
}

//字节转Emoji表情
function bytes_to_emoji($cp)
{
    if ($cp > 0x10000){       # 4 bytes
        return chr(0xF0 | (($cp & 0x1C0000) >> 18)).chr(0x80 | (($cp & 0x3F000) >> 12)).chr(0x80 | (($cp & 0xFC0) >> 6)).chr(0x80 | ($cp & 0x3F));
    }else if ($cp > 0x800){   # 3 bytes
        return chr(0xE0 | (($cp & 0xF000) >> 12)).chr(0x80 | (($cp & 0xFC0) >> 6)).chr(0x80 | ($cp & 0x3F));
    }else if ($cp > 0x80){    # 2 bytes
        return chr(0xC0 | (($cp & 0x7C0) >> 6)).chr(0x80 | ($cp & 0x3F));
    }else{                    # 1 byte
        return chr($cp);
    }
}

function logger($log_content){
    $content = date('Y-m-d H:i:s')." ".$log_content."\r\n";
    if (isset($_SERVER['HTTP_APPNAME'])) { //如果是SAE
        sae_set_display_errors(false);
        sae_debug(trim($content));
        sae_set_display_errors(true);
    } else if ($_SERVER['REMOTE_ADDR'] != "127.0.0.1") {//LOCAL
        $max_size = 100000;
        $log_filename = "log.txt";
        if (file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)) {
            unlink($log_filename);
        }
        file_put_contents($log_filename, $content, FILE_APPEND);
    }

}
?>