<?php
/**
	/////////////////////////////////////////////////////////////////////////////////////////
	//如果您看见这句话，说明您现在使用的服务器不支持PHP
	/////////////////////////////////////////////////////////////////////////////////////////

	//************ ************   版 权 声 明  ************* *********** ************ *********
	||
	|| UenuProbe PHP探针 完全采用Div+Css前端设计架构编写的新型PHP探针 Ver 2.09.10 Build 091001 编码为:UTF-8
	||
	|| 作  者：田慧民  电邮：info@uenu.com
	||
	|| 维  护：shvip	 主页：http://Tech.uenu.com   
	||
	|| QQ:191633089   
	||
	|| 如果你需要修改本程式请保留原作者的版权,谢谢。
    //************ ************ ************ ************ ************ ************ ************ 
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
		This PHP probe programe is designed based on the new structure Div + Css , the free open source software, powerful, clear structure and easy to use. 
		1. To support Windows, linux, Unix, FreeBSD, Sun Solar System.
		2. Support for IE6, IE7, Firefox, Google chrome, and other browsers. 
		
		The main use and application of the object: 
		1. Be familiar with the PHP programming of amateur and professional developers. 
		2. The managers to configure Linux (Windows) + PHP + mySQL + Zend system environment, be sure of the successful detection system configuration. 
		3. For the company's customers to buy Virtual host using testing server performance.
		
		一、本程序基于Div+Css 新型架构PHP探针，免费开源的自由软件，功能强大，结构清晰，使用方便。
		1.支持Windows，linux,Unix,FreeBSD,Sun Solar系统
		2.支持IE6，IE7，Firefox,Google chrome等浏览器。
		
		二、主要用途及适用对象：
		1.熟悉PHP编程的业余爱好者及专业开发人员。
		2.机房管理人员配置Linux（Windows）+PHP+mySQL+Zend系统环境，检测系统是否配置成功。
		3.对于购买虚拟主机的用户，用于测试服务器性能。
       //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    */

// header("content-Type: text/html; charset=utf-8");
@ini_set('memory_limit', -1);
ini_set("max_execution_time", "0");
ini_set('date.timezone','Asia/Shanghai');

if(isset($_GET['debug']) && $_GET['debug'] == 1){
	error_reporting(E_ALL & ~E_NOTICE);
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
}else{
	error_reporting(0);
}

$q = $_SERVER["QUERY_STRING"];
$urlarray = explode(".", $q);
$urlarr   = explode("-", $urlarray[0]);
//echo $urlarr[0];echo $urlarr[1];
//http://uenu.com/index.php?style=yel&icon=image
//http://www.uenu.com/index.php?a/a/a.html#testinfo
//exit;
ob_start();
$valInt = (false == empty($_POST['pInt']))?$_POST['pInt']:"未测试";
$valFloat = (false == empty($_POST['pFloat']))?$_POST['pFloat']:"未测试";
$valIo = (false == empty($_POST['pIo']))?$_POST['pIo']:"未测试";
$mysqlReShow = "none";
$mailReShow  = "none";
$funReShow   = "none";
$opReShow    = "none";
$sysReShow   = "none";

define("ICON", "<span class='icon'>2</span>&nbsp;");
$phpSelf = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
define("PHPSELF", preg_replace("/(.{0,}?\/+)/", "", $phpSelf));

$UenuCom['Version'] = "Ver 2.09.10";
$UenuCom['Date']    = "2009年10月01日";
$UenuCom['Contact'] = "http://Tech.uenu.com";
$UenuCom['HTTP']    = "http://Tech.uenu.com";

//============   定义常量 用于替换模板输出变量  =======================

//$icon = $_GET['icon'];
$icon = $urlarr[1];
switch($icon) {
    case "image":
        define("YES", "<span class='resYes'>√</span>");
        define("NO", "<span class='resNo'>×</span>");
        break;
    case "english":
        define("YES", "<span class='EnglishYes'>Yes</span>");
        define("NO", "<span class='EnglishNo'>No</span>");
        break;
    case "cn":
        define("YES", "<span class='CNYes'>支持</span>");
        define("NO", "<span class='CNNo'>不支持</span>");
        break;
    default:
        $icon = "image";
        define("YES", "<span class='resYes'>√</span>");
        define("NO", "<span class='resNo'>×</span>");
        break;
}
//================== 基类库 ===================================
class FunCheck {

    /**
    *-------------------------------------------------------------------------------------------------------------
    *    检测函数支持
    *-------------------------------------------------------------------------------------------------------------
    */
    public function isfun($funName) {
        return (false !== function_exists($funName))?YES:NO;
    }

    /**
    *-------------------------------------------------------------------------------------------------------------
    *    内存使用量检测， 输出显示进度条
    *-------------------------------------------------------------------------------------------------------------
    */
    public function bar($percent)
    {
        echo '<br/><ul class="bar">
	<li style="width:';
        echo $percent."%\">";
        echo '&nbsp;</li>
    </ul>';
    }

    /**
    *-------------------------------------------------------------------------------------------------------------
    *    检测PHP设置参数
    *-------------------------------------------------------------------------------------------------------------
    */
    public function getcon($varName) {
        switch($res = @get_cfg_var($varName))
        {
            case 0:
                return NO;
                break;
            case 1:
                return YES;
                break;
            default:
                return $res;
                break;
        }
    }

    /**
    *-------------------------------------------------------------------------------------------------------------
    *    整数运算能力测试
    *-------------------------------------------------------------------------------------------------------------
    */
    public function test_int() {
        $timeStart = gettimeofday();
        for($i = 0; $i <= 3000000; $i++);
        {
            $t = 1+1;
        }
        $timeEnd = gettimeofday();
        $time = ($timeEnd["usec"]-$timeStart["usec"])/1000000+$timeEnd["sec"]-$timeStart["sec"];
        $time = round($time, 6)."秒";
        return $time;
    }

    /**
    *-------------------------------------------------------------------------------------------------------------
    *    浮点运算能力测试
    *-------------------------------------------------------------------------------------------------------------
    */
    public function test_float() {
        $t = pi();
        $timeStart = gettimeofday();
        for($i = 0; $i < 3000000; $i++);
        {
            sqrt($t);
        }
        $timeEnd = gettimeofday();
        $time = ($timeEnd["usec"]-$timeStart["usec"])/1000000+$timeEnd["sec"]-$timeStart["sec"];
        $time = round($time, 6)."秒";
        return $time;
    }
    /**
    *-------------------------------------------------------------------------------------------------------------
    *    数据IO能力测试
    *-------------------------------------------------------------------------------------------------------------
    */
    public function test_io()   {
        $fp = fopen(PHPSELF, "r");
        $timeStart = gettimeofday();
        for($i = 0; $i < 10000; $i++)
        {
            fread($fp, 10240);
            rewind($fp);
        }
        $timeEnd = gettimeofday();
        fclose($fp);
        $time = ($timeEnd["usec"]-$timeStart["usec"])/1000000+$timeEnd["sec"]-$timeStart["sec"];
        $time = round($time, 6)."秒";
        return($time);
    }

    /**
    *-------------------------------------------------------------------------------------------------------------
    * 确定执行文件位置 FreeBSD
    --------------------------------------------------------------------------------------------------------------    
    */
    public function find_command($commandName)
    {
        $path = array('/bin', '/sbin', '/usr/bin', '/usr/sbin', '/usr/local/bin', '/usr/local/sbin');
        foreach($path as $p)
        {
            if (@is_executable("$p/$commandName")) return "$p/$commandName";
        }
        return false;
    }


    /**
     *-------------------------------------------------------------------------------------------------------------
     *    执行系统命令 FreeBSD
     *--------------------------------------------------------------------------------------------------------------    
    */
    public function do_command($commandName, $args)
    {
        $buffer = "";
        if (false === ($command = $this->find_command($commandName))) return false;
        if ($fp = @popen("$command $args", 'r'))
        {
            while (!@feof($fp))
            {
                $buffer .= @fgets($fp, 4096);
            }
            return trim($buffer);
        }
        return false;
    }

    /**
    *-------------------------------------------------------------------------------------------------------------
    *   取得参数值 FreeBSD
    *-------------------------------------------------------------------------------------------------------------    
    */
    public function get_key($keyName)
    {
        return $this->do_command('sysctl', "-n $keyName");
    }


    /**
    *-------------------------------------------------------------------------------------------------------------
    *   系统参数探测 Windows
    *-------------------------------------------------------------------------------------------------------------    
    */
    public function sys_windows()
    {
        //$phpos=PHP_OS;
        $sysInfo['uptime'] ="此系统暂不支持检测";
    }


