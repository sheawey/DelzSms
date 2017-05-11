# 短信组件

## 发送短信代码示例

    use Delz\SMS\Provider\Tong3;
    use Delz\SMS\Manager;

    //利用composer的autoload
    /** @var \Composer\Autoload\ClassLoader $loader */
    $loader = require __DIR__ . "/../vendor/autoload.php";
    
    //实例化一个短信服务发送对象
    $account = ''; 
    $password = '';
    $sign = '';
    $subCode = '';
    $tong3 = new Tong3($account, $password, $sign, $subCode);
    //将发送对象注入到发送管理器
    $manager = new Manager($tong3);
    //创建消息并发送
    $result = $manager->createMessage()
            ->setTo('13888888888')
            ->setContent('您好')
            ->send();
            
    //也可以直接发送
    $result = $manager->send('13888888888', '您好');
            
    if($result->isSuccessful()) {
        echo '发送成功';
        //可以将发送日志保存
        ...
    } else {
        echo '发送失败,原因是:' . $result->getMessage()->getFailReason();
    }
    
## 短信发送报告获取

    use Delz\SMS\Provider\Tong3;
    use Delz\SMS\Manager;
    
    //利用composer的autoload
    /** @var \Composer\Autoload\ClassLoader $loader */
    $loader = require __DIR__ . "/../vendor/autoload.php";
        
    //实例化一个短信服务发送对象
    $account = ''; 
    $password = '';
    $sign = '';
    $subCode = '';
    $tong3 = new Tong3($account, $password, $sign, 
    
    //将发送对象注入到发送管理器
    $manager = new Manager($tong3);
    
    $reports = $manager->getReports();
    
    //更新日志，报告Id即消息Id，根据消息Id，读取消息日志，然后用报告的反馈更新消息日志的状态
    
    
    
    
    
