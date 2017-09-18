'use strict';

var $$, globals;

$$ = (function() {
		// IE <= 11 
	if(!window.Promise) {
		if(window.location.pathname !== '/index.php') {
			alert('Ваш браузер не поддерживает или имеет частичную поддержку текущей спецификации JS. Пожалуйста, смените браузер.');				
		}
		window.location += '?destroy';
		return;
	}

	function $$ (selector, start, i) {
		var root   = start || document,
			idx    = i || 0,
			entity = selector.substr(1, selector.length - 1); 
				switch (selector.charAt(0)) {
					case '#':
						return root.getElementById(entity);
					case '.':
						return root.getElementsByClassName(entity)[idx];
					default: 
						return root.getElementsByTagName(selector)[idx];
				}
	}

	$$.isMobile = function() {
		return (document.children[0].offsetWidth >= 993) ? false : true;
	};

	$$.m = function() {
		return ($$.isMobile()) ? '#main-navbar-mobile' : '#main-navbar';
	}();

	$$($$.m).addEventListener('click', function(e){
				initClientRequest(e, clientRequest);
	}, false);

	return $$;
})();

globals = {
	state: {
		running: false,
		counter: 0,
		currTpl: 'doc',
		prevTpl: 'home',
		prevMenuElem: ''
	},
	'$$': $$
};


//@param tpl string
globals.c = function(tpl) {
	var prev, curr, tmp;
	tmp  = tpl.split('/');
	tmp  = tmp[tmp.length - 1].split('.')[0];

	curr = this.$$('#' + tmp);
	
	if(this.state.prevTpl) {
		prev = this.$$('#' + this.state.currTpl);
	}

	this.state.prevTpl = this.state.currTpl;
	this.state.currTpl = tmp;
	
		return {
			curr: curr,
			prev: prev
		}

};

globals.getCurrentState = function() {
		return (this.state.running) ? 'running' : 'stopped';
};

globals.setCurrentState =  function(v) {
		this.state.running = v;
};

//@param selector string
globals.collection = function(s) {
	return document.getElementsByClassName(s);
};

globals.each = function(s, f) {
	this.elems = this.collection(s);
	this.elems.forEach = [].forEach;
	this.elems.forEach(f);
};

globals.getCurrentElemID = function() {
	return ($$.isMobile()) ? '#li-clients-mobile' : '#li-clients';
};

function initClientRequest(e, cr) {
	var target = e.target,
		main   = $$('#main-content');
	
	if(target.classList.contains('active1') || target.nodeName !== 'LI') return;

	if(target.classList.contains('dpd')) {
		openDropDown(target);
		return;
	}

	cr(main, target, blockSwitcher);	
}

function clientRequest(container, tgt, bs) {
	globals.each('opaque', function(v){
		v.style.opacity = 0;
	})

	globals.each('preloader-wrapper', function(v){
		if(!v.classList.contains('active')) v.classList.add('active');
	});

	var promise = new Promise(function(res, rej){
		var client, login = ($$.isMobile()) ? $$('#login-mobile') : $$('#login');
			try {
				client = new window.XMLHttpRequest;
			} catch(e) {
				rej('Не удалось инициализировать объект. Попробуйте позже.');
				return;
			}

			if(globals.getCurrentState() === 'running') {
				rej('Запрос еще выполняется. Подождите.');
				return;
			}	
		
			globals.setCurrentState(true);
			
			client.open('POST', '/controller.php', true);
			client.setRequestHeader('Content-type', 'application/json');
			client.setRequestHeader('X-Requested-With', 'xmlhttprequest');
	
			client.onabort = client.onerror = function(e){
				rej(e.message);
				return;
			}

			client.onreadystatechange = function(e) {
				if(client.readyState !== client.DONE) return;
				
				if(client.status === 200)  {
					res(client.response);
				} else {
					rej('Код ошибки: ' + client.status);
				}
			}
	
			client.send(JSON.stringify(buildPayload(tgt, login)));
	});	
	
	promise
		.then(runUp, onError)
		.then(bs.bind(null, tgt, 
						function(t, fn) {
							removeClassName(t) && addClassName(t) && fn(t) && resolveElemByRoute(t);
						},
						function (t) {
							return (t.textContent) ? $$('#page-header').innerHTML = t.textContent : false;
						}));
}

