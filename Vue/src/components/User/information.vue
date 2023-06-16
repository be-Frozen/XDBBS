<template>
    <div
        class="container-body ucenter"
        :style="{ width: this.globalInfo.bodyWidth + 'px' }"
    >
        <div class="user-banner">
            <router-link to="/userhome" class="a-link">首页</router-link>
            <span class="iconfont icon-right"></span>
            <span>个人中心</span>
        </div>
        <div class="ucenter-panel">
            <div class="user-side">
                <!--头像信息-->
                <div class="avatar-panel">
                    <div
                        class="edit-btn a-link"
                        v-if="true"
                        @click="changeinformation"
                    >
                        修改资料
                    </div>
                    <div class="avatar-inner">
                        <img style="width:50px;height:50px;border-radius:50%;margin-right:0;"
                             :src="this.$store.state.user_info.head">
                        <h2 style="padding-left:10px;padding-top: 4%;" class="nick-name">{{ this.$store.state.user_info.name }}</h2>
                        <span style="padding-left:10px; padding-top: 6%; color: cornflowerblue" v-if="this.$store.state.user_info.sex === '男'" class="iconfont icon-man"></span>
                        <span style="padding-left:10px; padding-top: 6%; color: hotpink;" v-if="this.$store.state.user_info.sex === '女'" class="iconfont icon-woman"></span>
                    </div>
<!--                    <div class="nick-name">-->
<!--                        <span>{{ user_info.name }}</span>-->
<!--                        <span v-if="user_info.sex === '男'" class="iconfont icon-man"></span>-->
<!--                        <span v-if="user_info.sex === '女'" class="iconfont icon-woman"></span>-->
<!--                    </div>-->
                    <div class="desc">
                        {{ this.$store.state.user_info.sign }}
                    </div>
                </div>
                <!--扩展信息-->
                <div class="user-extend-panel">
                    <div class="info-item">
                        <div class="label iconfont icon-integral">等级</div>
                        <!--                        <div-->
                        <!--                            style="color: gold;"-->
                        <!--                            class="value a-link"-->
                        <!--                            v-if="true"-->
                        <!--                            @click="showIntegralRecord"-->
                        <!--                        >-->
                        <!--                            ????-->
                        <!--                        </div>-->
                        <!--                        <div v-else class="value">????</div>-->
                        <el-dropdown style="position: relative;top: 11px;height: 12px;">
                            <span style="color: gold" class="value">{{ this.$store.state.user_info.level }}</span>
<!--                            <template #dropdown>-->
<!--                                <el-dropdown-menu>-->
<!--                                    <el-dropdown-item style="color: black;">???/???</el-dropdown-item>-->
<!--                                </el-dropdown-menu>-->
<!--                            </template>-->
                        </el-dropdown>
                    </div>
                    <div class="info-item">
                        <div class="label iconfont icon-user">职位</div>
                        <div class="value">{{ this.$store.state.user_info.job }}</div>
                    </div>
                    <div class="info-item">
                        <div class="label iconfont icon-like">获赞</div>
                        <div class="value">{{ this.$store.state.user_info.likenum }}</div>
                    </div>
                    <div class="info-item">
                        <div class="label iconfont icon-post">发帖</div>
                        <div class="value">{{ this.$store.state.user_info.postnum }}</div>
                    </div>
                    <div class="info-item">
                        <div class="label iconfont icon-register">加入</div>
                        <div class="value">{{ this.$store.state.user_info.jointime }}</div>
                    </div>
                    <div class="info-item">
                        <div class="label iconfont icon-login">最后登录</div>
                        <div class="value">{{ this.$store.state.user_info.logintime }}</div>
                    </div>
                </div>
            </div>
            <div class="article-panel">
                <div class="article-list">
                    <PostList
                        :loading="loading"
                        :dataSource="this.$store.state.nowArticleListInfo"
                        @loadData="loadArticle"
                        noDataMsg="快去发表第一个帖子吧"
                    >
                    </PostList>
                </div>
            </div>
        </div>
        <div style="position:relative; left: 25%;" class="pagination">
            <el-pagination
                v-if="this.$store.state.nowArticleListInfo.pageTotal > 1"
                background
                :total="this.$store.state.nowArticleListInfo.totalCount"
                :current-page.sync="this.$store.state.nowArticleListInfo.pageNo"
                layout="prev, pager, next"
                @current-change="handlePageNoChange"
                style="text-align: right"
                :page-size="15"
            ></el-pagination>
        </div>

        <!--修改用户信息-->
        <Dialog
            :show="dialogConfig.show"
            :title="dialogConfig.title"
            :buttons="dialogConfig.buttons"
            width="450px"
            :showCancel="false"
            @close="closedialog"
        >
            <el-form :model="formData" ref="formDataRef" label-width="40px">
                <!--input输入-->
                <el-form-item label="昵称" prop="nickeName">
                    <el-input v-model=" formData.name "></el-input>
                </el-form-item>
                <el-form-item label="头像" prop="avatar">
                    <CoverUpload
                        v-model="formData.head"
                    ></CoverUpload>
                </el-form-item>
                <!-- 单选 -->
                <el-form-item label="性别" prop="sex">
                    <el-radio-group v-model="formData.sex">
                        <el-radio label="女">女</el-radio>
                        <el-radio label="男">男</el-radio>
                    </el-radio-group>
                </el-form-item>
                <!--textarea输入-->
                <el-form-item label="" prop="">
                    <el-input
                        clearable
                        placeholder="请输入简介"
                        type="textarea"
                        :rows="5"
                        :maxlength="100"
                        resize="none"
                        show-word-limit
                        v-model="formData.sign"
                    ></el-input>
                </el-form-item>
            </el-form>
        </Dialog>
    </div>

