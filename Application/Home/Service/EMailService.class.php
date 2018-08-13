<?php
namespace Home\Service;

/**
 * Class EMailService
 * @package Home\Service
 *
 * 可能需要打开ssl模块
 */
class EMailService
{
    public $ErrorInfo = '';
    function send($address, $title, $message) {
        Vendor('PHPMailer.PHPMailerAutoload');
        $mail = new \PHPMailer(); //实例化
        $mail->IsSMTP();                                // 启用SMTP
        $mail->SMTPAuth  = true;                        //C('SMTP_AUTH'); //启用smtp认证
        $mail->AddAddress($address,"尊敬的客户");
        $mail->IsHTML();                                //(C('SMTP_MAIL_TYPE')); // 是否HTML格式邮件
        $mail->Host      = 'smtp.qq.com';               //'smtp.ym.163.com';//C('SMTP_SERVER'); //smtp服务器的名称（这里以QQ邮箱为例）
        $mail->SMTPSecure= 'ssl';
        $mail->Port      = 465;                         //QQ是465，雅虎是25
        $mail->Username  = '475185283@qq.com';          //'service@7typ.cn';//C('SMTP_USER_EMAIL'); //你的邮箱名
        $mail->From      = '475185283@qq.com';          //'service@7typ.cn';//C('SMTP_USER_EMAIL'); //发件人地址（也就是你的邮箱地址）
        $mail->Password  = 'euznjoicptvabiic';          // '3y9a23a5QL';//C('SMTP_PWD') ; //邮箱密码（邮箱的授权码，在开通POP3/SMTP服务时，会给的）
        $mail->FromName  = '百里杨周';                  //C('SMTP_USER'); //发件人姓名
        $mail->Subject   = $title;                      //邮件主题
        $mail->Body      = $message;                    //邮件内容
        $mail->WordWrap  = 50;                          //设置每行字符长度
        $mail->CharSet   = "UTF-8";                     //设置邮件编码
//        $mail->SMTPDebug =1;                            //调试模式
        $mail->AltBody   = "邮件正文不支持HTML";         //邮件正文不支持HTML的备用显示
        $mail->addAttachment(APP_PATH.'../Public/_img/mail/1.jpg'.'');
        $mail->addAttachment(APP_PATH.'../Public/_img/mail/2.png'.'');
        $mail->addAttachment(APP_PATH.'../Public/_img/mail/3.jpg'.'');
        $mail->addAttachment(APP_PATH.'../Public/_img/mail/4.png'.'');
        $result = $mail -> send();
        if($result){
            return $result;
        }else{
            $this->ErrorInfo = $mail ->ErrorInfo;
            return false;
        }
    }

    /**
     * @param $address
     * @param $title
     * @param $message
     * @return mixed
    'SMTP_USER'=>'七天优配', // 邮箱地址
    'SMTP_SERVER'=>'smtp.ym.163.com', // 邮箱SMTP服务器
    'SMTP_PORT'=>25,//邮件服务器端口
    'SMTP_USER_EMAIL'=>'service@7typ.cn', // 邮箱登录帐号
    'SMTP_PWD'=>'3y9a23a5QL', // 邮箱密码
    'SMTP_TIME_OUT'=>30,//编码
    'SMTP_AUTH'=>true,//邮箱认证
    'SMTP_MAIL_TYPE'=>'HTML',//true HTML格式 false TXT格式
     */

    function sendMail($address, $title, $message) {
        Vendor('PHPMailer.PHPMailerAutoload');
        $mail = new PHPMailer(); //实例化
        $mail->IsSMTP(); // 启用SMTP
        $mail->Host=C('SMTP_SERVER'); //smtp服务器的名称（这里以QQ邮箱为例）
        $mail->SMTPAuth = C('SMTP_AUTH'); //启用smtp认证
        $mail->Username = C('SMTP_USER_EMAIL'); //你的邮箱名
        $mail->Password = C('SMTP_PWD') ; //邮箱密码
        $mail->From = C('SMTP_USER_EMAIL'); //发件人地址（也就是你的邮箱地址）
        $mail->FromName = C('SMTP_USER'); //发件人姓名
        $mail->AddAddress($address,"尊敬的客户");
        $mail->WordWrap = 50; //设置每行字符长度
        $mail->IsHTML(C('SMTP_MAIL_TYPE')); // 是否HTML格式邮件
        $mail->CharSet="UTF-8"; //设置邮件编码
        $mail->Subject =$title; //邮件主题
        $mail->Body = $message; //邮件内容
        $mail->AltBody = "邮件正文不支持HTML"; //邮件正文不支持HTML的备用显示
        return $mail->Send();
    }
    /**
     * @param $address
     * @param $title
     * @param $message
     * @return mixed
    'SMTP_USER'=>'七天优配', // 邮箱地址
    'SMTP_SERVER'=>'smtp.ym.163.com', // 邮箱SMTP服务器
    'SMTP_PORT'=>25,//邮件服务器端口
    'SMTP_USER_EMAIL'=>'service@7typ.cn', // 邮箱登录帐号
    'SMTP_PWD'=>'3y9a23a5QL', // 邮箱密码
    'SMTP_TIME_OUT'=>30,//编码
    'SMTP_AUTH'=>true,//邮箱认证
    'SMTP_MAIL_TYPE'=>'HTML',//true HTML格式 false TXT格式
     */

    function send2($address, $title, $message) {
        Vendor('PHPMailer.PHPMailerAutoload');
        $mail = new \PHPMailer(); //实例化
        $mail->IsSMTP(); // 启用SMTP
        $mail->AddAddress($address,"尊敬的客户");
        $mail->IsHTML('HTML'); // 是否HTML格式邮件
        $mail->Host     ='smtp.ym.163.com'; //smtp服务器的名称（这里以QQ邮箱为例）
        $mail->SMTPAuth = true; //启用smtp认证
        $mail->Username = 'service@7typ.cn'; //你的邮箱名
        $mail->Password = '3y9a23a5QL' ; //邮箱密码
        $mail->From     = 'service@7typ.cn'; //发件人地址（也就是你的邮箱地址）
        $mail->FromName = '七天优配'; //发件人姓名
        $mail->WordWrap = 50; //设置每行字符长度
        $mail->CharSet  ="UTF-8"; //设置邮件编码
        $mail->Subject  =$title; //邮件主题
        $mail->Body     = $message; //邮件内容
        $mail->AltBody  = "邮件正文不支持HTML"; //邮件正文不支持HTML的备用显示
        return $mail->Send();
    }
}