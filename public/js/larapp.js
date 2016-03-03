app.url = function(path) {
    return this.baseUrl + '/' + path;
};

app.asset = function(path) {
    return this.publicUrl + '/' + path;
};

app.bdTrack = function(buttonName, pageName) {
    if (window._hmt) {
        _hmt.push(['_trackEvent', buttonName, pageName + ' - ' + buttonName]);
    }
};

app.googleTrack = function(source, step, data) {
    if (window.ga) {
        ga('send', 'event', source, step, data);
    }
};

app.pages = {
    '/auth/login': '留手机页面',
    '/rules': '活动规则页面',
    '/prize': '活动首页'
};

app.setupClickTrack = function() {
    var url = window.location.href.split('?')[0];
    var path = url.replace(app.baseUrl, '');
    var defaultPageName = app.pages[path] ? app.pages[path] : path;

    $('a, button, input[type=submit]').click(function() {
        var $this = $(this);
        var btnName = $this.attr('btnName');
        if (!btnName) {
            if ($this.prop('tagName') == 'INPUT') {
                btnName = $.trim($this.attr('value'));
            }
            else {
                btnName = $.trim($this.text());
            }
        }

        var pageName = $this.attr('pageName');
        if (!pageName) {
            pageName = defaultPageName;
        }

        app.bdTrack(btnName, pageName);
        app.googleTrack(pageName, btnName);
    });
};
