<template>
    <div class="comment-body">
        <div class="comment-title">
            <div class="title">
                评论<span style="top: -2px;position: relative;" class="count">{{ Storage.state.articleDetail.forumArticle.commentcount }}</span>
            </div>

        </div>
        <!--发送评论-->
        <div class="comment-form-panel">
            <CommentPost
                :articleId="articleId"
                :pCommentId="0"
                :avatarWidth="50"
                :userId="Storage.state.user_info.name"
                :showInsertImg="true"
                @postCommentFinish="postCommentFinish"
            ></CommentPost>
        </div>
        <div class="comment-list">
<!--            <DataList-->
<!--                :dataSource="commentListInfo"-->
<!--                :loading="loading"-->
<!--                @loadData="loadComment"-->
<!--                noDataMsg="暂无评论，赶紧占沙发吧！"-->
<!--            >-->
<!--                <template #default="{ data }">-->
<!--                    <CommentListItem-->
<!--                        :articleId="articleId"-->
<!--                        :commentData="data"-->
<!--                        :articleUserId="articleUserId"-->
<!--                        :currentUserId="currentUserInfo.userId"-->
<!--                        @hiddenAllReply="hiddenAllReplyHandler"-->
<!--                        @reloadData="loadComment"-->
<!--                    ></CommentListItem>-->
<!--                </template>-->
<!--            </DataList>-->
            <div
                v-if="!loading && commentListInfo.list != null && commentListInfo.list.length == 0"
            >
                <NoData :msg="noDataMsg"></NoData>
            </div>
            <div class="skeleton" v-if="false">
                <el-skeleton :row="2" animated></el-skeleton>
            </div>
            <div v-for="item in Storage.state.commentListInfo.list" v-if="Storage.state.commentListInfo.list.length>0">
                <div class="comment-item">
                    <img style="width:50px;height:50px;border-radius:50%;margin-right:0;"
                         :src="item.userhead">
                    <div class="comment-info">
                        <div class="nick-name">
        <span style="margin-left:-11px;margin-top: 3px;" class="name">{{ item.username }}</span>
                            <span style="margin-left:10px " class="tag-author" v-if="item.userid===Storage.state.articleDetail.forumArticle.authorid"
                            >作者</span
                            >
                            <br v-else>
                        </div>
                        <div class="comment-content">
                            <div>
          <span style="position: relative;top: -2px" class="tag tag-topping" v-if="item.top==='1'"
          >置顶</span
          >
                                <span style="color: #b2c7e7;" v-html="item.content"></span>
                            </div>
                            <CommentImage
                                :style="{ 'margin-top': '10px' }"
                                v-if="item.imgpath!==''"
                                :src="item.imgpath"
                            ></CommentImage>
                        </div>

                        <div class="op-panel">
                            <div class="time">
                                <span>{{ item.time }}</span>
                            </div>


                            <el-dropdown v-if="Storage.state.user_info.id===Storage.state.articleDetail.forumArticle.authorid">
                                <div class="iconfont icon-more"></div>
                                <template #dropdown>
                                    <el-dropdown-menu>
                                        <el-dropdown-item @click="opTop(item.id,item.top)">
                                            {{ item.top==='0'  ? "设为置顶" : "取消置顶" }}
                                        </el-dropdown-item>
                                    </el-dropdown-menu>
                                </template>
                            </el-dropdown>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>

</template>

<script setup>
import CommentPost from "./CommentPost.vue";
import CommentListItem from "./CommentListItem.vue";
import DataList from "@/components/widget/DataList";
import {ref, reactive, getCurrentInstance, watch} from "vue";
import {useRouter, useRoute} from "vue-router";
import {useStore} from "vuex";
import NoData from "@/components/widget/NoData"
import CommentImage from "@/components/widget/CommentImage"
import Storage from "@/tools/Storage";
const {proxy} = getCurrentInstance();
const router = useRouter();
const route = useRoute();
const store=useStore();


const props = defineProps({
    dataSource: {
        type: Object,
    },
    articleId: {
        type: String,
    },
    articleUserId: {
        type: String,
    },
    noDataMsg: {
        type: String,
        default: "空空如也",
    },

});

