/*** RequestAnimationFrame Polyfill ***/
window.requestAnimationFrame = window.requestAnimationFrame
                               || window.mozRequestAnimationFrame
                               || window.webkitRequestAnimationFrame
                               || window.msRequestAnimationFrame
                               || function(f){return setTimeout(f, 1000/60)};

window.cancelAnimationFrame = window.cancelAnimationFrame
                              || window.mozCancelAnimationFrame
                              || function(requestID){clearTimeout(requestID)}; //fall back
							  
/*** Math Override and Addons ***/
(function(Math) {
	const ratio = Math.PI / 180;
	Math.rad = {
		sin: Math.sin,
		cos: Math.cos
	}
	Math.sin = function (deg) {
		return parseFloat(Math.rad.sin(deg * ratio).toFixed(5));
	}
	Math.cos = function (deg) {
		return parseFloat(Math.rad.cos(deg * ratio).toFixed(5));
	}
	Math.rand = function (min, max) {
		if (!max) {
			if (!min) return Math.random();
			max = min;
			min = 0;
		}
		let range = max - min;
		return Math.floor(Math.random() * range) + min;
	}
}(Math));