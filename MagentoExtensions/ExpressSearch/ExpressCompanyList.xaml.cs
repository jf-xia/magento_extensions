using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using Windows.Foundation;
using Windows.Foundation.Collections;
using Windows.UI.Xaml;
using Windows.UI.Xaml.Controls;
using Windows.UI.Xaml.Controls.Primitives;
using Windows.UI.Xaml.Data;
using Windows.UI.Xaml.Input;
using Windows.UI.Xaml.Media;
using Windows.UI.Xaml.Navigation;

// “分组项页”项模板在 http://go.microsoft.com/fwlink/?LinkId=234231 上提供
/**
 * 快递查询
 * 
 * @author      xiayang
 * @email       c61811@163.com
 * @sina微博    www.weibo.com/xy26
 * @date        2012-09-25
 * @company     eoe.cn
 **/

namespace ExpressSearch
{
    /// <summary>
    /// 显示分组的项集合的页。
    /// </summary>
    public sealed partial class ExpressCompanyList : ExpressSearch.Common.LayoutAwarePage
    {
        public ExpressCompanyList()
        {
            this.InitializeComponent();
        }

        /// <summary>
        /// 使用在导航过程中传递的内容填充页。在从以前的会话
        /// 重新创建页时，也会提供任何已保存状态。
        /// </summary>
        /// <param name="navigationParameter">最初请求此页时传递给
        /// <see cref="Frame.Navigate(Type, Object)"/> 的参数值。
        /// </param>
        /// <param name="pageState">此页在以前会话期间保留的状态
        /// 字典。首次访问页面时为 null。</param>
        protected override void LoadState(Object navigationParameter, Dictionary<String, Object> pageState)
        {
            // TODO: 将可绑定组集合分配到 this.DefaultViewModel["Groups"]
            SampleDataSource.addCommonExpress();
            SampleDataSource.LoadFile();
            var sampleDataGroups = SampleDataSource.GetGroups((String)navigationParameter);
            this.DefaultViewModel["Groups"] = sampleDataGroups;
        }

        //选中了快递公司之后，返回到首页，并且把快递公司的英文值赋值给input输入框中
        private void itemGridView_ItemClick_1(object sender, ItemClickEventArgs e)
        {
            string en = ((SampleDataItem)e.ClickedItem).ExpressEn;

            this.Frame.Navigate(typeof(MainPage), en);
        }
    }
}
