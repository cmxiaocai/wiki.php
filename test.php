<?php

$dbhost    = '10.79.150.36';
$dbuser    = 'bbs91new';
$dbpw      = 'XczhDd5LxdUzsrmF';
$dbcharset = 'utf8';
$dbname    = 'sjbbs_x3';

$key1 = "怎么购买|游戏元宝|廠家|仿表|鑒別|华人捕鱼|仿款|代理|高仿|精仿|多少錢|仿真品|元游|仿專賣|仿品|高仿|進貨|前列腺炎|哪里学|阳痿|好人评选|九牌技|赌技|老千|看牌|斗牛|发票|麻将机|扑克|试管婴儿|受孕|男人生殖|排卵|好人榜评选活动|哪里好|是哪家|线肌症|症状|痛经|子宫|体检|医院|彩超|最好的医院|治疗价格|牌具|阴道|男公关|全身体检|分析仪|骨龄|阴蒂|医院哪家好|手术|人流|早泄|男科|保守治疗|增高|肾虚|鼻炎|咽炎|看哪科|最好医院|专科|发病原因|症状|彩超|唇炎|医院专家|雷诺氏|医院排名|硬皮病|多少费用|萌宝大赛|治疗方法|如何治疗|晚期症状|综合症|泌尿科|包皮";


function mysql_fetch_data($sql, $con) {
    $result = mysql_query($sql, $con);
    while($row=mysql_fetch_array($result)) {
        $return[] = $row;
    }
   return $return;
}

//preg_match（"/[\u4e00-\u9fa5]/",$str）;
$deltids = array();
$datas   = array(
    array('tid'=>1, 'subject'=>'出售华人捕鱼游戏银子'),
    array('tid'=>2, 'subject'=>'东 莞 东 城 前 列 腺 炎 花 多 少 钱'),
    array('tid'=>3, 'subject'=>'月好@人∯评选活∰动'),
);

/* 匹配 */
foreach ($datas as $key => $value) {
    $tid     = $value['tid'];
    $subject = $value['subject'];

    //剔除空格和字母数字
    $subject = str_replace(' ', '', $subject);
    $subject = preg_replace('|[0-9a-zA-Z/]+|','',$subject);

    //第一轮关键字匹配
    $result  = preg_match('/'.$key1.'/', $subject);
    if($result){
        $deltids[$tid] = $subject;
        continue;
    }

    //第二轮去除非中文字符匹配
    preg_match_all('/[\x{4e00}-\x{9fff}]+/u', $subject, $result);
    if( $result[0] ){

        //拆好的中文拼成字符串
        $str = implode('', $result[0]);
        $is  = preg_match('/'.$key1.'/', $str);
        if($is){
            $deltids[$tid] = $subject;
            continue;
        }

        //匹配每段中文字符串
        foreach ($result[0] as $value) {
            $is = preg_match('/'.$key1.'/', $value);
            if($is){
            $deltids[$tid] = $subject;
                continue;
            }
        }
    }
}
var_dump($deltids);
die;
//删帖
if(empty($deltids)){
  exit('No Data');
}
$tids = array_keys($deltids);
$tids = implode(',', $tids);
$sql  = "DELETE FROM `pre_forum_thread` WHERE `tid` IN({$tids})";
$result = mysql_query($sql, $con);
$sql  = "DELETE FROM `pre_forum_post` WHERE `tid` IN({$tids})";
$result = mysql_query($sql, $con);
var_dump($deltids);

mysql_close($con);
