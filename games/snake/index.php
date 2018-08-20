<html>
<head>
	<title>Snake</title>
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
			<canvas id="game" class="game-window" width="800" height="600"></canvas>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-start" tabindex="-1" role="dialog" aria-labelledby="modal-start-title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-start-title">Snake</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
			<p>Classic arcade Snake game written in javascript.</p>
			<p>Use the WASD keys to turn.</p>
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

let canvas = document.getElementById('game');
options = {
	canvas: canvas,
	grid: {width: 21, height: 15},
	scale: 2,
	step: 1/9
}

game = new Game(options);

let fruitsImage = new Image(),
	positions = [
		[6,   0], [212,   0], [418,   0],
		[6, 188], [212, 188], [418, 188],
		[6, 376], [212, 376], [418, 376],
	];

fruitsImage.src = 'fruits.png';
let fruitsSprite = new Sprite(fruitsImage, 188, 188, positions);
game.resources.set('fruits', fruitsImage);
game.sprites.set('fruits', fruitsSprite);

snake = new Snake();
fruits = [
	new Fruit(3, 4, fruitsSprite),
	new Fruit(12, 12, fruitsSprite),
];
snake.init(11, 8, 4, true, fruits);

game.bind(snake);

</script>
<!-- END scripts -->
</body>
</html>