    /**
    *-------------------------------------------------------------------------------------------------------------
    *   系统参数探测 LINUX 
    *-------------------------------------------------------------------------------------------------------------
    */
    public function sys_linux()
    {
        // CPU
        if (false === ($str = @file("/proc/cpuinfo"))) return false;
        $str = implode("", $str);
        @preg_match_all("/model\s+name\s{0,}\:+\s{0,}([\w\s\)\(.]+)[\r\n]+/", $str, $model);
        //@preg_match_all("/cpu\s+MHz\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/", $str, $mhz);
        @preg_match_all("/cache\s+size\s{0,}\:+\s{0,}([\d\.]+\s{0,}[A-Z]+[\r\n]+)/", $str, $cache);
        if (false !== is_array($model[1]))
        {
            $res['cpu']['num'] = sizeof($model[1]);
            for($i = 0; $i < $res['cpu']['num']; $i++)
            {
                $res['cpu']['detail'][] = "类型：".$model[1][$i]." 缓存：".$cache[1][$i];
            }
            if (false !== is_array($res['cpu']['detail'])) $res['cpu']['detail'] = implode("<br />", $res['cpu']['detail']);
        }

        // UPTIME
        if (false === ($str = @file("/proc/uptime"))) return false;
        $str = explode(" ", implode("", $str));
        $str = trim($str[0]);
        $min = $str / 60;
        $hours = $min / 60;
        $days = floor($hours / 24);
        $hours = floor($hours - ($days * 24));
        $min = floor($min - ($days * 60 * 24) - ($hours * 60));
        if ($days != 0) {$res['uptime'] = $days."天";}
        if ($hours != 0) {$res['uptime'] .= $hours."小时";}
        $res['uptime'] .= $min."分钟";

        // MEMORY
        if (false === ($str = @file("/proc/meminfo"))) return false;
        $str = implode("", $str);
        preg_match_all("/MemTotal\s{0,}\:+\s{0,}([\d\.]+).+?MemFree\s{0,}\:+\s{0,}([\d\.]+).+?SwapTotal\s{0,}\:+\s{0,}([\d\.]+).+?SwapFree\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $buf);

        $res['memTotal'] = round($buf[1][0]/1024, 2);
        $res['memFree'] = round($buf[2][0]/1024, 2);
        $res['memUsed'] = ($res['memTotal']-$res['memFree']);
        $res['memPercent'] = (floatval($res['memTotal'])!=0)?round($res['memUsed']/$res['memTotal']*100,2):0;

        $res['swapTotal'] = round($buf[3][0]/1024, 2);
        $res['swapFree'] = round($buf[4][0]/1024, 2);
        $res['swapUsed'] = ($res['swapTotal']-$res['swapFree']);
        $res['swapPercent'] = (floatval($res['swapTotal'])!=0)?round($res['swapUsed']/$res['swapTotal']*100,2):0;

        // LOAD AVG
        if (false === ($str = @file("/proc/loadavg"))) return false;
        $str = explode(" ", implode("", $str));
        $str = array_chunk($str, 3);
        $res['loadAvg'] = implode(" ", $str[0]);

        return $res;
    }


    /**
    *-------------------------------------------------------------------------------------------------------------
    *    系统参数探测 FreeBSD
    *-------------------------------------------------------------------------------------------------------------
    */
    public function sys_freebsd()
    {
        //CPU
        if (false === ($res['cpu']['num'] = $this->get_key("hw.ncpu"))) return false;
        $res['cpu']['detail'] = $this->get_key("hw.model");

        //LOAD AVG
        if (false === ($res['loadAvg'] = $this->get_key("vm.loadavg"))) return false;
        $res['loadAvg'] = str_replace("{", "", $res['loadAvg']);
        $res['loadAvg'] = str_replace("}", "", $res['loadAvg']);

        //UPTIME
        if (false === ($buf = $this->get_key("kern.boottime"))) return false;
        $buf = explode(' ', $buf);
        $sys_ticks = time() - intval($buf[3]);
        $min = $sys_ticks / 60;
        $hours = $min / 60;
        $days = floor($hours / 24);
        $hours = floor($hours - ($days * 24));
        $min = floor($min - ($days * 60 * 24) - ($hours * 60));
        if ($days != 0) $res['uptime'] = $days."天";
        if ($hours != 0) $res['uptime'] .= $hours."小时";
        $res['uptime'] .= $min."分钟";

        //MEMORY
        if (false === ($buf = $this->get_key("hw.physmem"))) return false;
        $res['memTotal'] = round($buf/1024/1024, 2);
        $buf = explode("\n", $this->do_command("vmstat", ""));
        $buf = explode(" ", trim($buf[2]));

        $res['memFree'] = round($buf[5]/1024, 2);
        $res['memUsed'] = ($res['memTotal']-$res['memFree']);
        $res['memPercent'] = (floatval($res['memTotal'])!=0)?round($res['memUsed']/$res['memTotal']*100,2):0;

        $buf = explode("\n", $this->do_command("swapinfo", "-k"));
        $buf = $buf[1];
        preg_match_all("/([0-9]+)\s+([0-9]+)\s+([0-9]+)/", $buf, $bufArr);
        $res['swapTotal'] = round($bufArr[1][0]/1024, 2);
        $res['swapUsed'] = round($bufArr[2][0]/1024, 2);
        $res['swapFree'] = round($bufArr[3][0]/1024, 2);
        $res['swapPercent'] = (floatval($res['swapTotal'])!=0)?round($res['swapUsed']/$res['swapTotal']*100,2):0;

        return $res;
    }

}


//=================================================================
$MyCheck = new FunCheck();

//if ($_GET['Action'] == "phpinfo") {
if ($urlarr['2'] == "phpinfo") {
    echo phpinfo();
    exit;
}  elseif($_POST['Action'] == "TEST_1") {
    $valInt = $MyCheck->test_int();
}  elseif($_POST['Action'] == "TEST_2") {
    $valFloat = $MyCheck->test_float();
}  elseif($_POST['Action'] == "TEST_3") {
    $valIo = $MyCheck->test_io();
}  elseif($_POST['Action'] == "CONNECT") {
    $mysqlReShow = "show";
    $mysqlRe = "MYSQL连接测试结果：";
    $mysqlRe .= (false !== @mysql_connect($_POST['mysqlHost'], $_POST['mysqlUser'], $_POST['mysqlPassword']))?"MYSQL服务器连接正常, ":"MYSQL服务器连接失败, ";
    $mysqlRe .= "数据库 <b>".$_POST['mysqlDb']."</b> ";
    $mysqlRe .= (false != @mysql_select_db($_POST['mysqlDb']))?"连接正常":"连接失败";
}  elseif($_POST['Action'] == "SENDMAIL") {
    $mailReShow = "show";
    $mailRe = "MAIL邮件发送测试结果：发送";
    $mailRe .= (false !== @mail($_POST["mailReceiver"], "UenuProbe Mail Server Test.", "This email is sent by UenuProbe.\r\n\r\n\r\n\r\n\r\nCopyRight UenuCom\r\nhttp://www.uenu.com"))?"完成":"失败";
}  elseif($_POST['Action'] == "FUNCTION_CHECK")  {
    $funReShow = "show";
    $funRe = "函数 <b>".$_POST['funName']."</b> 支持状况检测结果：".$MyCheck->isfun($_POST['funName']);
}  elseif($_POST['Action'] == "CONFIGURATION_CHECK") {
    $opReShow = "show";
    $opRe = "配置参数 <b>".$_POST['opName']."</b> 检测结果：".$MyCheck->getcon($_POST['opName']);
}

//========================================================================

//=============    风格设置  ==============================================
//$style = $_GET['style'];
$style = $urlarr[0];

if(empty($style) or ($style !== "yel" and $style !== "sky")) {
    $style = "sum";
}
switch($style) {
    case "yel":
        // if ($style == "yel") {
        {
            ///////////粉色情人风格/////////
            $skin['background'] = "#FFFFCC"; //页面背景颜色
            $skin['title'] = "#FF9D6F"; //大标题里的背景颜色
            $skin['border'] = "#FB5200"; //所有的边框颜色

            //按钮 输入框颜色
            $skin['button'] = "#F7F7F7"; //所有的按钮颜色
            $skin['inputborder'] = "#FF9D6F"; //输入框颜色
            $skin['inputbackground'] = "#FFFFDD"; //输入框背景颜色

            ///menu
            $skin['menubgcolor'] = "#999999";  //菜单bgcolor]颜色
            $skin['menulink'] = "#FFFFFF";  //菜单link颜色
            $skin['menuvisited'] = "#FFFFFF"; //菜单visited颜色
            $skin['menuhover'] = "#FFFFFF"; //菜单hover颜色
            $skin['menuactive'] = "#FFFFFF";  //菜单active颜色

            //模板输出（字体 符号）
            $skin['resYes'] = "#339900";  //resYes 颜色
            $skin['resNo'] = "red";   //resNo 颜色
            $skin['font'] = "#333333"; //所有的字体颜色
            $skin['titlefont'] = "#FF0000"; //大标题里的字体颜色

            //链接颜色
            $skin['Alink'] = "green";  //link 颜色
            $skin['Ahover'] = "red"; //hover 颜色
            $skin['Aactive'] = "#007700"; //active 颜色
            $skin['Avisited'] = "#007700"; //visited 颜色
        }
        break;
    case "sky":
        //elseif($style == "sky") {
        {
            ///////////蓝色天空风格//////////
            $skin['background'] = "#F8FCFE"; //页面背景颜色
            $skin['title'] = "#0CCCC0"; //大标题里的背景颜色66CC66
            $skin['border']="#0CCCC0"; //所有的边框颜色

            //按钮 输入框颜色
            $skin['button']="#F7F7F7"; //所有的按钮颜色
            $skin['inputborder'] = "#0CCCC0"; //输入框颜色
            $skin['inputbackground'] = "#F8FCFE"; //输入框背景颜色

            ///menu
            $skin['menubgcolor']="#999999";  //菜单bgcolor]颜色
            $skin['menulink']="#FFFFFF";  //菜单link颜色
            $skin['menuvisited']="#FFFFFF"; //菜单visited颜色
            $skin['menuhover']="#FFFFFF"; //菜单hover颜色
            $skin['menuactive']="#FFFFFF";  //菜单active颜色

            //模板输出（字体 符号）
            $skin['resYes']="#339900";  //resYes 颜色
            $skin['resNo']="red";   //resNo 颜色
            $skin['font']="#333333"; //所有的字体颜色
            $skin['titlefont']="#FF0000"; //大标题里的字体颜色

            //链接颜色
            $skin['Alink'] = "green";  //link 颜色
            $skin['Ahover'] = "red"; //hover 颜色
            $skin['Aactive'] = "#007700"; //active 颜色
            $skin['Avisited'] = "#007700"; //visited 颜色
        }
        break;
    default:
        //elseif($style == "sum") {
        {
            ///////////清爽夏日风格//////////
            $skin['background'] = "#EEFEE0"; //页面背景颜色
            $skin['title'] = "#72CF72"; //大标题里的背景颜色
            $skin['border'] = "#007700"; //所有的边框颜色

            //按钮 输入框颜色
            $skin['button'] = "#F7F7F7"; //所有的按钮颜色
            $skin['inputborder'] = "#007700"; //输入框颜色
            $skin['inputbackground'] = "#EEFEE0"; //输入框背景颜色

            ///menu
            $skin['menubgcolor'] = "#999999";  //菜单bgcolor]颜色
            $skin['menulink'] = "#FFFFFF";  //菜单link颜色
            $skin['menuvisited'] = "#FFFFFF"; //菜单visited颜色
            $skin['menuhover'] = "#FFFFFF"; //菜单hover颜色
            $skin['menuactive'] = "#FFFFFF";  //菜单active颜色

            //模板输出（字体 符号）
            $skin['resYes'] = "#339900";  //resYes 颜色
            $skin['resNo'] = "red";   //resNo 颜色
            $skin['font'] = "#333333"; //所有的字体颜色
            $skin['titlefont'] = "#FF0000"; //大标题里的字体颜色

            //链接颜色
            $skin['Alink'] = "green";  //link 颜色
            $skin['Ahover'] = "red"; //hover 颜色
            $skin['Aactive'] = "#007700"; //active 颜色
            $skin['Avisited'] = "#007700"; //visited 颜色
        }
        break;
}
//=============    结束风格  ==============================================
//========================================================================
switch (PHP_OS)
{
    case "Linux":
        $sysReShow = (false != ($sysInfo = $MyCheck->sys_linux()))?"show":"none";
        break;
    case "FreeBSD":
        $sysReShow = (false != ($sysInfo = $MyCheck->sys_freebsd()))?"show":"none";
        break;
    case "Windows":
        //$sysReShow = (false != ($sysInfo = $MyCheck->sys_windows()))?"show":"none";
        $sysInfo['uptime'] ="此系统暂不支持检测";
        break;
    default:
        break;
}

//========================================================================
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>优艺国际 PHP探针 <?php echo $UenuCom['Version']?> Copyright Uenu.Com</title>
<meta name="keywords" content="PHP,探针,PHP探针,PHP编程,UenuProbe,Probe,Uenu.com,优艺国际"/>
<meta name="description" content="UenuProbe PHP探针 完全采用Div+Css 前端设计架构编写的新型PHP探针 <?php echo $UenuCom['Version']?> Build 090328 编码为:UTF-8 (UenuProbe,UenuCom,Uenu.com,PHP探针,PHP编程,优艺国际)"/>
<meta name="generator" content="UenuProbe <?php echo $UenuCom['Version']?>" />
<meta name="author" content="田慧民 , Tian Huimin" />
<meta name="copyright" content="2001-2009 UenuCom Corp." />
<style type="text/css">
<!--
body {
	text-align: center;
	margin-top: 0;
	margin-right: auto;
	margin-bottom: 0;
	margin-left: auto;
	font-size: 12px;
	color: <?php echo $skin['font']?>;
}
#header {
	font-style: normal;	width: 96%;
	height: 40px;
	margin-right: auto;
	margin-left: auto;
	margin-top: 10px;
}
#header #left {
	float: left;
	height: 28px;
	width: 28%;
	text-align: center;
}
#header #left  #a{
	height: 12px;
	width: auto;
	text-align: left;
	padding-left: 20px;
	font-size: 14px;
	font-weight: bold;
	color: #FF0000;

}
#header #left #b{
	height: 28px;
	width: auto;
	text-align: left;
	padding-left: 20px;
	font-size: 24px;
	font-weight: bold;
	color: <?php echo $skin['border']?>;
}
#header #right {
	height: 28px;
	width: 72%;
	float: left;
	background-color: #CCCCCC;
	padding-top: 13px;
	text-align: left;
}
#header #right #blank {
	height: 2px;
	width: 20px;
	float: left;
}
#header #menu {
	text-align: left;
	height: 25px;	width: 96%;
	padding-top: 10px;
	background-color: <?php echo $skin['menubgcolor']?>;
}
A.menu:link {
	PADDING-RIGHT: 2px;
	PADDING-LEFT: 2px;
	FONT-SIZE: 13px;
	COLOR: <?php echo $skin['menulink']?>;
	TEXT-DECORATION: none
}
A.menu:visited {
	PADDING-RIGHT: 2px;
	PADDING-LEFT: 2px;
	FONT-SIZE: 13px;
	COLOR: <?php echo $skin['menuvisited']?>;
	TEXT-DECORATION: none
}
A.menu:hover {
	PADDING-RIGHT: 2px;
	PADDING-LEFT: 2px;
	FONT-SIZE: 13px;
	PADDING-BOTTOM: 10px;
	COLOR: <?php echo $skin['menuhover']?>;
	PADDING-TOP: 10px;
	BACKGROUND-COLOR: <?php echo $skin['border']?>;
	TEXT-ALIGN: center;
	TEXT-DECORATION: none
}
A.menu:active {
	PADDING-RIGHT: 2px;
	PADDING-LEFT: 2px;
	FONT-SIZE: 13px;
	COLOR: <?php echo $skin['menuactive']?>;
	TEXT-ALIGN: center;
	TEXT-DECORATION: none
}
A.sky:link {
	color: #0CCCC0;
}
A.sky:visited {
	color: #0CCCC0;
}
A.yel:link {
	color: #FF9D6F;
}
A.yel:visited {
	color: #FB5200;
}
A.sum:link {
	color: #72CF72;
}
A.sum:visited {
	color:#007700;
}
A.download:link {
	color: #000;
	text-decoration: none;
}
A.download:hover {
	color: #FF6600;
    text-decoration: underline;
}
A.download:active {
	color: #000;
    text-decoration: underline;
}
A.download:visited {
	color:#000;
	text-decoration: none;
}
A:link {
	color: <?php echo $skin['Alink']?>;
}
A:hover {
	color: <?php echo $skin['Ahover']?>;
}
A:active {
	color: <?php echo $skin['Aactive']?>;
}
A:visited {
	color: <?php echo $skin['Avisited']?>;
}
.resYes {
	font-size: 14px;
	color: <?php echo $skin['resYes']?>;
	font-weight: bold;
	font-family: Verdana;
} 
.resNo {
	font-size: 14px;
	color: <?php echo $skin['resNo']?>;
	font-weight: bold;
	font-family: Verdana;
}
.EnglishYes {
	font-size: 12px;
	color: <?php echo $skin['resYes']?>;
	font-family: Verdana;
} 
.EnglishNo {
	font-size: 12px;
	color: <?php echo $skin['resNo']?>;
	font-family: Verdana;
}
.CNYes {
	font-size: 12px;
	color: <?php echo $skin['resYes']?>;
	font-family: Verdana;
} 
.CNNo {
	font-size: 12px;
	color: <?php echo $skin['resNo']?>;
	font-family: Verdana;
}
.myButton {
	font-size:10px;
	font-weight:normal;
	background-color: <?php echo $skin['button']?>;
}
input {
	border: 1px solid <?php echo $skin['inputborder']?>;
	background:<?php echo $skin['inputbackground']?>;
	height: 20px;
	width: 120px;
	margin-left: 5px;
}
#total {
	height: auto;
	width: 96%;
	margin-right: auto;
	margin-left: auto;
	margin-top: 2px;
	border: 1px solid <?php echo $skin['border']?>;
	text-align: center;
	background-color: <?php echo $skin['background']?>;
}
#total #style  {
	height: 30px;
	width: auto;
	text-align: left;
}
#total #style #left {
	height: 20px;
	width: 40%;
	padding-top: 10px;
	float: left;
	font-size: 13px;
	font-weight: bold;
	text-align: center;
}
#total #style #right {
	text-align: center;
	float: left;
	height: 20px;
	width: auto;
	padding-top: 10px;
}
#total .title {
	text-align: center;
	height: 25px;	width: auto;
	margin-right: auto;
	margin-left: auto;
	background-color: <?php echo $skin['title']?>;
	padding-top: 5px;
	margin-top: 5px;
	margin-bottom: 5px;
	font-size: 14px;
	font-weight: bold;
}
#total #serverinfo {
<?php
$os = explode(" ", php_uname()); if ($os[0] =="Windows") {echo "height:292px";} else {echo "height:356px";}
//	height:266px;
?>;
	width: 720px;
	margin-right: auto;
	margin-left: auto;
}
#total #serverinfo .info1 {
	width: 195px;
	height: 18px;
	border: 1px solid <?php echo $skin['border']?>;
	float: left;
	padding-top: 3px;
	text-align: left;
	padding-left: 5px;
}
#total #serverinfo .info2 {
	width: 509px;
	height: 18px;
	float: left;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-top-color: <?php echo $skin['border']?>;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-color: <?php echo $skin['border']?>;
	padding-top: 3px;
	text-align: left;
	padding-left: 5px;
	overflow: hidden;
}
#total #serverinfo .info3 {
	width: 195px;
	height: 18px;
	float: left;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-color: <?php echo $skin['border']?>;
	border-left-color: <?php echo $skin['border']?>;
	padding-top: 3px;
	text-align: left;
	padding-left: 5px;
}
#total #serverinfo .info4 {
	width: 509px;
	height: 18px;
	float: left;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-right-style: solid;
	border-bottom-style: solid;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-color: <?php echo $skin['border']?>;
	padding-top: 3px;
	text-align: left;
	padding-left: 5px;
	overflow: hidden;
}
#total #phpinfo {
	height:270px;
	width: 720px;
	margin-right: auto;
	margin-left: auto;
}
#total  #phpinfo  #left {
	width: 359px;
	height: 270px;
	float: left;
	text-align: left;
	margin-right: 1px;
}
#total #phpinfo #left .info01 {
	width: 250px;
	height: 18px;
	border: 1px solid <?php echo $skin['border']?>;
	float: left;
	padding-top: 3px;
	text-align: left;
	padding-left: 5px;
}
#total #phpinfo #left .info02 {
	width: 95px;
	height: 18px;
	float: left;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-top-color: <?php echo $skin['border']?>;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-color: <?php echo $skin['border']?>;
	padding-top: 3px;
	text-align: left;
	padding-left: 5px;
	overflow: hidden;
}
#total #phpinfo #left .info03 {
	width: 250px;
	height: 18px;
	float: left;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-color: <?php echo $skin['border']?>;
	border-left-color: <?php echo $skin['border']?>;
	padding-top: 3px;
	text-align: left;
	padding-left: 5px;
}
#total #phpinfo #left .info04 {
	width: 95px;
	height: 18px;
	float: left;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-right-style: solid;
	border-bottom-style: solid;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-color: <?php echo $skin['border']?>;
	padding-top: 3px;
	text-align: left;
	padding-left: 5px;
	overflow: hidden;
}
#total #phpinfo #right {
	width: 359px;
	height: 270px;
	float: left;
	text-align: left;
	margin-left: 1px;
}
#total #phpinfo #right .info01 {
	width: 250px;
	height: 18px;
	border: 1px solid <?php echo $skin['border']?>;
	float: left;
	padding-top: 3px;
	text-align: left;
	padding-left: 5px;
}
#total #phpinfo #right .info02 {
	width: 95px;
	height: 18px;
	float: left;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-top-color: <?php echo $skin['border']?>;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-color: <?php echo $skin['border']?>;
	padding-top: 3px;
	text-align: left;
	padding-left: 5px;
}
#total #phpinfo #right .info03 {
	width: 250px;
	height: 18px;
	float: left;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-color: <?php echo $skin['border']?>;
	border-left-color: <?php echo $skin['border']?>;
	padding-top: 3px;
	text-align: left;
	padding-left: 5px;
}
#total #phpinfo #right .info04 {
	width: 95px;
	height: 18px;
	float: left;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-right-style: solid;
	border-bottom-style: solid;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-color: <?php echo $skin['border']?>;
	padding-top: 3px;
	text-align: left;
	padding-left: 5px;
}
#total #otherinfo {
	height:620px;
	width: 720px;
	margin-right: auto;
	margin-left: auto;
}
#total #otherinfo  #left {
	width: 359px;
	height: 500px;
	float: left;
	text-align: left;
	margin-right: 1px;
}
#total #otherinfo #left .infoe01 {
	width: 250px;
	height: 18px;
	border: 1px solid <?php echo $skin['border']?>;
	float: left;
	padding-top: 3px;
	text-align: left;
	padding-left: 5px;
}
#total #otherinfo #left .infoe02 {
	width: 95px;
	height: 18px;
	float: left;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-top-color: <?php echo $skin['border']?>;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-color: <?php echo $skin['border']?>;
	padding-top: 3px;
	text-align: left;
	padding-left: 5px;
}
#total #otherinfo #left .infoe03 {
	width: 250px;
	height: 18px;
	float: left;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-color: <?php echo $skin['border']?>;
	border-left-color: <?php echo $skin['border']?>;
	padding-top: 3px;
	text-align: left;
	padding-left: 5px;
}
#total #otherinfo #left .infoe04 {
	width: 95px;
	height: 18px;
	float: left;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-right-style: solid;
	border-bottom-style: solid;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-color: <?php echo $skin['border']?>;
	padding-top: 3px;
	text-align: left;
	padding-left: 5px;
}
#total #otherinfo #right {
	width: 359px;
	height: 500px;
	float: left;
	text-align: left;
	margin-left: 1px;
}
#total #otherinfo #right .infoe01 {
	width: 250px;
	height: 18px;
	border: 1px solid <?php echo $skin['border']?>;
	float: left;
	padding-top: 3px;
	text-align: left;
	padding-left: 5px;
}
#total #otherinfo #right .infoe02 {
	width: 95px;
	height: 18px;
	float: left;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-top-color: <?php echo $skin['border']?>;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-color: <?php echo $skin['border']?>;
	padding-top: 3px;
	text-align: left;
	padding-left: 5px;
}
#total #otherinfo #right .infoe03 {
	width: 250px;
	height: 18px;
	float: left;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-color: <?php echo $skin['border']?>;
	border-left-color: <?php echo $skin['border']?>;
	padding-top: 3px;
	text-align: left;
	padding-left: 5px;
}
#total #otherinfo #right .infoe04 {
	width: 95px;
	height: 18px;
	float: left;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-right-style: solid;
	border-bottom-style: solid;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-color: <?php echo $skin['border']?>;
	padding-top: 3px;
	text-align: left;
	padding-left: 5px;
}
#total #testinfo {
	height:190px;
	width: 720px;
	margin-right: auto;
	margin-left: auto;
}
#total #testinfo .test01 {
	float: left;
	height: 27px;
	width: 295px;
	padding-top: 10px;
	border: 1px solid <?php echo $skin['border']?>;
	text-align: center;
}
#total #testinfo .test02 {
	float: left;
	height: 32px;
	width: 140px;
	padding-top: 5px;
	border-top-width: 1px;
	border-right-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-top-color: <?php echo $skin['border']?>;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: <?php echo $skin['border']?>;
	text-align: center;
}
#total #testinfo .test03 {
	float: left;
	height: 32px;
	width: 140px;
	padding-top: 5px;
	border-top-width: 1px;
	border-right-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-top-color: <?php echo $skin['border']?>;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: <?php echo $skin['border']?>;
	text-align: center;
}
#total #testinfo .test04 {
	float: left;
	height: 33px;
	width: 140px;
	padding-top: 4px;
	border-top-width: 1px;
	border-right-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-top-color: <?php echo $skin['border']?>;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: <?php echo $skin['border']?>;
	text-align: center;
}
#total #testinfo .test05 {
	float: left;
	height: 25px;
	width: 285px;
	padding-top: 5px;
	border-right-width: 1px;
	border-left-width: 1px;
	border-right-style: solid;
	border-left-style: solid;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	text-align: left;
	padding-left: 10px;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-color: <?php echo $skin['border']?>;
	border-left-color: <?php echo $skin['border']?>;
}
#total #testinfo .test06 {
	float: left;
	height: 25px;
	width: 140px;
	padding-top: 5px;
	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: <?php echo $skin['border']?>;
	text-align: center;
}
#total #testinfo .test07 {
	float: left;
	height: 25px;
	width: 140px;
	padding-top: 5px;
	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: <?php echo $skin['border']?>;
	text-align: center;
}
#total #testinfo .test08 {
	float: left;
	height: 25px;
	width: 140px;
	padding-top: 5px;
	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: <?php echo $skin['border']?>;
	text-align: center;
}
#total #checkinfo {
	height:480px;
	width: 720px;
	border: 1px solid <?php echo $skin['border']?>;
	margin-right: auto;
	margin-left: auto;
}
#total #checkinfo .check00 {
	float: left;
	height: 25px;
	width: 700px;
	padding-top: 10px;
	text-align: left;
	padding-left: 20px;
}
#total #checkinfo #check01 {
	float: left;
	height: 25px;
	width: 120px;
	padding-top: 10px;
	text-align: left;
	padding-left: 20px;
	margin-left: 5px;
	border-top-width: 1px;
	border-right-width: 1px;
	border-left-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-left-style: solid;
	border-top-color: <?php echo $skin['border']?>;
	border-right-color: <?php echo $skin['border']?>;
	border-left-color: <?php echo $skin['border']?>;
}
#total #checkinfo #check02 {
	float: left;
	height: 30px;
	width: 140px;
	border-top-width: 1px;
	border-right-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-top-color: <?php echo $skin['border']?>;
	border-right-color: <?php echo $skin['border']?>;
	text-align: left;
	padding-top: 5px;
}
#total  #checkinfo  #check03 {
	float: left;
	height: 25px;
	width: 120px;
	padding-top: 10px;
	border-top-width: 1px;
	border-right-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-top-color: <?php echo $skin['border']?>;
	border-right-color: <?php echo $skin['border']?>;
	text-align: left;
	padding-left: 20px;
}
#total #checkinfo #check04 {
	float: left;
	height: 30px;
	width: 140px;
	padding-top: 5px;
	border-top-width: 1px;
	border-right-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-top-color: <?php echo $skin['border']?>;
	border-right-color: <?php echo $skin['border']?>;
	text-align: left;
}
#total #checkinfo #check05 {
	float: left;
	height: 30px;
	width: 140px;
	padding-top: 5px;
	text-align: center;
	border-top-width: 1px;
	border-right-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-top-color: <?php echo $skin['border']?>;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-color: <?php echo $skin['border']?>;
	border-left-color: <?php echo $skin['border']?>;
}
#total #checkinfo #check06 {
	float: left;
	height: 25px;
	width: 120px;
	padding-top: 10px;
	text-align: left;
	border: 1px solid <?php echo $skin['border']?>;
	padding-left: 20px;
	margin-left: 5px;
}
#total #checkinfo #check07 {
	float: left;
	height: 30px;
	width: 140px;
	border-top-width: 1px;
	border-right-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-top-color: <?php echo $skin['border']?>;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: <?php echo $skin['border']?>;
	text-align: left;
	padding-top: 5px;
}
#total  #checkinfo #check08 {
	float: left;
	height: 25px;
	width: 120px;
	padding-top: 10px;
	border-top-width: 1px;
	border-right-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-top-color: <?php echo $skin['border']?>;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: <?php echo $skin['border']?>;
	text-align: left;
	padding-left: 20px;
}
#total #checkinfo #check09 {
	float: left;
	height: 30px;
	width: 140px;
	padding-top: 5px;
	border-top-width: 1px;
	border-right-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-top-color: <?php echo $skin['border']?>;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: <?php echo $skin['border']?>;
	text-align: left;
}
#total #checkinfo #check10 {
	float: left;
	height: 30px;
	width: 140px;
	padding-top: 5px;
	text-align: center;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-top-color: <?php echo $skin['border']?>;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-color: <?php echo $skin['border']?>;
	border-left-color: <?php echo $skin['border']?>;
}
#total #checkinfo #check11 {
	float: left;
	height: 25px;
	width: 120px;
	padding-top: 10px;
	text-align: left;
	border: 1px solid <?php echo $skin['border']?>;
	padding-left: 20px;
	margin-left: 5px;
}
#total #checkinfo #check12 {
	float: left;
	height: 30px;
	width: 140px;
	padding-top: 5px;
	text-align: left;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-top-color: <?php echo $skin['border']?>;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-color: <?php echo $skin['border']?>;
}
#total #checkinfo #check13 {
	float: left;
	height: 30px;
	width: 250px;
	padding-top: 5px;
	text-align: center;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-top-color: <?php echo $skin['border']?>;
	border-right-color: <?php echo $skin['border']?>;
	border-bottom-color: <?php echo $skin['border']?>;
}
#total #aboutinfo {
	height:auto;
	width: 96%;
	margin-right: auto;
	margin-left: auto;
	text-align: center;
}
#total  #aboutinfo  #declare {
	font-size: 14px;
	height: 25px;	width: 96%;
	margin-right: auto;
	margin-left: auto;
	padding-top: 5px;
	text-align: left;
	padding-left: 50px;
	font-weight: bold;
}
#total #aboutinfo #top {
	height: 12px;
	width: 98%;
	padding-top: 5px;
	text-align: right;
}
#total #aboutinfo #gnu {
	height:auto;
	width: 60%;
	text-align: left;
	padding-top: 5px;
	padding-left: 160px;
}
#total #aboutinfo #hr {
	height:5px;
	width: 98%;
	text-align: center;
}
#total #aboutinfo #about {
	text-align: right;
	height: 160px;
	width: 98%;
}
#total #aboutinfo #about #us {
	float: right;
	height: 60px;
	width: 260px;
	text-align: left;
	border: 1px solid #999999;
	font-size: 12px;
	padding-left: 6px;
	padding-top: 6px;
	line-height: 150%;
	margin-top: 20px;
}

