/* eslint-disable */
module.exports={
  publicPath: process.env.NODE_ENV === 'production' ? './' : '/',
  outputDir: 'dist',
  indexPath: 'index.html',
  transpileDependencies: true,


  devServer:{
    proxy:{
      '/':{
        //target:'http://localhost/',
        target:'http://localhost/',
        //target:'http://47.94.251.130:88/',
        //target:'',
        changeOrigin:true,
        secure:false,
        pathRewrite:{
          '^/':''
        }
      }
    }
  },
  lintOnSave: false,
}