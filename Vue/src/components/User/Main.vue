<template>
    <div
        :style="{ width: this.globalInfo.bodyWidth + 'px' }"
        class="container-body article-list-body"
    >
        <!--二级板块信息-->
        <div v-if="activePboardId>0" class="sub-board">
        <span :class="['board-item', activeBoardId < 1 ? 'active' : '']">
        <span @click="boardClickHandler(boardList[activePboardId-1])">全部</span>
        </span>
            <span
                v-for="item in boardList[activePboardId-1].subboard"
                :class="['board-item', item.id == activeBoardId ? 'active' : '']"
            >
        <span @click="subboardClickHandler(item)">
            {{ item.name }}
        </span
        >
      </span>
        </div>
        <div class="article-panel">
            <div class="top-tab">
                <div
                    :class="['tab', this.$store.state.orderType == 0 ? 'active' : '']"
                    @click="changeOrderType(0)"
                >
                    热门
                </div>
                <el-divider direction="vertical"></el-divider>
                <div
                    :class="['tab', this.$store.state.orderType == 2 ? 'active' : '']"
                    @click="changeOrderType(2)"
                >
                    推荐
                </div>
                <el-divider direction="vertical"></el-divider>
                <div
                    :class="['tab', this.$store.state.orderType == 1 ? 'active' : '']"
                    @click="changeOrderType(1)"
                >
                    最新
                </div>


            </div>
            <div class="article-list" style="height: 720px; overflow:auto;">
                <PostList
                    :dataSource="null"
                    :loading="loading"
                    noDataMsg="暂无相关帖子"
                >

                    <!--                            <template #default="{ data }">-->
                    <!--                                <ArticleListItem-->
                    <!--                                    :data="data"-->
                    <!--                                    :showComment="showComment"-->
                    <!--                                ></ArticleListItem>-->
                    <!--                            </template>-->
                </PostList>
            </div>

        </div>
        <div class="pagination">
            <el-pagination
                v-if="this.$store.state.nowArticleListInfo.pageTotal > 1"
                :current-page.sync="this.$store.state.nowArticleListInfo.pageNo"
                :page-size="15"
                :total="this.$store.state.nowArticleListInfo.totalCount"
                background
                layout="prev, pager, next"
                style="text-align: right"
                @current-change="handlePageNoChange"
            ></el-pagination>
        </div>
    </div>

</template>

<style lang="scss">
.pagination {

    padding: 10px 0px 10px 10px;
}

.article-list-body {
    .sub-board {
        padding: 10px 0px 12px 0px;

        .board-item {
            background: #fff;
            border-radius: 15px;
            padding: 2px 10px;
            margin-right: 10px;
            color: #909090;
            cursor: pointer;
            font-size: 14px;

            a {
                text-decoration: none;
                color: #909090;
            }
        }

        .active {
            background: #D28D43FF;
            color: white;

            a {
                color: #fff;
            }
        }
    }

    .article-panel {
        background: transparent;
        color: white;

        .top-tab {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            font-size: 15px;

            .tab {
                cursor: pointer;
            }

            .active {
                color: #D28D43FF;
            }
        }

        ::-webkit-scrollbar-track {
            background-color: rgba(0, 0, 0, 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.3);
        }
    }

}


</style>

<script>

import Storage from "@/tools/Storage";
import PostList from "@/components/widget/PostList";

