<template>
    <div>
        <div v-if="showHeader" class="header">
            <div
                :style="{ width: this.globalInfo.bodyWidth + 'px' }"
                class="header-content"
            >
                <router-link class="logo" to="/userhome" @click="GoHome">
                    <span>响当当bbs</span>
                </router-link>
                <!--板块信息-->
                <div class="menu-panel">
                    <span
                        :class="[
              'menu-item',
              activePboardId < 1  ? 'active' : '',
            ]"
                        @click="GoHome"
                    >首页
                    </span
                    >
                    <template v-for="board in boardList">
                        <el-popover
                            v-if="board.subboard.length > 0"
                            :width="300"
                            placement="bottom-start"
                            trigger="hover"
                        >
                            <template #reference>
                                    <span
                                        :class="[
                                        'menu-item',
                                        board.id === activePboardId ? 'active' : '',
                                      ]"
                                        @click="boardClickHandler(board)"
                                    >{{ board.name }}</span
                                    >
                            </template>
                            <div class="sub-board-list">
                                    <span
                                        v-for="subBoard in board.subboard"
                                        :class="[
                                        'sub-board',
                                        subBoard.id == activeBoardId ? 'active' : '',
                                      ]"
                                        @click="subBoardClickHandler(board, subBoard)"
                                    >{{ subBoard.name }}</span
                                    >
                            </div>
                        </el-popover>
                        <span
                            v-else
                            :class="[
                                    'menu-item',
                                    board.id == activePboardId ? 'active' : '',
                                  ]"
                            @click="boardClickHandler(board)"
                        >{{ board.name }}</span
                        >


                    </template>


                    <span
                        class="search-panel"
                    >
                    <el-form
                        :model="formData"
                        :rules="rules"
                        @submit.prevent
                    >
                            <!--input输入-->
                                <el-input

                                    v-model="searchword"
                                    :placeholder=searchtip
                                    class="searchinput"
                                    clearable
                                    size="large"
                                    @keyup.enter="search"
                                >

                                </el-input>


                        </el-form>
                    </span>


                    <el-button
                        style="border: none; margin:0;background-color:#d26b00; display: inline-block;height: 38px;"
                        type="primary" @click="search">
                        搜索<span class="iconfont icon-search"></span>
                    </el-button>
                </div>


                <!--登录，注册 用户信息-->
                <div class="user-info-panel">
                    <div class="op-btn">
                        <el-button class="op-btn" style="border:none;height: 38px;background-color: #47d24f;"
                                   type="primary" @click="newPost">
                            发帖<span class="iconfont icon-add"></span>
                        </el-button>
                        <!--                        <el-button type="primary" class="op-btn" @click="goSearch">-->
                        <!--                            搜索<span class="iconfont icon-search"></span>-->
                        <!--                        </el-button>-->
                    </div>
                    <!--显示用户信息-->
                    <div style="cursor: default;" class="message-info">
                            <el-badge

                                style="cursor: none;pointer-events: none; color: transparent;background-color: transparent;"
                            >
                                <div style="color:transparent;" class="iconfont icon-message"></div>
                            </el-badge>

                    </div>
                    <div class="user-info-panel">
                        <el-dropdown>
                            <img :src="'/'+'bbs/头像/默认/派蒙.jpg'"
                                 style="width:100px;height:100px;border-radius:50%;margin:auto;margin-right:0;">
                            <template #dropdown>
                                <el-dropdown-menu>
                                    <el-dropdown-item @click="information()"
                                    >我的主页
                                    </el-dropdown-item
                                    >
                                    <el-dropdown-item @click="logout">退出</el-dropdown-item>
                                </el-dropdown-menu>
                            </template>
                        </el-dropdown>
                    </div>
                </div>
            </div>
        </div>

        <div class="body-content">
            <router-view/>
        </div>


    </div>
</template>

<script>
import Storage from '../../tools/Storage'
import Dialog from "@/components/widget/Dialog";

