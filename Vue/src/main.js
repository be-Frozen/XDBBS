import {createApp, inject} from 'vue'
import  {createStore} from 'vuex'
import App from './App.vue'
import ElementPlus from 'element-plus'
import '@/assets/base.scss'
import '@/assets/icon/iconfont.css'
import * as ElementPlusIconsVue from '@element-plus/icons-vue'
import VueAxios from "vue-axios";
import axios from "axios";
import VueCookies from "vue-cookies"
import {createRouter,createWebHashHistory} from 'vue-router'
import Router  from "@/tools/Router";
import Store from "@/tools/Storage";
let instance = createApp(App)
instance.use(ElementPlus)
instance.use(VueAxios,axios)
instance.use(Router)
instance.use(Store)
instance.use(VueCookies)
instance.config.globalProperties.VueCookies=VueCookies

instance.config.globalProperties.globalInfo = {
    bodyWidth: 1300,
}
instance.mount("#Application")
for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
    instance.component(key, component)
}

instance.config.warnHandler = () => null  // 清除控制台的vue warn
export default VueCookies;