function buildPayload(tgt, el) {
	var route = tgt.getAttribute('data-route');
		return {
			"query": route,
			"ident": {
				"User": { "login": el.textContent }
			}
		}

}

function runUp(d) {
	return (document.URL.indexOf('redirect=true') !== -1) ? document.location = '/main.php' : onResolve(d);
}

function onResolve(data){
	var docfrag = document.createDocumentFragment(),
		container = ($$.isMobile()) ? $$('#dd-mobile') : $$('#dd'),
		i = 0,
		o,
		pattern,
		li, 
		jn;

	try {
		jn = JSON.parse(data);
	} catch(e) {
		console.log(data);
		console.log(e.message);
		onError(e.message);
		return;
	}

	if(jn.is_ajax && jn.timeout) {
		document.location = '/';
	}
	
	if(jn.result.hasError){
		onError(jn.result.ErrorDescription);
		return;
	}

	o = globals.c(jn.result.Template);

	if(globals.state.prevTpl !== globals.state.currTpl) {
		o.curr.classList.remove('hide-me');
			if(o.prev) {
				o.prev.classList.add('hide-me');
			}
	}
	
	if(typeof jn.result.Clients === 'object') {
		pattern = /(\s|^)[П,З,А,О]{1}[О,А]{1,2}/g;
			jn.result.Clients.forEach(function(v){
				li = document.createElement('LI');
				li.classList.add('waves-effect');
				li.setAttribute('data-route', container.getAttribute('data-route') + '/' + v.Code);
				li.innerHTML = (pattern.test(v.Name)) ? v.Name.replace(pattern, '') : v.Name;
				docfrag.appendChild(li);
			})
	} else {
		onError(new Error('Не удалось получить список клиентов. Пожалуйста, попробуйте позже.'));
	}

	if(globals.state.counter === 0) {
		container.appendChild(docfrag);
		$$('#man_ident').textContent = jn.result.Manager.Name;
		$$('#man_ident').parentNode.setAttribute('href', 'mailto:' + jn.result.Manager.Contacts[2].Value);	
		$$('#phone').innerHTML = jn.result.Manager.Contacts[1].Value;
	}

	return finalize();
}

function onError(e){
	Materialize.toast(e, 5000);
	finalize();
}

function finalize() {
	globals.each('preloader-wrapper', function(v){
		v.classList.remove('active');
	});

	globals.each('opaque', function(v){
		v.style.opacity = 1;
		v.classList.remove('hide-me')
	})
	
	globals.setCurrentState(false);
	++globals.state.counter;

}

function blockSwitcher(target, changeMenuElemCallback, changeHeaderCallback) {
	globals.each('inner-menu-desc', function(v){
		if(!v.classList.contains('hide-me')) {
			v.classList.add('hide-me');
		}
	})

	changeMenuElemCallback(target, changeHeaderCallback);
}

function resolveElemByRoute(tgt) {
	var route = tgt.getAttribute('data-route'),
		curr;

	if(route.split('/').length !== 3) return;

	if(curr = $$('#' + route.split('/')[2])) curr.classList.remove('hide-me');
}

function addClassName(elem) {
	var c = globals.getCurrentElemID();
	globals.state.prevMenuElem = elem;

	if(elem.parentNode.nodeName === 'DIV') {
		if(!$$(c).classList.contains('active1')) {
			$$(c).classList.add('active1');
		}
		globals.state.prevMenuElem = $$(c);
	}

	elem.classList.add('active1');
		return true;
}

function removeClassName(elem){
	var c;

	if(!globals.state.prevMenuElem) return true;
	
	c = globals.getCurrentElemID();
	
	if(globals.state.prevMenuElem.getAttribute('id') === c.substr(1, c.length)) {
			$$('.active1', globals.state.prevMenuElem.nextElementSibling).classList.remove('active1');
	} 
	globals.state.prevMenuElem.classList.remove('active1');
		return true;
}

function openDropDown(self) {
	self.classList.remove('active1');
  		$(self).dropdown({
  		    inDuration: 300,
  		    outDuration: 225,
  		    constrainWidth: true, 
  		    gutter: 0, 
  		    belowOrigin: true, 
  		    alignment: 'left', 
  		    stopPropagation: true
  		});
	$(self).dropdown('open');
}

clientRequest($$('#main-content'), $$($$.m).children[1], blockSwitcher);