<%
	/* *
	 *功能：确认发货接口调试入口页面
	 *版本：3.2
	 *日期：2011-03-17
	 *说明：
	 *以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
	 */
%>
<%@ page language="java" contentType="text/html; charset=gbk"
	pageEncoding="gbk"%>
<%@ page import="com.alipay.services.*"%>
<%@ page import="com.alipay.util.*"%>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=gbk">
		<title>确认发货接口</title>
	</head>
	<BODY>
		<FORM name=alisubmit action=send_goods_confirm_by_platform.jsp method=post target="_blank">
			<div style="text-align: center; font-size: 9pt; font-family: 宋体">
			  支付宝交易号：<INPUT type="text" size="30" name="trade_no" value=""><br />
			 物流公司名称：<INPUT type="text" size="30" name="logistics_name" value=""><br />
              物流发货单号：<INPUT type="text" size="30" name="invoice_no" value=""><br />
                <INPUT type="submit" value="确认发货"  name="btnAlipay">
			</div>
		</FORM>
	</BODY>
</html>