export default {
    // eslint-disable-next-line vue/multi-word-component-names
    name: "UserHome",
    components: {Dialog},
    data() {
        return {
            scrollindex: 0,
            //userName: Storage.state.userName,
            mytimer: "",
            showHeader: true,
            dialogshow: true,
            dialogtitle: "test",
            dialogbuttons: [{
                text: "确定",
                type: "primary",
            }],
            rules: {
                keyword: [
                    {required: true, message: "请输入关键字"},
                    {min: 3, message: "关键字太少，至少三个字"},
                ],
            },
            formData: {
                keyword: "",
            },
            messageCountInfo: {
                total: 5,
                reply: 1,
                likePost: 1,
                downloadAttachment: 1,
                likeComment: 1,
                sys: 1,
            },
            searchtip: "输入你想要查找的关键词",
            searchword:"",
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
        }
    },
    methods: {
        newPost() {
            this.$router.push(`/userhome/user/order`);
        },
        information() {
            this.$router.push(`/userhome/user/information`);
        },
        gotoMessage(type) {
            this.$router.push(`/userhome/user/share`);
        },
        GoHome() {
            Storage.commit("Set", {
                key: "activePboardId",
                pramas: '0'
            });
            Storage.commit("Set", {
                key: "activeBoardId",
                pramas: '0'
            });
            this.getarticleListInfo();
            this.getNowArticleListInfo();
            console.log("home"+this.activePboardId);
            this.$router.push(`/userhome/user/block/${this.activePboardId}/${this.activeBoardId}`);
        },
        boardClickHandler(board) {
            Storage.commit("Set", {
                key: "activePboardId",
                pramas: board.id
            });
            Storage.commit("Set", {
                key: "activeBoardId",
                pramas: '0'
            });
            this.getarticleListInfo();
            this.getNowArticleListInfo();
            this.$router.push(`/userhome/user/block/${this.activePboardId}/${this.activeBoardId}`)
        },
        subBoardClickHandler(board, subboard) {
            Storage.commit("Set", {
                key: "activePboardId",
                pramas: board.id
            });
            Storage.commit("Set", {
                key: "activeBoardId",
                pramas: subboard.id
            });
            this.getarticleListInfo();
            this.getNowArticleListInfo();
            console.log("go");
            console.log(this.activeBoardId);
            this.$router.push(`/userhome/user/block/${this.activePboardId}/${this.activeBoardId}`)
        },
        search() {
            this.getarticleListInfo();
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
                if(!Storage.state.articleListInfo.list[i].title.match(this.searchword)&&!Storage.state.articleListInfo.list[i].summary.match(this.searchword)&&!Storage.state.articleListInfo.list[i].content.match(this.searchword)){
                    fine=false;
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
        logout() {
            Storage.commit('clearUserInfo');
            this.VueCookies.remove("loginInfo");
            this.$router.push({name: 'login'})
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
        }
        // closeDialog() {
        //     this.dialogshow = false;
        // },
        // roll(e) {
        //     console.log(Storage.state.allowroll)
        //     if(Storage.state.allowroll===false)
        //     {
        //         return;
        //     }
        //     // console.log(this.scrollindex)
        //     // console.log("2213"+event.wheelDelta)
        //
        //
        //     if (event.wheelDelta > 0) {
        //         this.scrollindex = 0;
        //         this.showHeader = true;
        //     }
        //     if (event.wheelDelta < 0) {
        //         this.scrollindex += 1;
        //         if (this.scrollindex > 3) this.showHeader = false
        //     }
        // },
        // GoHome() {
        //     this.activePboardId = 0;
        //     this.activeBoardId = 0;
        //     this.$router.push(`/userhome`);
        // },
        // boardClickHandler(board) {
        //     this.activePboardId = board.boardId;
        //     this.activeBoardId = 0;
        //     this.$router.push(`/userhome/user/block/${board.boardName}/home`);
        // },
        // subBoardClickHandler(board, subBoard) {
        //     this.activePboardId = board.boardId;
        //     this.activeBoardId = subBoard.boardId;
        //     this.$router.push(`/userhome/user/block/${board.boardName}/${subBoard.boardName}`);
        // },

        // selectItem(index) {
        //     this.$router.push(index)
        // },
        // myTime() {
        //     let time = new Date();
        //     let hh = time.getHours();  //时
        //     let mm = time.getMinutes();  //分
        //     let ss = time.getSeconds();  //秒
        //     // Math.floor() 向下取整
        //     document.getElementById("1").innerText = Math.floor(hh / 10);
        //     document.getElementById("2").innerText = hh % 10;
        //     document.getElementById("4").innerText = Math.floor(mm / 10);
        //     document.getElementById("5").innerText = mm % 10;
        //     document.getElementById("7").innerText = Math.floor(ss / 10);
        //     document.getElementById("8").innerText = ss % 10;
        // }
    },
    beforeRouteUpdate(to,from){
        if(to.params.postid!==null){
            return;
        }
        console.log(to.params.boardid);
        console.log(to.params.subboardid)
        console.log(from);
        Storage.commit("Set", {
            key: "activePboardId",
            pramas: to.params.boardid
        });
        Storage.commit("Set", {
            key: "activeBoardId",
            pramas: to.params.subboardid
        });
        this.getarticleListInfo();
    },
    beforeCreate() {

        // //console.log("ok");
        // let phpaddr = "album/php/getAlbum.php";
        // var albumxmlhttp;
        // albumxmlhttp = new XMLHttpRequest();
        // albumxmlhttp.onreadystatechange = function () {
        //     if (albumxmlhttp.readyState == 4 && albumxmlhttp.status == 200) {
        //         var x = JSON.parse(albumxmlhttp.responseText);
        //         // Storage.commit("getAlbumInfo", x);
        //         //console.log(Storage.state.album_info);
        //         //console.log(Storage.state.album_info);
        //         //console.log(Storage.state.album_info[0]);
        //         //console.log(Storage.state.album_info[0]['a_name']);
        //     }
        // }
        // albumxmlhttp.open("GET", '/' + phpaddr + '?order=0&name=' + Storage.state.userName, false);
        //
        // albumxmlhttp.send();
        //
        // phpaddr = "album/php/getHead.php";
        // var headxmlhttp;
        // headxmlhttp = new XMLHttpRequest();
        // headxmlhttp.onreadystatechange = function () {
        //     if (headxmlhttp.readyState == 4 && headxmlhttp.status == 200) {
        //         var x = JSON.parse(headxmlhttp.responseText);
        //         Storage.commit("getHeadInfo", x);
        //         console.log(x);
        //         //console.log(Storage.state.album_info);
        //         //console.log(Storage.state.album_info);
        //         //console.log(Storage.state.album_info[0]);
        //         //console.log(Storage.state.album_info[0]['a_name']);
        //     }
        // }
        // headxmlhttp.open("GET", '/' + phpaddr, true);
        // headxmlhttp.send();
        //
        //
        // var photoxmlhttp = [];
        // for (var i = 0; i < Storage.state.album_info.length; i++) {
        //     let phpaddr = "album/php/getPhoto.php";
        //
        //     photoxmlhttp[i] = new XMLHttpRequest();
        //     photoxmlhttp[i].album = Storage.state.album_info[i]['a_name'];
        //     photoxmlhttp[i].onreadystatechange = function () {
        //         if (this.readyState == 4 && this.status == 200) {
        //             var x = this.album;
        //             //console.log(x);
        //             var text = JSON.parse(this.responseText);
        //             //console.log(text);
        //             //console.log(text);
        //             Storage.commit("getPhotoList", {
        //                 album: x,
        //                 photolist: text,
        //             })
        //             //console.log(text);
        //         }
        //     }
        //     //console.log(Storage.state.album_info[i]);
        //     photoxmlhttp[i].open("GET", '/' + phpaddr + '?owner=' + Storage.state.userName + '&album=' + Storage.state.album_info[i]['a_name'], true);
        //
        //     photoxmlhttp[i].send();
        //
        // }
        //
        // phpaddr = "album/php/getShare.php";
        // var sharexmlhttp;
        // sharexmlhttp = new XMLHttpRequest();
        // sharexmlhttp.onreadystatechange = function () {
        //     if (sharexmlhttp.readyState == 4 && sharexmlhttp.status == 200) {
        //         var x = JSON.parse(sharexmlhttp.responseText);
        //         Storage.commit("getShareInfo", x);
        //         //console.log(x);
        //     }
        // }
        // sharexmlhttp.open("GET", '/' + phpaddr + "?name=" + Storage.state.userName, true);
        //
        // sharexmlhttp.send();
    },
    /*created() {
        console.log(Storage.state.album_info.length);
        var xmlhttp = [];
        for (var i = 0; i < Storage.state.album_info.length; i++) {
            let phpaddr = "album/php/outdir.php";

            xmlhttp[i] = new XMLHttpRequest();
            xmlhttp[i].album = Storage.state.album_info[i];
            xmlhttp[i].onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    var x = this.album;
                    Storage.commit("getFirstPhoto", {
                        album: x,
                        first_photo: JSON.parse(this.responseText),
                    })
                    console.log(this.responseText);
                }
            }
            console.log(Storage.state.album_info[i]);
            xmlhttp[i].open("GET", '/' + phpaddr + '?readall=0&type=1&addr=原神/' + Storage.state.album_info[i], true);

            xmlhttp[i].send();

        }
    },*/
    mounted() {
        Storage.commit("Set", {
            key: "activePboardId",
            pramas: '0'
        });
        Storage.commit("Set", {
            key: "activeBoardId",
            pramas: '0'
        });
        console.log(Storage.state.activePboardId);

        let phpaddr = "bbs/php/getboardlist.php";
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
            key: "boardList",
            pramas: params
        });
        console.log(Storage.state.boardList)

        // this.getarticleListInfo();


        //this.mytimer = setInterval(this.myTime, 1000);


        // let phpaddr = "album/php/GetUserInformation.php";
        // var xmlhttp;
        // var success = false;
        // xmlhttp = new XMLHttpRequest();
        // xmlhttp.onreadystatechange = function () {
        //     if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        //         //var x = JSON.parse(xmlhttp.responseText);
        //         var x = JSON.parse(xmlhttp.responseText);
        //         Storage.commit('getUserInformation', {
        //             head: x['u_head'],
        //             sex: x['u_sex'],
        //             birthday: x['u_birthday'],
        //             signature: x['u_signature'],
        //             status: x['u_status'],
        //             registertime: x['u_registertime']
        //         })
        //         //console.log(x);
        //     }
        // }
        // xmlhttp.open("GET", '/' + phpaddr + '?name=' + this.userName, true);
        // xmlhttp.send();

    },
    unmounted() {
        // clearInterval(this.mytimer);
    }
}
</script>

