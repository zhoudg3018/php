$(document).ready(function () {
       //layuiAdmin 自定义的表名  sessionData关闭页面即删除token
       //如果前端本地表中的token为空，则表示未登录
    if (layui.sessionData('layuiAdmin').token == undefined) {
        location.href = './user/login.html'; //登录界面
    }
});
 
 
var lastTime = new Date().getTime();
var currentTime = new Date().getTime();
var timeOut = 15 * 60 * 1000; //设置超时时间： 1分
 
window.onload = function () {
    window.sessionStorage.setItem("lastTime", new Date().getTime());
    window.document.onmousedown = function () {
        window.sessionStorage.removeItem("lastTime");
        window.sessionStorage.setItem("lastTime", new Date().getTime());
    }
};
/*document.getElementById("iframeMain").contentWindow.onload= function () {
    document.getElementById("iframeMain").contentWindow.document.onmousedown = function () {
    window.sessionStorage.removeItem("lastTime");
    window.sessionStorage.setItem("lastTime", new Date().getTime());
    }
}
 */
function checkTimeout() {
    currentTime = new Date().getTime(); //更新当前时间
    lastTime = window.sessionStorage.getItem("lastTime");
    // console.log(currentTime - lastTime);
    // console.log(timeOut);
    if (currentTime - lastTime > timeOut) { //判断是否超时
        // console.log("超时");
        location.href = './user/login.html'; //登录页面
    }
}
 
/* 定时器 间隔30秒检测是否长时间未操作页面 */
window.setInterval(checkTimeout, 30000);