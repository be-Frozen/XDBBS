<template>
    <div class="edit-post">
        <el-form
            ref="formDataRef"
            :model="formData"
            :rules="rules"
            class="post-panel"
            label-width="60px"
        >
            <div class="post-setting">
                <el-card :body-style="{ padding: '5px' }">
                    <template #header>
                        <span>设置</span>
                    </template>
                    <div class="setting-inner">
                        <!--input输入-->
                        <el-form-item label="标题" prop="title">
                            <el-input
                                v-model="formData.title"
                                :maxlength="150"
                                clearable
                                placeholder="提示信息"
                            ></el-input>
                        </el-form-item>
                        <el-form-item label="板块" prop="boardIds">
                            <el-cascader
                                v-model="formData.boardIds"
                                :options="formData.options"
                                :props="boardProps"
                                :style="{ width: '100%' }"
                                clearable
                                placeholder="请选择板块"
                            />
                        </el-form-item>
                        <el-form-item label="封面" prop="cover">
                            <CoverUpload v-model="formData.cover"></CoverUpload>
                        </el-form-item>

                        <!--textarea输入-->
                        <el-form-item label="摘要" prop="summary">
                            <el-input
                                v-model="formData.summary"
                                :maxlength="200"
                                :rows="5"
                                clearable
                                placeholder="提示信息"
                                resize="none"
                                show-word-limit
                                type="textarea"
                            ></el-input>
                        </el-form-item>
                        <el-form-item label="附件" prop="cover">
                            <AttachmentSelector
                                v-model="formData.attachment"
                            ></AttachmentSelector>
                        </el-form-item>
                        <!--input输入-->

                        <el-form-item label="" prop="">
                            <el-button
                                :style="{ width: '100%' }"
                                type="primary"
                                @click="postHandler"
                            >保存
                            </el-button
                            >
                        </el-form-item>
                    </div>
                </el-card>
            </div>
            <div class="post-editor">
                <el-card :body-style="{ padding: '5px' }">
                    <template #header>
                        <div class="post-editor-title">
                            <span>正文</span>
                        </div>
                    </template>
                    <!--input输入-->
                    <el-form-item label-width="0" prop="content">
                        <EditorHtml
                            v-if="true"
                            v-model="formData.content"
                            :height="700"
                        ></EditorHtml>
                    </el-form-item>
                </el-card>
            </div>

        </el-form>
    </div>
    <!--    <div class="content-container" direction="vertical">-->
    <!--        &lt;!&ndash; button &ndash;&gt;-->
    <!--        <div class="content-row" style="height: 10%">-->
    <!--            <el-container>-->

    <!--                &lt;!&ndash;:headers="upHeaders"&ndash;&gt;-->
