using System;
using System.Data;
using System.Configuration;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;
using System.Web.UI.HtmlControls;
using System.Collections.Generic;
using Com.Alipay;

/// <summary>
/// 功能：标准双接口接入页
/// 版本：3.2
/// 日期：2011-03-11
/// 说明：
/// 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
/// 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
/// 
/// /////////////////注意///////////////////////////////////////////////////////////////
/// 如果您在接口集成过程中遇到问题，可以按照下面的途径来解决
/// 1、商户服务中心（https://b.alipay.com/support/helperApply.htm?action=consultationApply），提交申请集成协助，我们会有专业的技术工程师主动联系您协助解决
/// 2、商户帮助中心（http://help.alipay.com/support/232511-16307/0-16307.htm?sh=Y&info_type=9）
/// 3、支付宝论坛（http://club.alipay.com/read-htm-tid-8681712.html）
/// 
/// 如果不想使用扩展功能请把扩展功能参数赋空值。
/// 总金额计算方式是：总金额=price*quantity+logistics_fee+discount。
/// 建议把price看作为总金额，是物流运费、折扣、购物车中购买商品总额等计算后的最终订单的应付总额。
/// 建议物流参数只使用一组，根据买家在商户网站中下单时选择的物流类型（快递、平邮、EMS），程序自动识别logistics_type被赋予三个中的一个值
/// 各家快递公司都属于EXPRESS（快递）的范畴
/// </summary>
public partial class _Default : System.Web.UI.Page 
{
    protected void Page_Load(object sender, EventArgs e)
    {
    }

    protected void BtnAlipay_Click(object sender, EventArgs e)
    {

        ////////////////////////////////////////////请求参数////////////////////////////////////////////

        //必填参数//

        string out_trade_no = DateTime.Now.ToString("yyyyMMddHHmmss");  //请与贵网站订单系统中的唯一订单号匹配
        string subject = Request.Form["TxtSubject"];                    //订单名称，显示在支付宝收银台里的“商品名称”里，显示在支付宝的交易管理的“商品名称”的列表里。
        string body = Request.Form["TxtBody"];                          //订单描述、订单详细、订单备注，显示在支付宝收银台里的“商品描述”里
        string price = Request.Form["TxtTotal_fee"];    		        //订单总金额，显示在支付宝收银台里的“商品单价”里

        string logistics_fee = "0.00";                  				//物流费用，即运费。
        string logistics_type = "EXPRESS";				                //物流类型，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
        string logistics_payment = "SELLER_PAY";            			//物流支付方式，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）

        string quantity = "1";              							//商品数量，建议默认为1，不改变值，把一次交易看成是一次下订单而非购买一件商品。

        //选填参数//

        //买家收货信息（推荐作为必填）
        //该功能作用在于买家已经在商户网站的下单流程中填过一次收货信息，而不需要买家在支付宝的付款流程中再次填写收货信息。
        //若要使用该功能，请至少保证receive_name、receive_address有值
        //收货信息格式请严格按照姓名、地址、邮编、电话、手机的格式填写
        string receive_name = "收货人姓名";			                    //收货人姓名，如：张三
        string receive_address = "收货人地址";			                //收货人地址，如：XX省XXX市XXX区XXX路XXX小区XXX栋XXX单元XXX号
        string receive_zip = "123456";                  			    //收货人邮编，如：123456
        string receive_phone = "0571-81234567";                		    //收货人电话号码，如：0571-81234567
        string receive_mobile = "13312341234";               		    //收货人手机号码，如：13312341234

        //网站商品的展示地址，不允许加?id=123这类自定义参数
        string show_url = "http://www.xxx.com/myorder.aspx";

        ////////////////////////////////////////////////////////////////////////////////////////////////

        //把请求参数打包成数组
        SortedDictionary<string, string> sParaTemp = new SortedDictionary<string, string>();
        sParaTemp.Add("body", body);
        sParaTemp.Add("logistics_fee", logistics_fee);
        sParaTemp.Add("logistics_payment", logistics_payment);
        sParaTemp.Add("logistics_type", logistics_type);
        sParaTemp.Add("out_trade_no", out_trade_no);
        sParaTemp.Add("payment_type", "1");
        sParaTemp.Add("price", price);
        sParaTemp.Add("quantity", quantity);
        sParaTemp.Add("receive_address", receive_address);
        sParaTemp.Add("receive_mobile", receive_mobile);
        sParaTemp.Add("receive_name", receive_name);
        sParaTemp.Add("receive_phone", receive_phone);
        sParaTemp.Add("receive_zip", receive_zip);
        sParaTemp.Add("show_url", show_url);
        sParaTemp.Add("subject", subject);

        //构造标准双接口表单提交HTML数据，无需修改
        Service ali = new Service();
        string sHtmlText = ali.Trade_create_by_buyer(sParaTemp);
        Response.Write(sHtmlText);
    }
}
