function Painter(canvas, grid, scale) {
	this.canvas = canvas;
	this.context = canvas.getContext('2d');
	this.grid = grid;
	this.scale = scale;
	this.aspectRatio = grid.width / grid.height;
	this.side = this.canvas.width / grid.width;
	window.onresize = () => { this.resize() };
}

Painter.prototype = {
	tune: function (options) {
		for (let i in options) {
			if (this.context[i]) {
				this.context[i] = options[i];
			}
		}
	},
	resize: function () {
		let ww = window.innerWidth,
			wh = window.innerHeight,
			port = Math.max(wh, ww) == wh,
			sw = (ww - (ww % this.grid.width)),
			sh = (wh - (wh % this.grid.height));
			sh = sw / this.aspectRatio;
		
		let cw = this.scaleUnit(sw),
			ch = this.scaleUnit(sh);
		
		this.canvas.width = cw;
		this.canvas.height = ch;
		this.canvas.style.width = `${sw}px`;
		this.canvas.style.height = `${sh}px`;
		this.side = this.canvas.width / this.grid.width;
	},
	clear: function () {
		this.tune({
			fillStyle: 'black',
			strokeStyle: 'transparent'
		})
		this.rect(0, 0, this.canvas.width, this.canvas.height);
	},
	scaleUnit: function (u) {
		return u * this.scale;
	},

	rect: function (x, y, w, h) {
		this.context.beginPath();
		this.context.rect(x, y, w, h);
		this.context.fill();
		this.context.stroke();
	},

	square: function (x, y, s) {
		this.rect(x, y, s, s);
	},
	gridSquare: function (pt) {
		let s = this.side,
			x = pt.x * s,
			y = pt.y * s;
		this.square(x, y, s);
	},
	circle: function (x, y, r) {
		
	},

	trace: function (points) {
		for (let i in points) {
			let point = points[i],
				x = point.x,
				y = point.y,
				b = point.b || false;
			if (i == 0 || b ) {
				this.context.beginPath();
				this.context.moveTo(x,y);
			} else {
				this.context.lineTo(x,y);
			}
		}
		this.context.stroke();
	},

	sprite: function (pos, sprite, idx) {
		this.context.drawImage(
			sprite.img,
			sprite.positions[idx][0],
			sprite.positions[idx][1],
			sprite.width,
			sprite.height,
			pos.x, pos.y,
			this.side,
			this.side
		);
	},
	gridSprite: function (pos, sprite, idx) {
		this.context.drawImage(
			sprite.img,
			sprite.positions[idx][0],
			sprite.positions[idx][1],
			sprite.width,
			sprite.height,
			pos.x * this.side, pos.y * this.side,
			this.side,
			this.side
		);
	},
	debug: function () {
		this.tune({
			lineWidth: 3.5,
			strokeStyle: '#494949',
			fillStyle: 'transparent'
		})
		this.context.beginPath();
		for (let i = 0; i < this.grid.width; i++) {
			this.context.moveTo(i * this.side, 0);
			this.context.lineTo(i * this.side, this.canvas.height);
		}
		for (let j =0; j < this.grid.height; j++) {
			this.context.moveTo(0, j * this.side);
			this.context.lineTo(this.canvas.width, j * this.side);
		}
		this.context.stroke();
	}
}