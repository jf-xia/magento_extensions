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
using Windows.Web.Syndication;

// “空白页”项模板在 http://go.microsoft.com/fwlink/?LinkId=234238 上有介绍
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
    /// 可用于自身或导航至 Frame 内部的空白页。
    /// </summary>
    public sealed partial class MainPage : Page
    {
        public MainPage()
        {
            this.InitializeComponent();
        }

        /// <summary>
        /// 在此页将要在 Frame 中显示时进行调用。
        /// </summary>
        /// <param name="e">描述如何访问此页的事件数据。Parameter
        /// 属性通常用于配置页。</param>
        protected override void OnNavigatedTo(NavigationEventArgs e)
        {
            Object obj = e.Parameter;
            if (!obj.ToString().Equals(""))
            {
                expressCompany.Text = obj.ToString();
            }
            //获取历史查询数据
            FeedDataSource dataSource = new FeedDataSource();
            dataSource.GetHSData();
            searchHistory.ItemsSource = dataSource.getHSItem();
        }

        private async void searchExpress(object sender, RoutedEventArgs e)
        {
            String com = expressCompany.Text.Trim();
            String num = orderNum.Text.Trim();
            if (com == null || com.Equals(""))
            {
                Windows.UI.Popups.MessageDialog dlg = new Windows.UI.Popups.MessageDialog("请填写快递公司");
                await dlg.ShowAsync();
                return;
            }
            if (num == null || num.Equals(""))
            {
                Windows.UI.Popups.MessageDialog dlg = new Windows.UI.Popups.MessageDialog("请填写快递单号");
                await dlg.ShowAsync();
                return;
            }
            Loading.Text = "正在加载。。。";

            //异步获取快递查询结果
            FeedDataSource dataSource = new FeedDataSource();
            await dataSource.GetFeedsAsync(com, num);
            List<FeedItem> item = dataSource.getItem();
            Loading.Text = "";
            if (item == null)
            {
                Windows.UI.Popups.MessageDialog dlg = new Windows.UI.Popups.MessageDialog("不能查询到您的快递信息");
                await dlg.ShowAsync();
                searchHistory.ItemsSource = dataSource.getHSItem();
                return;
            }
            else
            {
                listSearch.ItemsSource = dataSource.getItem();
                searchHistory.ItemsSource = dataSource.getHSItem();
            }
        }

        //跳转到快递公司页面
        private void selectExpressCompany(object sender, RoutedEventArgs e)
        {
            this.Frame.Navigate(typeof(ExpressCompanyList));
        }

        //清除历史查询记录
        private void Button_Click_1(object sender, RoutedEventArgs e)
        {
            var localSettings = Windows.Storage.ApplicationData.Current.LocalSettings;
            if (localSettings.Values.ContainsKey("searchHistory"))
            {
                localSettings.Values["searchHistory"] = "";
            }
            FeedDataSource dataSource = new FeedDataSource();
            dataSource.GetHSData();
            searchHistory.ItemsSource = dataSource.getHSItem();
        }

        //选中历史查询数据将赋值到查询input输入框中
        private void searchHistory_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            var selected = searchHistory.SelectedValue;
            if (selected != null)
            {
                var companyEn = ((HistorySearchItem)selected).CompanyEn;
                var number = ((HistorySearchItem)selected).Number;
                expressCompany.Text = companyEn;
                orderNum.Text = number;
            }
        }

        
       
    }
}