-->
</style>
</head>

<body>

<!--header 开始-->
<div id="header">
<a name="top"></a>
<div id="left"  style="CURSOR: hand;" onClick="window.open('http://tech.uenu.com/UenuProbe/')">
<div id="a">Tech.uenu.com &reg;</div>
<div id="b">PHP 探针<span style="font-size:12px"><?php echo $UenuCom['Version']?></span></div>
</div>
<div id="right">
<div id="blank"></div>
<A href="<?php echo $phpSelf."?$style-$icon.shtml#serverinfo"?>" class="menu" >服务器特征</A>
<A href="<?php echo $phpSelf."?$style-$icon.shtml#phpinfo"?>" class="menu" >PHP基本特征</A>
<A href="<?php echo $phpSelf."?$style-$icon.shtml#otherinfo"?>" class="menu" >PHP组件支持状况</A>
<A href="<?php echo $phpSelf."?$style-$icon.shtml#testinfo"?>" class="menu" >服务器性能检测</A>
<A href="<?php echo $phpSelf."?$style-$icon.shtml#customcheckinfo"?>" class="menu" >自定义检测</A>
<A href="<?php echo $phpSelf."?$style-$icon.shtml#aboutus"?>" class="menu" >关于我们</A>
<A href="<?php echo $phpSelf."?$style-$icon.shtml#style"?>" class="menu" >风格选择</A>
<A href="<?php echo $phpSelf."?$style-$icon.shtml"?>" class="menu" >刷新</A></div>
</div>
<!--header 结束-->
<!--total 开始-->
<a name="serverinfo"></a>
<div id="total">
<div id="style">
<div id="left"><a href="http://Tech.uenu.com/UenuProbe/UenuProbe.rar" class="download" title="
  ┏━━━━━━━━━━━━━━━━┓  
  ┠                                ┨  
  ┠           软件下载             ┨  
  ┠  软件名称：UenuProbe PHP探针   ┨  
  ┠  最新版本：<?php echo $UenuCom['Version']?>         ┨  
  ┠  发布日期：<?php echo $UenuCom['Date']?>      ┨  
  ┠  技术支持：Tech.uenu.com       ┨  
  ┠                                ┨  
  ┗━━━━━━━━━━━━━━━━┛  