<style lang="scss" scoped>


.header {
    top: 0px;
    width: 100%;
    position: fixed;

    z-index: 1000;
    background: transparent;

    .header-content {
        margin: 0px auto;
        align-items: center;
        height: 60px;
        display: flex;

        .logo {
            display: block;
            text-decoration: none;
            margin-right: 5px;

            span {
                font-size: 35px;
                color: white;
            }
        }

        .menu-panel {
            flex: 1;

            span {
                color: white;
            }

            .menu-item {
                margin-left: 20px;
                cursor: pointer;
            }

            .home {
                text-decoration: none;
                color: #cb9e9e;
            }

            .active {
                color: var(--link);
            }
        }

        .user-info-panel {
            width: 18%;
            display: flex;
            align-items: center;

            .op-btn {
                .iconfont {
                    margin-left: 5px;
                }

                .el-button + .el-button {
                    margin-left: 5px;
                }
            }

            .message-info {
                margin-left: 5px;
                margin-right: 25px;
                cursor: pointer;

                .icon-message {
                    font-size: 25px;
                    color: rgb(147, 147, 147);
                }
            }
        }
    }
}

.search-panel {
    display: inline-block;
    margin-left: 20px;
    width: 70%;

    .el-input::v-deep {
        .el-input__wrapper {
            padding-top: 0 !important;

            padding-right: 0 !important;
            padding-bottom: 0 !important;
        }

        i {
            margin-right: 20px;
        }
    }
}


