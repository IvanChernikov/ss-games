<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">

	<title>Game Portal</title>
	<meta name="description" content="HTML5 Games Portal">
	<meta name="author" content="Ivan Chernikov">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- BEGIN styles -->
	<link rel="stylesheet" href="/css/required.css">
	<link rel="stylesheet" href="/css/theme.css">
	<!-- END styles -->
</head>

<body>
<!-- BEGIN content -->
<div class="container">
	<h1>Game Portal</h1>
	
	<div class="card-deck">
		<?php
		foreach (new DirectoryIterator('games') as $fi) { 
			if ($fi->isDir() && !$fi->isDot()) {
				$path = $fi->getPath();
				$name = $fi->getFilename();
				$title = strtoupper(substr($name, 0, 1)) . substr($name, 1);
				$date = date('F jS, \a\t G:i', $fi->getCTime())
		?>
		<div class="card">
			<img class="card-img-top" src="/games/<?= sprintf('%s/logo.jpg', $name)?>" alt="game logo">
			
			<div class="card-body">
				<h5 class="card-title"><?=$title?></h5>
				<div class="card-text"><?=file_get_contents(sprintf('games/%s/info.txt', $name))?></div>
			</div>
			<div class="list-group list-group-flush text-center">
				<a href="/games/<?=$name?>" class="list-group-item list-group-item-action list-group-item-primary">Play <?=$title?></a>
			</div>
			<div class="card-footer">
				<div class="card-text text-muted">Updated on <?=$date?></div>
			</div>
		</div>
		<?php
			}
		}
		?>
	</div>
</div>
<!-- END content -->
<!-- BEGIN scripts -->
<script src="/js/required.js"></script>
<!-- END scripts -->
</body>
</html>