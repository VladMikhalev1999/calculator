<?php
		function calculateSquare($a, $b, $c) {
			if ($a == 0) return "a=0: уравнение не квадратное!";
			$D2 = $b * $b - 4 * $a * $c;
			if ($D2 >= 0) {
				return [
					'1' => ((-$b + sqrt($D2)) / (2 * $a)),
					'2' => ((-$b - sqrt($D2)) / (2 * $a))
				];
			} else {
				return "D < 0: корней нет!";
			}
		}
?>