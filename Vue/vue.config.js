/* eslint-disable */
module.exports={
  publicPath: process.env.NODE_ENV === 'production' ? './' : '/',
  outputDir: 'dist',
  indexPath: 'index.html',
  transpileDependencies: true,


  devServer:{
    proxy:{
      '/':{
        target:'http://localhost/',
        // target:'http://118.178.88.16:88/',
        //target:'http://47.94.251.130:88/',
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