<template>
    <div class="editor-html">
        <Toolbar
            :defaultConfig="toolbarConfig"
            :editor="editorRef"
            :mode="mode"

            style="border-bottom: 1px solid #ccc"
        />
        <Editor
            :defaultConfig="editorConfig"
            :mode="mode"
            :model-value="modelValue"

            :style="{ height: height + 'px', 'overflow-y': 'hidden' }"
            @onChange="onChange"
            @onCreated="handleCreated"
        />
    </div>
</template>

<script setup>
import "@wangeditor/editor/dist/css/style.css"; // 引入 css
import {getCurrentInstance, onBeforeUnmount, ref, shallowRef} from "vue";
import {Editor, Toolbar} from "@wangeditor/editor-for-vue";
import {useStore} from "vuex";
import {ElMessage} from "element-plus";

const store = useStore();
const {proxy} = getCurrentInstance();

const props = defineProps({
    modelValue: {
        type: String,
        default: "",
    },
    height: {
        type: Number,
        default: 500,
    },


});
var uploadsuccess=false;

const mode = ref("default");
const editorRef = shallowRef();

const toolbarConfig = {
    excludeKeys: [
        "emotion", // 排除菜单组，写菜单组 key 的值即可
        "insertLink",
        "insertTable",
    ],
};

const editorConfig = {
    placeholder: "请输入内容...",
    excludeKeys: ["emotion"],
    MENU_CONF: {
        uploadImage: {
            maxFileSize: 3000 * 1024 * 1024,
            server: "/bbs/php/upload.php",
            fieldName: "file",

            // uploadImgServer: "/bbs/php",
            // uploadImgUrl:'/http',

            onBeforeUpload(file,filelist){
                if(file[Object.keys(file)[0]].data.size>3 * 1024 * 1024)
                {
                    ElMessage({
                        message: '大于3MB的图片请在附件上传',
                        type: 'error',
                        duration: 1500
                    })
                    uploadsuccess=false;
                    return;
                }
                // console.log(filelist);
                // console.log(file);
                // console.log(file[Object.keys(file)[0]].data);
                var d = new Date().getTime();
                if (window.performance && typeof window.performance.now === "function") {
                    d += performance.now(); //use high-precision timer if available
                }
                var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                    var r = (d + Math.random() * 16) % 16 | 0;    // d是随机种子
                    d = Math.floor(d / 16);
                    return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
                });
                console.log(file);
                var suffix = file[Object.keys(file)[0]].data.name.substring(file[Object.keys(file)[0]].data.name.lastIndexOf("."));
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
                uploadsuccess=true;
            },

            customInsert(responseData, insertFn) {
                if(uploadsuccess === false)
                {
                    return;
                }
                uploadsuccess=false
                var name = "";
                let phpaddr = "bbs/php/uploadfile.php";
                var xmlhttp;
                xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        var x = xmlhttp.responseText;
                        name=x;
                        console.log(x);

                    }
                }

                xmlhttp.open("GET", '/' + phpaddr+'?address='+name, false);
                xmlhttp.send();

                insertFn(
                    "/bbs/文件/"+name,
                    "",
                    ""
                );
            },
        },
        uploadVideo: {
            maxFileSize: 1000 * 1024 * 1024,
            server: "/bbs/php/upload.php",
            fieldName: "file",
            // uploadImgServer: "/bbs/php",
            // uploadImgUrl:'/http',

            onBeforeUpload(file,filelist){
                if(file[Object.keys(file)[0]].data.size>10 * 1024 * 1024)
                {
                    ElMessage({
                        message: '大于10MB的视频请在附件上传',
                        type: 'error',
                        duration: 1500
                    })
                    uploadsuccess=false;
                    return;
                }
                // console.log(filelist);
                // console.log(file);
                // console.log(file[Object.keys(file)[0]].data);
                var d = new Date().getTime();
                if (window.performance && typeof window.performance.now === "function") {
                    d += performance.now(); //use high-precision timer if available
                }
                var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                    var r = (d + Math.random() * 16) % 16 | 0;    // d是随机种子
                    d = Math.floor(d / 16);
                    return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
                });
                console.log(file);
                var suffix = file[Object.keys(file)[0]].data.name.substring(file[Object.keys(file)[0]].data.name.lastIndexOf("."));
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
                uploadsuccess=true;
            },

            customInsert(responseData, insertFn) {
                if(uploadsuccess === false)
                {
                    return;
                }
                uploadsuccess=false
                var name = "";
                let phpaddr = "bbs/php/uploadfile.php";
                var xmlhttp;
                xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        var x = xmlhttp.responseText;
                        name=x;
                        console.log(x);

                    }
                }

                xmlhttp.open("GET", '/' + phpaddr+'?address='+name, false);
                xmlhttp.send();

                insertFn(
                    "/bbs/文件/"+name,
                    "",
                    ""
                );
            },
        },
    },
};

const emit = defineEmits();
const onChange = (editor) => {
    emit("update:modelValue", editor.getHtml());
};

// 组件销毁时，也及时销毁编辑器
onBeforeUnmount(() => {
    const editor = editorRef.value;
    if (editor == null) return;
    editor.destroy();
});

const handleCreated = (editor) => {
    editorRef.value = editor; // 记录 editor 实例，重要！
};
</script>

<style lang="scss">
.editor-html {
    border: 1px solid #ddd;
    z-index: 1001;
}
</style>