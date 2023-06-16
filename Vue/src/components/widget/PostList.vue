<template>
    <div
        v-if="!loading && this.$store.state.nowArticleListInfo.list != null && this.$store.state.nowArticleListInfo.list.length === 0"
    >
        <NoData :msg="noDataMsg"></NoData>
    </div>
    <div class="skeleton" v-if="loading">
        <el-skeleton :row="2" animated></el-skeleton>
    </div>
    <div v-for="item in this.$store.state.nowArticleListInfo.list" v-if="!loading">
        <div class="article-item" v-if="item.page===this.$store.state.nowArticleListInfo.pageNo">
            <div class="article-item-inner">
                <div class="article-body">
                    <div class="user-info">
                        <img style="width:30px;height:30px;border-radius:50%;margin-right:0;"
                             :src="item.authorhead">
                        <router-link :to="'/userhome/user/information'" class="link-name">{{ item.author }}</router-link>
                        <el-divider direction="vertical"></el-divider>
                        <div class="post-time">{{item.time}}</div>
                        <el-divider direction="vertical"></el-divider>
                        <router-link :to="`/userhome/user/block/${item.boardid}/0`" class="link-info">{{ item.boardname }}</router-link>
                        <template v-if="true">
                            <span style="color: white;">&nbsp;/&nbsp;</span>
                            <router-link
                                :to="`/userhome/user/block/${item.boardid}/${item.subboardid}`"
                                class="link-info"
                            >{{ item.subboardname }}</router-link
                            >
                        </template>
                    </div>
                    <router-link :to="`/userhome/user/post/${item.id}`" class="title">
                        <span v-if="item.top==='1'" class="top">置顶</span>
                        <span style="border-color:#42D2AF;color: #42d2af" v-if="item.file!=='0'" class="top">附件</span>
                        <span v-if="true" v-html="`${item.title}`"></span>
                        <span v-else>{{ item.title }}</span>
                    </router-link>
                    <div class="summary">{{ item.summary }}</div>
                    <div class="article-info">
          <span class="iconfont icon-eye-solid">
            {{ item.readcount === '0' ? "阅读" : item.readcount }}
          </span>
                        <span class="iconfont icon-good">
            {{ item.likecount === '0' ? "点赞" : item.likecount }}
          </span>
                        <span class="iconfont icon-comment" v-if="true">
            {{ item.commentcount === '0' ? "评论" : item.commentcount }}
          </span>
                        <span
                            class="iconfont icon-edit edit-btn"
                            v-if="item.authorid===this.$store.state.user_info.id"
                            @click="deleteArticle(item.id)"
                        >删除</span
                        >
                    </div>
                </div>
                <router-link :to="`/userhome/user/post/${item.id}`"
                ><Cover :cover="item.cover" :width="100" v-if="item.cover!==''"></Cover
                ></router-link>
            </div>
        </div>
    </div>
<!--    <div class="pagination">-->
<!--        <el-pagination-->
<!--            v-if="dataSource.pageTotal > 1"-->
<!--            background-->
<!--            :total="dataSource.totalCount"-->
<!--            :current-page.sync="dataSource.pageNo"-->
<!--            layout="prev, pager, next"-->
<!--            @current-change="handlePageNoChange"-->
<!--            style="text-align: right"-->
<!--            :page-size="15"-->
<!--        ></el-pagination>-->
<!--    </div>-->
</template>

<script scope>
import Cover from "@/components/widget/Cover"
import NoData from "@/components/widget/NoData"
import Storage from "@/tools/Storage";