.sub-board-list {
    display: flex;
    flex-wrap: wrap;

    .sub-board {
        padding: 0px 10px;
        border-radius: 20px;
        margin-right: 10px;
        background: rgb(239, 239, 239);
        border: 1px solid #ddd;
        color: rgb(119, 118, 118);
        margin-top: 10px;
        cursor: pointer;
    }

    .sub-board:hover {
        color: var(--link);
    }

    .active {
        background: var(--link);
        color: #fff;
    }

    .active:hover {
        color: #fff;
    }
}

.body-content {
    margin-top: 60px;
    position: relative;
    min-height: calc(100vh - 210px);
}

.message-item {
    display: flex;
    justify-content: space-around;

    .text {
        flex: 1;
    }

    .count-tag {
        position: absolute;
        right: 10px;
        height: 15px;
        line-height: 15px;
        min-width: 20px;
        display: inline-block;
        background: #f56c6c;
        border-radius: 10px;
        font-size: 13px;
        text-align: center;
        color: #fff;
        margin-left: 10px;
    }
}

#container {
    height: 100%;
    width: 100%;
    position: absolute;
}

#top {
    margin-right: 1px;
    text-align: center;
    height: 60px;
}

.el-menu-item {
    font-size: 15px;
}

.el-sub-menu__title span {
    font-size: 15px;
}

.clock {
    float: right;
}

.head h1 {
    float: left;
}

.el-menu-item:hover {
    /* 鼠标移入元素沿Y轴上移 */
}

/* 加个扫光动画 */
.el-menu-item:before {
    content: "";
    position: absolute;
    top: 0px;
    left: 0px;
    width: 50px;
    height: 100%;
    background-color: rgb(37, 33, 33);;
    /* 元素沿X轴45横切，沿X轴右移150px */
    transform: skewX(45deg) translateX(300px);
    /* 动画过渡 */
}

.el-menu-item:hover::before {
    /* 元素沿X轴45横切，沿X轴左移150px */
    transform: skewX(45deg) translateX(-300px);
    transition: all 0.5s;
}


</style>