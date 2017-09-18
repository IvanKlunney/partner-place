var page = require('webpage').create(),
	args = require('system').args,
	o 	 = JSON.parse(args[1]);

page.viewportSize = o;
page.open(args[2], function(s){
		var _do = function () {
				page.render('test/screenshots/page_'+page.viewportSize.width+'x'+page.viewportSize.height+'.png');
		}
		// Check spinner
		// var d = page.evaluate(function(){
		// 	var elem = document.getElementsByClassName('preloader-wrapper')[0];
		// 		elem.className += ' active';

		// 	return elem.className;
		// });

		if(true) {
			_do();
		}

		phantom.exit();
});

