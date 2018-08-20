function Sprite(img, width, height, positions){
	this.img = img;
	this.width = width;
	this.height = height;
	this.positions = positions;
}
Sprite.prototype = {
	draw: function(position, x, y){
		var pos = this.positions[position];
		draw.context.drawImage(
			this.img,
			pos[0],
			pos[1],
			this.width,
			this.height,
			x, y,
			config.grid,
			config.grid
		);
	}
}

function Point(x,y) {
	this.x = x;
	this.y = y;
}
Point.prototype = {
	mid: function (p) {
		return new Point(
			(this.x + p.x) / 2,
			(this.y + p.y) / 2
		);
	}
}

function Rect(x,y,w,h) {
	this.x = x; this.y = y;
	this.w = w; this.h = h;
}
Rect.prototype = {
	corners: function () {
		return [
			new Point(this.x, this.y),
			new Point(this.x + this.w, this.y),
			new Point(this.x + this.w, this.y + this.h),
			new Point(this.x, this.y + this.h)
		];
	},
	center: function () {
		let c = this.corners();
		return c[0].mid(c[2]);
	}
}