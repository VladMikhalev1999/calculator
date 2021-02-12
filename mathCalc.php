<?php
	include "modTrue.php";

	class Calculator {
		private $prn = NULL;
		function __construct($prn) {
			$this->prn = $prn;
		}
		private function sum($o1, $o2) {
			if (($o1 + $o2) < -2147483648 || ($o1 + $o2) > 2147483647) return ERROR_03;
			return $o1 + $o2;
		}
		private function sub($o1, $o2) {
			if (($o2 - $o1) < -2147483648 || ($o2 - $o1) > 2147483647) return ERROR_03;
			return $o2 - $o1;
		}
		private function mult($o1, $o2) {
			if (($o1 * $o2) < -2147483648 || ($o1 * $o2) > 2147483647) return ERROR_03;
			return $o1 * $o2;
		}
		private function div($o1, $o2) {
			if ($o1 == 0) return ERROR_06;
			if (($o2 / $o1) < -2147483648 || ($o2 / $o1) > 2147483647) return ERROR_03;
			return $o2 / $o1;
		}
		private function mod($o1, $o2) {
			if ($o1 == 0) return ERROR_06;
			if ($o2 < $o1) return $o2;
			if (($o2 % $o1) < -2147483648 || ($o2 % $o1) > 2147483647) return ERROR_03;
			return $o2 % $o1;
		}
		public function calculate() {
			$st = array();
			foreach ($this->prn as $op) {
				switch ($op) {
					case "+": case "-": case "*": case "/": case "%":
						$op1 = (int)array_pop($st);
						if ($op1 < -2147483648 || $op1 > 2147483647) return ERROR_03;
						$op2 = (int)array_pop($st);
						if ($op2 < -2147483648 || $op2 > 2147483647) return ERROR_03;
						switch ($op) {
							case "+":
								$res = $this->sum($op1, $op2);
								if ($res === ERROR_03) return ERROR_03;
								array_push($st, $res);
								break;
							case "-":
								$res = $this->sub($op1, $op2);
								if ($res === ERROR_03) return ERROR_03;
								array_push($st, $res);
								break;
							case "*":
								$res = $this->mult($op1, $op2);
								if ($res === ERROR_03) return ERROR_03;
								array_push($st, $res);
								break;
							case "/":
								$res = $this->div($op1, $op2);
								if ($res === ERROR_03) return ERROR_03;
								if ($res === ERROR_06) return ERROR_06;
								array_push($st, $res);
								break;
							case "%":
								$res = $this->mod($op1, $op2);
								if ($res === ERROR_03) return ERROR_03;
								array_push($st, $res);
								break;
						}
						break;
					default:
						array_push($st, $op);
						break;
				}
			}
			return [array_pop($st)];
		}
	}
?>