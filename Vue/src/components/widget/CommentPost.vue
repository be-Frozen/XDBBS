<template>
    <div class="post-comment-panel">
        <img style="width:50px;height:50px;border-radius:50%;margin-right:0;"
             :src="Storage.state.user_info.head">
        <div class="comment-form">
            <el-form :model="formData" :rules="rules" ref="formDataRef">
                <!--textarea输入-->
                <el-form-item prop="content">
                    <el-input
                        clearable
                        :placeholder="placeholderInfo"
                        type="textarea"
                        :maxlength="800"
                        resize="none"
                        show-word-limit
                        v-model="formData.content"
                    ></el-input>
                    <div class="insert-img" v-if="showInsertImg">
                        <div class="pre-img" v-if="commentImg">
                            <CommentImage :src="commentImg"></CommentImage>
                            <span
                                style="color: red;"
                                class="iconfont icon-remove"
                                @click="removeCommentImg"
                            ></span>
                        </div>
                        <el-upload
                            v-else
                            name="file"
                            :show-file-list="false"
                            accept=".png,.PNG,.jpg,.JPG,.jpeg,.JPEG,.gif,.GIF,.bmp,.BMP"
                            :multiple="false"
                            :before-upload="beforeUpload"
                            :on-exceed="handleExceed"
                            :on-success="handleSuccess"
                            :on-error="handleError"
                            :on-preview="handlePreview"
                            :on-remove="handleRemove"
                            :before-remove="beforeRemove"
                            :action="'/bbs/php/upload.php'"
                        >
                            <span style="color: white;" class="iconfont icon-image"></span>
                        </el-upload>
                    </div>
                </el-form-item>
            </el-form>
        </div>
        <div class="send-btn" @click="postCommentDo">发表</div>
    </div>
</template>

<script setup>
import CommentImage from "./CommentImage.vue";
import {ref, reactive, getCurrentInstance} from "vue";
import {useRouter, useRoute} from "vue-router";
import Storage from "@/tools/Storage";
import Router from "@/tools/Router"
import {ElMessage} from "element-plus";

const {proxy} = getCurrentInstance();

const api = {
    postComment: "/comment/postComment",
};

const props = defineProps({
    articleId: {
        type: String,
    },
    pCommentId: {
        type: Number,
    },
    replyUserId: {
        type: String,
    },
    avatarWidth: {
        type: Number,
    },
    userId: {
        type: String,
    },
    showInsertImg: {
        type: Boolean,
    },
    placeholderInfo: {
        type: String,
        default: "快来发表你的评论吧",
    },
});



//form信息

const checkPostComment = (rule, value, callback) => {
    if (value == null && formData.value.image == null) {
        callback(new Error(rule.message));
    } else {
        callback();
    }
};

const formData = ref({});
const formDataRef = ref();

var comment={
    userid:Storage.state.user_info.id,
    postid:Storage.state.articleDetail.forumArticle.id,
    content:"",
    username:Storage.state.user_info.name,
    userhead:Storage.state.user_info.head,
    imgpath:"",
};
var imgpath='';
const rules = {
    content: [
        {required: true, message: "请输入评论内容", validator: checkPostComment}
    ],
};

const emit = defineEmits(["postCommentFinish"]);
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
const postCommentDo = () => {
    if(formData.value.content===""||formData.value.content===null||formData.value.content===undefined){
        ElMessage({
            message: '评论不能为空',
            type: 'error',
            duration: 1500
        })
        return
    }
    comment={
        userid:Storage.state.user_info.id,
        postid:Storage.state.articleDetail.forumArticle.id,
        content:formData.value.content,
        username:Storage.state.user_info.name,
        userhead:Storage.state.user_info.head,
        imgpath:imgpath,
    };
    console.log(comment);
    comment.content=formData.value.content;
    console.log(Storage.state.articleDetail.forumArticle)
    // return;
    console.log(comment);
    var p=JSON.stringify(comment);
    let phpaddr = "bbs/php/sendcomment.php";
    var xmlhttp;
    var path = "";
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var x = xmlhttp.responseText;
            p=x;
            console.log(p);
        }
    }
    // // this.formData.content=escape(this.formData.content);
    // // console.log(this.formData.content);
    // var cloneObj = function (obj) {
    //     var newObj = {};
    //     if (obj instanceof Array) {
    //         newObj = [];
    //     }
    //     for (var key in obj) {
    //         var val = obj[key];
    //         newObj[key] = typeof val === 'object' ? cloneObj(val) : val;
    //     }
    //     return newObj;
    // };

    // var content=escape(this.formData.content);
    // var formData=cloneObj(this.formData);
    // formData.content=null;
    // console.log("old");
    // console.log(this.formData);
    // console.log("new");
    // console.log(formData);
    // var params = JSON.stringify(formData);
    //
    // console.log(content);
    //xmlhttp.open("GET", '/' + phpaddr + '?name='+this.name+'&password=', false);
    xmlhttp.open("GET", '/' + phpaddr+'?params='+p, false);
    xmlhttp.send();
    Router.push(`/userhome/user/post/${comment.postid}`);
    update();

    imgpath="";
    comment={
        userid:Storage.state.user_info.id,
        postid:Storage.state.articleDetail.forumArticle.id,
        content:'',
        username:Storage.state.user_info.name,
        userhead:Storage.state.user_info.head,
        imgpath:'',
    };
    formDataRef.value.resetFields();
    removeCommentImg();
    return;
    // formDataRef.value.validate(async (valid) => {
    //     if (!valid) {
    //         return;
    //     }
    //     let params = Object.assign({}, formData.value);
    //     params.articleId = props.articleId;
    //     params.pCommentId = props.pCommentId;
    //     params.replyUserId = props.replyUserId;
    //     let result = await proxy.Request({
    //         url: api.postComment,
    //         params,
    //     });
    //     if (!result) {
    //         return;
    //     }
    //     proxy.Message.success("评论发表成功");
    //     formDataRef.value.resetFields();
    //     removeCommentImg();
    //     emit("postCommentFinish", result.data);
    // });
};

