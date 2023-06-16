<template>
    <div
        v-if="!loading && dataSource.list != null && dataSource.list.length === 0"
    >
        <NoData :msg="noDataMsg"></NoData>
    </div>
    <div class="skeleton" v-if="loading">
        <el-skeleton :row="2" animated></el-skeleton>
    </div>
    <div v-for="item in dataSource.list" v-if="!loading">
        <!--系统消息-->
        <div class="message-item" v-if="true">
            <div class="message-content">
                <span style="color: red;" v-html="'???????????????????????????'"></span>
                <span class="create-time">2000-00-00</span>
            </div>
        </div>
        <!--回复我的-->
        <div class="message-item" v-if="true">
            <img style="width:50px;height:50px;border-radius:50%;margin-right:0;"
                 :src="'/'+'bbs/头像/默认/八重神子 头像.png'">
            <div class="message-content">
                <div>
                    <router-link style="color:#17cf4b; " class="a-link" :to="`/userhome/user/information`"
                    >@???</router-link
                    >
                    对我的文章【<router-link style="color:#0496e3; "
                    class="a-link"
                    :to="`/userhome/user/post/0`"
                >????</router-link
                >】发表了评论
                    <span class="create-time">2000-00-00</span>
                </div>
                <div class="reply-content" v-html="'????'"></div>
            </div>
        </div>

        <!--赞了我的评论-->
        <div class="message-item" v-if="true">
            <img style="width:50px;height:50px;border-radius:50%;margin-right:0;"
                 :src="'/'+'bbs/头像/默认/钟离 头像.png'">
            <div class="message-content">
                <div>
                    <router-link style="color:#17cf4b; " class="a-link" :to="`/userhome/user/information`"
                    >@???</router-link
                    >
                    在文章【<router-link style="color:#0496e3; "
                    class="a-link"
                    :to="`/userhome/user/post/0`"
                >???</router-link
                >】中赞了我的评论
                    <span class="create-time">2000-00-00</span>
                </div>
                <div style="color: white;" class="reply-content" v-html="'???'"></div>
            </div>
        </div>

        <!--赞了我的文章-->
        <div class="message-item" v-if="true">
            <img style="width:50px;height:50px;border-radius:50%;margin-right:0;"
                 :src="'/'+'bbs/头像/默认/雷电将军 头像.png'">
            <div class="message-content">
                <div>
                    <router-link style="color:#17cf4b; " class="a-link" :to="`/userhome/user/information`"
                    >@???</router-link
                    >
                    赞了我的文章【<router-link style="color:#0496e3; "
                    class="a-link"
                    :to="`/userhome/user/post/0`"
                >???</router-link
                >】
                    <span class="create-time">2000-00-00</span>
                </div>
            </div>
        </div>
        <!--下载了我的附件-->
        <div class="message-item" v-if="true">
            <img style="width:50px;height:50px;border-radius:50%;margin-right:0;"
                 :src="'/'+'bbs/头像/默认/凝光 头像 纱中幽兰.png'">
            <div class="message-content">
                <div>
                    <router-link style="color:#17cf4b; " class="a-link" :to="`/userhome/user/information`"
                    >@???</router-link
                    >
                    在文章【<router-link style="color:#0496e3; "
                    class="a-link"
                    :to="`/userhome/user/post/0`"
                >???</router-link
                >】中下载了我的附件
                    <span class="create-time">2000-00-00</span>
                </div>
            </div>
        </div>
    </div>
    <div class="pagination">
        <el-pagination
            v-if="dataSource.pageTotal > 1"
            background
            :total="dataSource.totalCount"
            :current-page.sync="dataSource.pageNo"
            layout="prev, pager, next"
            @current-change="handlePageNoChange"
            style="text-align: right"
            :page-size="15"
        ></el-pagination>
    </div>
</template>

<script scope>
import Cover from "@/components/widget/Cover"
import NoData from "@/components/widget/NoData"



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
        handlePageNoChange(pageNo){
            this.dataSource.pageNo = pageNo;
            this.$emit("loadData")
        },
    },
    // const emit = defineEmits(["loadData"]);
    // const handlePageNoChange = (pageNo) => {
    //     props.dataSource.pageNo = pageNo;
    //     emit("loadData");
    // };
}
</script>

<style lang="scss" scoped>
.message-center {
    .user-banner {
        color: #9ba7b9;
        line-height: 35px;
        .icon-right {
            padding: 0px 5px;
        }
    }
    .message-panel {
        background: transparent;
        padding: 10px;
        margin-top: 5px;
        .tab-list {
            position: relative;
            .tab-item {
                position: relative;
                padding: 0px 10px;
                .count-tag {
                    position: absolute;
                    right: -10px;
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
            .go-ucenter {
                position: absolute;
                top: 5px;
                right: 10px;
                font-size: 14px;
            }
        }
        .message-list {

            .message-item {
                display: flex;
                justify-content: flex-start;
                align-items: center;
                font-size: 14px;
                border-bottom: 1px solid #ddd;
                padding: 10px;
                .message-content {
                    color:orange;
                    margin-left: 5px;
                    .create-time {
                        color: #9ba7b9;
                        margin-left: 10px;
                    }

                    .reply-content {
                        border-left: 2px solid #11a8f4;
                        padding-left: 5px;
                        margin-top: 5px;
                    }
                }
            }
        }
    }
}

.pagination {
    padding: 10px 0px 10px 10px;
}

.skeleton {
    padding: 15px;
}
.article-item {
    padding: 5px 15px 0 15px;
    .article-item-inner {
        border-bottom: 1px solid #ddd;
        padding: 10px 0px;
        display: flex;
        .article-body {
            flex: 1;
            .user-info {
                display: flex;
                align-items: center;
                font-size: 14px;
                color: #4e5969;
                .link-info {
                    margin-left: 5px;
                    color: #494949;
                    text-decoration: none;
                }
                .link-info:hover {
                    color: var(--link);
                }
                .post-time {
                    font-size: 13px;
                    color: #9a9a9a;
                }
            }
            .title {
                font-weight: bold;
                text-decoration: none;
                color: #4a4a4a;
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
                color: #86909c;
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
    background: #fffbfb;
}
</style>