<!--                    <el-upload-->
<!--                        style="margin-right: 10px;margin-left: 10px;"-->
<!--                        class="upload-demo"-->
<!--                        :action="myApi"-->
<!--                        accept="image/*"-->
<!--                        :on-preview="handlePreview"-->
<!--                        :on-remove="handleRemove"-->
<!--                        :before-remove="beforeRemove"-->
<!--                        multiple-->
<!--                        :show-file-list="false"-->
<!--                        :before-upload="beforeUpload"-->
<!--                        :on-exceed="handleExceed"-->
<!--                        :on-success="handleSuccess"-->
<!--                        :on-error="handleError">-->
<!--                        <el-button type="success">新增照片</el-button>-->
<!--                    </el-upload>-->
    <!--                <el-button type="danger" @click="backmain">返回主页</el-button>-->
    <!--                <el-radio-group @change="changeOrder" v-model="order" class="album_order">-->
    <!--                    <el-radio label="0" size="large">按上传时间降序</el-radio>-->
    <!--                    <el-radio label="1" size="large">按上传时间升序</el-radio>-->
    <!--                    <el-radio label="2" size="large">按名称降序</el-radio>-->
    <!--                    <el-radio label="3" size="large">按名称升序</el-radio>-->
    <!--                </el-radio-group>-->
    <!--            </el-container>-->
    <!--        </div>-->

    <!--        &lt;!&ndash; list &ndash;&gt;-->
    <!--        <div class="show" style="height: 88%;overflow: auto">-->
    <!--            &lt;!&ndash;<img style="width: 20%;" src="~@/assets/1.png">-->
    <!--            <img style="width:35px;height:35px;border-radius:50%;margin:auto;margin-right:0;"-->
    <!--                 src="~@/assets/logo.jpg"/>&ndash;&gt;-->
    <!--            <div v-for="(item,index) in photo_list" class="row">-->
    <!--                <div v-for="(pic,picindex) in item" class="column">-->
    <!--                    &lt;!&ndash;<img decoding="async" :src="'http://localhost/album/原神/'+albumname+'/'+pic" style="width:100%"-->
    <!--                         @click="openModal(index*4+picindex);" class="hover-shadow cursor">&ndash;&gt;-->
    <!--                    <img decoding="async" :src="'/'+'album/照片/'+username+'/'+$route.params.type+'/'+pic['p_address']" style="width:100%"-->
    <!--                         @click="openModal(index*4+picindex);" class="hover-shadow cursor">-->
    <!--                    <h3>{{photoname[index*4+picindex]}}<button @click="deletePhoto(index*4+picindex)" class="deletephoto">×</button></h3>-->
    <!--                    <div style="height: 20px"></div>-->
    <!--                </div>-->
    <!--            </div>-->

    <!--            <div id="myModal" class="modal">-->
    <!--                <div style="position: absolute;width: 100%;height: 100%;background-color: rgb(0 0 0 / 46%);"-->
    <!--                     @click="closeModal()"></div>-->
    <!--                <div class="modal-content" style="height: 100%;width: 100%">-->
    <!--                    <div  @mousewheel="rollImg" class="mySlides" style="width: 100%; height: 100%;">-->
    <!--                        <div class="numbertext">{{pic_index+1}}/{{photolist.length}}</div>-->
    <!--                        &lt;!&ndash;<div class="close" @click="closeModal">×</div>&ndash;&gt;-->
    <!--                        <div class="close" @click="closeModal">×</div>-->
    <!--                        <img ref="imgDiv" class="img" @mousedown="move" decoding="async" :src="'/'+'album/照片/'+username+'/'+$route.params.type+'/'+nowphoto['p_address']">-->
    <!--                    </div>-->


    <!--                    <a class="prev" @click="PrevPic()">&#10094;</a>-->
    <!--                    <a class="next" @click="NextPic()">&#10095;</a>-->

    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
</template>

<style lang="scss">
.edit-post {
    .post-panel {
        display: flex;

        .el-card__header {
            padding: 10px;
        }

        .post-editor {
            flex: 1;

            .post-editor-title {
                display: flex;
                justify-content: space-between;

                .change-editor-type {
                    .iconfont {
                        cursor: pointer;
                        color: var(--link);
                        font-size: 14px;
                    }
                }
            }
        }

        .post-setting {
            margin-left: 10px;
            width: 450px;

            .setting-inner {
                max-height: calc(100vh - 120px);
                overflow: auto;

                .el-form-item {
                    align-items: flex-start;
                }
            }

            .tips {
                color: #797979;
                font-size: 13px;
            }
        }
    }
}

</style>

<script>

import Storage from "@/tools/Storage";
import {ElMessage} from "element-plus";
import EditorHtml from "@/components/widget/EditorHtml"
import CoverUpload from "@/components/widget/CoverUpload"
import AttachmentSelector from "@/components/widget/AttachmentSelector"
import Verify from "@/tools/Verify";
import {ref} from "vue";

