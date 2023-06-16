import { createRouter, createWebHashHistory } from 'vue-router'
import Login from '../components/login/Login.vue'
import AdminHome from '../components/home/AdminHome.vue'
import UserHome from "../components/home/UserHome.vue";
import Order from '../components/order/Order.vue'
import Store from '../tools/Storage'
import Main from "../components/User/Main";
import Share from "../components/share/Share";
import Information from "@/components/User/information";
import userAdmin from "@/components/admin/userAdmin";
import blockAdmin from "@/components/admin/blockAdmin";
import postAdmin from "@/components/admin/postAdmin";
import shareAdmin from "@/components/admin/shareAdmin";
import Post from "@/components/order/Post"
import VueCookies from "@/main";
import Storage from "@/tools/Storage";
const Router = createRouter({
    history:createWebHashHistory('/'),
    //mode:"hash",
    //base: process.env.BASE_URL,
    routes:[
        {
            path:'/login',
            component:Login,
            name:"login"
        },
        {
            path:'/test',
            component:UserHome,
            name:"test"
        },
        {
            path:'/userhome',
            component:UserHome,
            name:"userhome",
            children:[
                {
                    path:'user/main/home',
                    component: Main,
                    name:"Main"
                },
                {
                    path:'user/block/:boardid/:subboardid',
                    component: Main,
                    name:"Block"
                },
                {
                    path:'user/share',
                    component:Share,
                    name:"Share"
                },
                {
                    path:'user/order',
                    component:Order,
                    name:"order"
                },
                {
                    path:'user/information',
                    component:Information,
                    name:"information"
                },
                {
                    path:'user/post/:postid',
                    component: Post,
                    name: "post"
                },
            ],
            redirect:'/userhome/user/block/0/0'
        },
        {
            path:'/adminhome',
            component:AdminHome,
            name:"adminhome",
            children:[
                {
                    path:'admin/useradmin',
                    component:userAdmin,
                    name:"useradmin"
                },
                {
                    path:'admin/blockadmin',
                    component: blockAdmin,
                    name:"admin/blockadmin"
                },
                {
                    path:'admin/postadmin',
                    component: postAdmin,
                    name:"admin/postadmin"
                },
            ],
            redirect:'/adminhome/admin/useradmin'
        },

    ]
})

Router.beforeEach((from) => {

    // const cookieLoginInfo = VueCookies.get("loginInfo");
    // if (cookieLoginInfo) {
    //     console.log(cookieLoginInfo);
    //
    //     return;
    // }
    if (Store.state.user_info!==null ||Store.state.user_info!==null&& Store.state.user_info.ok) {
        return true;
    } else {
        console.log("back to login");
        Storage.commit("Set", {
            key:"user_info",
            pramas:[]
        });
        console.log(Store.state.user_info);
        return {name: 'login'}
    }
    
})

export default Router;