" >UenuProbe -- ※基于Div+Css 架构PHP探针</a></div>
<div id="right">页面风格：<a href="<?php echo $phpSelf."?sky-$icon.shtml"?>" class="sky">[蓝色天空]</a>&nbsp;&nbsp;<a href="<?php echo $phpSelf."?yel-$icon.shtml"?>" class="yel">[粉色情人]</a>&nbsp;&nbsp;<a href="<?php echo $phpSelf."?sum-$icon.shtml"?>" class="sum">[清爽夏日]</a>&nbsp;&nbsp;&nbsp;&nbsp;输出格式：<a href="<?php echo $phpSelf."?$style-english.shtml"?>" >English</a> ：<a href="<?php echo $phpSelf."?$style-cn.shtml"?>">中文</a>：<a href="<?php echo $phpSelf."?$style-image.shtml"?>">图型</a> </div>
</div>
<!--PHP基本特性 开始-->
<div class="title">...:::服务器特性:::...</div>
<!--PHP基本特性 结束-->
<div id="serverinfo"><div class="info1">服务器时间</div><div class="info2"><?php echo gmdate("Y年m月d日 h:i:s", time());?>&nbsp;(格林威治标准时间)&nbsp;&nbsp;<?php echo gmdate("Y年n月j日 H:i:s",time()+8*3600)?>&nbsp;(北京时间)</div>
<div class="info3">服务器域名</div><div class="info4"><?php echo("<a href=\"http://".$_SERVER["HTTP_HOST"]."\"  title=\"访问此域名\" target=\"_blank\">".$_SERVER["HTTP_HOST"]."</a>"); ?></div>
<div class="info3">服务器IP地址</div><div class="info4"><?php echo gethostbyname($_SERVER["HTTP_HOST"])?></div>
<div class="info3">服务器操作系统</div><div class="info4"><?php $os = explode(" ", php_uname()); echo $os[0]; echo "&nbsp;&nbsp;";
 if ($os[0] =="Windows") {echo "主机名称：".$os[2];} else {echo "内核版本：".$os[2];}?></div>
