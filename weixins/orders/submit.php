<?php
/**
 * Created by PhpStorm.
 * User: eli9
 * Date: 5/18/2018
 * Time: 17:32
 */

$openId = $_POST["openid"];
$name = $_POST["name"];
$sex = $_POST["sex"];
$age = $_POST["age"];
$mobile = $_POST["mobile"];
$bookdate = $_POST["bookdate"];
$bookexpert = $_POST["bookexpert"];
$result = sendMail();

function sendMail(){

    global $openId; #是 全局变量 $openid的引用。本身是局部变量
    global $name;
    global $sex;
    global $age;
    global $mobile;
    global $bookdate;
    global $bookexpert;

    $subject = "微信订单";
    $receiver = "554248859@qq.com";
    $content = "姓名: ".$name."\n".
        "性别: ".$sex."\n".
        "年龄: ".$age."\n".
        "手机: ".$mobile."\n".
        "预约日期: ".$bookdate."\n".
        "预约专家: ".$bookexpert."\n".
        "微信Id: ".$openId;

    require_once ("phpmailer/class.phpmailer.php");
    $mail = new PHPMailer();
    $mail -> isSMTP();
    $mail->CharSet = "utf-8";
    $mail->Host= "smtp.163.com";
    $mail->SMTPAuth= true;
    $mail->Username="haoyuexv@163.com";
    $mail->Password="liyan1994";
    $mail->From = "haoyuexv@163.com";
    $mail->FromName="微信订单";
    $mail->AddAddress($receiver, "");
    $mail->Subject= $subject;
    $mail->Body = $content;

    try {
        if (!$mail->Send()) {
            return "提交失败！" . $mail->ErrorInfo;
        } else {
            return "提交成功";
        }
    } catch (phpmailerException $e) {
        return "异常".$e;
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>预约口腔医生</title>
    <meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="css/order.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
</head>

<body id="wrap" style="">
<div class="banner">
    <div id="wrapper">
        <div id="scroller" style="float:none">
            <ul id="thelist">
                <li style="float:none">
                    <img src="img/logo.png" alt="" style="width:100%">
                </li>
            </ul>
        </div>
    </div>
    <div class="clr"></div>
</div>
<div class="cardexplain">
    <ul class="round roundyellow" id="success" >
        <li style="height:40px;line-height:40px; font-size:16px; text-align:center"><?php echo $result;?></li>
    </ul>
    <ul class="round">
        <li class="title mb"><span class="none">您提交的信息</span></li>
        <li class="nob" style="height:30px;line-height:30px;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
                <tbody>
                <tr>
                    <th>姓名</th>
                    <td><?php echo $name;?></td>
                </tr>
                </tbody>
            </table>
        </li>
        <li class="nob" style="height:30px;line-height:30px;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
                <tbody>
                <tr>
                    <th>性别</th>
                    <td><?php echo $sex;?></td>
                </tr>
                </tbody>
            </table>
        </li>
        <li class="nob" style="height:30px;line-height:30px;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
                <tbody>
                <tr>
                    <th>年龄</th>
                    <td><?php echo $age;?></td>
                </tr>
                </tbody>
            </table>
        </li>
        <li class="nob" style="height:30px;line-height:30px;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
                <tbody>
                <tr>
                    <th>手机</th>
                    <td><?php echo $mobile;?></td>
                </tr>
                </tbody>
            </table>
        </li>
        <li class="nob" style="height:30px;line-height:30px;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
                <tbody>
                <tr>
                    <th>预约日期</th>
                    <td><?php echo $bookdate;?></td>
                </tr>
                </tbody>
            </table>
        </li>
        <li class="nob" style="height:30px;line-height:30px;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
                <tbody>
                <tr>
                    <th>预约专家</th>
                    <td><?php echo $bookexpert;?></td>
                </tr>
                </tbody>
            </table>
        </li>
    </ul>
</div>
</body>
</html>