<?php
$timezone = date_default_timezone_get();           // 获取默认时区
if ($timezone !== 'Asia/Shanghai') {
    date_default_timezone_set('Asia/Shanghai');    // 设置默认时区
}
echo @$_GET['echostr'];
require_once 'Duck.php';

// 
$ConfigPath = __DIR__.'/config.ini';
// 获取配置文件ConfigPath
$config = getConfig($ConfigPath);

// 实例化Duck类 首先执行构造函数 即function __construct($config)
$start = new Duck($config);
function getConfig($path)
{
    $configdata = []; // 初始化配置数据数组
    $file = fopen($path, "r"); // 打开文件
    // 检查文件是否成功打开
    if (!$file) {
        return []; // 如果打开失败，返回空数组
    }
    // 读取配置文件 一行一行读取
    while (!feof($file)) {
        // 获取一行
        $data = fgets($file);

        // 如果读取的数据为空 或者 该行是注释，则跳过
        if ($data === false || trim($data) === '' || strpos($data, '#') === 0) {
            continue;
        }

        // 去除$data字符串两端的空白符
        $data = trim($data);
        
        // 使用explode分割并检查分割结果
        $datalist = explode('=', $data);
        if (count($datalist) === 2) {
            $key = trim($datalist[0]);
            $value = trim($datalist[1]);
            $configdata[$key] = $value; // 存储键值对
        }
    }
    
    fclose($file); // 关闭文件
    return $configdata; // 返回配置数据
}

$weekarray=array("日","一","二","三","四","五","六");
// to Twelveee
$data = [
    # 指定接收者
    //'touser' => '',
    // 指定模板 发送消息1时需要
    // 'template_id' => $start->getTemplateList()['template_list'][0]['template_id'],//默认只给第一个模板发消息
    // 点击模板消息跳转链接
    'touser' => 'oTlR66tPgN-Kc8QR1GPRPRQTRjEE',  // twelveee
    'template_id' => 'XviqvrDJD93KqqpS-ThbzLycOnQMu2VSWptHmHi1kj8',  // 宝宝安安_模板
    'url' => 'http://www.weather.com.cn/weather1d/101180110.shtml#input',   //
    'data' => [
        'date' => [
            'value' => date('Y年n月j日 H时i分s秒'),
        ],
        'week' => [
            'value' => $weekarray[date("w")],
        ],
        'city' => [//城市
            'value' => $start->getCity(),
        ],
        'weather' => [ //天气现象
            'value' => $start->getWeather()['now']['text'],
        ],
        'temp' => [ //温度
            'value' => $start->getWeather()['now']['temp'],
        ],
        'humidity' => [//相对湿度
            'value' => $start->getWeather()['now']['humidity'].'%',
        ],
        'qinghua' => [//情话
            'value' => $start->getQingHua(),
        ],
        'birthday1' => [//生日
            'value' => $start->getBirthday($config['birthday1']),
        ],
        'birthday2' => [//第二个人的生日
            'value' => $start->getBirthday($config['birthday2']),
        ],
        'birthday3' => [//纪念日
            'value' => $start->getBirthday($config['birthday3']),
        ],
        'togetherdays' => [//在一起多久了
            'value' => $start->getTogetherDays(),
        ]
    ],
];
// TO struggle
$data1 = [
    'touser' => 'oTlR66qPQgHvIuBLRON6B6HkJzI0',  //struggle
    'template_id' => 'XviqvrDJD93KqqpS-ThbzLycOnQMu2VSWptHmHi1kj8',
    'url' => 'http://www.weather.com.cn/weather1d/101180110.shtml#input',
    'data' => [
        'date' => [
            'value' => date('Y年n月j日 H时i分s秒'),
        ],
        'week' => [
            'value' => $weekarray[date("w")],
        ],
        'city' => [// 深圳
            'value' => $start->getCity(),
        ],
        'city2' => [// 余杭
            'value' => $start->getCity2(),
        ],
        'weather' => [ //天气现象
            'value' => $start->getWeather()['now']['text'],
        ],
        'temp' => [ //温度
            'value' => $start->getWeather()['now']['temp'],
        ],
        'humidity' => [//相对湿度
            'value' => $start->getWeather()['now']['humidity'].'%',
        ],
        'qinghua' => [//情话
            'value' => $start->getQingHua(),
        ],
        'birthday1' => [//宝子
            'value' => $start->getBirthday($config['birthday1']),
        ],
        'birthday2' => [//傻狗
            'value' => $start->getBirthday($config['birthday2']),
        ],
        'birthday3' => [//在一起纪念日
            'value' => $start->getBirthday($config['birthday3']),
        ],
        'togetherdays' => [//在一起多久了
            'value' => $start->getTogetherDays(),
        ]
    ],
];
/**
 * 启动点
 */
foreach ($start->getUserList()['data']['openid'] as $user)
{
    // 接收者已在 Data中设置
    // $data['touser'] = $user;
    // Send Massage to Twelveee
    $start->sendTemplateMessage(json_encode($data));
    // Send Massage to Struggle
    $start->sendTemplateMessage(json_encode($data1));
    //$start->sendTemplateMessage(json_encode($data1));
}
 ?>
