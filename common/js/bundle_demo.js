var app = document.createElement('script');
var xapi = document.createElement('script');
var jsxgraph = document.createElement('script');
var jsxgrapg_css = document.createElement('link');

app.src = '/data/ccl/common/V2/js/app.js';
xapi.src = '/data/ccl/common/V2/js/xapiwrapper.min.js';
jsxgraph.src = 'http://jsxgraph.uni-bayreuth.de/distrib/jsxgraphcore.js';

jsxgrapg_css.rel  = 'stylesheet';
jsxgrapg_css.type = 'text/css';
jsxgrapg_css.href = '/data/ccl/common/V2/css/jsxgraph.css';
jsxgrapg_css.media = 'all';

document.head.appendChild(app);
document.head.appendChild(xapi);
document.head.appendChild(jsxgraph);
document.head.appendChild(jsxgrapg_css);