//选择图片
const commentImg = ref(null);
const selectImg = (file) => {
    file = file.file;
    let img = new FileReader();
    img.readAsDataURL(file);
    img.onload = ({target}) => {
        let imgData = target.result;
        commentImg.value = imgData;
        formData.value.image = file;
    };
};

const removeCommentImg = () => {
    commentImg.value = null;
    formData.value.image = null;
};

// 删除文件钩子
const handleRemove=(file, fileList)=> {
    console.log(file, fileList)
}
// 已上传文件钩子
const handlePreview=(file)=> {
    console.log(file)
}
// 文件上传前的钩子(这里可以对文件上传时的后缀进行限制)

const beforeUpload=(file)=>{
    var d = new Date().getTime();
    if (window.performance && typeof window.performance.now === "function") {
        d += performance.now(); //use high-precision timer if available
    }
    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = (d + Math.random() * 16) % 16 | 0;    // d是随机种子
        d = Math.floor(d / 16);
        return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
    });
    var suffix = file.name.substring(file.name.lastIndexOf("."));
    let phpaddr = "bbs/php/beforeupload.php";
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var x=xmlhttp.responseText;
            console.log(x);
        }
    }
    var params=uuid+suffix
    //xmlhttp.open("GET", '/' + phpaddr + '?name='+this.name+'&password=', false);
    xmlhttp.open("GET", '/' + phpaddr + '?params='+params, false);
    xmlhttp.send();
}

// 上传文件个数限制钩子
const handleExceed=(files, fileList)=> {
    this.$message.warning(`当前限制选择 1 个文件，本次选择了 ${files.length} 个文件，共选择了 ${files.length + fileList.length} 个文件`)
}

// 删除文件前的钩子
const beforeRemove=(file, fileList)=> {
    // return this.$confirm(`确定移除 ？`);
}

// 上传失败钩子
const handleError=(err, file, fileList)=> {
    this.$message.error(err.errormsg)
}

// 上传成功钩子(在这里可以处理上传成功后端返回的数据)
const handleSuccess=(req, file, fileList)=> {
    var name = req;
    let phpaddr = "bbs/php/uploadfile.php";
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var x = xmlhttp.responseText;
            console.log(x);

        }
    }

    xmlhttp.open("GET", '/' + phpaddr+'?address='+name, false);
    xmlhttp.send();
    console.log(file);
    file = file.raw;
    let img = new FileReader();
    img.readAsDataURL(file);
    img.onload = ({target}) => {
        let imgData = target.result;
        commentImg.value = imgData;
        formData.value.image = file;
    };
    imgpath='/bbs/文件/'+name;

}
</script>

<style lang="scss">
.input .el-textarea__inner {
    background-color: transparent;
    color: white;
    width: 50%;
    margin-left: 15px;
}
.comment-form {
    .el-textarea__inner,.el-input__count {
        border-color: white;
        background-color: transparent;
        color: white;
        padding-top: 0 !important;

        padding-right: 0 !important;
        padding-bottom: 0 !important;
    }
    i{
        margin-right: 20px;
    }
}

.post-comment-panel {
    display: flex;
    align-items: top;

    .comment-form {
        flex: 1;
        margin: 0px 10px;

        .el-textarea__inner {
            height: 60px;
        }

        .insert-img {
            line-height: normal;

            .iconfont {
                margin-top: 3px;
                font-size: 20px;
                color: #3f3f3f;
            }

            .pre-img {
                margin-top: 10px;
                position: relative;

                .iconfont {
                    cursor: pointer;
                    position: absolute;
                    top: -10px;
                    right: -10px;
                    color: rgb(121, 121, 121);
                }
            }
        }
    }

    .send-btn {
        cursor: pointer;
        width: 60px;
        height: 60px;
        background: var(--link);
        color: #fff;
        text-align: center;
        line-height: 60px;
        border-radius: 5px;
    }
}
</style>