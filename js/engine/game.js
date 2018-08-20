/** OPTION LIST
{
	canvas: <canvas>, // dom node
	grid: {width, height}, // in square units
	scale: float, // scale for canvas
	step: float, // amount of time between game logic steps
	init: callback, // 
}

**/
function Game(options) {
	this.input = new Input();
	this.painter = new Painter(options.canvas, options.grid, options.scale);
	this.clock = new Clock(options.step);
	
	this.updaters = new Map();
	this.renderers = new Map();
	
	this.resources = new Map();
	this.sprites = new Map();
	
	this.init = options.init || function () {};
}

Game.prototype = {
	start: function () {
		this.init();
		this.painter.resize();
		this.frame();
	},
	frame: function () {
		this.clock.tick();
		
		// Catch up loop (in case of tab switching)
		this.clock.process(() => { this.update(this.clock.delta) });
		
		game.render(this.clock.delta);
		
		this.clock.tock();
		window.requestAnimationFrame(() => { this.frame() });
	},
	update: function (delta) {
		for (let f of this.updaters.values()) {
			f(delta, this);
		}
	},
	render: function (delta) {
		this.painter.clear();
		this.painter.debug();
		for (let f of this.renderers.values()) {
			f(delta, this.painter);
		}
	},
	bind: function (obj) {
		console.log(this);
		if (obj.update) {
			this.updaters.set(obj, function (delta, engine) { obj.update(delta, engine); });
		}
		if (obj.draw) {
			this.renderers.set(obj, function (delta, painter) { obj.draw(delta, painter); });
		}
	},
	unbind: function (obj) {
		if (obj.update && this.updaters.has(obj)) {
			this.updaters.delete(obj);
		}
		if (obj.draw && this.renderers.has(obj)) {
			this.renderers.delete(obj);
		}
	}
}