<!-- 仅在windows 环境中输出-->
<?php if(("show" !== $sysReShow) & ("0" != $_ENV["NUMBER_OF_PROCESSORS"])& ("" != $_ENV["NUMBER_OF_PROCESSORS"])){?>
<div class="info3">服务器处理器</div><div class="info4">CPU个数：<?php echo $_ENV["NUMBER_OF_PROCESSORS"]?> <?php  echo "&nbsp;&nbsp;".$_ENV["PROCESSOR_IDENTIFIER"]; echo "&nbsp;&nbsp;运行级别:".$_ENV["PROCESSOR_LEVEL"]."&nbsp;&nbsp;版本:".$_ENV["PROCESSOR_REVISION"];?></div>
<?php }?>
<!-- 仅在windows 环境中输出结束-->
<!-- linux or unix 参数输出-->
<?php if(("show" == $sysReShow)&("0" != $sysInfo['cpu']['num'])&("" != $sysInfo['cpu']['num'])){?>
<div class="info3">服务器处理器</div><div class="info4">CPU个数：<?php echo $sysInfo['cpu']['num']?> &nbsp;&nbsp;<?php $list = explode('<br />', $sysInfo['cpu']['detail']); echo $list[0]; ?></div>
<?php }?>
<?php if("show" == $sysReShow){?>
<div class="info3">内存使用状况</div><div class="info4">
<?php echo $sysInfo['memTotal']?>M, 已使用
<?php echo $sysInfo['memUsed']?>M, 空闲
<?php echo $sysInfo['memFree']?>M, 使用率
<?php echo $sysInfo['memPercent']?>%</div>
<div class="info3">SWAP区</div><div class="info4">
共<?php echo $sysInfo['swapTotal']?>M, 已使用
<?php echo $sysInfo['swapUsed']?>M, 空闲
<?php echo $sysInfo['swapFree']?>M, 使用率
<?php echo $sysInfo['swapPercent']?>%</div>
<div class="info3">系统平均负载</div><div class="info4"><?php echo $sysInfo['loadAvg']?></div>
<?php }?>
<!-- linux or unix 参数输出结束-->
<div class="info3">服务器运行时间</div><div class="info4"><?php if ("" != $sysInfo['uptime']){ echo $sysInfo['uptime'];} else  echo "暂不支持此系统"; ?> </div>
<div class="info3">服务器操作系统文字编码</div><div class="info4"><?php echo $_SERVER["HTTP_ACCEPT_LANGUAGE"]?></div>
<div class="info3">服务器解译引擎</div><div class="info4"><?php echo $_SERVER["SERVER_SOFTWARE"]?></div>
<div class="info3">Web服务端口</div><div class="info4"><?php echo $_SERVER["SERVER_PORT"]?></div>
<div class="info3">服务器管理员</div><div class="info4"><?php 
if (isset($_SERVER["SERVER_ADMIN"])) {
    echo "<a href=\"mailto:$_SERVER[SERVER_ADMIN]\" title=\"发送邮件\">$_SERVER[SERVER_ADMIN]</a>";
} else {
    echo "<a href=\"mailto:@get_cfg_var(sendmail_from)\" title=\"发送邮件\">@get_cfg_var(sendmail_from)</a>";
}?></div>
<div class="info3">本文件路径</div><div class="info4"><?php echo $_SERVER["SCRIPT_FILENAME"];?></div>
<div class="info3">服务端剩余空间</div><div class="info4"><?php echo intval(diskfreespace(".") / (1024 * 1024)).'Mb';?></div>
<div class="info3">系统当前用户名</div><div class="info4"><?php echo @get_current_user();?></div>
</div>
<!--tag1 -->
<a name="phpinfo"></a>
<!--PHP基本特性 开始-->
<div class="title">...:::PHP基本特性:::...</div>
<div id="phpinfo">
<div id="left">
<div class="info01">PHP版本</div><div class="info02"><?php echo PHP_VERSION;?></div>
<div class="info03">PHP运行方式</div><div class="info04"><?php /**strtoupper(php_sapi_name());*    */ echo ucwords(php_sapi_name());?></div>
<div class="info03">支持ZEND编译运行&nbsp;&nbsp;(<?php if($zend = "YES") {echo "版本:"; echo zend_version();}?>)</div><div class="info04"><?php echo $zend=(@get_cfg_var("zend_optimizer.optimization_level")||@get_cfg_var("zend_extension_manager.optimizer_ts")||@get_cfg_var("zend_extension_ts")) ?YES:NO?></div>
<div class="info03">运行于安全模式</div><div class="info04"><?php if(@get_cfg_var("safe_mode")){echo "是";} else echo"否"; ?></div>
<div class="info03">自动定义全局变量&nbsp;register_globals</div><div class="info04"><?php echo @get_cfg_var("register_globals")?"ON":"OFF"?></div>
<div class="info03">允许使用URL打开文件allow_url_fopen</div><div class="info04"><?php echo @get_cfg_var("allow_url_fopen")=="1"?YES:NO?></div>
<div class="info03">允许动态加载链接库enable_dl</div><div class="info04"><?php echo @get_cfg_var("enable_dl")=="1"?YES:NO?></div>
<div class="info03">显示错误信息&nbsp;display_errors</div><div class="info04"><?php echo @get_cfg_var("display_errors")=="1"?YES:NO?></div>
<div class="info03">短标记&lt;? ?&gt;支持</div><div class="info04"><?php echo @get_cfg_var("short_open_tag")?YES:NO?></div>
<div class="info03">标记&lt;% %&gt;支持</div><div class="info04"><?php echo @get_cfg_var("asp_tags")?YES:NO?></div>
<div class="info03">COOKIE支持</div><div class="info04"><?php echo @isset($HTTP_COOKIE_VARS)?YES:NO?></div>
<div class="info03">Session支持</div><div class="info04"><?php echo $MyCheck->isfun('session_start')?></div>
</div>
<div id="right">
<div class="info01">浮点运算有效数字显示位数</div><div class="info02"><?php echo @get_cfg_var("precision")?></div>
<div class="info03">强制y2k兼容</div><div class="info04"><?php echo @get_cfg_var("y2k_compliance")?YES:NO?></div>
<div class="info03">被禁用的函数disable_functions</div><div class="info04">
<?php $disused = @get_cfg_var("disable_functions")?"1":"0"; 
if($disused =="1") {
    echo '<a href="#" title="
  '.@get_cfg_var("disable_functions").'  
">'."More".'</a>';
} else {
    echo "None";
}?></div>
<div class="info03">程序最长运行时间max_execution_time</div><div class="info04"><?php if(@get_cfg_var("max_execution_time") !="0") {echo @get_cfg_var("max_execution_time")."秒";} else {echo "不限";}?></div>
<div class="info03">程序最多允许使用内存量 memory_limit</div><div class="info04"><?php echo @get_cfg_var("memory_limit")?></div>
<div class="info03">POST最大字节数&nbsp;post_max_size</div><div class="info04"><?php echo @get_cfg_var("post_max_size")?></div>
<div class="info03">允许最大上传文件&nbsp;upload_max_filesize</div><div class="info04"><?php echo @get_cfg_var("file_uploads")?@get_cfg_var("upload_max_filesize"):"Error";?></div>
<div class="info03">PHP信息 PHPINFO</div><div class="info04"><?php echo (false !== empty(@get_cfg_var("disable_functions")))?"NO":"<a href=\"{$phpSelf}?{$style}-{$icon}-phpinfo.shtml\" target=\"_blank\" class=\"static\" title=\" 点击查看 \">PHPINFO</a>"; ?></div>
<div class="info03">Html错误显示</div><div class="info04"><?php echo @get_cfg_var("html_errors")?YES:NO?></div>
<div class="info03">调试器地址/端口</div><div class="info04"><?php
$debugerhost=@get_cfg_var("debugger.host")?"YES":"NO";
if ($debugerhost == "YES") {
    echo @get_cfg_var("debugger.port");
} else {
    echo ($debugerhost == "NO")?NO:YES;
}?></div>
<div class="info03">SMTP支持</div><div class="info04"><?php echo @get_cfg_var("SMTP")?YES:NO?></div>
<div class="info03">SMTP地址</div><div class="info04"><?php echo @get_cfg_var("SMTP")?></div>
</div>
</div>
<a name="otherinfo"></a>
<!--PHP基本特性 结束-->
<div class="title">...:::组件支持状况:::...</div>
<div id="otherinfo">
<div id="left">
<div class="infoe01">组件名称</div><div class="infoe02">支持情况</div>
<div class="infoe03">拼写检查 ASpell Library</div><div class="infoe04"><?php echo $MyCheck->isfun('aspell_new')?></div>
<div class="infoe03">高精度数学运算 BCMath</div><div class="infoe04"><?php echo $MyCheck->isfun('bcadd')?></div>
<div class="infoe03">历法运算 Calendar</div><div class="infoe04"><?php echo $MyCheck->isfun('JDToFrench')?></div>
<div class="infoe03">图形处理 GD Library</div><div class="infoe04"><?php echo $MyCheck->isfun('imageline')?></div>
<div class="infoe03">类/对象支持</div><div class="infoe04"><?php echo $MyCheck->isfun('class_exists')?></div>
<div class="infoe03">字串类型检测支持</div><div class="infoe04"><?php echo $MyCheck->isfun('ctype_upper')?></div>
<div class="infoe03">iconv编码支持</div><div class="infoe04"><?php echo $MyCheck->isfun('iconv')?></div>
<div class="infoe03">MCrypt加密处理支持</div><div class="infoe04"><?php echo $MyCheck->isfun('mcrypt_cbc')?></div>
<div class="infoe03">哈稀计算 MHash</div><div class="infoe04"><?php echo $MyCheck->isfun('mhash')?></div>
<div class="infoe03">OpenSSL支持</div><div class="infoe04"><?php echo $MyCheck->isfun('openssl_open')?></div>
<div class="infoe03">PREL相容语法 PCRE</div><div class="infoe04"><?php echo $MyCheck->isfun('preg_match')?></div>
<div class="infoe03">正则扩展(兼容perl)支持</div><div class="infoe04"><?php echo $MyCheck->isfun('preg_match')?></div>
<div class="infoe03">Socket支持</div><div class="infoe04"><?php echo $MyCheck->isfun('fsockopen')?></div>
<div class="infoe03">流媒体支持</div><div class="infoe04"><?php echo $MyCheck->isfun('stream_context_create')?></div>
<div class="infoe03">Tokenizer支持</div><div class="infoe04"><?php echo $MyCheck->isfun('token_name')?></div>
<div class="infoe03">URL支持</div><div class="infoe04"><?php echo $MyCheck->isfun('parse_url')?></div>
<div class="infoe03">WDDX支持(Web Distributed Data Exchange)</div><div class="infoe04"><?php echo $MyCheck->isfun('wddx_add_vars')?></div>
<div class="infoe03">压缩文件支持(Zlib)</div><div class="infoe04"><?php echo $MyCheck->isfun('gzclose')?></div>
<div class="infoe03">XML解析</div><div class="infoe04"><?php echo $MyCheck->isfun('xml_set_object')?></div>
<div class="infoe03">FTP</div><div class="infoe04"><?php echo $MyCheck->isfun('ftp_login')?></div>
<div class="infoe03">MySQL数据库支持</div><div class="infoe04"><?php echo $MyCheck->isfun('mysql_close')?></div>
<div class="infoe03">MySQL数据库持续连接</div><div class="infoe04"><?php echo @get_cfg_var("mysql.allow_persistent")?YES:NO?></div>
<div class="infoe03">MySQL最大连接数</div><div class="infoe04"><?php echo @get_cfg_var("mysql.max_links") == "-1" ? "不限" : @get_cfg_var("mysql.max_links")?></div>
<div class="infoe03">ODBC数据库连接</div><div class="infoe04"><?php echo $MyCheck->isfun('odbc_close')?></div>
<div class="infoe03">SQL Server数据库支持</div><div class="infoe04"><?php echo $MyCheck->isfun('mssql_close')?></div>
<div class="infoe03">mSQL数据库支持</div><div class="infoe04"><?php echo $MyCheck->isfun('msql_close')?></div>
<div class="infoe03">Postgre SQL数据库支持</div><div class="infoe04"><?php echo $MyCheck->isfun('pg_close')?></div>
</div>

