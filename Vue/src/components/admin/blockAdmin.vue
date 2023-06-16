
<template>
    <div class="content-container" direction="vertical" style="position:absolute;top:30px;left: 200px" :style="{ width: this.globalInfo.bodyWidth + 'px' }">

        <!-- list -->
        <div class="show" style="height: 98%;overflow: auto">

            <el-table
                highlight-current-row
                :data="this.$store.state.blocklist"
                tooltip-effect="dark"
                height="750"
                empty-text="没有数据"
                style="width: 100%">
                <el-table-column
                    label="主版块id"
                    width="200"
                    prop="pid">
                    <!--<template #default="scope">
                        <div style="text-align:center"><el-image :src="scope.row.img" style="width: 60px; height: 100px"/></div>
                        <div style="text-align:center">{{scope.row.name}}</div>
                    </template>-->

                </el-table-column>
                <el-table-column
                    label="主版块名"
                    width="200"
                    prop="pname">
                    <!--<template #default="scope">
                        <div style="text-align:center"><el-image :src="scope.row.img" style="width: 60px; height: 100px"/></div>
                        <div style="text-align:center">{{scope.row.name}}</div>
                    </template>-->

                </el-table-column>
                <el-table-column
                    label="子版块id"
                    width="200"
                    prop="id">
                </el-table-column>
                <el-table-column
                    label="子版块名"
                    prop="name">
                </el-table-column>

                <el-table-column
                    label="操作"
                    width="200">
                    <template #default="scope">
                        <el-button @click="openModal(scope.$index)" size="small" type="success">创建版块</el-button>
                        <el-button size="small" type="danger" @click="deleteUser(scope.$index)">删除</el-button>
                    </template>
                </el-table-column>

            </el-table>

        </div>
        <div>

        </div>
    </div>
</template>

<style>

.album_order{
    margin-left: 10px;
}
.album_order span{
    color: white;
}

.show::-webkit-scrollbar {

    display: none;

}

body {
    font-family: Verdana, sans-serif;
    margin: 0;
}

* {
    box-sizing: border-box;
}

.row > .column {
    padding: 0 8px;
}

.row:after {
    content: "";
    display: table;
    clear: both;
}

.column {
    float: left;
    width: 25%;
}
*{

    margin: 0;

    padding: 0;

}

html,body{

    width: 100%;

    height: 100%;

}
/* 弹窗背景 */
.modal {
    overflow: unset;
    display: none;
    position: absolute;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
    background-color: transparent;
}

/* 弹窗内容 */
.modal-content {
    position: relative;
    top: 0;
    background-color: rgb(0 0 0 / 46%);
    margin: auto;
    padding: 0;
    width: 90%;
}

/* 关闭按钮 */
.close {
    color: white;
    position: absolute;
    top: 10px;
    right: 25px;
    font-size: 35px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #999;
    text-decoration: none;
    cursor: pointer;
}

.mySlides {
    display: none;
}

.cursor {
    cursor: pointer
}



.prev {
    left: 0;
}

/* 定位下一页按钮到右侧 */
.next {
    right: 0;
    border-radius: 3px 0 0 3px;
}



/* 页数(1/3 etc) */
.numbertext {
    color: #f2f2f2;
    font-size: 12px;
    padding: 8px 12px;
    position: absolute;
    top: 0;
}

img {
    margin-bottom: -4px;
}

.caption-container {
    text-align: center;
    background-color: black;
    padding: 2px 16px;
    color: white;
}

.demo {
    opacity: 0.6;
}

.active,
.demo:hover {
    opacity: 1;
}

img.hover-shadow {
    transition: 0.3s
}

.hover-shadow:hover {
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)
}
</style>

<script>

import Storage from "@/tools/Storage";

export default {
    data() {
        return {
            boardlist:[],
        }
    },
    computed: {

    },
    // 组件挂载时获取数据
    mounted() {
        this.getboardlist();
    },

    methods: {
        getboardlist(){
            let phpaddr = "bbs/php/getblock.php";
            var xmlhttp;
            xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    //var x = JSON.parse(xmlhttp.responseText);
                    var x = JSON.parse(xmlhttp.responseText);
                    Storage.commit("Set", {
                        key:"blocklist",
                        pramas:x
                    });
                    console.log(Storage.state.blocklist);
                    // console.log(x);
                }
            }
            xmlhttp.open("GET", '/' + phpaddr, false);
            xmlhttp.send();
        },
        getuserlist(){
            let phpaddr = "bbs/php/getuser.php";
            var xmlhttp;
            xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    //var x = JSON.parse(xmlhttp.responseText);
                    var x = JSON.parse(xmlhttp.responseText);
                    Storage.commit("Set", {
                        key:"alluser_info",
                        pramas:x
                    });
                    console.log(Storage.state.alluser_info);
                    // console.log(x);
                }
            }
            xmlhttp.open("GET", '/' + phpaddr, false);
            xmlhttp.send();
        },
        getScrollTop(){
            return document.documentElement.scrollTop ||
                window.pageYOffset ||
                document.body.scrollTop;
        },
        openModal(index) {
            /*document.getElementById('myModal').style.display = "block";
            this.pic_index=index;
            this.nowphoto=this.firstphoto[this.albumList[index]][0];
            document.getElementsByClassName("mySlides")[0].style.display = "block";*/
            //this.$router.push({name: "userhome/order/"+index});
            Storage.commit("getName",Storage.state.user_info[index]['name']);
            this.$router.push({path: "albumadmin/"+Storage.state.user_info[index]['name']});


        },
        deleteUser(index){
            var id=Storage.state.alluser_info[index].id;
            this.$confirm(
                '确认删除该用户吗？（注意，该操作不可逆。）',
                '警告',
                {
                    confirmButtonText: '确认',
                    cancelButtonText: '取消',
                    type: 'warning',
                })
                .then(() => {
                    let phpaddr = "bbs/php/deleteuser.php";
                    var albumxmlhttp;
                    albumxmlhttp = new XMLHttpRequest();
                    albumxmlhttp.onreadystatechange = function () {
                        if (albumxmlhttp.readyState == 4 && albumxmlhttp.status == 200) {
                            //
                        }
                    }
                    albumxmlhttp.open("GET", '/' + phpaddr + '?id='+id, false);
                    albumxmlhttp.send();

                    this.getuserlist();
                })
                .catch(() => {
                    // catch error
                });


        },
    }
}
</script>