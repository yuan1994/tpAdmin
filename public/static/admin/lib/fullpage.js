fml.define("blog:common/fullscreen", [], function(a, b) {
    var c = function(a) {
        function b(b) {
            this.options = a.extend(!0, {
                element: a("body"),
                callback: a.noop,
                noSafari: !1
            }, b),
            this.options.noSafari && this._browser.safari && (this.fullscreenEnabled = !1),
                this._initEvents()
        }
        return a.extend(b.prototype, {
            toggleFullscreen: function() {
                if (!this.fullscreenEnabled)
                    return;
                if (this.fullscreen())
                    this.exitFullscreen();
                else {
                    var a = this.options.element.addClass("fullscreen");
                    this.requestFullscreen(a.get(0))
                }
            },
            _initEvents: function() {
                if (!this.fullscreenEnabled)
                    return;
                var b = this
                    , c = a(document);
                c.unbind("fullscreenchange webkitfullscreenchange mozfullscreenchange").bind("fullscreenchange webkitfullscreenchange mozfullscreenchange", function(a) {
                    b.fullscreen() || b.options.element.removeClass("fullscreen"),
                        b.options.callback.call(b, b.fullscreen())
                })
            },
            _browser: function() {
                var a = window.navigator.userAgent.toUpperCase()
                    , b = {};
                return b.chrome = /CHROME/.test(a),
                    b.safari = !b.chrome && /SAFARI/.test(a),
                    b
            }(),
            fullscreen: function() {
                return document.fullscreen || document.webkitIsFullScreen || document.mozFullScreen || !1
            },
            fullscreenElement: function() {
                return document.fullscreenElement || document.webkitCurrentFullScreenElement || document.mozFullScreenElement || null
            },
            fullscreenEnabled: function() {
                var a = document.documentElement;
                return "requestFullscreen"in a || "webkitRequestFullScreen"in a || "mozRequestFullScreen"in a && document.mozFullScreenEnabled || !1
            }(),
            requestFullscreen: function(a) {
                a.requestFullscreen ? a.requestFullscreen() : a.webkitRequestFullScreen ? this._browser.chrome ? a.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT) : a.webkitRequestFullScreen() : a.mozRequestFullScreen && a.mozRequestFullScreen()
            },
            exitFullscreen: function() {
                document.exitFullscreen ? document.exitFullscreen() : document.webkitCancelFullScreen ? document.webkitCancelFullScreen() : document.mozCancelFullScreen && document.mozCancelFullScreen()
            }
        }),
            b
    }(jQuery);
    return c
});