export default {
    components:{Cover,NoData},
    props: {
        dataSource: {
            type: Object,
        },
        loading: {
            type: Boolean,
        },
        noDataMsg: {
            type: String,
            default: "空空如也",
        },
    },
    methods:{
        deleteArticle(id){
            Storage.state.user_info.postnum-=1;
            let phpaddr = "bbs/php/deletepost.php";
            var xmlhttp;
            var success = false;
            xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    var x = xmlhttp.responseText;
                    // params = JSON.parse(x);
                    // success = params.ok;
                    // console.log(params);
                }
            }
            var params = [];
            //xmlhttp.open("GET", '/' + phpaddr + '?name='+this.name+'&password=', false);
            xmlhttp.open("GET", '/' + phpaddr+"?id="+id, false);
            xmlhttp.send();
            this.getarticleListInfo();
            this.getNowArticleListInfo();
        },
        getarticleListInfo(){
            let phpaddr = "bbs/php/getarticlelist.php";
            var xmlhttp;
            var success = false;
            xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    var x = xmlhttp.responseText;
                    params = JSON.parse(x);
                    success = params.ok;
                    console.log(params);
                }
            }
            var params = [];
            //xmlhttp.open("GET", '/' + phpaddr + '?name='+this.name+'&password=', false);
            xmlhttp.open("GET", '/' + phpaddr, false);
            xmlhttp.send();
            Storage.commit("Set", {
                key: "articleListInfo",
                pramas: params
            });
            console.log(Storage.state.articleListInfo)
        },
        getNowArticleListInfo(){
            console.log("this");
            var articleListInfo=Storage.state.articleListInfo;
            console.log("1");
            console.log(articleListInfo);
            articleListInfo={
                list: [],
                pageTotal: 0,
                totalCount: 0,
                pageNo: 0
            }
            var i=0,j=0;
            for(i=0;i<Storage.state.articleListInfo.list.length;i++){
                console.log('Storage.state.activePboardId');
                console.log(Storage.state.activePboardId);
                console.log(Storage.state.articleListInfo.list[i].boardid)
                console.log('Storage.state.activeBoardId');
                console.log(Storage.state.activeBoardId);
                console.log(Storage.state.articleListInfo.list[i].subboardid)
                console.log("yes0");
                var fine=true;
                if(Storage.state.activePboardId!=='0'){
                    if(Storage.state.articleListInfo.list[i].boardid!==Storage.state.activePboardId)
                    {
                        fine=false;
                        console.log("yes1");
                    }
                }
                if(Storage.state.activeBoardId!=='0'){
                    if(Storage.state.articleListInfo.list[i].subboardid!==Storage.state.activeBoardId)
                    {
                        fine=false;
                        console.log("yes2");
                    }
                }
                if(Storage.state.userid!==0){
                    if(Storage.state.articleListInfo.list[i].authorid!==Storage.state.user_info.id){
                        fine=false;
                        console.log("yes3");
                    }
                }
                if(fine){
                    console.log("yes4");
                    articleListInfo.list.push(Storage.state.articleListInfo.list[i]);
                    articleListInfo.list[j].page=Math.ceil((j+1)/15);
                    j++;
                }
                console.log("no");
            }
            articleListInfo.pageNo=1;
            articleListInfo.totalCount=articleListInfo.list.length;
            articleListInfo.pageTotal=Math.ceil(articleListInfo.totalCount/15);
            console.log(articleListInfo);
            // console.log(user_info);


            console.log('userhome')
            console.log(Storage.state.orderType);
            var compare = function (name, fun) {
                return function (o, p) {
                    var a, b;
                    if (o && p && typeof o === 'object' && typeof p === 'object') {
                        a = o[name];
                        b = p[name];
                        if(parseInt(o['top'])<parseInt(p['top'])){
                            return 1;
                        }
                        if (name === 'time') {
                            console.log('userhome')
                            console.log(Storage.state.orderType);
                            return new Date(a)<new Date(b)?1:-1;
                        }
                        if (name === 'a_size') {
                            a = parseInt(a);
                            b = parseInt(b);
                        }
                        //当o[name] 和 p[name] 相等时，利用fun函数根据另一个属性来进行比较
                        if (a === b) {
                            a = o['time'];
                            b = p['time'];
                            return new Date(a)<new Date(b)?1:-1;
                        }
                        if (typeof a === typeof b) {
                            return a < b ? -1 : 1;
                        }
                        return typeof a < typeof b ? -1 : 1;
                    }
                }
            };
            var rcompare = function (name, fun) {
                return function (o, p) {
                    var a, b;
                    if (o && p && typeof o === 'object' && typeof p === 'object') {
                        a = o[name];
                        b = p[name];
                        if(parseInt(o['top'])>parseInt(p['top'])){
                            return -1;
                        }
                        if (name === 'time') {
                            console.log('userhome')
                            console.log(Storage.state.orderType);
                            return new Date(a)>new Date(b)?1:-1;
                        }
                        if (name === 'likecount') {
                            console.log("yes");
                            a = parseInt(a)*30+parseInt(o['readcount']);
                            b = parseInt(b)*30+parseInt(p['readcount']);
                            return a<=b?1:-1;
                        }
                        //当o[name] 和 p[name] 相等时，利用fun函数根据另一个属性来进行比较
                        if (a === b) {
                            a = o['time'];
                            b = p['time'];
                            return new Date(a)<new Date(b)?1:-1;
                        }
                        if (typeof a === typeof b) {
                            return a > b ? -1 : 1;
                        }
                        return typeof a > typeof b ? -1 : 1;
                    }
                }
            };
            if (Storage.state.orderType===0||Storage.state.orderType===2) articleListInfo.list.sort(rcompare('likecount'));
            else if (Storage.state.orderType===1) articleListInfo.list.sort(compare('time'));
            for(var i=0;i<articleListInfo.list.length;i++){
                articleListInfo.list[i].page=Math.ceil((i+1)/15);
            }


            Storage.commit("Set", {
                key: "nowArticleListInfo",
                pramas: articleListInfo
            });
            console.log("now");
            console.log(Storage.state.nowArticleListInfo);
        },
        // handlePageNoChange(pageNo){
        //     this.$store.state.nowArticleListInfo.pageNo = pageNo;
        //     this.$emit("loadData")
        //     console.log(this.$store.state.nowArticleListInfo);
        //     console.log(this.$store.state.nowArticleListInfo.list);
        // },
    },
    // const emit = defineEmits(["loadData"]);
    // const handlePageNoChange = (pageNo) => {
    //     props.dataSource.pageNo = pageNo;
    //     emit("loadData");
    // };
}
</script>

