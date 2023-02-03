(function (global) {
    global.$_Chat_AccountKey = "{{$propertyKey}}";
    global.$_Chat_WidgetId = "{{$widgetId}}";
    global.$_Chat_Unstable = false;
    (function (w) {
        function l() {
            let basePath = "{{url('/')}}";

            var files = [
                basePath+"/widget/dist/assets/index.2481e91f.css",
                basePath+"/widget/dist/assets/index.6c53470d.js",
            ];

            var s0 = document.getElementsByTagName("script")[0];

            for (var i = 0; i < files.length; i++) {
                if(files[i].substring(fileName.lastIndexOf('.') + 1) == 'js'){
                    var s1 = document.createElement("script");
                    s1.charset = "UTF-8";
                    s1.setAttribute("crossorigin", "*");
                    s1.src = files[i];
                }
                if(files[i].substring(fileName.lastIndexOf('.') + 1) == 'css'){
                    var s1 = document.createElement("link");
                    s1.setAttribute("rel", "stylesheet");
                    s1.href = files[i];
                }
                s0.parentNode.insertBefore(s1, s0);
            }
            chat = document.createElement("div");
            chat.id = "chat-widget";
            document.getElementsByTagName("body")[0].appendChild(chat);
        }
        if (document.readyState === "complete") {
            l();
        } else if (w.attachEvent) {
            w.attachEvent("onload", l);
        } else {
            w.addEventListener("load", l, false);
        }
    })(window);
})(window);