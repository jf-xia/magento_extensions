<%
' 功能：快捷登录接口接入页
' 版本：3.2
' 日期：2011-03-31
' 说明：
' 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
' 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
	
' /////////////////注意/////////////////
' 如果您在接口集成过程中遇到问题，可以按照下面的途径来解决
' 1、商户服务中心（https://b.alipay.com/support/helperApply.htm?action=consultationApply），提交申请集成协助，我们会有专业的技术工程师主动联系您协助解决
' 2、商户帮助中心（http://help.alipay.com/support/232511-16307/0-16307.htm?sh=Y&info_type=9）
' 3、支付宝论坛（http://club.alipay.com/read-htm-tid-8681712.html）
' /////////////////////////////////////

%>

<!--#include file="class/alipay_service.asp"-->

<%
'/////////////////////请求参数/////////////////////

'//选填参数//

'//防钓鱼
'获取客户端的IP地址，建议：编写获取客户端IP地址的程序
exter_invoke_ip   = ""
'防钓鱼时间戳
anti_phishing_key = ""
'注意：
'请慎重选择是否开启防钓鱼功能
'exter_invoke_ip、anti_phishing_key一旦被设置过，那么它们就会成为必填参数
'若要使用防钓鱼功能，建议使用POST方式请求数据
'示例：
'exter_invoke_ip = "202.1.1.1"
'Set objQuery_timestamp = New AlipayService
'anti_phishing_key = objQuery_timestamp.Query_timestamp()		'获取防钓鱼时间戳函数

'/////////////////////请求参数/////////////////////

'构造请求参数数组
sParaTemp = Array("service=alipay.auth.authorize","target_service=user.auth.quick.login","partner="&partner,"return_url="&return_url,"_input_charset="&input_charset,"anti_phishing_key="&anti_phishing_key,"exter_invoke_ip="&exter_invoke_ip)

'构造快捷登录接口表单提交HTML数据，无需修改
Set objService = New AlipayService
sHtml = objService.Alipay_auth_authorize(sParaTemp)
response.Write sHtml
%>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>支付宝快捷登录接口</title>
</head>
<body>
</body>
</html>
