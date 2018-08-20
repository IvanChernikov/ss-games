<html>
<head>
	<title>Space Invaders</title>
	<!-- BEGIN styles -->
	<link rel="stylesheet" href="/css/required.css">
	<link rel="stylesheet" href="/css/theme.css">
	<style>
body {
	background: black;
}
.game-container {
	max-width: 100vw;
	max-height: 100vh;
}
.game-window {
	max-width: 100vw;
	max-height: 100vh;
	object-fit: scale-down;
	background-color: rgba(40,40,40,1);
}
	</style>
	<!-- END styles -->
</head>
<body>
<!-- BEGIN content -->
<div class="container-fluid">
	<div class="row">
		<div class="col-12 d-flex justify-content-center game-container">
			<canvas class="game-window" width="800" height="600"></canvas>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-start" tabindex="-1" role="dialog" aria-labelledby="modal-start-title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-start-title">Space Invaders</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
			<p>Classic arcade Space Invaders game written in javascript.</p>
			<p>Use the A and D to move, and space to shoot.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn-block" data-dismiss="modal" onclick="game.start()">Start</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-end" tabindex="-1" role="dialog" aria-labelledby="modal-end-title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-end-title">Snake</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
			<h2 class="text-center">GAME OVER</h2>
			<p>Your final score is <b id="game-score"></b></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn-block" data-dismiss="modal" onclick="location.reload()">Restart</button>
      </div>
    </div>
  </div>
</div>

<!-- END content -->
<!-- BEGIN scripts -->
<script src="/js/required.js"></script>
<?php
foreach (new DirectoryIterator(realpath('../../js/engine')) as $fi) {
	$file = $fi->getFilename();
	if (preg_match('/.js$/', $file)) {
		echo sprintf('<script src="/js/engine/%s"></script>', $file);
	}
}
?>
<script src="game.js"></script>
<script>
$('#modal-start').modal('show');

config = {
	window: $('.game-window')[0],
	width: 800,
	height: 600,
	
	playerSpeed: 4,
	playerCooldown: 0.3,
	bulletSpeed: 10,
	invaderSpeed: 2,
	invaderDrop: 16
}

player = {
	h: 32,
	w: 32,
	x: 384,
	y: 556,
	update: function (delta) {
		this.move(delta);
		this.shoot(delta);
		let killed = null;
		for (i in this.bullets) {
			let bullet = this.bullets[i];
			bullet.update(delta);
			for (j in invaders.items) {
				let invader = invaders.items[j];
				if (bullet.x >= invader.x &&
					bullet.x <= invader.x + invader.w &&
					bullet.y >= invader.y &&
					bullet.y <= invader.y + invader.h) {
					killed = [i, j];
				}
			}
		}
		if (killed !== null) {
			let i = killed[0],
				j = killed[1];
			this.bullets.splice(i, 1);
			invaders.items.splice(j, 1);
			if (invaders.items.count == 0) {
				alert('You win!');
			}
		}
	},
	move: function (delta) {
		if (game.keys.KeyA) {
			if (this.x - config.playerSpeed > 0) {
				this.x -= config.playerSpeed;
			}
		}
		if (game.keys.KeyD) {
			if (this.x + config.playerSpeed < config.width - this.w) {
				this.x += config.playerSpeed;
			}
		}
	},
	cooldown: 0,
	shoot: function (delta) {
		this.cooldown -= delta;
		if (this.cooldown < 0 && game.keys.Space) {
			let bullet = {
				x: this.x + this.w/2,
				y: this.y + this.h/2,
				s: config.bulletSpeed,
				update: function(delta) {
					this.y -= this.s;
					if (this.y < 0) {
						player.bullets.shift();
					}
				}
			}
			this.bullets.push(bullet);
			this.cooldown = config.playerCooldown;
		}
	},
	bullets: [],
	draw: function(delta) {
		draw.square(this.x, this.y, this.w, this.h, 'green', 'black');
		for (i in this.bullets) {
			let bullet = this.bullets[i];
			draw.circle(bullet.x, bullet.y, 8, 'red', 'black');
		}
	}
	
}

invaders = {
	items: [],
	reverse: false,
	won: false,
	direction: 1, // left: -1, right: 1
	update: function (delta) {
		if (this.won) return;
		for (i in this.items) {
			let invader = this.items[i];
			invader.x += config.invaderSpeed * this.direction;
			if (invader.x <= 0 || invader.x >= config.width - 32)  {
				this.reverse = true;
			}
		}
		if (this.reverse) {
			for (i in this.items) {
				let invader = this.items[i];
				invader.y += config.invaderDrop;
				if (invader.y >= 524) {
					this.won = true;
					alert('You lose');
				}
			}
			this.direction *= -1;
			this.reverse = false;
		}
	},
	draw: function (delta) {
		for (i in this.items) {
			let invader = this.items[i];
			draw.square(
				invader.x, invader.y, 
				invader.w, invader.h, 
				'orange', 'black');
		}
	},
	init: function () {
		for (i = 0; i < 10; i++) {
			for (j = 0; j < 5; j++) {
				this.items.push({
					x: i * 64,
					y: j * 64 + 64,
					w: 32,
					h: 32
				})
			}
		}
	
	}
	
}

game = {
	score: 0,
	start: function() {
		
		/*game.resize();
		$(window).resize(game.resize);*/
		resource.load();
		
		draw.context = config.window.getContext('2d');
		
		this.time.last = performance.now();
		this.frame();
		
		// Init invaders
		invaders.init();
		// Create input buffer handler
		$(window)
			.keydown(function (event) {
				if (typeof game.keys[event.originalEvent.code] !== 'undefined')
					game.keys[event.originalEvent.code] = true;
			})
			.keyup(function (event) {
				if (typeof game.keys[event.originalEvent.code] !== 'undefined')
					game.keys[event.originalEvent.code] = false;
			});
		
	},
	resize: function () {
		let height = window.innerHeight,
			width = window.innerWidth;

		width = (width - (width % 20)) * 2;
		height = (height - (height % 15)) * 2;
		config.window.width = width;
		config.window.height = width / 20 * 15;

		config.grid = width / 20;
		
		widthStyle = config.grid /2 * 20 + 'px';
		heightStyle = config.grid / 2 * 15 + 'px';
		$(config.window).css('height', heightStyle)
			.css('width', widthStyle);
	},
	keys: {
		KeyA: false,
		KeyD: false,
		Space: false
	},
	time: {
		current: 0,
		last: 0,
		step: 1/60,
		delta: 0,
		tick: function () {
			this.current = performance.now();
			this.delta += Math.min((this.current - this.last) / 1000);
		}
	},
	frame: function() {
		game.time.tick();
		
		while (game.time.delta > game.time.step) {
			game.time.delta -= game.time.step;
			game.update(game.time.delta);
		}
		
		game.draw(game.time.delta);
		
		game.time.last = game.time.current;
		window.requestAnimationFrame(game.frame);
	},
	update: function(delta) {
		player.update(delta);
		invaders.update(delta);
	},
	draw: function (delta) {
		let ctx = draw.context;
		// Clear all
		ctx.clearRect(0,0,config.window.width, config.window.height);
		ctx.beginPath();
		ctx.fillStyle = 'darkgray';
		ctx.fillRect(0,0,config.window.width, config.window.height);
		
		player.draw(delta);
		invaders.draw(delta);
	}
}
</script>
<!-- END scripts -->
</body>
</html>
