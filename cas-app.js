if(!window.casExtension) {
	window.casExtension = true;
	var username, password, newUP = false, loginScreen = '483988', loginBtn = 'item950234', moreScreen = '483067', logoutBtn = 'item950342';
	app.addScreenEvent(loginScreen, function() {
		var screenEl = this;
		if(localStorage.casExtensionUsername && localStorage.casExtensionPassword) {
			app.showRemoteScreen('http://cas.extensions.appshed.com/forums.php?username=' + encodeURIComponent(localStorage.casExtensionUsername) + '&password=' + encodeURIComponent(localStorage.casExtensionPassword));
		}
		document.id(loginBtn).addEvent('click', function() {
			username = screenEl.getElement('[name=username]').get('value');
			password = screenEl.getElement('[name=password]').get('value');
			newUP = true;
		});
	});
	
	app.addScreenEvent(moreScreen, function() {
		document.id(logoutBtn).addEvent('click', function() {
			localStorage.casExtensionUsername = '';
			localStorage.casExtensionPassword = '';
		});
	});

	app.phone.addEvent('screen', function(id, screenEl, remote) {
		var fetchUrl;
		if(remote && (fetchUrl = screenEl.get('data-fetch-url')) && fetchUrl.indexOf('http://cas.extensions.appshed.com') == 0) {
			if(screenEl.getElement('.header .title').get('text') != 'Incorrect Login') {
				if(newUP) {
					localStorage.casExtensionUsername = username;
					localStorage.casExtensionPassword = password;
					newUP = false;
				}
			}
			else {
				localStorage.casExtensionUsername = '';
				localStorage.casExtensionPassword = '';
				app.showScreen(loginScreen);
			}
		}
	});
}
