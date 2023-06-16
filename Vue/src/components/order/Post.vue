<template>
    <div
        v-if="true"
        :style="{ width: this.globalInfo.bodyWidth + 'px' }"
        class="container-body article-detail-body"
    >
        <!--板块导航-->
        <div class="board-info">
            <router-link :to="`/userhome/user/block/${this.$store.state.articleDetail.forumArticle.boardid}/0`"
                         class="a-link">{{ this.$store.state.articleDetail.forumArticle.boardname }}
            </router-link>
            <span class="iconfont icon-right" style="color: white;"></span>
            <template v-if="true">
                <router-link
                    :to="`/userhome/user/block/${this.$store.state.articleDetail.forumArticle.boardid}/${this.$store.state.articleDetail.forumArticle.subboardid}`"
                    class="a-link"
                >{{ this.$store.state.articleDetail.forumArticle.subboardname }}
                </router-link
                >
                <span class="iconfont icon-right" style="color: white;"></span>
            </template>
            <span style="color: #ffe6b0;">{{
                    this.$store.state.articleDetail.forumArticle.title
                }}</span>
        </div>
        <!--文章详情-->
        <div
            :style="{ width: this.globalInfo.bodyWidth - 300 + 'px' }"
            class="detail-container"
        >
            <div class="article-detail" id="view-top">
                <!--标题-->
                <h1 class="title">

                    {{ this.$store.state.articleDetail.forumArticle.title }}
                </h1>
                <!--用户信息-->
                <div class="user-info">
                    <img :src="this.$store.state.articleDetail.forumArticle.authorhead"
                         style="width:50px;height:50px;border-radius:50%;margin-right:0;">
                    <div class="user-info-detail">
                        <router-link
                            :to="`/userhome/user/information`"
                            class="nick-name a-link"
                        >{{ this.$store.state.articleDetail.forumArticle.author }}
                        </router-link
                        >
                        <div class="time-info">
                            <span>{{ this.$store.state.articleDetail.forumArticle.time }}</span>
                            <span class="iconfont icon-eye-solid">
                {{
                                    this.$store.state.articleDetail.forumArticle.readcount === '0' ? "阅读" : this.$store.state.articleDetail.forumArticle.readcount
                                }}
              </span>

                        </div>
                    </div>
                </div>
                <!--文章详情-->
                <div id="detail" class="detail" v-html="this.$store.state.articleDetail.forumArticle.content"></div>
            </div>
            <!--附件-->
            <div v-if="this.$store.state.articleDetail.forumArticle.file>0" id="view-attachment"
                 class="attachment-panel">
                <div class="title">附件</div>
                <div class="attachment-info">
                    <div class="iconfont icon-zip item"></div>
                    <div class="file-name item">{{ this.$store.state.fileDetail.name }}</div>
                    <div class="size item">
                        {{ Math.floor(this.$store.state.fileDetail.size / 1024) }}kb
                    </div>
                    <div class="download-btn item">
                        <el-button
                            size="small"
                            type="primary"
                        ><a :href="this.$store.state.fileDetail.address" style="color: white;" target="_blank">下载</a>
                        </el-button
                        >
                    </div>
                </div>
            </div>
            <!--评论-->
            <div
                id="view-comment"
                class="comment-panel"
            >
                <CommentList
                    v-if="true"
                    :articleId="this.$store.state.articleDetail.forumArticle.id"
                    :articleUserId="this.$store.state.articleDetail.forumArticle.authorid"
                    :dataSource="this.$store.state.commentListInfo"
                    @updateCommentCount="updateCommentCount"
                ></CommentList>
            </div>

        </div>
        <!--目录-->
        <div class="toc-panel">
            <div class="top-containner">
                <div class="toc-title"><b style="font-size: 22px">相关文章</b></div>
                <div class="toc-list" style="height: 500px">
                    <div v-for="toc in this.$store.state.nowArticleListInfo.list"
                         style="border-bottom: 3px solid rgba(255,255,255,0.17);">
                            <span
                                :class="['toc-item', toc.id == this.$store.state.articleDetail.forumArticle.id ? 'active' : '']"
                                :style="{ 'padding-left': 1 * 15 + 'px' }"
                                style="color: #bfbfbf;"
                                @click="gotoAnchor(toc.id)"
                            >{{ toc.title }}</span
                            >
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--快捷操作-->
    <!--回到开头-->
    <div
        v-if="true > 0"
        style="left: -0px;top: 700px;width:200px;position: fixed;"
        class="quick-panel"
    >
        <el-badge
            v-if="true"
            :hidden="1"
            :value="0"
            type="info"
        >
            <div
                v-if="true"
                class="quick-item"
                @click="goToPostion('view-top')"
            >
                <span
                    :class="['iconfont icon-top', this.$store.state.articleDetail.forumArticle.commentcount !== '0' ? 'have-comment' : '']"></span>
            </div>
        </el-badge>
    </div>


    <div
        v-if="true > 0"
        :style="{ left: quickPanelLeft + 'px' }"
        class="quick-panel"
    >
        <!--点赞-->
        <el-badge
            :hidden="this.$store.state.articleDetail.forumArticle.likecount === '0'"
            :value="this.$store.state.articleDetail.forumArticle.likecount"
            type="info"
        >
            <div class="quick-item" @click="doLikeHandler">
        <span
            :class="['iconfont icon-good', this.$store.state.articleDetail.forumArticle.havelike !== '0' ? 'have-like' : '']"
        ></span>
            </div>
        </el-badge>
        <!--评论-->
        <el-badge
            v-if="true"
            :hidden="this.$store.state.articleDetail.forumArticle.commentcount === '0'"
            :value="this.$store.state.articleDetail.forumArticle.commentcount"
            type="info"
        >
            <div
                v-if="true"
                class="quick-item"
                @click="goToPostion('view-comment')"
            >
                <span
                    :class="['iconfont icon-comment', this.$store.state.articleDetail.forumArticle.commentcount !== '0' ? 'have-comment' : '']"></span>
            </div>
        </el-badge>
        <!--附件-->
        <el-badge
            v-if="true"
            :hidden="this.$store.state.articleDetail.forumArticle.file === '0'"
            :value="1"
            type="info"
        >
            <div class="quick-item" @click="goToPostion('view-attachment')">
                <span
                    :class="['iconfont icon-attachment', this.$store.state.articleDetail.forumArticle.file !== '0' ? 'have-file' : '']"></span>
            </div>
        </el-badge>
    </div>
