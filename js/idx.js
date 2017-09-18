'use strict';

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

function validatingUserData () {
	var spinner  = $$('.preloader-wrapper'),
		icon  	 = $$('.material-icons'),
		password = $$('#password'),
		login	 = $$('#login'),
		retval,
		strval;

		spinner.classList.add('active');

				switch(checkingUserInput({login: login.value, password: password.value})) {
					case 1: 
						retval = 1;
						strval = 'Не заполнен логин или/и пароль.';
						break;;
					case 2:
						retval = 2;
						strval = 'Логин должен содержать только латинские буквы, цифры или «underscore».';
						break;
					case 3: 
						retval = 3
						strval = 'Логин не должен начинаться с цифры.';
						break;
					default:
						retval = 0;
				}
				
		if(retval > 0) {
			spinner.classList.remove('active');
			Materialize.toast(strval, 5000);
			return false;
		}
			
	$$('#main').submit();
}

function checkingUserInput(u){
	var i = 0, code;
	if(!u.login || !u.password) return 1;

			while(i < u.login.length) {
				code = u.login.charCodeAt(i);
					if(i === 0 && (code >= 48 && code <= 57)) return 3;
					if( !(code >= 65 && code <= 90) && 
						!(code >= 97 && code <= 122) && 
						!(code >= 48 && code <= 57) &&
						!(code === 95) ) return 2;
				i++;
			} 
	return 0;
}