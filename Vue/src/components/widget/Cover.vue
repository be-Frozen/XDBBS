<template>
  <div class="cover" :style="{ width: width + 'px', height: width + 'px' }">
    <el-image
      :style="{ width: width + 'px', height: width + 'px' }"
      fit="scale-down"
      loading="lazy"
      :src="cover ?  cover : localCover"
    >
    </el-image>
  </div>
</template>

<script setup>
import { getCurrentInstance } from "vue";
const { proxy } = getCurrentInstance();

const localCover = new URL("@/assets/kong.jpg", import.meta.url).href;

const props = defineProps({
  cover: {
    type: String,
  },
  width: {
    type: Number,
    default: 60,
  },
});
</script>

<style lang="scss">
.cover {
  background: #ddd;
  border-radius: 5px;
  overflow: hidden;
}
</style>