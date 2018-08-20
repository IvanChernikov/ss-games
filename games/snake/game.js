/** SNAKE **/
function Snake() {
	this.direction = 0;
	this.x = 0;
	this.y = 0;
	this.isAlive = true;
	this.canGrow = true;
	this.canBurrow = true;
	this.score = 0;
	this.body = [];
	this.fruits = [];
	this.controls = {
		turnNorth: ['KeyW', 'ArrowUp'],
		turnWest: ['KeyA', 'ArrowLeft'],
		turnSouth: ['KeyS', 'ArrowDown'],
		turnEast: ['KeyD', 'ArrowRight']
	}
}
Snake.prototype = {
	init: function (x, y, length, burrow, fruits) {
		this.x = x;
		this.y = y;
		this.canBurrow = burrow || true;
		for (let i = 0; i < length; i++) {
			this.body.push(
				new Point(this.x - i, this.y)
			);
		}
		this.fruits = fruits;
	},
	draw: function (delta, painter) {
		for (let i in this.body) {
			let p = this.body[i];
			painter.tune({
				strokeStyle: '#88FF88',
				fillStyle: '#00EE00',
				lineWidth: 5
			});
			painter.gridSquare(p);
		}
		for (let i in this.fruits) {
			let f = this.fruits[i];
			
			f.draw(delta, painter);
		}
	},
	update: function (delta, engine) {
		if (this.isAlive && this.canGrow) {
			this.turn(engine.input);
			this.move(engine.painter.grid);
			this.check(engine.painter.grid);
		}
	},
	turn: function (input) {
		let turned = false,
			ns = [0, 180].includes(this.direction),
			ew = [90, 270].includes(this.direction);
		if (ns && !turned && input.any(this.controls.turnNorth)) {
			this.direction = 90; turned = true;
		}
		if (ns && !turned && input.any(this.controls.turnSouth)) {
			this.direction = 270; turned = true;
		}
		if (ew && !turned && input.any(this.controls.turnEast)) {
			this.direction = 0; turned = true;
		}
		if (ew && !turned && input.any(this.controls.turnWest)) {
			this.direction = 180; turned = true;
		}
	},
	move: function (grid) {
		this.x += Math.cos(this.direction);
		this.y -= Math.sin(this.direction);
		
		if (this.canBurrow) {
			this.burrow(grid.width - 1, grid.height - 1);
		}
		this.body.unshift(new Point(this.x, this.y));
	},
	burrow: function (mx, my) {
		if (this.x < 0) this.x = mx;
		if (this.x > mx) this.x = 0;
		if (this.y < 0) this.y = my;
		if (this.y > my) this.y = 0;
		
	},
	check: function (grid) {
		let hasEaten = false;
		for (let i = 0; i < this.fruits.length; i++) {
			let fruit = this.fruits[i];
			if (fruit.x == this.x && fruit.y == this.y) {
				this.eat(fruit, grid);
				hasEaten = true;	break;
			}
		}
		if (!hasEaten) this.body.pop();
		
		for (i in this.body) {
			let b = this.body[i];
			if (i > 0 && this.x == b.x && this.y == b.y) {
				this.isAlive = false;
				alert(`You lost! Final score ${this.score}`);
			}
		}
	},
	eat: function (fruit, grid) {
		this.score += 10;
		if (this.body.length > (grid.width * grid.height) ) {
			let i = this.fruits.indexOf(fruit);
			this.fruits.splice(i, 1);
			if (this.fruits.length == 0) {
				this.canGrow = false;
			}
		} else {
			let occupied = this.body.concat(
				this.fruits.map( f => new Point(f.x, f.y) ));
			fruit.move(occupied, grid);
		}
	}
}
/** FRUITS **/
function Fruit(x, y, sprite) {
	this.x = x;
	this.y = y;
	this.spriteIndex = Math.rand(sprite.positions.length);
	this.sprite = sprite;
}
Fruit.prototype = {
	draw: function (delta, painter) {
		painter.gridSprite(this.position(), this.sprite, this.spriteIndex);
	},
	position: function () {
		return new Point(this.x, this.y);
	},
	move: function (occupied, grid) {
		let hasMoved = false;
		while (!hasMoved) {
			let hasCollided = false;
			this.x = Math.rand(0, grid.width);
			this.y = Math.rand(0, grid.height);
			for (let i in occupied) {
				let pt = occupied[i];
				if (pt.x == this.x && pt.y == this.y)
					hasCollided = true;
			}
			hasMoved = !hasCollided;
		}
		this.spriteIndex = Math.rand(this.sprite.positions.length);
	}
}