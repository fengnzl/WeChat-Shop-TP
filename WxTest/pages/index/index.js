//index.js
//获取应用实例
const app = getApp()
const baseUrl = 'http://wxtp.io/api/v1'
Page({
  data: {
   
  },
  //获取令牌
  getToken(){
    wx.login({
      success(res) {
        // console.log(res.code);
        // return
        if (res.code) {
          //发起网络请求
          wx.request({
            url: `${baseUrl}/Token/user`,
            method: 'POST',
            data: {
              code: res.code
            },
            success(res){
              console.log(res.data);
              // 将令牌存入缓存
              wx.setStorageSync('token', res.data.token);
            },
            fail(res){
              console.log(res.errMsg);
            }
          })
        } else {
          console.log('登录失败！' + res.errMsg)
        }
      }
    })
  },
  //获取super权限令牌
  getSuperToken(){
    wx.request({
      url: `${baseUrl}/Token/app`,
      method: 'POST',
      data: {
        ac: 'warcraft',
        se: '777'
      },
      success(res) {
        console.log(res.data);
        // 将令牌存入缓存
        wx.setStorageSync('super_token', res.data.token);
      },
      fail(res) {
        console.log(res.errMsg);
      }
    })
  },

  // 判断登陆是否失效
  checkSession(){
    wx.checkSession({
      success() {
        //session_key 未过期，并且在本生命周期一直有效
        console.log('succession success')
      },
      fail() {
        // session_key 已经失效，需要重新执行登录流程
        this.getToken()//重新登录
      }
    })
  },

  // 支付 务必在点击支付前，先点击申请令牌，确保令牌申请成功
  pay(){
    var token = wx.getStorageSync('token');
    var that = this;
    wx.request({
      url: baseUrl +'/order',
      header: {
        token: token
      },
      data: {
        products:[
          {
            product_id:1, count:1
          },
          {
            product_id: 2, count: 1
          }
        ]
      },
      method: 'POST',
      success(res){
        console.log(res.data)
        if(res.data.pass){
          wx.setStorageSync('order_id', res.data.order_id);
          that.getPreOrder(token,res.data.order_id);

        }else{
          console.log('创建订单失败');
        }
      },
      fail(res){
        console.log(res.data);
      }
    })
  },
  getPreOrder(token, orderID){
    if(token){
      wx.request({
        url: baseUrl +'/pay/pre_order',
        method: 'POST',
        header:{
          token:token
        },
        data:{
          id: orderID
        },
        success(res){
          var preData = res.data;
          console.log(preData);
          // wx.requestPayment({
          //   timeStamp: preData.timeStamp.toString(),
          //   nonceStr: preData.conceStr,
          //   package: preData.package,
          //   signType: preData.signType,
          //   paySign: preData.paySign,
          //   success(res){
          //     console.log(res.data);
          //   },
          //   fail(error){
          //     console.log(error);
          //   }
          // })
        }
      })
    }
  }
})