<div id="right">
<div class="infoe01">组件名称</div><div class="infoe02">支持情况</div>
<div class="infoe03">Oracle数据库支持</div><div class="infoe04"><?php echo $MyCheck->isfun('ora_close')?></div>
<div class="infoe03">Oracle 8 数据库支持</div><div class="infoe04"><?php echo $MyCheck->isfun('OCILogOff')?></div>
<div class="infoe03">dBase数据库支持</div><div class="infoe04"><?php echo $MyCheck->isfun('dbase_close')?></div>
<div class="infoe03">SyBase数据库支持</div><div class="infoe04"><?php echo $MyCheck->isfun('sybase_close')?></div>
<div class="infoe03">DBA数据库支持</div><div class="infoe04"><?php echo $MyCheck->isfun('dba_close')?></div>
<div class="infoe03">DBM数据库支持</div><div class="infoe04"><?php echo $MyCheck->isfun('dbmclose')?></div>
<div class="infoe03">DBX数据库支持</div><div class="infoe04"><?php echo $MyCheck->isfun('dbx_close')?></div>
<div class="infoe03">DB++数据库支持</div><div class="infoe04"><?php echo $MyCheck->isfun('dbplus_close')?></div>
<div class="infoe03">FrontBase数据库支持</div><div class="infoe04"><?php echo $MyCheck->isfun('fbsql_close')?></div>
<div class="infoe03">FilePro数据库支持</div><div class="infoe04"><?php echo $MyCheck->isfun('filepro')?></div>
<div class="infoe03">Informix数据库支持</div><div class="infoe04"><?php echo $MyCheck->isfun('ifx_close')?></div>
<div class="infoe03">Lotus Notes数据库支持</div><div class="infoe04"><?php echo $MyCheck->isfun('notes_version')?></div>
<div class="infoe03">InterBase数据库支持</div><div class="infoe04"><?php echo $MyCheck->isfun('ibase_close')?></div>
<div class="infoe03">ingres数据库支持</div><div class="infoe04"><?php echo $MyCheck->isfun('ingres_close')?></div>
<div class="infoe03">Hyperwave数据库支持</div><div class="infoe04"><?php echo $MyCheck->isfun('hw_close')?></div>
<div class="infoe03">Ovrimos SQL数据库连接支持</div><div class="infoe04"><?php echo $MyCheck->isfun('ovrimos_close')?></div>
<div class="infoe03">SESAM数据库连接支持</div><div class="infoe04"><?php echo $MyCheck->isfun('sesam_disconnect')?></div>
<div class="infoe03">SQLite数据库连接支持</div><div class="infoe04"><?php echo $MyCheck->isfun('sqlite_close')?></div>
<div class="infoe03">Adabas D数据库连接支持</div><div class="infoe04"><?php echo $MyCheck->isfun('ada_close')?></div>
<div class="infoe03">目录存取协议(LDAP)支持</div><div class="infoe04"><?php echo $MyCheck->isfun('ldap_close')?></div>
<div class="infoe03">Yellow Page系统支持</div><div class="infoe04"><?php echo $MyCheck->isfun('yp_match')?></div>
<div class="infoe03">PHP和JAVA综合支持</div><div class="infoe04"><?php echo $MyCheck->isfun('java_last_exception_get')?></div>
<div class="infoe03">IMAP电子邮件系统支持</div><div class="infoe04"><?php echo $MyCheck->isfun('imap_close')?></div>
<div class="infoe03">SNMP网络管理协议支持</div><div class="infoe04"><?php echo $MyCheck->isfun('snmpget')?></div>
<div class="infoe03">VMailMgr邮件处理支持</div><div class="infoe04"><?php echo $MyCheck->isfun('vm_adduser')?></div>
<div class="infoe03">PDF文档支持</div><div class="infoe04"><?php echo $MyCheck->isfun('pdf_close')?></div>
<div class="infoe03">FDF表单资料格式支持</div><div class="infoe04"><?php echo $MyCheck->isfun('FDF_close')?></div>
</div>
</div>
<a name="testinfo"></a>
<form method="post" action="<?php echo PHPSELF."?$style-$icon.shtml#testinfo"?>"  id="main_form">
<div class="title">...:::服务器性能检测:::...</div>
<div id="testinfo">
<div class="test01">检测对象</div>
<div class="test02">整数运算能力测试<br />
(1+1运算300万次)</div>
<div class="test03">浮点运算能力测试<br />
(开平方300万次)</div>
<div class="test04">数据I/O能力测试<br />
(读取10K文件10000次)</div>
<div class="test05">Uenu.com  (Xeon(TM) 2.00GHz*4+4G+CentOS 5.2)</div>
<div class="test06">0.068秒</div>
<div class="test07">0.086秒</div>
<div class="test08">小于0.100秒</div>
<div class="test05">sakura.ad.jp (Xeon(TM) 2.80GHz+2G+FreeBSD 4.0)</div>
<div class="test06">0.501秒</div>
<div class="test07">0.694秒</div>
<div class="test08">小于0.100秒</div>
<div class="test05">Shvip的办公电脑 (PD 2.8GHz*2 +2G+FreeBSD 7.0)</div>
<div class="test06">0.425秒</div>
<div class="test07">0.417 秒</div>
<div class="test08">小于0.100秒</div>
<div class="test05">测试当前服务器的性能</div>
<div class="test06"><?php echo $valInt?></div><div class="test07"><?php echo $valFloat?></div><div class="test08"><?php echo $valIo?></div>
<div class="test05"></div>
<div class="test06"><input type="submit" value="TEST_1" class="myButton"  name="Action" /></div><div class="test07">
<input type="submit" value="TEST_2" class="myButton"  name="Action" /></div><div class="test08"><input type="submit" value="TEST_3" class="myButton"  name="Action" /></div>
</div>
<input type="hidden" name="pInt" value="<?php echo $valInt?>" />
<input type="hidden" name="pFloat" value="<?php echo $valFloat?>" />
<input type="hidden" name="pIo" value="<?php echo $valIo?>" />
<?php
$isMysql = (false !== $MyCheck->isfun("mysql_query"))?"":" disabled";
$isMail = (false !== $MyCheck->isfun("mail"))?"":" disabled";
?>
<a name="customcheckinfo"></a>
<div class="title">...:::服务器自定义检测:::...</div>
<div id="checkinfo">
<div class="check00">MYSQL连接测试</div>
<div id="check01">MYSQL服务器</div><div id="check02"> <input name="mysqlHost" type="text" id="mysqlHost" value="localhost" /></div><div id="check03">MYSQL用户名 </div><div id="check04"><input name="mysqlUser" type="text" id="mysqlUser" value="" /></div><div id="check05"></div>
<div id="check06">MYSQL用户密码 </div><div id="check07"><input type="password" name="mysqlPassword" <?php echo $isMysql?> /></div><div id="check08">MYSQL数据库名称 </div><div id="check09"><input type="text" name="mysqlDb" /></div><div id="check10"><input type="submit" class="myButton" value="CONNECT" <?php echo $isMysql?>  name="Action" /></div>
<div class="check00"><?php if("show" == $mysqlReShow){echo $mysqlRe;}?> </div>
<div class="check00">MAIL邮件发送测试</div>
<div id="check11">收信地址</div>
<div id="check12"><input type="text" name="mailReceiver" size="50" <?php echo $isMail?> /></div><div id="check13"><input type="submit" class="myButton" value="SENDMAIL" <?php echo $isMail?>  name="Action" /></div>
<div class="check00"><?php if("show" == $mailReShow){echo $mailRe;}?> </div>
<div class="check00">函数支持状况</div>
<div id="check11">函数名称</div>
<div id="check12"><input type="text" name="funName" size="50" /></div><div id="check13"><input type="submit" class="myButton" value="FUNCTION_CHECK" name="Action" /></div>
<div class="check00"><?php if("show" == $funReShow){echo $funRe;}?> </div>
<div class="check00">PHP配置参数状况</div>
<div id="check11">参数名称</div>
<div id="check12"><input type="text" name="opName" size="40" /></div><div id="check13"><input type="submit" class="myButton" value="CONFIGURATION_CHECK" name="Action" /></div>
<div class="check00"><?php if("show" == $opReShow){echo $opRe;}?> </div>
</div>
</form>
<a name="bottom"></a>
<a name="aboutus"></a>
<div id="aboutinfo">
<div id="declare">关于UenuProbe PHP探针程序声明：</div>
<div id="gnu"><b>This PHP probe programe is designed based on the new structure Div + Css , the free open source software, powerful, clear structure and easy to use. </b><br />
1. To support Windows, linux, Unix, BSD, Sun Solar System.<br />
2. Support for IE8, IE7, IE6, Firefox, Google chrome, and other browsers. <br />
3. Support for PHP 4, PHP 5.2, PHP 5.3+. <br /><br />

