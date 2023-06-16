<template>
  <div class="attachment-selector">
    <template v-if="modelValue">
      <div class="file-show">
        <div class="file-name" :title="modelValue.name">
          {{ modelValue.name }}
        </div>
        <div class="iconfont icon-del" @click="delFile"></div>
      </div>
    </template>
    <el-upload
      v-else
      name="file"
      :show-file-list="false"
      accept=".zip,.rar"
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
      <el-button type="primary">选择文件</el-button>
    </el-upload>
  </div>
</template>

<script setup>
import { getCurrentInstance } from "vue";
const { proxy } = getCurrentInstance();

const props = defineProps({
  modelValue: {
    type: Object,
    default: null,
  },
});

const emit = defineEmits();
const selectFile = async (file) => {
  emit("update:modelValue", file.file);
};

const delFile = () => {
  emit("update:modelValue", null);
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
    return this.$confirm(`确定移除 ${file.name}？`);
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

    file = file.raw;
    var filereturn={
        name:file.name,
        size:file.size,
        address:'/bbs/文件/'+name
    };
    // var filereturn=
    // emit("update:modelValue", file);

    emit("update:modelValue", filereturn);
}

</script>

<style lang="scss">
.attachment-selector {
  width: 100%;
  .file-show {
    display: flex;
    justify-content: space-around;
    .file-name {
      flex: 1;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      color: var(--link);
    }
    .icon-del {
      width: 20px;
      color: var(--link);
      cursor: pointer;
      margin-left: 10px;
    }
  }
}
</style>