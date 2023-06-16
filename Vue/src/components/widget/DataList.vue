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
        <div class="comment-item">
            <img style="width:30px;height:30px;border-radius:50%;margin-right:0;"
                 :src="'/'+'bbs/头像/默认/派蒙.jpg'">
            <div class="comment-info">
                <div class="nick-name">
        <span class="name" @click="gotoUcenter(commentData.userId)">???</span>
                    <span class="tag-author" v-if="true"
                    >作者</span
                    >
                </div>
                <div class="comment-content">
                    <div>
          <span class="tag tag-topping" v-if="true"
          >置顶</span
          >
                        <span class="tag no-audit" v-if="true"
                        >待审核</span
                        >
                        <span v-html="'玩原神玩的'"></span>
                    </div>
                    <CommentImage
                        :style="{ 'margin-top': '10px' }"
                        v-if="true"
                        :src="'/'+'bbs/头像/默认/妮露 头像.png'"
                        :imgList="['/'+'bbs/头像/默认/妮露 头像.png']"
                    ></CommentImage>
                </div>

                <div class="op-panel">
                    <div class="time">
                        <span>2000-00-00</span>
                    </div>
                    <div
                        :class="[
            'iconfont icon-good',
            1 == 1 ? 'active' : '',
          ]"
                        @click="doLike(commentData)"
                    >
                        {{ 0 > 0 ? commentData.goodCount : "点赞" }}
                    </div>
                    <div
                        class="iconfont icon-comment"
                        @click="showReplyPanel(commentData, 0)"
                    >
                        回复
                    </div>
                    <el-dropdown v-if="true">
                        <div class="iconfont icon-more"></div>
                        <template #dropdown>
                            <el-dropdown-menu>
                                <el-dropdown-item @click="opTop(commentData)">
                                    {{ 0 == 0 ? "设为置顶" : "取消置顶" }}
                                </el-dropdown-item>
                            </el-dropdown-menu>
                        </template>
                    </el-dropdown>
                </div>
                <div class="comment-sub-list" v-if="true">
                    <div class="comment-sub-item" v-for="sub in commentData.children">
                        <img style="width:30px;height:30px;border-radius:50%;margin-right:0;"
                             :src="'/'+'bbs/头像/默认/胡桃 头像.png'">
                        <div class="comment-sub-info">
                            <div class="nick-name">
              <span class="name" @click="gotoUcenter(sub.userId)">???</span>
                                <span class="tag-author" v-if="true"
                                >作者</span
                                >
                                <span class="reply-name">回复</span>
                                <span @click="gotoUcenter(sub.replyUserId)" class="a-link"
                                >@???</span
                                >
                                <span>：</span>
                                <span class="sub-content" v-html="'?????'"></span>
                            </div>
                            <div class="op-panel">
                                <div class="time">
                                    <span>2000-00-00</span>
                                </div>
                                <div
                                    :class="[
                  'iconfont icon-good',
                  1 == 1 ? 'active' : '',
                ]"
                                    @click="doLike(sub)"
                                >
                                    {{ 0 > 0 ? sub.goodCount : "点赞" }}
                                </div>
                                <div
                                    class="iconfont icon-comment"
                                    @click="showReplyPanel(sub, 1)"
                                >
                                    回复
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="reply-info" v-if="true">
                    <CommentPost
                        :placeholderInfo=[]
                        :articleId="0"
                        :pCommentId="0"
                        :replyUserId="0"
                        :avatarWidth="30"
                        :userId="0"
                        :showInsertImg="false"
                        @postCommentFinish="0"
                    ></CommentPost>
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
import CommentImage from "@/components/widget/CommentImage"
import CommentPost from "@/components/widget/CommentPost"

export default {
    components:{Cover,NoData,CommentImage,CommentPost},
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
.comment-body {
    .comment-title {
        display: flex;
        align-items: center;
        .title {
            font-size: 20px;
            .count {
                font-size: 14px;
                padding: 0px 10px;
            }
        }
        .tab {
            .tab-item {
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
</style>