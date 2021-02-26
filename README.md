# tbkzjy-nodb
免数据库淘宝客中间页，无后台，直接传入淘口令即可快速转链和解析商品信息。因为本套源码是请求大淘客API的，直接就通过淘口令解析获取商品信息，转链等。

# 大淘客API申请
http://www.dataoke.com/

# 配置
编辑index.php，修改相关配置即可
```
$appKey = "xxxxxxxxx";  // 填写你的大淘客APP_KEY
$key = "xxxxxxxxx"; // 填写你的大淘客APP_SECRET
```
# 如何使用
（1）上传代码到服务器<br/>
（2）编辑index.php，修改大淘客相关配置<br/>
（3）中间页网址格式如下<br/>
```
http://域名/目录/?tkl=去掉符号的淘口令
```

# 什么是去掉符号的淘口令

由于￥GZRtcyccpxF￥这种格式的淘口令在微信无法点击链接，所以在url后面需要去掉￥￥，即你?tkl=GZRtcyccpxF

最终的中间页链接是
```
http://域名/目录/?tkl=GZRtcyccpxF
```

例如你的域名是www.baidu.com，代码放在根目录下的tkl目录，那么你的中间页是<br/>
```
http://www.baidu.com/tkl/?tkl=GZRtcyccpxF
```

# 开发者信息
```
开源作者：TANKING
开源平台：open.likeyun.cn
如有遇到问题，请加入微信群
微信群进群地址：http://pic.iask.cn/fimg/591377922798.jpg
```
