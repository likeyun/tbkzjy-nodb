<!DOCTYPE html>
<html>
	<head>
		<title>粉丝福利购</title>
		<link rel="stylesheet" type="text/css" href="./css/style.css">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0,viewport-fit=cover">
	</head>
<?php
// 开源作者：TANKING
// 开源平台：open.likeyun.cn
// 如有遇到问题，请加入微信群
// 微信群进群地址：http://pic.iask.cn/fimg/591377922798.jpg
header("Content-type:text/html;charset=utf-8");

// 获得淘口令
$tkl = trim($_GET["tkl"]);
// 给淘口令增加符号
$tkl_fuhao = "￥".$tkl."￥";
// 为了防止解析出错，要对淘口令进行URL编码
$tkl_urlencode = urlencode($tkl_fuhao);

// 构建必要的请求参数
$appKey = "xxxxxxxxx";  // 填写你的大淘客APP_KEY
$key = "xxxxxxxxx"; // 填写你的大淘客APP_SECRET
$nonce = rand(100000,999999);

// 获得毫秒时间戳
$haomiao_time = microtime(true)*1000;

// 开始请求API
// 获得长标题、商品主图、原价、券价格
$ch = curl_init();

// 生成大写的签名
$sign = strtoupper(md5("appKey=".$appKey."&timer=".$haomiao_time."&nonce=".$nonce."&key=".$key));

// 发起请求
$jsondata = file_get_contents('http://openapi.dataoke.com/api/tb-service/parse-content?appKey='.$appKey.'&signRan='.$sign.'&version=v1.0.0&nonce='.$nonce.'&timer='.$haomiao_time.'&content='.$tkl_urlencode);

// 转换淘口令API
// 把别人的淘口令转换成你自己PID下的淘口令
$get_tkl = file_get_contents('http://openapi.dataoke.com/api/tb-service/twd-to-twd?appKey='.$appKey.'&signRan='.$sign.'&version=v1.0.0&nonce='.$nonce.'&timer='.$haomiao_time.'&content='.$tkl_urlencode);

// 解析数据
$goosinfo = json_decode($jsondata, true);
$image = $goosinfo["data"]["originInfo"]["image"]; // 商品主图
$price = $goosinfo["data"]["originInfo"]["price"]; // 商品原价
$title = $goosinfo["data"]["originInfo"]["title"]; // 商品标题
$actualPrice = $goosinfo["data"]["originInfo"]["actualPrice"]; // 券后价格

// 在长标题中截取短标题
$short_title = mb_substr($title,0,13,'utf-8');// 短标题

// 因为API解析出来的价格不够好看，这边做一下格式化
if(strpos($yprice,'.') !==false){
	// 判断小数点后有几位数
	$xiaoshudian_weishu = strlen(floor($price));
	if ($xiaoshudian_weishu == 2) {
		// 如果是两位数，就不用进行处理
		$yuanjia = $price;
	}else{
		// 否则需要加一个0
		$yuanjia = $price."0666";
	}
}else{
	// 不包含小数点，就要在最后面加.00
	$yuanjia = $price.".00";
}

// 原价格式化
if(strpos($price,'.') !==false){
	// 包含小数点，需要判断小数点后面的情况
	// 获取小数点后最后一位数
	$last_num = substr($price,-2,-1);
	if ($last_num == '.') {
		// 说明仅有一个小数
		if (substr($price,-1) !== 0) {
			// 如果不是0那就直接加一个0
			$yuanjia = $price."0";
		}else{
			$yuanjia = $price;
		}
	}else if ($last_num !== '.') {
		// 说明不止一个小数，保持原来的
		$yuanjia = $price;
	}
}else{
	// 不包含小数点，就要在最后面加.00
	$yuanjia = $price.".00";
}


// 券后价格式化
if(strpos($actualPrice,'.') !==false){
	// 包含小数点，需要判断小数点后面的情况
	// 获取小数点后最后一位数
	$last_num = substr($actualPrice,-2,-1);
	if ($last_num == '.') {
		// 说明仅有一个小数
		if (substr($actualPrice,-1) !== 0) {
			// 如果不是0那就直接加一个0
			$quanhoujia = $actualPrice."0";
		}else{
			$quanhoujia = $actualPrice;
		}
	}else if ($last_num !== '.') {
		// 说明不止一个小数，保持原来的
		$quanhoujia = $actualPrice;
	}
}else{
	// 不包含小数点，就要在最后面加.00
	$quanhoujia = $actualPrice.".00";
}

// 把淘口令解析出来
$tklinfo = json_decode($get_tkl, true);
$tklstr = $tklinfo["data"]["tpwd"]; // 转换后的淘口令
?>

<body>
<!-- 顶部 -->
<div id="top"><?php echo $short_title; ?></div>

<!-- 主图 -->
<div id="zhutu">
	<img src="<?php echo $image; ?>"/>
</div>

<!-- 产品标题 -->
<div id="title_con">
<span class="by">包邮</span>
<span class="title"><?php echo $title; ?></span>
</div>

<!-- 价格信息 -->
<div id="title_con">
<span class="qhj">券后价</span>
<span class="tofee">¥<?php echo $quanhoujia; ?> <span class="yj">原价 <s> ¥<?php echo $yuanjia; ?></s></span></span>
</div>

<!-- tkl -->
<div id="tkl">
<div class="kl" id="tkl_text"><?php echo $tklstr; ?></div>
<div class="copy" id="copy">立即复制</div>
</div>

<!-- 领取方法 -->
<p style="color: #999;text-align: center;font-size: 15px;">复制淘口令 -> 打开手机淘宝APP即可</p>

<!-- 复制成功提示 -->
<div id="copytips"><p class="success"><br/>复制成功<br/>打开淘宝APP</p></div>

</body>
<script src="./js/jquery.min.js"></script>
<script type="text/javascript">
	function hide(){
		$("#copytips .success").css("display","none");
	}

	function copyArticle(event){
	  const range = document.createRange();
	  range.selectNode(document.getElementById('tkl_text'));
	  const selection = window.getSelection();
	  if(selection.rangeCount > 0) selection.removeAllRanges();
	  selection.addRange(range);
	  document.execCommand('copy');
	  // alert("复制成功");
	  $("#copytips .success").css("display","block");
	  $("#tkl .copy").text("已复制");
	  setTimeout('hide()', 2000);
	  
	}

	window.onload = function () {
	  var obt = document.getElementById("copy");
	  obt.addEventListener('click', copyArticle, false);
	}
</script>
</html>