<b>The main use and application of the object: </b><br />
1. Be familiar with the PHP programming of amateur and professional developers.(Ver2.09.06 extended simple url rewrite) <br />
2. The managers to configure Linux（Windows）+Apache(Nginx、Lighttpd)+PHP+MySQL+Zend system environment, be sure of the successful detection system configuration. <br />
3. For the company's customers to buy Virtual host using testing server performance.<br /><br /><b>本程序基于Div+Css 新型架构PHP探针，免费开源的自由软件，功能强大，结构清晰，使用方便。</b><br />
1.支持Windows, linux, Unix, BSD, Sun Solar系统<br />
2.支持IE8，IE7，IE6，Firefox,Google chrome等浏览器。<br />
3.支持PHP 4、PHP 5.2、PHP 5.3+。<br />
 <br />
<b>主要用途及适用对象：</b><br />
1.熟悉PHP编程的业余爱好者及专业开发人员。(OOP编程，PHP5以上支持, V2.09.06增加了创新型的伪静态支持)<br />
2.机房管理人员配置Linux（Windows）+Apache(Nginx、Lighttpd)+PHP+MySQL+Zend系统环境，检测系统是否配置成功。<br />
3.对于购买虚拟主机的用户，用于测试服务器性能。<br /></div>
<div id="top"><a href="<?php echo $phpSelf."?$style-$icon.shtml#top"?>" title="返回顶部">顶部↑</a></div>
<div id="hr"> <hr color="#999999" size="1" /></div>
<div id="about">
  <div id="us">程序设计： <a href="<?php echo $UenuCom['Contact'];?>" title="发送邮件">田慧民</a>&nbsp;&nbsp;<a href="<?php echo $UenuCom['HTTP'];?>" title="访问本站" target="_blank">Shvip</a> <br> 
技术支持： <a href="<?php echo $UenuCom['HTTP'];?>" title="访问本站" target="_blank">Tech.uenu.com</a><br> 
※基于Div+Css 架构PHP探针 <?php echo $UenuCom['Version']?>
</div>
</div>
</div>
<!--total 结束-->
</div>
</body>
</html>
