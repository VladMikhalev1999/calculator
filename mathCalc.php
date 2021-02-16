<?php
	include "modTrue.php";

		function sum($o1, $o2) {
			if (($o1 + $o2) < -2147483648 || ($o1 + $o2) > 2147483647) return ERROR_03;
			return $o1 + $o2;
		}
		function sub($o1, $o2) {
			if (($o2 - $o1) < -2147483648 || ($o2 - $o1) > 2147483647) return ERROR_03;
			return $o2 - $o1;
		}
		function mult($o1, $o2) {
			if (($o1 * $o2) < -2147483648 || ($o1 * $o2) > 2147483647) return ERROR_03;
			return $o1 * $o2;
		}
		function div($o1, $o2) {
			if ($o1 == 0) return ERROR_06;
			if (($o2 / $o1) < -2147483648 || ($o2 / $o1) > 2147483647) return ERROR_03;
			return $o2 / $o1;
		}
		function mod($o1, $o2) {
			if ($o1 == 0) return ERROR_06;
			if ($o2 < $o1) return $o2;
			if (($o2 % $o1) < -2147483648 || ($o2 % $o1) > 2147483647) return ERROR_03;
			return $o2 % $o1;
		}
		function calculate($prn) {
			$st = array();
			foreach ($prn as $op) {
				switch ($op) {
					case "+": case "-": case "*": case "/": case "%":
						$op1 = (float)array_pop($st);
						if ($op1 < -2147483648 || $op1 > 2147483647) return ERROR_03;
						$op2 = (float)array_pop($st);
						if ($op2 < -2147483648 || $op2 > 2147483647) return ERROR_03;
						switch ($op) {
							case "+":
								$res = sum($op1, $op2);
								if ($res === ERROR_03) return ERROR_03;
								array_push($st, $res);
								break;
							case "-":
								$res = sub($op1, $op2);
								if ($res === ERROR_03) return ERROR_03;
								array_push($st, $res);
								break;
							case "*":
								$res = mult($op1, $op2);
								if ($res === ERROR_03) return ERROR_03;
								array_push($st, $res);
								break;
							case "/":
								$res = div($op1, $op2);
								if ($res === ERROR_03) return ERROR_03;
								if ($res === ERROR_06) return ERROR_06;
								array_push($st, $res);
								break;
							case "%":
								$res = mod($op1, $op2);
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
?>