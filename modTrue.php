<?php
	define ("ERROR_00", "Успех!");
	define ("ERROR_01", "Неверная синтаксическая конструкция!");
	define ("ERROR_02", "Незаконченное выражение!");
	define ("ERROR_03", "Слишком малое или слишком большое значение числа для int!");
	define ("ERROR_04", "Слишком длинное выражение!");
	define ("ERROR_05", "Суммарное количество чисел и операторов превышает 30!");
	define ("ERROR_06", "Ошибка деления на 0!");
	define ("ERROR_07", "Неверная запись вычисляемого выражения!");

		#наше выражение в виде строки
		$pattern = "";
		$lastError = ERROR_00;
		#формируем число (а ля конечный автомат для разбора числа)
		function getNumber($pattern, $i) {
			$str = "";
			while ($i < strlen($pattern)) {
				$c = $pattern[$i];
				switch ($c) {
					case "0": case "1": case "2": case "3": case "4":
					case "5": case "6": case "7": case "8": case "9":
						$str .= $c;
						$i++;
						break;
					default:
						return [$str, $i];
				}
			}
			return [$str, $i];
		}
		#Преобразует строку в массив (типа лексический анализ, ага)
		function format($pattern) {
			$i = 0;
			$array = array();
			while ($i < strlen($pattern)) {
				$c = $pattern[$i];
				switch ($c) {
					case " ":
						$i++;
						break;
					case "0": case "1": case "2": case "3": case "4":
					case "5": case "6": case "7": case "8": case "9":
						$nums = getNumber($pattern, $i);
						array_push($array, $nums[0]);
						$i = $nums[1];
						break;
					case "+": case "-": case "*": case "/": 
					case "%": case "(": case ")":
						$sym = $c; 
						array_push($array, $sym);
						$i++;
						break;
					default:
						return ERROR_01;
				}
			}
			return $array;
		}
		function postfix($arr) {
			$out = array();
			$st = array();
			$d = "";
			foreach ($arr as $op) {
				switch ($op) {
					case "%":
						if (count($st) != 0) {
							$d = $st[count($st) - 1];
							if ($d == "%") {
								array_push($out, array_pop($st));
							}
						}
						array_push($st, "%");
						break;
					case "/":
						if (count($st) != 0) {
							$d = $st[count($st) - 1];
							if ($d == "*" || $d == "/" || $d == "%") {
								array_push($out, array_pop($st));
							}
						}
						array_push($st, "/");
						break;
					case "*":
						if (count($st) != 0) {
							$d = $st[count($st) - 1];
							if ($d == "*" || $d == "/" || $d == "%") {
								array_push($out, array_pop($st));
							}
						}
						array_push($st, "*");
						break;
					case "+":
						if (count($st) != 0) {
							$d = $st[count($st) - 1];
							if ($d == "+" || $d == "-" || $d == "*" || $d == "/" || $d == "%") {
								array_push($out, array_pop($st));
							}
						}
						array_push($st, "+");
						break;
					case "-":
						if (count($st) != 0) {
							$d = $st[count($st) - 1];
							if ($d == "-" || $d == "+" || $d == "*" || $d == "/" || $d == "%") {
								array_push($out, array_pop($st));
							}
						}
						array_push($st, "-");
						break;
					case "(":
						array_push($st, $op);
						break;
					case ")":
						do {
							if (count($st) == 0) {
								return ERROR_02;
							}
							$d = array_pop($st);
							if ($d != "(") {
								array_push($out, $d);
							} 
						} while ($d != "(");
						break;
					default:
						array_push($out, $op);
						break;
				}
			}
			foreach (array_reverse($st) as $s) {
				array_push($out, $s);
			}
			return $out;
		}
		function opera($s) {
			switch ($s) {
				case "+": case "-": case "*": case "/": case "%": return true;
				default: return false;
			}
		}
		function number($s) {
			return !opera($s);
		}
		function correct($arr) {
			$cnt = 0;
			if (count($arr) == 1) {
				if (opera($arr[0]) || $arr[0] == "(" || $arr[0] == ")") { 
					$lastError = ERROR_07; 
					return false;
				}
			}
			else for ($i = 0; $i < count($arr) - 1; $i++) {
				$s = $arr[$i];
				if ($i == 0 &&opera($s)) {
					$lastError = ERROR_07; 
					return false;
				} else if ($s == "(") {
					$cnt++;
					if (opera($arr[$i + 1])) { $lastError = ERROR_07; return false; }
				} else if ($s == ")") {
					$cnt--;
					if ($arr[$i + 1] != ")" && number($arr[$i + 1])) { $lastError = ERROR_07; return false; }
				}
				else if (opera($s)) {
					if ($arr[$i + 1] != "(" && opera($arr[$i + 1])) { $lastError = ERROR_02; return false; }
				} else {
					if (number($arr[$i + 1]) && $arr[$i + 1] != ")") { $lastError = ERROR_07; return false; }
				}
			}
			if (opera($arr[count($arr) - 1])) { 
				$lastError = ERROR_02; 
				return false; 
			}
			return true;
		}
		function replaceUnarMinuses($arr) {
			$x = array();
			$prev = $arr[0];
			foreach ($arr as $s) {
				if ($s == "-") {
					if ($prev == "(" || $prev == $s) {
						array_push($x, "0");
					}
				}
				array_push($x, $s);
				$prev = $s;
			}
			return $x;
		}
		function check($pattern) {
			if (strlen($pattern) > 65536) return ERROR_04;
			$arr = format($pattern);
			if (!is_array($arr)) return ERROR_01;
			if (count($arr) == 0) {
				return ERROR_02;
			}
			$arr = replaceUnarMinuses($arr);
			if (!correct($arr)) {
				return $lastError;
			}
			if (count($arr) > 30) return ERROR_05;
			$parr = postfix($arr);
			return $parr;
		}

?>