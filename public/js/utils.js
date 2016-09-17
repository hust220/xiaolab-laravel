function merge_objects() {
    var obj = {};
    for (var el in arguments) {
        for (var par in arguments[el]) {
            obj[par] = arguments[el][par];
        }
    }
    return obj;
}

function browser_versions() {
    var Sys = {};
    var ua = navigator.userAgent.toLowerCase();
    var s;
    (s = ua.match(/rv:([\d.]+)\) like gecko/)) ? Sys.ie = s[1] :
    (s = ua.match(/msie ([\d.]+)/)) ? Sys.ie = s[1] :
    (s = ua.match(/firefox\/([\d.]+)/)) ? Sys.firefox = s[1] :
    (s = ua.match(/chrome\/([\d.]+)/)) ? Sys.chrome = s[1] :
    (s = ua.match(/opera.([\d.]+)/)) ? Sys.opera = s[1] :
    (s = ua.match(/version\/([\d.]+).*safari/)) ? Sys.safari = s[1] : 0;
    return Sys;
}

function handle_ie() {
    var Sys = browser_versions();
    if ("ie" in Sys && parseFloat(Sys.ie) < 9) {
        document.body.innerHTML = "<p>The version of your IE browser is too old (" + Sys.ie + "), it should be not less than 9.0!</p> \
            <p>Please use another browser or a higher version IE.</p>";
    }
}

function cnzz() {
    var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");
    document.write(
        unescape(
            "%3Cspan id='cnzz_stat_icon_1256809603'%3E%3C/span%3E%3Cscript src='" + 
            cnzz_protocol + 
            "s11.cnzz.com/z_stat.php%3Fid%3D1256809603%26show%3Dpic' type='text/javascript'%3E%3C/script%3E"
        )
    );
}

function jian_ajax() {
    var method = arguments[0].toLowerCase();
    var url = arguments[1];
    var ok_callback;
    var error_callback;
    var data;
    if (method == "get") {
        ok_callback = arguments[2];
        error_callback = arguments[3];
    } else if (method == "post") {
        data = arguments[2];
        ok_callback = arguments[3];
        error_callback = arguments[4];
    } else {
        return;
    }
    var xhr = new XMLHttpRequest();
    xhr.open(method, url, true );
    xhr.setRequestHeader('X-Requested-With','XMLHttpRequest');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                ok_callback(xhr.responseText);
            } else {
                error_callback(xhr.responseText);
            }
        }
    }
    if (method == "post") {
        xhr.send(data);
    } else {
        xhr.send();
    }
}

function jian_newtab(url) {
    var a = $("<a href='"+url+"' target='_blank'>jian</a>").get(0);    
    var e = document.createEvent('MouseEvents');  
    e.initEvent('click', true, true);  
    a.dispatchEvent(e);  
    console.log('event has been changed');
}

function jian_interval_when(interval, done, foo) {
    if (done()) {
        foo();
    } {
        window.setTimeOut(function(){
            jian_interval(interval, done, foo);
        }, interval);
    }
}

