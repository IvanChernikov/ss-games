function Clock(step) {
	this.time = {
		current: 0,
		last: 0,
		delta: function () { return (this.current - this.last) / 1000 }
	};
	this.last = 0;
	this.delta = 0;
	this.step = step;
}

Clock.prototype = {
	tick: function () {
		this.time.current = performance.now();
		this.delta += Math.min(1, this.time.delta());
	},

	tock: function () {
		this.time.last = this.time.current;
	},

	process: function (callback) {
		while (this.delta > this.step) {
			this.delta -= this.step;
			callback(this.delta);
		}
	}
}