</template>

<script>
import CommentList from "@/components/widget/CommentList"
import Storage from "@/tools/Storage";

export default {
    name: "Post",
    props: {},
    components: {CommentList},
    data() {
        return {
            quickleft:(window.innerWidth - this.globalInfo.bodyWidth) / 2 - 110,
            tocArray: [],
            commentListInfo: {
                list: [
                    {
                        children: [{}, {}, {}]
                    },
                    {},
                    {},
                    {},
                    {},
                    {},
                    {},
                    {},
                    {},
                ],
                pageTotal: 20,
                totalCount: 200,
                pageNo: 5,
            },
            articleDetail: {},
            fileDetail: {},
            show: false,
        }
    },
    computed: {
        activePboardId() {
            return Storage.state.activePboardId;
        },
        activeBoardId() {
            return Storage.state.activeBoardId;
        },

    },


    mounted() {
        this.getarticleListInfo();
        this.getNowArticleListInfo();
        let phpaddr = "bbs/php/getpost.php";
        var xmlhttp;
        var success = false;
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var x = xmlhttp.responseText;
                params = JSON.parse(x);
                success = params.ok;
                // console.log(params);
            }
        }
        var params = {
            id: this.$route.params.postid,
            userid: Storage.state.user_info.id
        };
        //xmlhttp.open("GET", '/' + phpaddr + '?name='+this.name+'&password=', false);
        xmlhttp.open("GET", '/' + phpaddr + '?pramas=' + params.id + '&userid=' + params.userid, false);
        xmlhttp.send();
        Storage.commit("Set", {
            key: "articleDetail",
            pramas: params
        });
        this.articleDetail = Storage.state.articleDetail;
        console.log(Storage.state.articleDetail);


        let filephpaddr = "bbs/php/getfile.php";
        var filexmlhttp;
        var filesuccess = false;
        filexmlhttp = new XMLHttpRequest();
        filexmlhttp.onreadystatechange = function () {
            if (filexmlhttp.readyState == 4 && filexmlhttp.status == 200) {
                var x = filexmlhttp.responseText;
                fileparams = JSON.parse(x);
                filesuccess = fileparams.ok;
                console.log(fileparams);
            }
        }
        var fileparams = {
            id: Storage.state.articleDetail.forumArticle.file
        };
        //xmlhttp.open("GET", '/' + phpaddr + '?name='+this.name+'&password=', false);
        filexmlhttp.open("GET", '/' + filephpaddr + '?pramas=' + fileparams.id, false);
        filexmlhttp.send();
        Storage.commit("Set", {
            key: "fileDetail",
            pramas: fileparams
        });
        this.fileDetail = Storage.state.fileDetail;
        console.log(this.fileDetail);

        let commentphpaddr = "bbs/php/getcommentlist.php";
        var commentxmlhttp;
        var commentsuccess = false;
        commentxmlhttp = new XMLHttpRequest();
        commentxmlhttp.onreadystatechange = function () {
            if (commentxmlhttp.readyState == 4 && commentxmlhttp.status == 200) {
                var x = commentxmlhttp.responseText;
                commentparams = JSON.parse(x);
                commentsuccess = params.ok;
                console.log(commentparams);
            }
        }
        var commentparams = {
            id: Storage.state.articleDetail.forumArticle.id
        };
        //xmlhttp.open("GET", '/' + phpaddr + '?name='+this.name+'&password=', false);
        commentxmlhttp.open("GET", '/' + commentphpaddr + '?pramas=' + commentparams.id, false);
        commentxmlhttp.send();

        var compare = function (name, fun) {
            return function (o, p) {
                var a, b;
                if (o && p && typeof o === 'object' && typeof p === 'object') {
                    a = o[name];
                    b = p[name];
                    if (parseInt(o['top']) < parseInt(p['top'])) {
                        return 1;
                    }
                    if (name === 'time') {
                        console.log('userhome')
                        console.log(Storage.state.orderType);
                        return new Date(a) < new Date(b) ? 1 : -1;
                    }
                    if (name === 'a_size') {
                        a = parseInt(a);
                        b = parseInt(b);
                    }
                    //当o[name] 和 p[name] 相等时，利用fun函数根据另一个属性来进行比较
                    if (a === b) {
                        a = o['time'];
                        b = p['time'];
                        return new Date(a) < new Date(b) ? 1 : -1;
                    }
                    if (typeof a === typeof b) {
                        return a < b ? -1 : 1;
                    }
                    return typeof a < typeof b ? -1 : 1;
                }
            }
        };
        console.log(commentparams);
        commentparams.list.sort(compare('time'));

        Storage.commit("Set", {
            key: "commentListInfo",
            pramas: commentparams
        });
        // this.commentListInfo=Storage.state.commentListInfo;
        console.log(Storage.state.commentListInfo);
    },
    methods: {
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

            var compare = function (name, fun) {
                return function (o, p) {
                    var a, b;
                    if (o && p && typeof o === 'object' && typeof p === 'object') {
                        a = o[name];
                        b = p[name];
                        if (parseInt(o['top']) < parseInt(p['top'])) {
                            return 1;
                        }
                        if (name === 'time') {
                            console.log('post')
                            console.log(Storage.state.orderType);
                            return new Date(a) < new Date(b) ? 1 : -1;
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
                            return new Date(a) < new Date(b) ? 1 : -1;
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
                        if (parseInt(o['top']) > parseInt(p['top'])) {
                            return -1;
                        }
                        if (name === 'time') {
                            console.log('post')
                            console.log(Storage.state.orderType);
                            return new Date(a) > new Date(b) ? 1 : -1;
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
                            return new Date(a) < new Date(b) ? 1 : -1;
                        }
                        if (typeof a === typeof b) {
                            return a > b ? -1 : 1;
                        }
                        return typeof a > typeof b ? -1 : 1;
                    }
                }
            };
            if (Storage.state.orderType === 0) articleListInfo.list.sort(rcompare('likecount'));
            else if (Storage.state.orderType === 1) articleListInfo.list.sort(compare('time'));
            // else if (this.order === '2') this.allalbumList.sort(compare('a_name'));
            // else if (this.order === '3') this.allalbumList.sort(rcompare('a_name'));
            // else if (this.order === '4') this.allalbumList.sort(rcompare('a_size'));
            // else if (this.order === '5') this.allalbumList.sort(compare('a_size'));
            // this.updatealbumlist();
            for (var i = 0; i < articleListInfo.list.length; i++) {
                articleListInfo.list[i].page = Math.ceil((i + 1) / 15);
            }

            // console.log(user_info);
            Storage.commit("Set", {
                key: "nowArticleListInfo",
                pramas: articleListInfo
            });
            console.log("now");
            console.log(Storage.state.nowArticleListInfo);
        },
        gotoAnchor(id) {
            let phpaddr = "bbs/php/getpost.php";
            var xmlhttp;
            var success = false;
            xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    var x = xmlhttp.responseText;
                    params = JSON.parse(x);
                    success = params.ok;
                    // console.log(params);
                }
            }
            var params = {
                id: id,
                userid: Storage.state.user_info.id
            };
            //xmlhttp.open("GET", '/' + phpaddr + '?name='+this.name+'&password=', false);
            xmlhttp.open("GET", '/' + phpaddr + '?pramas=' + params.id + '&userid=' + params.userid, false);
            xmlhttp.send();
            Storage.commit("Set", {
                key: "articleDetail",
                pramas: params
            });
            this.articleDetail = Storage.state.articleDetail;
            // console.log(this.articleDetail);


            let filephpaddr = "bbs/php/getfile.php";
            var filexmlhttp;
            var filesuccess = false;
            filexmlhttp = new XMLHttpRequest();
            filexmlhttp.onreadystatechange = function () {
                if (filexmlhttp.readyState == 4 && filexmlhttp.status == 200) {
                    var x = filexmlhttp.responseText;
                    fileparams = JSON.parse(x);
                    filesuccess = fileparams.ok;
                    console.log(fileparams);
                }
            }
            var fileparams = {
                id: Storage.state.articleDetail.forumArticle.file
            };
            //xmlhttp.open("GET", '/' + phpaddr + '?name='+this.name+'&password=', false);
            filexmlhttp.open("GET", '/' + filephpaddr + '?pramas=' + fileparams.id, false);
            filexmlhttp.send();
            Storage.commit("Set", {
                key: "fileDetail",
                pramas: fileparams
            });
            this.fileDetail = Storage.state.fileDetail;
            console.log(this.fileDetail);

            let commentphpaddr = "bbs/php/getcommentlist.php";
            var commentxmlhttp;
            var commentsuccess = false;
            commentxmlhttp = new XMLHttpRequest();
            commentxmlhttp.onreadystatechange = function () {
                if (commentxmlhttp.readyState == 4 && commentxmlhttp.status == 200) {
                    var x = commentxmlhttp.responseText;
                    commentparams = JSON.parse(x);
                    commentsuccess = params.ok;
                    console.log(commentparams);
                }
            }
            var commentparams = {
                id: Storage.state.articleDetail.forumArticle.id
            };
            //xmlhttp.open("GET", '/' + phpaddr + '?name='+this.name+'&password=', false);
            commentxmlhttp.open("GET", '/' + commentphpaddr + '?pramas=' + commentparams.id, false);
            commentxmlhttp.send();
            Storage.commit("Set", {
                key: "commentListInfo",
                pramas: commentparams
            });
            // this.commentListInfo=Storage.state.commentListInfo;
            console.log(Storage.state.commentListInfo);
            this.$router.push(`/userhome/user/post/${id}`)
        },
        handlePageNoChange(pageNo) {
            this.commentListInfo.pageNo = pageNo;
            this.$emit("loadData");
        },
        doLikeHandler() {
            if(Storage.state.articleDetail.forumArticle.authorid===Storage.state.user_info.id) {
                if (Storage.state.articleDetail.forumArticle.havelike === '0') Storage.state.user_info.likenum = parseInt(Storage.state.user_info.likenum) + 1;
                else Storage.state.user_info.likenum = parseInt(Storage.state.user_info.likenum) - 1;
            }
            let phpaddr = "bbs/php/setpostlike.php";
            var xmlhttp;
            var success = false;
            xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    var x = xmlhttp.responseText;
                    console.log(x);
                    params = JSON.parse(x);
                    // success = params.ok;
                    console.log(params);
                }
            }
            var params = {
                userid: Storage.state.user_info.id,
                postid: Storage.state.articleDetail.forumArticle.id,
                havelike: Storage.state.articleDetail.forumArticle.havelike === "0" ? 1 : 0
            };
            params = JSON.stringify(params)
            //xmlhttp.open("GET", '/' + phpaddr + '?name='+this.name+'&password=', false);
            xmlhttp.open("GET", '/' + phpaddr + '?params=' + params, false);
            xmlhttp.send();
            Storage.commit("Set", {
                key: "articleDetail",
                pramas: params
            });
            // this.getarticleListInfo();
            // this.getNowArticleListInfo();
            // this.gotoAnchor(Storage.state.articleDetail.forumArticle.id);
            console.log(Storage.state.articleDetail.forumArticle);
        },
        goToPostion(domId) {
            document.querySelector("#" + domId).scrollIntoView();
        }
    }
}
</script>