const formDataRef = ref()
export default {
    components: {EditorHtml, CoverUpload, AttachmentSelector},
    data() {
        return {
            nowphoto: "",
            //:'/'+'album/temp',
            // myApi: '/' + 'album/php/uploadPhoto.php',
            firstphoto: Storage.state.firstphoto,
            firstphotoarr: [],
            albumList: Storage.state.album_info,
            album_list: [],
            goodsData: [],
            pic_index: "",
            order: '0',
            //photo_list:[],
            update: 1,
            first: 'false',
            //album:'',
            formData: {
                title: "",
                boardIds: [],
                options: [
                    {
                        value: '部门',
                        label: '部门',
                        children: [
                            {
                                value: '行政部',
                                label: '行政部',
                            },
                            {
                                value: '财务部',
                                label: '财务部',
                            },
                            {
                                value: '技术部',
                                label: '技术部',
                            }],
                    },
                    {
                        value: '社区',
                        label: '社区',
                        children: [
                            {
                                value: '寻物墙',
                                label: '寻物墙',
                            },
                            {
                                value: '表白墙',
                                label: '表白墙',
                            },
                            {
                                value: '交友墙',
                                label: '交友墙',
                            },
                            {
                                value: '游戏墙',
                                label: '游戏墙',
                            }],
                    }
                ],
                cover:"",
                summary:"",
                content:"",
                attachment:null,
            },
            rules: {
                title:
                    [{required: true, message: "请输入标题"}],
                boardIds:
                    [{required: true, message: "请选择板块"}],
                content:
                    [{required: true, message: "请输入正文"}],
                integral:
                    [
                        {required: true, message: "请输入下载所需积分"},
                        {validator: Verify.number, message: "积分只能是数字"},
                    ],
            }
            ,
            boardProps: "",

        }
    }
    ,
    computed: {

    }
    ,
    watch: {
        //'$route.params.type' (to, from) {

    }
    ,
// 组件挂载时获取数据
    mounted() {
        var i,j;
        this.formData.options=[];
        for(i of Storage.state.boardList){
            console.log(i);
            var x={};
            x.value=i.name;
            x.label=i.name;
            x.children=[];
            for(j of i.subboard){
                var y={};
                console.log(j);
                y.value=j.name;
                y.label=j.name;
                console.log("y:");
                console.log(y);
                x.children.push(y);
                console.log("children");
                console.log(x.children);
            }
            this.formData.options.push(x);
        }
        console.log(this.formData.options);

    }
    ,
    beforeUnmount() {
    }
    ,
// 路由更新时刷新数据
    beforeRouteUpdate(to) {
        var x = document.getElementsByClassName("show");
        //console.log(x[0].style);
        x[0].scrollTop = 0;
    }
    ,

    beforeCreate() {


        /*//let phpaddr="index.php";
        axios
            //.post('http://localhost/album/php/outdir.php')
            .get('/'+phpaddr)
            .then(response => (console.log(response.data)))
            .catch(function (error) { // 请求失败处理
                console.log(error);
            });

        console.log(this.goodsData);
        console.log("???");*/
    }
    ,

    methods: {
        show(){
            console.log(this.formData);
        },
        postHandler(){
            if(this.formData.title===""){
                ElMessage({
                    message: '标题不能为空',
                    type: 'error',
                    duration: 1500
                });
                return;
            }
            if(this.formData.boardIds.length===0){
                ElMessage({
                    message: '版块不能为空',
                    type: 'error',
                    duration: 1500
                });
                return;
            }
            this.formData.author=Storage.state.user_info.id;


            let phpaddr = "bbs/php/sendpost.php";
            var xmlhttp;
            var path = "";
            xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    var x = xmlhttp.responseText;
                    path=x;
                    console.log(params);
                }
            }
            // this.formData.content=escape(this.formData.content);
            // console.log(this.formData.content);
            var cloneObj = function (obj) {
                var newObj = {};
                if (obj instanceof Array) {
                    newObj = [];
                }
                for (var key in obj) {
                    var val = obj[key];
                    newObj[key] = typeof val === 'object' ? cloneObj(val) : val;
                }
                return newObj;
            };

            var content=escape(this.formData.content);
            var formData=cloneObj(this.formData);
            formData.content=null;
            console.log("old");
            console.log(this.formData);
            console.log("new");
            console.log(formData);
            var params = JSON.stringify(formData);

            console.log(content);
            //xmlhttp.open("GET", '/' + phpaddr + '?name='+this.name+'&password=', false);
            xmlhttp.open("GET", '/' + phpaddr+'?params='+params+'&content='+content, false);
            xmlhttp.send();
            Storage.state.user_info.postnum=parseInt(Storage.state.user_info.postnum)+1;
            this.$router.push(`/userhome/user/post/${path}`);
            // Storage.commit("Set", {
            //     key: "articleDetail",
            //     pramas: params
            // });
            // this.articleDetail=Storage.state.articleDetail;
            // console.log(this.articleDetail);
        },


        // 删除文件钩子
        handleRemove(file, fileList) {
            console.log(file, fileList)
        }
        ,
        // 已上传文件钩子
        handlePreview(file) {
            console.log(file)
        }
        ,
        // 文件上传前的钩子(这里可以对文件上传时的后缀进行限制)
        beforeUpload(file) {
            /*var FileExt = file.name.replace(/.+\./, '')
            if (['jpg', 'JPG'].indexOf(FileExt) === -1) {
                this.$message({
                    type: 'warning',
                    message: '请上传后缀名为 json 的文件！',
                })
                return false
            }*/
        }
        ,
        // 上传文件个数限制钩子
        handleExceed(files, fileList) {
            this.$message.warning(`当前限制选择 1 个文件，本次选择了 ${files.length} 个文件，共选择了 ${files.length + fileList.length} 个文件`)
        }
        ,
        // 删除文件前的钩子
        beforeRemove(file, fileList) {
            return this.$confirm(`确定移除 ${file.name}？`);
        }
        ,
        // 上传失败钩子
        handleError(err, file, fileList) {
            this.$message.error(err.errormsg)
        }
        ,
        // 上传成功钩子(在这里可以处理上传成功后端返回的数据)
        handleSuccess(req, file, fileList) {
            console.log(req)
            var name = req;
            let phpaddr = "album/php/addPhoto.php";
            var photoxmlhttp;
            photoxmlhttp = new XMLHttpRequest();
            photoxmlhttp.album = this.$route.params.type;
            photoxmlhttp.onreadystatechange = function () {
                if (photoxmlhttp.readyState == 4 && photoxmlhttp.status == 200) {
                    var x = photoxmlhttp.responseText;
                    if (x === '同名图片已存在') {
                        ElMessage({
                            message: '同名图片已存在',
                            type: 'error',
                            duration: 1500
                        })
                    } else {
                        x = JSON.parse(photoxmlhttp.responseText);

                        console.log(x);

                        var arr = Storage.state.photolist;
                        console.log(arr);
                        arr[this.album].push(x['photo']);
                        console.log(arr[this.album]);
                        var newarr = Storage.state.album_info;

                        for (var i = 0; i < newarr.length; i++) {
                            if (newarr[i]['a_name'] === this.album) {
                                console.log(newarr[i]['a_size']);
                                console.log(Storage.state.album_info[i]['a_size']);
                                newarr[i]['a_size'] = parseInt(newarr[i]['a_size']) + 1;
                                newarr[i]['a_edittime'] = x['time'];
                                break;
                            }

                        }
                        //Storage.commit("getAlbumInfo", x);
                        //console.log(Storage.state.album_info);
                        //console.log(Storage.state.album_info);
                        //console.log(Storage.state.album_info[0]);
                        //console.log(Storage.state.album_info[0]['a_name']);
                    }
                }
            }
            photoxmlhttp.open("GET", '/' + phpaddr + '?owner=' + Storage.state.userName + '&album=' + this.$route.params.type + '&address=' + name, false);

            photoxmlhttp.send();
            //console.log(req.data.path);
            //console.log(file);

        }
        ,


        upload(param) {
            console.log(param.file);
            const formData = new FormData()
            formData.append('file', param.file)
            const url = '/' + 'album/temp'
            this.$http.post(url, formData).then((res) => {
                console.log('ok');
                console.log(res)
            })
        }
        ,

        uploadimg(res, file, files) {
            console.log(file);
        }
    }
}
</script>