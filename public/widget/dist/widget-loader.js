// Self invoking function
(function (w, d, link, script, p) {
  window.onload = function () {
    let sitelink = 'http://192.168.2.116:8000';
    // Load css
    var css = sitelink + "/widget/dist/assets/index.2481e91f.css";
    // Load js
    var js = sitelink + "/widget/dist/assets/index.6c53470d.js";

    link = d.createElement("link");
    link.rel = "stylesheet";
    link.href = css;
    // Appending stylesheet in the head tag
    d.getElementsByTagName("head")[0].appendChild(link);

    script = d.createElement("script");
    script.async = 1;
    script.src = js;
    p = d.getElementsByTagName("script")[0];
    p.parentNode.insertBefore(script, p);

    chat = d.createElement("div");
    chat.id = "chat-widget";
    d.getElementsByTagName("body")[0].appendChild(chat);
  };
})(window, document);
