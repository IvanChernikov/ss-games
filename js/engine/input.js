function Input () {
	this.keys = {};
	
	// Add handlers to key up and down
	handler = (event) => {
		let key = event.code,
			down = event.type === 'keydown';
		this.keys[key] = down;
	}
	window.onkeyup = handler;
	window.onkeydown = handler;
}
Input.prototype = {
	clear: function () {
		this.keys = {};
	},
	down: function (key) {
		return this.keys[key] || false;
	},
	up: function (key) {
		return !this.down(key);
	},
	any: function (arr) {
		let pressed = false;
		arr.forEach( (e, i) => {
			pressed = pressed || this.down(e);
		});
		return pressed;
	}
}