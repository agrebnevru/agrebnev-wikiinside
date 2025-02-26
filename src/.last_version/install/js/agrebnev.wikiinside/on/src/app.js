const func = function(event, selector, handler) {
	this.addEventListener(event, function(e) {
		for (var target = e.target; target && target != this; target = target.parentNode) {
			if (target.matches(selector)) {
				handler.call(target, e)
				break
			}
		}
	}, true)
}

Document.prototype.on = func
HTMLElement.prototype.on = func