const api = {
    loadComment: "/comment/loadComment",
};

//排序
const orderType = ref(0);
const orderChange = (type) => {
    orderType.value = type;
    loadComment();
};
//评论列表
const loading = ref(false);
const commentListInfo = ref(
    {
        list: [
            {
                children:[{},{},{}]
            },
            {

            },
            {

            },
            {

            },
            {

            },
            {

            },
            {

            },
            {

            },
            {

            },
        ],
        pageTotal: 20,
        totalCount: 200,
        pageNo: 5,
    }
);

const handlePageNoChange = (pageNo) => {
    commentListInfo.pageNo = pageNo;
    emit("loadData");
};
const loadComment = async () => {
    // let params = {
    //     pageNo: commentListInfo.value.pageNo,
    //     articleId: props.articleId,
    //     orderType: orderType.value,
    // };
    // loading.value = true;
    // let result = await proxy.Request({
    //     url: api.loadComment,
    //     showLoading: false,
    //     params,
    // });
    // loading.value = false;
    // if (!result) {
    //     return;
    // }
    // commentListInfo.value = result.data;
};
loadComment();

//隐藏所有回复框
const hiddenAllReplyHandler = () => {
    commentListInfo.value.list.forEach((element) => {
        element.showReply = false;
    });
};

const emit = defineEmits(["updateCommentCount"]);
//评论发布完成
const postCommentFinish = (resultData) => {
    commentListInfo.value.list.unshift(resultData);
    //更新数量
    const totalCount = commentListInfo.value.totalCount + 1;
    commentListInfo.value.totalCount = totalCount;
    emit("updateCommentCount", totalCount);
};

const update=()=>{
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
        id:Storage.state.articleDetail.forumArticle.id,
        userid:Storage.state.user_info.id
    };
    //xmlhttp.open("GET", '/' + phpaddr + '?name='+this.name+'&password=', false);
    xmlhttp.open("GET", '/' + phpaddr+'?pramas='+params.id+'&userid='+params.userid, false);
    xmlhttp.send();
    Storage.commit("Set", {
        key: "articleDetail",
        pramas: params
    });
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
        id:Storage.state.articleDetail.forumArticle.file
    };
    //xmlhttp.open("GET", '/' + phpaddr + '?name='+this.name+'&password=', false);
    filexmlhttp.open("GET", '/' + filephpaddr+'?pramas='+fileparams.id, false);
    filexmlhttp.send();
    Storage.commit("Set", {
        key: "fileDetail",
        pramas: fileparams
    });

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
        id:Storage.state.articleDetail.forumArticle.id
    };
    //xmlhttp.open("GET", '/' + phpaddr + '?name='+this.name+'&password=', false);
    commentxmlhttp.open("GET", '/' + commentphpaddr+'?pramas='+commentparams.id, false);
    commentxmlhttp.send();

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
    console.log(commentparams);
    commentparams.list.sort(compare('time'));
    Storage.commit("Set", {
        key: "commentListInfo",
        pramas: commentparams
    });
    // this.commentListInfo=Storage.state.commentListInfo;
    console.log(Storage.state.commentListInfo);
}

const opTop=(id,top)=>{
    let phpaddr = "bbs/php/settop.php";
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
    if(top==='0'){
        top=1;
    }
    else if(top==='1'){
        top=0;
    }
    var params = [];
    //xmlhttp.open("GET", '/' + phpaddr + '?name='+this.name+'&password=', false);
    xmlhttp.open("GET", '/' + phpaddr+"?id="+id+"&top="+top, false);
    xmlhttp.send();
    update();
};


//当前用户信息
const currentUserInfo = ref({});
watch(
    () => store.state.loginUserInfo,
    (newVal, oldVal) => {
        currentUserInfo.value = newVal || {};
    },
    {immediate: true, deep: true}
);
</script>

<style lang="scss">
.comment-body {
    .comment-title {
        display: flex;
        align-items: center;

        .title {
            font-size: 20px;
            color: #ff8800;
            .count {
                font-size: 14px;
                padding: 0px 10px;
            }
        }

        .tab {
            .tab-item {
                color: #9f9f9f;
                cursor: pointer;
            }

            .active {
                color: var(--link);
            }
        }
    }

    .comment-form-panel {
        margin-top: 20px;
    }
}

