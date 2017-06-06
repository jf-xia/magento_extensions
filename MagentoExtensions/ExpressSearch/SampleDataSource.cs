using System;
using System.Linq;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using Windows.ApplicationModel.Resources.Core;
using Windows.Foundation;
using Windows.Foundation.Collections;
using Windows.UI.Xaml.Data;
using Windows.UI.Xaml.Media;
using Windows.UI.Xaml.Media.Imaging;
using Windows.Storage;
using Windows.ApplicationModel;
using Windows.Data.Json;
using ExpressSearch.Common;

// The data model defined by this file serves as a representative example of a strongly-typed
// model that supports notification when members are added, removed, or modified.  The property
// names chosen coincide with data bindings in the standard item templates.
//
// Applications may use this model as a starting point and build on it, or discard it entirely and
// replace it with something appropriate to their needs.

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
    /// Base class for <see cref="SampleDataItem"/> and <see cref="SampleDataGroup"/> that
    /// defines properties common to both.
    /// </summary>
    [Windows.Foundation.Metadata.WebHostHidden]
    public abstract class SampleDataCommon : BindableBase
    {
        public SampleDataCommon(String expressEn, String expressZh)
        {
            this._expressEn = expressEn;
            this._expressZh = expressZh;
        }

        protected string _expressEn = string.Empty;
        public string ExpressEn
        {
            get { return this._expressEn; }
            set { this.SetProperty(ref this._expressEn, value); }
        }

        protected string _expressZh = string.Empty;
        public string ExpressZh
        {
            get { return this._expressZh; }
            set { this.SetProperty(ref this._expressZh, value); }
        }


    }

    /// <summary>
    /// Generic item data model.
    /// </summary>
    public class SampleDataItem : SampleDataCommon
    {
        public SampleDataItem(String expressEn, String expressZh, SampleDataGroup group)
            : base(expressEn, expressZh)
        {
            this._expressEn = expressEn;
            this._expressZh = expressZh;
            this._group = group;
        }

        private string _expressZh = string.Empty;
        public string ExpressEn
        {
            get { return this._expressEn; }
            set { this.SetProperty(ref this._expressEn, value); }
        }
        public string ExpressZh
        {
            get { return this._expressZh; }
            set { this.SetProperty(ref this._expressZh, value); }
        }

        private SampleDataGroup _group;
        public SampleDataGroup Group
        {
            get { return this._group; }
            set { this.SetProperty(ref this._group, value); }
        }
    }

    /// <summary>
    /// Generic group data model.
    /// </summary>
    public class SampleDataGroup : SampleDataCommon
    {
        public SampleDataGroup(String expressEn, String expressZh)
            : base(expressEn, expressZh)
        {
        }

        private ObservableCollection<SampleDataItem> _items = new ObservableCollection<SampleDataItem>();
        public ObservableCollection<SampleDataItem> Items
        {
            get { return this._items; }
        }

        public IEnumerable<SampleDataItem> TopItems
        {
            // Provides a subset of the full items collection to bind to from a GroupedItemsPage
            // for two reasons: GridView will not virtualize large items collections, and it
            // improves the user experience when browsing through groups with large numbers of
            // items.
            //
            // A maximum of 12 items are displayed because it results in filled grid columns
            // whether there are 1, 2, 3, 4, or 6 rows displayed
            get { return this._items.Take(12); }
        }
    }

    /// <summary>
    /// Creates a collection of groups and items with hard-coded content.
    /// </summary>
    public sealed class SampleDataSource
    {
        private static SampleDataSource _sampleDataSource = new SampleDataSource();


        private Dictionary<string, SampleDataGroup> _groupDict = new Dictionary<string, SampleDataGroup>();
        private ObservableCollection<SampleDataGroup> _allGroups = new ObservableCollection<SampleDataGroup>();
        public ObservableCollection<SampleDataGroup> AllGroups
        {
            get { return this._allGroups; }
        }

        public static IEnumerable<SampleDataGroup> GetGroups(string expressEn)
        {
            return _sampleDataSource.AllGroups;
        }

        public static SampleDataGroup GetGroup(string expressEn)
        {
            if (_sampleDataSource._groupDict.ContainsKey(expressEn))
            {
                return _sampleDataSource._groupDict[expressEn];
            }
            return null;
        }

        public static void AddGroup(string key,SampleDataGroup group)
        {
            if (!_sampleDataSource._groupDict.ContainsKey(key))
            {
                _sampleDataSource._allGroups.Add(group);
                _sampleDataSource._groupDict.Add(key, group);
            }
        }

        public static SampleDataItem GetItem(string expressEn)
        {
            // Simple linear search is acceptable for small data sets
            var matches = _sampleDataSource.AllGroups.SelectMany(group => group.Items).Where((item) => item.ExpressEn.Equals(expressEn));
            if (matches.Count() == 1) return matches.First();
            return null;
        }

        //初始化所有快递公司
        public static JsonObject parseExpress()
        {
            string text = "{\"aae\":\"AAE快递\",\"anjie\":\"安捷快递\",\"anxinda\":\"安信达快递\",\"aramex\":\"Aramex国际快递\",\"cces\":\"CCES快递\",\"changtong\":\"长通物流\",\"chengguang\":\"程光快递\",\"chuanxi\":\"传喜快递\",\"chuanzhi\":\"传志快递\",\"citylink\":\"CityLinkExpress\",\"coe\":\"东方快递\",\"cszx\":\"城市之星\",\"datian\":\"大田物流\",\"debang\":\"德邦物流\",\"dhl\":\"DHL快递\",\"disifang\":\"递四方速递\",\"dpex\":\"DPEX快递\",\"dsu\":\"D速快递\",\"ees\":\"百福东方物流\",\"fedex\":\"国际Fedex\",\"fedexcn\":\"Fedex国内\",\"feibang\":\"飞邦物流\",\"feibao\":\"飞豹快递\",\"feihang\":\"原飞航物流\",\"feiyuan\":\"飞远物流\",\"fengda\":\"丰达快递\",\"fkd\":\"飞康达快递\",\"fkdex\":\"飞快达快递\",\"gdyz\":\"广东邮政物流\",\"gongsuda\":\"共速达物流|快递\",\"huayu\":\"天地华宇物流\",\"huitong\":\"汇通快递\",\"jiaji\":\"佳吉快运\",\"jiayi\":\"佳怡物流\",\"jiayunmei\":\"加运美快递\",\"jingguang\":\"京广快递\",\"jinyue\":\"晋越快递\",\"jldt\":\"嘉里大通物流\",\"kuaijie\":\"快捷快递\",\"lanbiao\":\"蓝镖快递\",\"lejiedi\":\"乐捷递快递\",\"lianhaotong\":\"联昊通快递\",\"longbang\":\"龙邦快递\",\"minhang\":\"民航快递\",\"nengda\":\"港中能达\",\"ocs\":\"OCS快递\",\"pinganda\":\"平安达\",\"quanchen\":\"全晨快递\",\"quanfeng\":\"全峰快递\",\"quanjitong\":\"全际通快递\",\"quanritong\":\"全日通快递\",\"quanyi\":\"全一快递\",\"rpx\":\"RPX保时达\",\"rufeng\":\"如风达快递\",\"santai\":\"三态速递\",\"scs\":\"伟邦(SCS)快递\",\"shengfeng\":\"盛丰物流\",\"shenghui\":\"盛辉物流\",\"shentong\":\"申通快递（可能存在延迟）\",\"shunfeng\":\"顺丰快递\",\"sure\":\"速尔快递\",\"tiantian\":\"天天快递\",\"tnt\":\"TNT快递\",\"tongcheng\":\"通成物流\",\"ups\":\"UPS\",\"usps\":\"USPS快递\",\"wanjia\":\"万家物流\",\"xinbang\":\"新邦物流\",\"xinfeihong\":\"鑫飞鸿速递\",\"xinfeng\":\"信丰快递\",\"yad\":\"源安达快递\",\"yafeng\":\"亚风快递\",\"yibang\":\"一邦快递\",\"yinjie\":\"银捷快递\",\"yousu\":\"优速快递\",\"ytfh\":\"北京一统飞鸿快递\",\"yuancheng\":\"远成物流\",\"yuantong\":\"圆通快递\",\"yuanzhi\":\"元智捷诚\",\"yuefeng\":\"越丰快递\",\"yunda\":\"韵达快递\",\"yuntong\":\"运通中港快递\",\"ywfex\":\"源伟丰\",\"zhaijisong\":\"宅急送快递\",\"zhongtie\":\"中铁快运\",\"zhongtong\":\"中通快递\",\"zhongxinda\":\"忠信达快递\",\"zhongyou\":\"中邮物流\",\"ems\":\"EMS快递\"}";
            JsonObject parsedResponse = JsonObject.Parse(text);

            return parsedResponse;
        }

        //加载所有的快递公司列表
        public static void LoadFile()
        {
            JsonObject parsedResponse = parseExpress();
           
            foreach (string key in parsedResponse.Keys)
            {
                var val = parsedResponse.GetNamedString(key);
                //按照快递公司的英文首字母进行分组
                var firstChar = key.Substring(0, 1).ToUpper();                    
                SampleDataGroup group = GetGroup(firstChar);
                if (group == null)
                {
                    group = new SampleDataGroup(firstChar, val);
                    AddGroup(firstChar, group);
                }
                group.Items.Add(new SampleDataItem(key,val,group));

            }
            //对所有分组进行排序
            _sampleDataSource._allGroups = new ObservableCollection<SampleDataGroup>(_sampleDataSource._allGroups.OrderBy(item => item.ExpressEn));
            
        }

        //添加常用快递公司
        public static void addCommonExpress()
        {
            //添加常用快递公司
            string common = "{\"ems\":\"EMS快递\",\"shentong\":\"申通快递\",\"shunfeng\":\"顺丰快递\",\"tiantian\":\"天天快递\",\"yunda\":\"韵达快递\",\"yuantong\":\"圆通快递\",\"zhaijisong\":\"宅急送快递\",\"zhongtong\":\"中通快递\"}";
            JsonObject commonExpress = JsonObject.Parse(common);
            foreach (string key in commonExpress.Keys)
            {
                var val = commonExpress.GetNamedString(key);
                var commonUser = " 常用";
                SampleDataGroup group = GetGroup(commonUser);
                if (group == null)
                {
                    group = new SampleDataGroup(commonUser, val);
                    AddGroup(commonUser, group);
                }
                group.Items.Add(new SampleDataItem(key, val, group));
            }
        }

        public SampleDataSource()
        {
            //LoadFile();

        }
    }
}