export default {
    components: {PostList},
    data() {
        return {
            showpic: false,  //以图片方式显示
            showlist: true,//以列表方式显示
            nowphoto: "",
            albumList: [],
            //firstphoto:Storage.state.firstphoto,
            firstphotoarr: [],
            create: false,
            change: false,
            goodsData: [],
            update: 0,
            pic_index: 0,
            newa_name: '',
            newa_introduction: '',
            olda_name: '',
            olda_introduction: '',
            exsit: '',
            firstphoto: '',
            order: '0',  //按时间降序：0；按时间升序：1；按名称降序：2；按名称升序：3；按照片数降序：4；按照片数升序：5
            // 模拟分类数据
            categorys: [
                "全部",
                "男装",
                "女装"
            ],
            queryParams: {
                name: "",
                id: "",
                category: "",
                sellMode: 2, //0 否 1是 2全部
                expMode: 2,
            },
            pBoardId: true,
            boardId: 0,
            subBoardList: [
                {
                    boardId: 1,
                    boardName: "版块1"
                },
                {
                    boardId: 2,
                    boardName: "版块2"
                }, {
                    boardId: 3,
                    boardName: "版块3"
                }
            ],
            loading: false,
        }
    },
    computed: {
        activePboardId() {
            return Storage.state.activePboardId;
        },
        activeBoardId() {
            return Storage.state.activeBoardId;
        },
        boardList() {
            return Storage.state.boardList;
        },
        articleListInfo() {
            return Storage.state.articleListInfo;
        }

    },
    // 组件挂载时获取数据

    mounted() {
        Storage.commit("Set", {
            key: "userid",
            pramas: 0
        });
        Storage.commit("Set", {
            key: "orderType",
            pramas: 0
        });
        Storage.commit("Set", {
            key: "activePboardId",
            pramas: this.$route.params.boardid
        });
        Storage.commit("Set", {
            key: "activeBoardId",
            pramas: this.$route.params.subboardid
        });
        this.getarticleListInfo();
        this.getNowArticleListInfo();
    },
    beforeRouteUpdate(to, from) {
        // console.log(to.params.boardid);
        // console.log(to.params.subboardid)
        // console.log(from);
        console.log(to.params.postid);
        if(to.params.postid!==null){
            return;
        }
        Storage.commit("Set", {
            key: "activePboardId",
            pramas: to.params.boardid
        });
        Storage.commit("Set", {
            key: "activeBoardId",
            pramas: to.params.subboardid
        });
        // this.getarticleListInfo();
        // console.log(this.articleListInfo);
        // console.log(this.articleListInfo.pageTotal)
        this.getNowArticleListInfo();
    },
    beforeCreate() {

        // this.update += 1;
    }
    ,
    beforeMount() {

    }
    ,
    methods: {
        changeOrderType(order) {
            Storage.commit("Set", {
                key: "orderType",
                pramas: order
            });
            console.log(Storage.state.orderType);
            this.getNowArticleListInfo();
        },
        getarticleListInfo() {
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

        handlePageNoChange(pageNo) {
            Storage.state.nowArticleListInfo.pageNo = pageNo;
        }
        ,
        boardClickHandler(board) {
            console.log(this.$route.params.boardid);
            Storage.commit("Set", {
                key: "activeBoardId",
                pramas: '0'
            });
            this.getarticleListInfo();
            this.getNowArticleListInfo();
            this.$router.push(`/userhome/user/block/${this.activePboardId}/${this.activeBoardId}`)
        }
        ,
        subboardClickHandler(item) {
            Storage.commit("Set", {
                key: "activeBoardId",
                pramas: item.id
            });
            this.getarticleListInfo();
            this.getNowArticleListInfo();
            this.$router.push(`/userhome/user/block/${this.activePboardId}/${this.activeBoardId}`);
        },
        getNowArticleListInfo() {
            var articleListInfo = Storage.state.articleListInfo;
            console.log("1");
            console.log(articleListInfo);
            articleListInfo = {
                list: [],
                pageTotal: 0,
                totalCount: 0,
                pageNo: 0
            }
            var i = 0, j = 0;
            for (i = 0; i < Storage.state.articleListInfo.list.length; i++) {
                // console.log('Storage.state.activePboardId');
                // console.log(Storage.state.activePboardId);
                // console.log(Storage.state.articleListInfo.list[i].boardid)
                // console.log('Storage.state.activeBoardId');
                // console.log(Storage.state.activeBoardId);
                // console.log(Storage.state.articleListInfo.list[i].subboardid)
                // console.log("yes0");
                var fine = true;
                if (Storage.state.activePboardId !== '0') {
                    if (Storage.state.articleListInfo.list[i].boardid !== Storage.state.activePboardId) {
                        fine = false;
                        // console.log("yes1");
                    }
                }
                if (Storage.state.activeBoardId !== '0') {
                    if (Storage.state.articleListInfo.list[i].subboardid !== Storage.state.activeBoardId) {
                        fine = false;
                        // console.log("yes2");
                    }
                }
                if (Storage.state.userid !== 0) {
                    if (Storage.state.articleListInfo.list[i].authorid !== Storage.state.user_info.id) {
                        fine = false;
                        // console.log("yes3");
                    }
                }
                if (fine) {
                    // console.log("yes4");
                    articleListInfo.list.push(Storage.state.articleListInfo.list[i]);
                    articleListInfo.list[j].page = Math.ceil((j + 1) / 15);
                    j++;
                }
                // console.log("no");
            }
            articleListInfo.pageNo = 1;
            articleListInfo.totalCount = articleListInfo.list.length;
            articleListInfo.pageTotal = Math.ceil(articleListInfo.totalCount / 15);
            console.log(articleListInfo);


            // var a = [].concat(articleListInfo.list);
            // if (Storage.state.orderType === 0) {
            //     function sortId(a, b) {
            //         // if(a.readcount===b.readcount){
            //         //     return a.postTime<b.postTime;
            //         // }
            //         // return a.readcount>b.readcount
            //         // return a.time<b.time
            //         return parseInt(a.id) > parseInt(b.id);
            //     }
            //
            //     console.log("时间降序");
            //     a.sort(sortId);
            //     console.log(a);
            // }
            // if (Storage.state.orderType === 1) {
            //     function sortId(a, b) {
            //         // return a.time>b.time
            //         return parseInt(a.id) > parseInt(b.id);
            //     }
            //
            //     console.log("时间升序");
            //     articleListInfo.list.sort(sortId);
            //     console.log(articleListInfo);
            // }
            // console.log("yes");


            var compare = function (name, fun) {
                return function (o, p) {
                    var a, b;
                    if (o && p && typeof o === 'object' && typeof p === 'object') {
                        a = o[name];
                        b = p[name];
                        // console.log(o['top']+':'+p['top']);
                        if(parseInt(o['top'])<parseInt(p['top'])){
                            return 1;
                        }

                        if (name === 'time') {
                            console.log('main')
                            console.log(Storage.state.orderType);
                            // console.log(b+'?'+a+'?'+b.localeCompare(a, 'zh-CN'));
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
                            // return a.localeCompare(b, 'zh-CN');
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
                    // console.log(new Date('2023-06-04 17:18:53')>new Date('2023-06-04 19:53:50'));
                    // console.log(new Date('2023-06-04 17:18:53')>new Date('2023-06-04 15:45:07'));
                    if (o && p && typeof o === 'object' && typeof p === 'object') {
                        a = o[name];
                        b = p[name];
                        if(parseInt(o['top'])>parseInt(p['top'])){
                            return -1;
                        }
                        if (name === 'time') {
                            console.log('main')
                            console.log(Storage.state.orderType);
                            // console.log(a+'?'+b+'?'+a.localeCompare(b, 'zh-CN'));
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

            console.log(articleListInfo.list);
            // else if (this.order === '2') this.allalbumList.sort(compare('a_name'));
            // else if (this.order === '3') this.allalbumList.sort(rcompare('a_name'));
            // else if (this.order === '4') this.allalbumList.sort(rcompare('a_size'));
            // else if (this.order === '5') this.allalbumList.sort(compare('a_size'));
            // this.updatealbumlist();
            for(var i=0;i<articleListInfo.list.length;i++){
                articleListInfo.list[i].page=Math.ceil((i+1)/15);
            }

            // console.log(user_info);
            Storage.commit("Set", {
                key: "nowArticleListInfo",
                pramas: articleListInfo
            });
            console.log("now");
            console.log(Storage.state.nowArticleListInfo);
        },
        // // 显示图片/列表
        // changeShowType() {
        //     var arr = [], i;
        //     //console.log(Storage.state.photolist);
        //     console.log(Storage.state.photolist);
        //     var key = Object.keys(Storage.state.photolist);
        //     //console.log(Storage.state.photolist);
        //     //console.log(key);
        //     for (var x of key) {
        //         if (Storage.state.photolist[x].length === 0) {
        //             //console.log(x);
        //             arr[x] = '';
        //         } else {
        //             //console.log(x,x);
        //             arr[x] = (Storage.state.photolist[x][0]['p_address']);
        //         }
        //     }
        //     console.log(arr);
        //     this.firstphoto = arr;
        //     //for(var i=0;i<10;i++) this.update+=1;
        //
        //
        //     this.showlist = !this.showlist;
        //     this.showpic = !this.showpic;
        //     if (Storage.state.albumshowtype === '显示图片') Storage.commit('getAlbumShowType', '显示列表');
        //     else Storage.commit('getAlbumShowType', '显示图片')
        // },
        // openModal(index) {
        //     console.log(index);
        //     /*document.getElementById('myModal').style.display = "block";
        //     this.pic_index=index;
        //     this.nowphoto=this.firstphoto[this.albumList[index]][0];
        //     document.getElementsByClassName("mySlides")[0].style.display = "block";*/
        //     //this.$router.push({name: "userhome/order/"+index});
        //     this.$router.push({path: "order/" + this.albumList[index]['a_name']});
        //
        // },
        // closeModal() {
        //     document.getElementById('myModal').style.display = "none";
        // },
        // NextPic() {
        //     if (this.pic_index == this.albumList.length - 1) {
        //         this.pic_index = 0;
        //     } else {
        //         this.pic_index++;
        //     }
        //     this.openModal(this.pic_index);
        // },
        // PrevPic() {
        //     if (this.pic_index == 0) {
        //         this.pic_index = this.albumList.length - 1;
        //     } else {
        //         this.pic_index--;
        //     }
        //     this.openModal(this.pic_index);
        // },
        // addAlbum() {
        //     this.create = true;
        //     this.exsit = '0';
        //     document.getElementById('newa_name').value = '';
        //     document.getElementById('newa_introduction').value = '';
        //     document.getElementById('create_or_change').innerText = '请输入新建相册信息';
        // },
        // cancel() {
        //     this.create = false;
        //     this.change = false;
        // },
        // ok() {
        //     this.newa_name = document.getElementById('newa_name').value;
        //     this.newa_introduction = document.getElementById('newa_introduction').value;
        //     if (this.newa_name === '') {
        //         ElMessage({
        //             message: '相册名称不能为空',
        //             type: 'error',
        //             duration: 1500
        //         })
        //         return;
        //     }
        //
        //     var newa_edittime;
        //
        //     let phpaddr = "album/php/addAlbum.php";
        //     var albumxmlhttp;
        //     albumxmlhttp = new XMLHttpRequest();
        //     albumxmlhttp.onreadystatechange = function () {
        //         if (albumxmlhttp.readyState == 4 && albumxmlhttp.status == 200) {
        //             newa_edittime = albumxmlhttp.responseText;
        //             //console.log(Storage.state.album_info);
        //             //console.log(Storage.state.album_info);
        //             //console.log(Storage.state.album_info[0]);
        //             //console.log(Storage.state.album_info[0]['a_name']);
        //             console.log(albumxmlhttp.responseText);
        //         }
        //     }
        //     albumxmlhttp.open("GET", '/' + phpaddr + '?oldname=' + this.olda_name + '&oldintroduction=' + this.olda_introduction + '&exsit=' + this.exsit + '&owner=' + Storage.state.userName + '&name=' + this.newa_name + '&introduction=' + this.newa_introduction, false);
        //
        //     albumxmlhttp.send();
        //     if (newa_edittime === 'false') {
        //         ElMessage({
        //             message: '该相册名已存在',
        //             type: 'error',
        //             duration: 1500
        //         })
        //         return;
        //     }
        //
        //     Storage.state.photolist[this.newa_name] = [];
        //     var p = {};
        //     p['a_name'] = this.newa_name;
        //     p['a_introduction'] = this.newa_introduction;
        //     p['a_edittime'] = newa_edittime;
        //     p['a_size'] = 0;
        //     console.log(Storage.state.album_info);
        //     var arr = [].concat(Storage.state.album_info);
        //     console.log(arr);
        //     if (this.exsit === '0') arr.push(p);
        //     else {
        //         for (var i = 0; i < arr.length; i++) {
        //             if (arr[i]['a_name'] == this.olda_name) {
        //                 arr[i]['a_name'] = this.newa_name;
        //                 arr[i]['a_introduction'] = this.newa_introduction;
        //                 arr[i]['a_edittime'] = newa_edittime;
        //             }
        //         }
        //     }
        //     // Storage.commit('getAlbumInfo', arr);
        //     this.order = '0';
        //     this.changeOrder();
        //     this.create = false;
        // },
        // changeOrder() {
        //     var compare = function (name, fun) {
        //         return function (o, p) {
        //             var a, b;
        //             if (o && p && typeof o === 'object' && typeof p === 'object') {
        //                 a = o[name];
        //                 b = p[name];
        //                 if (name === 'a_name') {
        //                     return b.localeCompare(a, 'zh-CN');
        //                 }
        //                 if (name === 'a_size') {
        //                     a = parseInt(a);
        //                     b = parseInt(b);
        //                 }
        //                 //当o[name] 和 p[name] 相等时，利用fun函数根据另一个属性来进行比较
        //                 if (a === b) {
        //                     a = o['a_name'];
        //                     b = p['a_name'];
        //                     return a.localeCompare(b, 'zh-CN');
        //                 }
        //                 if (typeof a === typeof b) {
        //                     return a < b ? -1 : 1;
        //                 }
        //                 return typeof a < typeof b ? -1 : 1;
        //             }
        //         }
        //     };
        //     var rcompare = function (name, fun) {
        //         return function (o, p) {
        //             var a, b;
        //             if (o && p && typeof o === 'object' && typeof p === 'object') {
        //                 a = o[name];
        //                 b = p[name];
        //                 if (name === 'a_name') {
        //                     return a.localeCompare(b, 'zh-CN');
        //                 }
        //                 if (name === 'a_size') {
        //                     a = parseInt(a);
        //                     b = parseInt(b);
        //                 }
        //                 //当o[name] 和 p[name] 相等时，利用fun函数根据另一个属性来进行比较
        //                 if (a === b) {
        //                     a = o['a_name'];
        //                     b = p['a_name'];
        //                     return a.localeCompare(b, 'zh-CN');
        //                 }
        //                 if (typeof a === typeof b) {
        //                     return a > b ? -1 : 1;
        //                 }
        //                 return typeof a > typeof b ? -1 : 1;
        //             }
        //         }
        //     };
        //     if (this.order === '0') this.allalbumList.sort(rcompare('a_edittime'));
        //     else if (this.order === '1') this.allalbumList.sort(compare('a_edittime'));
        //     else if (this.order === '2') this.allalbumList.sort(compare('a_name'));
        //     else if (this.order === '3') this.allalbumList.sort(rcompare('a_name'));
        //     else if (this.order === '4') this.allalbumList.sort(rcompare('a_size'));
        //     else if (this.order === '5') this.allalbumList.sort(compare('a_size'));
        //     this.updatealbumlist();
        // },
        // updatealbumlist() {
        //     if (this.filter === '') {
        //         this.albumList = this.allalbumList;
        //     } else {
        //         this.albumList = [];
        //         for (var i = 0; i < this.allalbumList.length; i++) {
        //             console.log(this.allalbumList[i]['a_name']);
        //             console.log(this.filter);
        //             if (this.allalbumList[i]['a_name'].match(this.filter)) {
        //                 this.albumList.push(this.allalbumList[i]);
        //             }
        //         }
        //     }
        // },
        // deleteAlbum(index) {
        //     var name = this.albumList[index]['a_name'];
        //     console.log(name);
        //     this.$confirm(
        //         '确认删除该相册吗？（注意，相册内如果有照片，照片也会被删除，该操作不可逆。）',
        //         '警告',
        //         {
        //             confirmButtonText: '确认',
        //             cancelButtonText: '取消',
        //             type: 'warning',
        //         })
        //         .then(() => {
        //             let phpaddr = "album/php/deleteAlbum.php";
        //             var albumxmlhttp;
        //             albumxmlhttp = new XMLHttpRequest();
        //             albumxmlhttp.onreadystatechange = function () {
        //                 if (albumxmlhttp.readyState == 4 && albumxmlhttp.status == 200) {
        //                     //
        //                 }
        //             }
        //             albumxmlhttp.open("GET", '/' + phpaddr + '?owner=' + Storage.state.userName + '&name=' + name, false);
        //
        //             albumxmlhttp.send();
        //             var i;
        //             for (i = 0; i < Storage.state.album_info.length; i++) {
        //                 if (Storage.state.album_info[i]['a_name'] === name) {
        //                     break;
        //                 }
        //             }
        //             var arr = [].concat(Storage.state.album_info);
        //             arr.splice(i, 1);
        //             // Storage.commit('getAlbumInfo', arr);
        //             this.order = '0';
        //             this.changeOrder();
        //         })
        //         .catch(() => {
        //             // catch error
        //         });
        //
        //
        // },
        // changeAlbum(index) {
        //     this.olda_name = this.albumList[index]['a_name'];
        //     this.olda_introduction = this.albumList[index]['a_introduction'];
        //     this.create = true;
        //     this.exsit = '1';
        //     document.getElementById('newa_name').value = this.olda_name;
        //     document.getElementById('newa_introduction').value = this.olda_introduction;
        //     document.getElementById('create_or_change').innerText = '请输入修改相册信息';
        // }
    }
}
</script>