</template>



<script>
import Storage from "@/tools/Storage";
import {ElMessage} from "element-plus";
import PostList from "@/components/widget/PostList";
import Dialog from "@/components/widget/Dialog"
import CoverUpload from "@/components/widget/CoverUpload"
import {reactive} from "vue";

export default {

    components: {PostList, Dialog,CoverUpload},
    data() {
        return {
            user_info:Storage.state.user_info,
            name: Storage.state.userName,
            head: Storage.state.head,
            sex: Storage.state.sex,
            birthday: Storage.state.birthday,
            signature: Storage.state.signature,
            status: Storage.state.status,
            registertime: Storage.state.registertime,
            oldname: '',
            oldsex: '',
            oldbirthday: '',
            oldsignature: '',
            edit: false,
            headedit: false,
            pwdedit: false,
            oldpwd: "",
            newpwd: "",
            loading: false,
            articleListInfo: {
                list: [],
                pageTotal: 0,
                totalCount: 0,
                pageNo: 0
            },
            showchangeinformation:false,
            dialogConfig :{
                show: false,
                title: "编辑个人信息",
                buttons: [
                    {
                        type: "primary",
                        text: "确定",
                        click: (e) => {
                            this.updateUserInfoHandler();
                        },
                    },
                ],
            },
            formData:{
                id:Storage.state.user_info.id,
                name:Storage.state.user_info.name,
                head:Storage.state.user_info.head,
                sex:Storage.state.user_info.sex,
                sign:Storage.state.user_info.sign
            },
        }
    },
    mounted() {
        Storage.commit("Set", {
            key: "userid",
            pramas: 1
        });
        Storage.commit("Set", {
            key: "orderType",
            pramas: 1
        });
        Storage.commit("Set", {
            key: "activePboardId",
            pramas: '0'
        });
        Storage.commit("Set", {
            key: "activeBoardId",
            pramas: '0'
        });
        console.log(Storage.state.userid);
        console.log(Storage.state.orderType);
        this.getNowArticleListInfo();
        // this.articleListInfo={
        //     list: [],
        //     pageTotal: 0,
        //     totalCount: 0,
        //     pageNo: 0
        // }
        // var i=0,j=0;
        // for(i=0;i<Storage.state.articleListInfo.list.length;i++){
        //     if(Storage.state.articleListInfo.list[i].authorid===Storage.state.user_info.id){
        //         this.articleListInfo.list.push(Storage.state.articleListInfo.list[i]);
        //         console.log(this.articleListInfo.list);
        //         this.articleListInfo.list[j].page=Math.ceil((j+1)/15);
        //         j++;
        //     }
        // }
        // this.articleListInfo.pageNo=1;
        // this.articleListInfo.totalCount=this.articleListInfo.list.length;
        // this.articleListInfo.pageTotal=Math.ceil(this.articleListInfo.totalCount/15);
        // console.log(this.articleListInfo);
        // console.log(this.user_info);
        // Storage.commit("Set", {
        //     key: "nowArticleListInfo",
        //     pramas: this.articleListInfo
        // });
        // console.log("now");
        // console.log(Storage.state.nowArticleListInfo);
    },
    watch:{

    },
    methods: {
        closedialog(){
            this.dialogConfig.show = false;
            // document.body.style.left = '0px';
            // while(document.body.style.left === '4px'){
            //     document.body.style.left = '0px'
            // }
        },
        updateUserInfoHandler(){
            console.log(Storage.state.user_info.sex);
            console.log(this.formData);
            let phpaddr = "bbs/php/updateinformation.php";
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
            var params = JSON.stringify(this.formData);
            //xmlhttp.open("GET", '/' + phpaddr + '?name='+this.name+'&password=', false);
            xmlhttp.open("GET", '/' + phpaddr+"?params="+params, false);
            xmlhttp.send();
            Storage.commit("Set", {
                key: "user_info",
                pramas: params
            });
            console.log(Storage.state.user_info)
            this.dialogConfig.show = false
            this.getarticleListInfo();
            this.getNowArticleListInfo();
        },
        changeinformation(){
            // document.body.style.left = '4px';
            this.dialogConfig.show=true;
            console.log(this.showchangeinformation);
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
                console.log('Storage.state.activePboardId');
                console.log(Storage.state.activePboardId);
                console.log(Storage.state.articleListInfo.list[i].boardid)
                console.log('Storage.state.activeBoardId');
                console.log(Storage.state.activeBoardId);
                console.log(Storage.state.articleListInfo.list[i].subboardid)
                console.log("yes0");
                var fine = true;
                if (Storage.state.activePboardId !== '0') {
                    if (Storage.state.articleListInfo.list[i].boardid !== Storage.state.activePboardId) {
                        fine = false;
                        console.log("yes1");
                    }
                }
                if (Storage.state.activeBoardId !== '0') {
                    if (Storage.state.articleListInfo.list[i].subboardid !== Storage.state.activeBoardId) {
                        fine = false;
                        console.log("yes2");
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

            console.log('information')
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
                            console.log('information')
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
                            console.log('information')
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
        handlePageNoChange(pageNo) {
            this.$store.state.nowArticleListInfo.pageNo = pageNo;
            this.$emit("loadData");
        },
        changeInfo() {
            this.edit = true;
            this.oldname = this.name;
            this.oldsex = this.sex;
            this.oldbirthday = this.birthday;
            this.oldsignature = this.signature;
        },
        changePwd() {
            this.pwdedit = true;
        },
        pwdcancel() {
            this.pwdedit = false;
            this.oldpwd = "";
            this.newpwd = "";
        },
        pwdok() {
            if (this.oldpwd !== Storage.state.userPassword) {
                ElMessage({
                    message: '请输入正确的原密码',
                    type: 'error',
                    duration: 1500
                })
                return;
            }
            if (this.newpwd === "") {
                ElMessage({
                    message: '新密码不能为空',
                    type: 'error',
                    duration: 1500
                })
                return;
            }


            ElMessage({
                message: '密码修改成功，请重新登录',
                type: 'success',
                duration: 1500
            })


            let phpaddr = "album/php/setPassword.php";
            var userxmlhttp;
            userxmlhttp = new XMLHttpRequest();
            userxmlhttp.onreadystatechange = function () {
                if (userxmlhttp.readyState == 4 && userxmlhttp.status == 200) {
                    var x = userxmlhttp.responseText;
                    console.log(x);
                }
            }
            userxmlhttp.open("GET", '/' + phpaddr + '?user=' + Storage.state.userName + '&password=' + this.newpwd, false);

            userxmlhttp.send();
            location.reload();
            this.oldpwd = "";
            this.newpwd = "";
            this.pwdedit = false;
        },
        changeHead() {
            this.headedit = true;
        },
        headcancel() {
            this.headedit = false;
        },
        headok(i) {

            Storage.commit("getHead", i);
            var arr = [].concat(Storage.state.share_info), j, k;
            for (j = 0; j < arr.length; j++) {
                if (arr[j]['user'] === Storage.state.userName) {
                    arr[j]['head'] = i;
                }
                for (k = 0; k < arr[j]['commentnum']; k++) {
                    if (arr[j]['comment'][k]['user'] === Storage.state.userName) {
                        arr[j]['comment'][k]['head'] = i;
                    }
                }
            }

            Storage.commit('getShareInfo', arr);


            let phpaddr = "album/php/setHead.php";
            var userxmlhttp;
            userxmlhttp = new XMLHttpRequest();
            userxmlhttp.onreadystatechange = function () {
                if (userxmlhttp.readyState == 4 && userxmlhttp.status == 200) {
                    var x = userxmlhttp.responseText;
                    console.log(x);
                }
            }
            userxmlhttp.open("GET", '/' + phpaddr + '?name=' + Storage.state.userName + '&img=' + i, true);

            userxmlhttp.send();
            this.headedit = false;
        },
        cancel() {
            this.name = this.oldname;
            this.sex = this.oldsex;
            this.birthday = this.oldbirthday;
            this.signature = this.oldsignature;
            this.edit = false;
        },
        ok() {
            this.$confirm(
                '请确认修改信息无误，修改无法撤回。',
                '警告',
                {
                    confirmButtonText: '确认',
                    cancelButtonText: '取消',
                    type: 'warning',
                })
                .then(() => {
                    let phpaddr = "album/php/updateUser.php";
                    var userxmlhttp;
                    userxmlhttp = new XMLHttpRequest();
                    userxmlhttp.onreadystatechange = function () {
                        if (userxmlhttp.readyState == 4 && userxmlhttp.status == 200) {
                            //
                        }
                    }
                    userxmlhttp.open("GET", '/' + phpaddr + '?user=' + Storage.state.userName + '&name=' + this.name + '&sex=' + this.sex + '' +
                        '&birthday=' + this.birthday + '&signature=' + this.signature, false);

                    userxmlhttp.send();


                    Storage.commit('getUserInformation', {
                        head: this.head,
                        sex: this.sex,
                        birthday: this.birthday,
                        signature: this.signature,
                        status: this.status,
                        registertime: this.registertime,
                    })
                    Storage.commit('getUserName', this.name);
                    console.log(Storage.state.userName);
                    this.edit = false;
                })
                .catch(() => {
                    // catch error
                });
        },
    },
}
</script>

<style scoped lang="scss">
.ucenter {
    .user-banner {
        color: #9ba7b9;
        line-height: 35px;

        .icon-right {
            padding: 0px 5px;
        }
    }

    .ucenter-panel {
        background-color: transparent;
        display: flex;

        .user-side {
            width: 300px;
            margin-right: 10px;

            .avatar-panel {
                background-color: transparent;
                text-align: center;
                padding: 10px;

                .edit-btn {
                    text-align: right;
                    font-size: 14px;
                }

                .avatar-inner {
                    display: flex;
                    justify-content: left;
                }

                .nick-name {
                    color: #17cf4b;

                    .iconfont {
                        margin-left: 5px;
                        color: var(--link);
                    }
                }

                .desc {
                    padding-top: 4%;
                    color: white;
                    margin-top: 5px;
                    text-align: left;
                    font-size: 14px;
                }
            }

            .user-extend-panel {
                background-color: transparent;
                margin-top: 10px;
                padding: 10px;

                .info-item {
                    display: flex;
                    justify-content: space-between;
                    line-height: 30px;

                    .label {
                        color: #9499a0;
                        font-size: 13px;
                    }

                    .label::before {
                        font-size: 22px;
                        color: #888888;
                        padding-right: 5px;
                    }

                    .value {
                        color: #d4d4d4;
                        font-size: 13px;
                    }
                }
            }
        }

        .article-panel {
            overflow: auto;
            height: 750px;
            flex: 1;
            background: transparent;
            padding: 0px 10px 10px 10px;
        }

        ::-webkit-scrollbar-track{
            background-color: rgba(0, 0, 0, 0.1);
        }
        ::-webkit-scrollbar-thumb{
            background-color: rgba(0, 0, 0, 0.3);
        }

    }
}

//.info{
//    color: white;
//
//}
//.el-message-box{
//    --el-messagebox-width: 600px;
//    --el-messagebox-height: 400px;
//}
//.usermsg{
//    position: relative;
//    height: 50px !important;
//}
//.usermsg *,.headimg *{
//    float: left;
//}
//.headimg{
//    position: relative;
//    height: 120px !important;
//}

</style>