<style lang="scss" scoped>
.pagination {
    padding: 10px 0px 10px 10px;
}

.iconfont {
    font-size: 17px;
    color: #d19bf3;
}

.skeleton {
    padding: 15px;
}
.article-item {
    padding: 5px 15px 0 15px;
    .article-item-inner {
        box-shadow: 0 2px 0 0 rgba(221, 221, 221, 0.4);
        padding: 10px 0px;
        display: flex;
        .article-body {
            flex: 1;
            .user-info {
                display: flex;
                align-items: center;
                font-size: 14px;
                color: #4e4f69;
                .link-name {
                    margin-left: 5px;
                    color: #17cf4b;
                    text-decoration: none;
                }
                .link-info {
                    margin-left: 5px;
                    color: #e6ea97;
                    text-decoration: none;
                }
                .post-time {
                    font-size: 13px;
                    color: #9a9a9a;
                }
            }
            .title {
                color: #0496e3;
                font-weight: bold;
                text-decoration: none;
                font-size: 16px;
                margin: 10px 0px;
                display: inline-block;
                .top {
                    font-size: 12px;
                    border-radius: 5px;
                    border: 1px solid var(--pink);
                    color: var(--pink);
                    padding: 0px 5px;
                    margin-right: 10px;
                }
            }
            .summary {
                font-size: 14px;
                color: white;
            }
            .article-info {
                margin-top: 10px;
                display: flex;
                align-items: center;
                font-size: 13px;
                .iconfont {
                    color: #86909c;
                    margin-right: 25px;
                    font-size: 14px;
                }
                .iconfont:before {
                    padding-right: 3px;
                }
                .edit-btn {
                    color: var(--link);
                    cursor: pointer;
                }
            }
        }
    }
}
.article-item:hover {
    background: rgba(255, 251, 251, 0.24);

}
</style>