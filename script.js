	function send_pattern(sqEq = false, a = "1", b = "0", c = "0") {
		if (!sqEq) document.location.href = "calc.php?pattern=" + pattern.value.replace("+", "^").replace("%", "@")
		else {
			document.location.href = "calc.php?a=" + a.replace("+", "^").replace("%", "@") + "&b=" + b.replace("+", "^").replace("%", "@") + "&c=" + c.replace("+", "^").replace("%", "@")
		}
	}
	var bMinus = false
	var len = 0
	var a = "0", b = "0", c = "0"
	window.onload = function() {
		let noButtons = document.getElementsByClassName('static');
		for (let i = 0; i < noButtons.length; i++) {
			let btn = noButtons[i]
			btn.addEventListener("click", (event) => {
				switch (event.target.innerText) {
					case "+": case "-": case "*": case "/": case "(": case ")": { 
						len = 0; bMinus = false; break;
					}
					default: len++;
				}
				document.getElementById('pattern').value += event.target.innerText
			})
		}
		document.getElementById('mod').addEventListener('click', (event) => {
			document.getElementById('pattern').value += "%"
		})

		document.getElementById('sqA').addEventListener('click', (event) => {
			a = document.getElementById('pattern').value
			if (a == "" || a == 0) a = "0"
			document.getElementById('pattern').value = ""
			len = 0;
			bMinus = false;
		})
		document.getElementById('sqB').addEventListener('click', (event) => {
			b = document.getElementById('pattern').value
			if (b == "") b = "0"
			document.getElementById('pattern').value = ""
			len = 0;
			bMinus = false;
		})
		document.getElementById('sqC').addEventListener('click', (event) => {
			c = document.getElementById('pattern').value
			if (c == "") c = "0"
			send_pattern(true, a, b, c)
		})

		document.getElementById('unar').addEventListener('click', (event) => {
			if (bMinus) {
				let x = document.getElementById('pattern').value
				let xx = x.substring(x.length - len - 1, x.length - 1)
				if (xx < -2147483648 || xx > 2147483647) {
					document.getElementById('expRes').value = "Слишком малое или слишком большое значение числа для int!"
					return;
				}
				let xxx = x.substring(0, 1)
				if (xxx == "(" && x.length == len + 3) document.getElementById('pattern').value = xx
				else document.getElementById('pattern').value = x.substring(0, x.length - len - 3) + xx
				bMinus = false
			} else {
				let x = document.getElementById('pattern').value
				let xx = x.substring(x.length - len, x.length)
				if (xx < -2147483648 || xx > 2147483647) {
					document.getElementById('expRes').value = "Слишком малое или слишком большое значение числа для int!"
					return;
				}
				document.getElementById('pattern').value = x.substring(0, x.length - len) + "(-" + xx + ")"
				bMinus = true
			}
		})
		document.getElementById('delLast').addEventListener('click', (event) => {
			let x = document.getElementById('pattern').value
			x = x.substring(0, x.length - 1)
			document.getElementById('pattern').value = x
			switch (x.substring(x.length - 1)) {
				case "*": case "+": case "-": case "*": case "/": case "(": case ")": {
					bMinus = false
					len = 0
					break;
				}
				default: {
					if (len > 0) len--;
				}
			}
		})
		document.getElementById('delAll').addEventListener('click', (event) => {
			document.getElementById('pattern').value = ""
			document.getElementById('expRes').value = ""
			len = 0
			bMinus = false
		})
		window.onkeydown = (event) => {
			if (event.code == "Enter") {
				send_pattern()
			} else if (event.code == "Space") {
				send_pattern(true, a, b ,c)
			}
		}
	}