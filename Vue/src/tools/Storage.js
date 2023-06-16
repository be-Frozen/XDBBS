import { createStore } from 'vuex'

const Store = createStore({
    state () {
        return {
            user_info:null,
            boardList:null,
            activePboardId:'0',
            activeBoardId:'0',
            orderType:0,
            articleListInfo: {
                list: [],
                pageTotal: 1,
                totalCount: 0,
                pageNo: 1,
            },
            articleDetail:{
                forumArticle:{
                    id:"",
                    file:0,
                    boardid:0,
                    boardname:"",
                    commentcount: 0,
                    content: "",
                    cover: "",
                    likecount: 37,
                    authorname:"",
                    subboardid:0,
                    subboardname:"",
                    postTime: "2000-00-00 12:00:00",
                    readcount:0,
                    summary:"",
                    title:"",
                    authorid:1,
                    havelike: "0"
                },
                havelike:false
            },
            fileDetail:{
                id:0,
                name:"",
                size:0,
                address:"",
                downloadcount:0,
            },
            commentListInfo: {
                list: []
            },
            nowArticleListInfo: {
                list: [],
                pageTotal: 1,
                totalCount: 0,
                pageNo: 1,
            },
            userid:0,
            alluser_info:[],
            blocklist:[],
        }
    },
    getters: {
    },
    mutations: {
        Set(state,{key,pramas=null}) {
            this.state[key]=pramas
            // console.log(pramas)
            // console.log(this.state)
        },
    }
})

export default Store;