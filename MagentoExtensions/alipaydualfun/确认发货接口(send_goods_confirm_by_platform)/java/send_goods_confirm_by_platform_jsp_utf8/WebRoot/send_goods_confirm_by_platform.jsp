<%
/* *
 *功能：确认发货接口接入页
 *版本：3.2
 *日期：2011-03-17
 *说明：
 *以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 *该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************注意*****************
 *如果您在接口集成过程中遇到问题，可以按照下面的途径来解决
 *1、商户服务中心（https://b.alipay.com/support/helperApply.htm?action=consultationApply），提交申请集成协助，我们会有专业的技术工程师主动联系您协助解决
 *2、商户帮助中心（http://help.alipay.com/support/232511-16307/0-16307.htm?sh=Y&info_type=9）
 *3、支付宝论坛（http://club.alipay.com/read-htm-tid-8681712.html）
 *如果不想使用扩展功能请把扩展功能参数赋空值。
 **********************************************
 */
%>
<%@ page language="java" contentType="text/html; charset=utf-8"
	pageEncoding="utf-8"%>
<%@ page import="com.alipay.services.*"%>
<%@ page import="com.alipay.util.*"%>
<%@ page import="java.util.HashMap"%>
<%@ page import="java.util.Map"%>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>确认发货接口</title>
	</head>
	<%
    
		////////////////////////////////////请求参数//////////////////////////////////////
		
		//必填参数//
		//支付宝交易号
		String trade_no = request.getParameter("trade_no");
		
		//物流公司名称
		String logistics_name = request.getParameter("logistics_name");
		
		//发货时的运输类型
		String transport_type = "EXPRESS";
		
		//选填参数
		
        //物流发货单号
		String invoice_no = request.getParameter("invoice_no");
		//////////////////////////////////////////////////////////////////////////////////
		
		//把请求参数打包成数组
		Map<String, String> sParaTemp = new HashMap<String, String>();
        sParaTemp.put("trade_no", trade_no);
        sParaTemp.put("logistics_name", logistics_name);
		sParaTemp.put("invoice_no", invoice_no);
		sParaTemp.put("transport_type", transport_type);
		//构造函数，生成请求URL  
		String sHtmlText = AlipayService.send_goods_confirm_by_platform(sParaTemp);

		//请在这里加上商户的业务逻辑程序代码

		//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

		//获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

		out.println(sHtmlText);

        	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	%>
	<body>
	</body>
</html>