<style lang="scss" scoped>
::-webkit-scrollbar-track {
    background-color: rgba(0, 0, 0, 0.1);
}

::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.3);
}

.article-detail-body {
    position: relative;

    .board-info {
        line-height: 40px;

        .a-link {
            color: #9ba7b9;
        }

        .icon-right {
            margin: 0px 10px;
        }
    }

    .detail-container {
        height: 790px;
        overflow: auto;

        .article-detail {

            border-bottom: 1px solid #ddd;
            background: transparent;
            padding: 15px;

            .title {
                color: #0496e3;
                font-weight: bolder;
            }

            .user-info {
                margin-top: 15px;
                display: flex;
                padding-bottom: 10px;

                .user-info-detail {
                    margin-left: 10px;

                    .nick-name {
                        text-decoration: none;
                        color: #129336;
                        font-size: 15px;
                    }

                    .nick-name:hover {
                        color: var(--link);
                    }

                    .time-info {
                        margin-top: 5px;
                        font-size: 13px;
                        color: #9a9a9a;

                        .iconfont {
                            margin-left: 10px;
                        }

                        .iconfont::before {
                            padding-right: 3px;
                        }

                        .btn-edit {
                            .iconfont {
                                font-size: 14px;
                            }
                        }
                    }
                }
            }

            .detail {
                letter-spacing: 1px;
                line-height: 22px;
                color: white;

                a {
                    text-decoration: none;
                    color: var(--link);
                }

                img {
                    max-width: 90%;
                    cursor: pointer;
                }
            }
        }

        .attachment-panel {
            border-bottom: 1px solid #ddd;
            margin-top: 20px;
            background: transparent;
            padding: 20px;

            .title {
                font-size: 18px;
                color: #ff8800;
            }

            .attachment-info {
                display: flex;
                align-items: center;
                color: #9f9f9f;
                margin-top: 10px;

                .item {
                    margin-right: 10px;
                }

                .icon-zip {
                    font-size: 20px;
                    color: #6ca1f7;
                }

                .file-name {
                    color: #6ca1f7;
                }

                .integral {
                    color: red;
                    padding: 0px 5px;
                }
            }
        }

        .comment-panel {
            margin-top: 20px;
            background: transparent;
            padding: 20px;
        }
    }
}

