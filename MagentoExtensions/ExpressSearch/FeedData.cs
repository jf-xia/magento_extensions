//*********************************************************
//
// Copyright (c) Microsoft. All rights reserved.
// THIS CODE IS PROVIDED *AS IS* WITHOUT WARRANTY OF
// ANY KIND, EITHER EXPRESS OR IMPLIED, INCLUDING ANY
// IMPLIED WARRANTIES OF FITNESS FOR A PARTICULAR
// PURPOSE, MERCHANTABILITY, OR NON-INFRINGEMENT.
//
//*********************************************************

using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Threading.Tasks;
using Windows.Web.Syndication;
using System.Linq;
using Windows.Storage;
using Windows.Storage.Streams;
using System.IO;
using System.Xml;
using System.Runtime.Serialization;
using System.Net.Http;
using Windows.Data.Xml.Dom;
using Windows.Data.Json;

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
    // FeedData
    // Holds info for a single blog feed, including a list of blog posts (FeedItem)
    public class FeedData
    {
        public String Time { get; set; }
        public String Context { get; set; }

        private List<FeedItem> _Items = new List<FeedItem>();
        public List<FeedItem> Items
        {
            get
            {
                return this._Items;
            }
        }
    }

    // FeedItem
    // Holds info for a single blog post
    public class FeedItem
    {
        public String Time { get; set; }
        public String Context { get; set; }
    }

    public class HistorySearchData
    {
        public String CompanyEn { get; set; }
        public String Company { get; set; }
        public String Number { get; set; }
        public String Time { get; set; }

        private List<HistorySearchItem> _HSItems = new List<HistorySearchItem>();
        public List<HistorySearchItem> HSItems
        {
            get
            {
                return this._HSItems;
            }
        }
    }

    public class HistorySearchItem
    {
        public String CompanyEn { get; set; }
        public String Company { get; set; }
        public String Number { get; set; }
        public String Time { get; set; }
    }



    // FeedDataSource
    // Holds a collection of blog feeds (FeedData), and contains methods needed to
    // retreive the feeds.
    public class FeedDataSource
    {
       // private static FeedDataSource _feedDataSource = new FeedDataSource();

        private ObservableCollection<FeedData> _Feeds = new ObservableCollection<FeedData>();
        private ObservableCollection<HistorySearchData> _HSData = new ObservableCollection<HistorySearchData>();

        private String _uri = "http://api.ickd.cn/";                    //快递查询api的uri
        private String _authId = "E077B7C6D3F2DA9C92D13D7BD36CD171";    //个人注册的key
        private String _type = "json";                                  //api返回值类型
        private String _encode = "utf8";                                //数据返回的字符集

        public ObservableCollection<FeedData> Feeds
        {
            get
            {
                return this._Feeds;
            }
        }

        public ObservableCollection<HistorySearchData> HistorySearch
        {
            get
            {
                return this._HSData;
            }
        }

        //异步获取接口数据
        public async Task GetFeedsAsync(String com, String num)
        {
            String url = _uri + "?com=" + com + "&nu=" + num + "&id=" + _authId + "&type=" + _type + "&encode=" + _encode;
            Task<FeedData> feedData = GetDataAsync(url);

            this.addHistorySearch(com, num);
            this.Feeds.Add(await feedData);

            this.GetHSData();
        }

        //获取历史查询数据
        public void GetHSData()
        {
            HistorySearchData hsdata = getHistorySearch();
            this.HistorySearch.Add(hsdata);
        }

        //获取查询结果记录集
        public List<FeedItem> getItem()
        {
            if (this._Feeds[0]!=null && this._Feeds[0].Items.Count > 0)
            {
                return this._Feeds[0].Items;
            }
            else
            {
                return null;
            }
        }

        //获取查询历史记录集
        public List<HistorySearchItem> getHSItem()
        {
            if (this._HSData[0]!=null && this._HSData[0].HSItems.Count > 0)
            {
                return this._HSData[0].HSItems;
            }
            else
            {
                return null;
            }
        }

        //异步获取api远程数据
        private async Task<FeedData> GetDataAsync(string xmlUriString)
        {
            Uri xmlUri = new Uri(xmlUriString);

            try
            {
                HttpClient client = new HttpClient();
                HttpResponseMessage response = await client.GetAsync(xmlUri);
                response.EnsureSuccessStatusCode();
                Boolean statusCode = response.IsSuccessStatusCode;
                if (!statusCode)
                {
                    Windows.UI.Popups.MessageDialog dlg = new Windows.UI.Popups.MessageDialog("网络通信不畅");
                    await dlg.ShowAsync();
                    return null;
                }
                String responseBody = await response.Content.ReadAsStringAsync();

                JsonObject obj = JsonObject.Parse(responseBody);
                JsonArray jsonArray = obj.GetNamedArray("data");

                FeedData feedData = new FeedData();

                if (obj.Count > 1)
                {
                    foreach (JsonValue value in jsonArray)
                    {
                        FeedItem item = new FeedItem();
                        var jsonObj = value.GetObject();
                        item.Time = jsonObj["time"].GetString();
                        item.Context = jsonObj["context"].GetString();
                        feedData.Items.Add(item);
                    }
                }
                return feedData;
            }
            catch (Exception)
            {
                return null;
            }
        }

        //获取当地存储的历史查询数据
        public HistorySearchData getHistorySearch()
        {
            //获取本地存储数据
            var localSettings = Windows.Storage.ApplicationData.Current.LocalSettings;
            if (localSettings.Values.ContainsKey("searchHistory"))
            {
                String searchHistory = localSettings.Values["searchHistory"].ToString();
                String[] sArray = searchHistory.Split(new char[1]{';'});
                if (sArray.Length > 0)
                {
                    HistorySearchData hsdata = new HistorySearchData();
                    foreach (String item in sArray)
                    {
                        if (item.Equals("") || item == null)
                            continue;
                        String[] itemArr = item.Split(new char[1] { '|' });
                        if (itemArr.Length > 0)
                        {
                            HistorySearchItem hsitem = new HistorySearchItem();
                            hsitem.CompanyEn = itemArr[0];
                            JsonObject jsonObject = SampleDataSource.parseExpress();
                            var company = "";
                            try
                            {
                                company = jsonObject.GetNamedString(itemArr[0].ToString());
                            }
                            catch(Exception)
                            {
                                company = "";
                            }
                            hsitem.Company = company;
                            hsitem.Number = itemArr[1];
                            hsitem.Time = itemArr[2];
                            hsdata.HSItems.Add(hsitem);
                        }
                    }
                    return hsdata;
                }
            }
            return null;
        }

        //添加历史查询数据到本地存储变量中
        public void addHistorySearch(string com, string num)
        {
            DateTime dt = DateTime.Now;
            string nowTime = dt.ToString();

            var localSettings = Windows.Storage.ApplicationData.Current.LocalSettings;
            //已经存在
            if (localSettings.Values.ContainsKey("searchHistory"))          
            {
                Object searchHistory = localSettings.Values["searchHistory"];
                String addSearch = com + "|" + num + "|" + dt + ";" + searchHistory.ToString();
                localSettings.Values["searchHistory"] = addSearch;
            }
            //第一次存储
            else
            {
                localSettings.Values["searchHistory"] = com + "|" + num + "|" + dt + ";";
            }
        }

    }
}
