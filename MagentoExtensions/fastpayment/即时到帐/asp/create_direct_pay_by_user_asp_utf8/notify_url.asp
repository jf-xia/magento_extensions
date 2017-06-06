<%@LANGUAGE="VBSCRIPT" CODEPAGE="65001"%>
<%
' 功能：支付宝服务器异步通知页面
' 版本：3.2
' 日期：2011-03-31
' 说明：
' 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
' 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
	
' //////////////页面功能说明//////////////
' 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
' 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
' 该页面调试工具请使用写文本函数LogResult，该函数已被默认开启，见alipay_notify.asp中的函数VerifyNotify
' 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知

' TRADE_FINISHED(表示交易已经成功结束，为普通即时到帐的交易状态成功标识);
' TRADE_SUCCESS(表示交易已经成功结束，为高级即时到帐的交易状态成功标识);
'////////////////////////////////////////
%>

<!--#include file="class/alipay_notify.asp"-->

<%
'计算得出通知验证结果
Set objNotify = New AlipayNotify
sVerifyResult = objNotify.VerifyNotify()

if sVerifyResult then	'验证成功
	'*********************************************************************
	'请在这里加上商户的业务逻辑程序代码
	
	'——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    '获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
    out_trade_no	= Request.Form("out_trade_no")	'获取订单号
    trade_no		= Request.Form("trade_no")		'获取支付宝交易号
    total_fee		= Request.Form("price")			'获取总金额
	
	If Request.Form("trade_status") = "TRADE_FINISHED"  or Request.Form("trade_status") = "TRADE_SUCCESS" Then	
		'判断该笔订单是否在商户网站中已经做过处理（可参考“集成教程”中“3.4返回数据处理”）
			'如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			'如果有做过处理，不执行商户的业务程序
		
		Response.Write "success"	'请不要修改或删除
		
		'调试用，写文本函数记录程序运行情况是否正常
        'LogResult("这里写入想要调试的代码变量值，或其他运行的结果记录")
	Else
		'其他状态判断。
		
		Response.Write "success"
		'调试用，写文本函数记录程序运行情况是否正常
		'LogResult ("这里写入想要调试的代码变量值，或其他运行的结果记录")
	End If
		
	'——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	
	'*********************************************************************
else '验证失败
    response.Write "fail"
	'调试用，写文本函数记录程序运行情况是否正常
	'LogResult("这里写入想要调试的代码变量值，或其他运行的结果记录")
end if
%>