.quick-panel {
    position: fixed;
    //width: 50px;
    width: auto;

    top: 200px;
    right: 235px;
    text-align: center;

    .el-badge__content.is-fixed {
        top: 5px;
        right: 15px;
    }

    .quick-item {
        margin-left: 20px;
        width: 60px;
        height: 60px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.50);
        margin-bottom: 30px;
        cursor: pointer;

        .iconfont {
            font-size: 30px;
            color: silver;
        }

        .have-like {
            color: red;
        }

        .have-comment {
            color: #ff8800;
        }

        .have-file {
            color: #6ca1f7;
        }
    }
}

.toc-panel {
    background-color: transparent;
    color: #2cd21a;
    position: absolute;
    top: 270px;
    right: 0px;
    width: 285px;

    .top-containner {
        background-color: transparent;
        width: 285px;
        position: fixed;

        .toc-title {
            border-bottom: 1px solid #ddd;
            padding: 10px;
        }

        .toc-list {
            max-height: calc(100vh - 200px);
            overflow: auto;
            padding: 5px;

            .no-toc {
                text-align: center;
                color: #5f5d5d;
                line-height: 40px;
                font-size: 13px;
            }

            .toc-item {
                cursor: pointer;
                display: block;
                line-height: 35px;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;
                color: #555666;
                border-radius: 3px;
                font-size: 14px;
                border-left: 2px solid #fff;
            }

            .toc-item:hover {
                background: rgba(77, 64, 64, 0.4);
            }

            .active {
                border-left: 2px solid #6ca1f7;
                border-radius: 0px 3px 3px 0px;
                background: rgba(0, 255, 81, 0.58);
                color: rgb(255, 255, 255) !important;
            }
        }
    }
}
</style>