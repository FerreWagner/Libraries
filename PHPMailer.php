#TIPS：这是一个PHPMail的使用实例，phpmail可从github重下载，也可以composer安装（composer require phpmailer/phpmailer）

1、原生使用：PHPmailer请在github下载，或者直接百度，也不难，虽然PHPmailer里面一大堆东西，但是我们只需要
      PHPMailer.class.php
      PHPMailerAutoload.class.php
      SMTP.class.php
      引入类文件即可。
      
2、TP5中的使用方法：composer require phpmailer/phpmailer
                  //舒勇composer在vendor中引入类库
                  $mail = new \PHPMailer();
                  //直接实例化使用，无需做use或require引用


使用实例：

public function mail()
    {
    
        /**
        //原生PHP中的应用
        include("class.phpmailer.php");
        include("class.smtp.php");
        */
        
        
        /**
        //TP5中的应用
        //实例化
        $mail             = new \PHPMailer();
        */
        
        
        //设置smtp参数
        
        $mail->IsSMTP();
        
        $mail->SMTPAuth   = true;
        
        $mail->SMTPKeepAlive = true;
        
//         $mail->SMTPSecure = "SSL";
        
        $mail->Host       = "smtp.163.com";
        
        $mail->Port       = 25;
        
        //填写你的邮箱账号和密码
        
        $mail->Username   = "xxx@163.com";
        
        $mail->Password   = "***";
        
        
        $html = '
            input in here
        ';
        
        
        
        //设置发送方，最好不要伪造地址
        
        $mail->From       = "xxx@163.com";
        
        $mail->FromName   = '邮箱外部标题';
        
        //标题，内容，和备用内容
        
        $mail->Subject    = time();
        
        $mail->Body		  = $html;
        
        $mail->AltBody    = time();//如果邮件不支持HTML格式，则替换成该纯文本模式邮件
        
        $mail->WordWrap   = 20; // 设置邮件每行字符数
        
        //$mail->MsgHTML($body);
        
        //设置回复地址
        
        $mail->AddReplyTo("xxx@163.com","yy");
        
        //添加附件，此处附件与脚本位于相同目录下,否则填写完整路径
        
        //$mail->AddAttachment("attachment.zip");
        
        //设置邮件接收方的邮箱和姓名
        
        $mail->AddAddress("xxx@qq.com","FirstName LastName");
        
        //使用HTML格式发送邮件
        
        $mail->IsHTML(true);
        
        //通过Send方法发送邮件,根据发送结果做相应处理
        $i = 0;
//         for ($i == 0; $i < 2; $i ++){  //垃圾邮箱的循环发送
            if(!$mail->Send()) {
            
//                 echo "发送失败: " . $mail->ErrorInfo;
            
            } else {
            
                echo "邮件已经成功发送".'<br />';
            
            }
//         }

    }
