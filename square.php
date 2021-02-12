<?php
	class SquareEquationCalculator {
		private $a = NULL;
		private $b = NULL;
		private $c = NULL;
		function __construct($sqArr) {
			$this->a = $sqArr[0];
			$this->b = $sqArr[1];
			$this->c = $sqArr[2];
		}
		public function calculate() {
			$a = $this->a;
			if ($a == 0) return "a=0: уравнение не квадратное!";
			$b = $this->b;
			$c = $this->c;
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
	}
?>