.comment-item {
    display: flex;
    padding-top: 15px;

    .comment-info {
        flex: 1;
        margin-left: 10px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 15px;

        .nick-name {
            .name {
                font-size: 14px;
                color: var(--text2);
                margin-right: 10px;
                cursor: pointer;
            }

            .tag-author {
                background: var(--pink);
                color: #fff;
                font-size: 12px;
                border-radius: 2px;
                padding: 0px 3px;
            }
        }

        .comment-content {
            margin-top: 5px;
            font-size: 15px;
            line-height: 22px;

            .tag {
                margin-right: 5px;
                font-size: 12px;
                border-radius: 3px;
                padding: 0px 5px;
            }

            .tag-topping {
                color: var(--pink);
                border: 1px solid var(--pink);
            }

            .no-audit {
                color: var(--text2);
                border: 1px solid var(--text2);
            }
        }

        .op-panel {
            display: flex;
            align-items: center;
            margin-top: 5px;
            font-size: 13px;
            color: var(--text2);

            .time {
                margin-right: 20px;
            }

            .iconfont {
                margin-right: 15px;
                font-size: 14px;
                color: var(--icon);
                cursor: pointer;
            }

            .iconfont::before {
                margin-right: 3px;
            }

            .active {
                color: var(--link);
            }
        }

        .comment-sub-list {
            margin-top: 10px;

            .comment-sub-item {
                display: flex;
                margin: 8px 0px;
                font-size: 14px;

                .comment-sub-info {
                    margin-left: 5px;

                    .nick-name {
                        .reply-name {
                            margin: 0px 5px;
                        }

                        .sub-content {
                            font-size: 15px;
                        }
                    }
                }
            }
        }

        .reply-info {
            margin-top: 15px;
        }
    }
}
.comment-body {
    .comment-title {
        display: flex;
        align-items: center;

        .title {
            font-size: 20px;
            color: #ff8800;
            .count {
                font-size: 14px;
                padding: 0px 10px;
            }
        }

        .tab {
            .tab-item {
                color: #9f9f9f;
                cursor: pointer;
            }

            .active {
                color: var(--link);
            }
        }
    }

    .comment-form-panel {
        margin-top: 20px;
    }
}

.comment-item {
    display: flex;
    padding-top: 15px;

    .comment-info {
        flex: 1;
        margin-left: 10px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 15px;

        .nick-name {
            .name {
                font-size: 14px;
                color: var(--text2);
                margin-right: 10px;
                cursor: pointer;
            }

            .tag-author {
                background: var(--pink);
                color: #fff;
                font-size: 12px;
                border-radius: 2px;
                padding: 0px 3px;
            }
        }

        .comment-content {
            margin-top: 5px;
            font-size: 15px;
            line-height: 22px;

            .tag {
                margin-right: 5px;
                font-size: 12px;
                border-radius: 3px;
                padding: 0px 5px;
            }

            .tag-topping {
                color: var(--pink);
                border: 1px solid var(--pink);
            }

            .no-audit {
                color: var(--text2);
                border: 1px solid var(--text2);
            }
        }

        .op-panel {
            display: flex;
            align-items: center;
            margin-top: 5px;
            font-size: 13px;
            color: var(--text2);

            .time {
                margin-right: 20px;
            }

            .iconfont {
                margin-right: 15px;
                font-size: 14px;
                color: var(--icon);
                cursor: pointer;
            }

            .iconfont::before {
                margin-right: 3px;
            }

            .active {
                color: var(--link);
            }
        }

        .comment-sub-list {
            margin-top: 10px;

            .comment-sub-item {
                display: flex;
                margin: 8px 0px;
                font-size: 14px;

                .comment-sub-info {
                    margin-left: 5px;

                    .nick-name {
                        .reply-name {
                            margin: 0px 5px;
                        }

                        .sub-content {
                            font-size: 15px;
                        }
                    }
                }
            }
        }

        .reply-info {
            margin-top: 15px;
        }
    }
}
.pagination {
    padding: 10px 0px 10px 10px;
}
.skeleton {
    padding: 15px;
}
</style>