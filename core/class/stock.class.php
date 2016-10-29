<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class stock extends eqLogic {
	public function postUpdate() {
		//('idcmd', 'typecmd', 'namecmd', 'subtypecmd', 'visible', 'template')
		$this->checkCmdOk('add1', 'action', 'Ajouter 1', 'other', '1', 'none');
		$this->checkCmdOk('minus1', 'action', 'Enlever 1', 'other', '1', 'none');
		$this->checkCmdOk('addx', 'action', 'Ajouter X', 'message', '1', 'none');
		$this->checkCmdOk('minusx', 'action', 'Enlever X', 'message', '1', 'none');
		$this->checkCmdOk('stock', 'action', 'Définir stock', 'message', '1', 'none');
		$this->checkCmdOk('price', 'action', 'Définir prix', 'message', '1', 'none');
		$this->checkCmdOk('stock', 'stock', 'Stock actuel', 'numeric', '1', 'badge');
		$this->checkCmdOk('percent', 'stock', 'Pourcentage', 'numeric', '1', 'badge');
		$this->checkCmdOk('price', 'stock', 'Prix actuel', 'numeric', '1', 'badge');
		$this->checkCmdOk('current', 'conso', 'Quantité du jour', 'numeric', '1', 'badge');
		$this->checkCmdOk('daily', 'conso', 'Quantité veille', 'numeric', '1', 'badge');
		$this->checkCmdOk('monthly', 'conso', 'Quantité mensuel', 'numeric', '0', 'badge');
		$this->checkCmdOk('weekly', 'conso', 'Quantité hebdomadaire', 'numeric', '0', 'badge');
		$this->checkCmdOk('weeklyCountinuous', 'conso', 'Quantité hebdomadaire continue', 'numeric', '0', 'badge');
		$this->checkCmdOk('current', 'price', 'Coût du jour', 'numeric', '0', 'badge');
		$this->checkCmdOk('daily', 'price', 'Coût veille', 'numeric', '0', 'badge');
		$this->checkCmdOk('monthly', 'price', 'Coût mensuel', 'numeric', '0', 'badge');
		$this->checkCmdOk('weekly', 'price', 'Coût hebdomadaire', 'numeric', '0', 'badge');
		$this->checkCmdOk('weeklyCountinuous', 'price', 'Coût hebdomadaire continu', 'numeric', '0', 'badge');
	}

	public function cronDaily() {
		log::add('stock', 'debug', 'calcul daily');
		foreach (eqLogic::byType('stock', true) as $stock) {
			$stock->dailyStock();
		}
	}

	public function checkCmdOk($_id, $_type, $_name, $_subtype, $_visible, $_template) {
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),$_type . '-' . $_id);
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande ' . $_type . '-' . $_id);
			$stockCmd = new stockCmd();
			$stockCmd->setName(__($_name, __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId($_type . '-' . $_id);
			if ($_subtype == 'numeric') {
				$stockCmd->setType('info');
				$stockCmd->setSubType('numeric');
			} else {
				$stockCmd->setType('action');
				if ($_subtype == 'other') {
					$stockCmd->setSubType('other');
				} else {
					$stockCmd->setSubType('message');
				}
			}
			$stockCmd->setConfiguration('inprogress', '0');
			$stockCmd->setConfiguration('value', '0');
			$stockCmd->setConfiguration('category', $_type);
			$stockCmd->setConfiguration('id', $_id);
			if ($_subtype == 'numeric') {
				$stockCmd->setIsHistorized('1');
			}
			if ($_visible == '1') {
				$stockCmd->setIsVisible('1');
			} else {
				$stockCmd->setIsVisible('0');
			}
			if ($_template != 'none') {
				$stockCmd->setTemplate("mobile",$_template );
	      $stockCmd->setTemplate("dashboard",$_template );
			}
			$stockCmd->save();
			if ($_subtype == 'numeric') {
				$stockCmd->event('0.0');
			}
		}
	}

	public function setPercent() {
		if ($this->getConfiguration('maximum') != '' && $this->getConfiguration('maximum') != '0' && is_numeric($this->getConfiguration('maximum'))) {
			$percentConf = $this->getConfiguration('maximum');
			$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'stock-stock');
			$percent = $stockCmd->getConfiguration('value') * $percentConf / 100;
			$percentCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'stock-percent');
			$percentCmd->setConfiguration('value',$percent);
			$percentCmd->save();
			$percentCmd->event($percent);
			log::add('stock', 'debug', 'setPercent : ' . $percent);
		}
	}

	public function addStock($value) {
		$priceCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'stock-price');
		//if price != 0, adding a list element
		if ($priceCmd->getConfiguration('value') != 0) {
			$this->addPrice($value);
		}
		log::add('stock', 'debug', 'addStock : ' . $value);
		$this->newStock(1,$value);
	}

	public function rmStock($value) {
		$priceCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'stock-price');
		//if price != 0, calculate a cost
		if ($priceCmd->getConfiguration('value') != '0') {
			$this->calCost($value);
		}
		log::add('stock', 'debug', 'rmStock : ' . $value);
		$this->newConso($value);
		$this->newStock(0,$value);
	}

	public function setStock($value) {
		//calcultate if it's a add or rm Stock
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'stock-stock');
		log::add('stock', 'debug', 'setStock : ' . $value);
		if ($value > $stockCmd->getConfiguration('value')) {
			log::add('stock', 'debug', 'setStock : ' . $value);
		} else {
			log::add('stock', 'debug', 'setStock : ' . $value);
		}
	}

	public function newStock($op, $value) {
		//change value of stock
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'stock-stock');
		if ($op) {
			$newstock = $stockCmd->getConfiguration('value') + $value;
		} else {
			$newstock = $stockCmd->getConfiguration('value') - $value;
		}
		$stockCmd->setConfiguration('value', $newstock);
		$stockCmd->save();
		$stockCmd->event($newstock);
		log::add('stock', 'debug', 'newStock : ' . $value . ' ' . $op);
		$this->setPercent();
	}

	public function newConso($value) {
		//change value of conso current
		$consoCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'conso-current');
		$conso = $value + $consoCmd->getConfiguration('value');
		$consoCmd->setConfiguration('value', $conso);
		$consoCmd->save();
		$consoCmd->event($conso);
		log::add('stock', 'debug', 'newConso : ' . $value . ' ' . $conso);
	}

	public function setPrice($value) {
		//set actual price (value + event)
		//if before = 0, then create list with actual stock, else nothing on list
		$priceCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'stock-price');
		if ($priceCmd->getConfiguration('value') == '0') {
			$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'stock-stock');
			$list[] = array('price' => $value, 'stock', $stockCmd->getConfiguration('value'));
			$priceCmd->setConfiguration('list',json_encode($list));
		}
		$priceCmd->setConfiguration('value',$value);
		$priceCmd->save();
		$priceCmd->event($value);
		log::add('stock', 'debug', 'setPrice : ' . $value);
	}

	public function addPrice($value) {
		//adding an element to list of prices
		$priceCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'stock-price');
		$list = $priceCmd->getConfiguration('list');
		if ($list == '') {
			$list = array();
		} else {
			$list = json_decode($list, true);
		}
		$list[] = array('price' => $priceCmd->getConfiguration('value'), 'stock' => $value);
		$priceCmd->setConfiguration('list',json_encode($list));
		$priceCmd->save();
		log::add('stock', 'debug', 'addPrice : ' . $value);
	}

	public function calCost($value) {
		$priceCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'stock-price');
		$consoCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'price-current');
		//do some calculation from list values
		$list = json_decode($priceCmd->getConfiguration('list'), true);
		log::add('stock', 'debug', 'price : ' . print_r($list,true));
		var_dump($priceCmd->getConfiguration('list'));
		$test = 1;
		$price = 0;
		while ($test == 1) {
			if ($list[0]['stock'] > $value) {
				$list[0]['stock'] = $list[0]['stock'] - $value;
				$add = $value * $list[0]['price'];
				$test = 0;
			} else {
				$add = $list[0]['price'] * $list[0]['stock'];
				$value = $value - $list[0]['stock'];
				unset($list[0]);
			}
			$price = $price + $add;
		}
		$priceCmd->setConfiguration('list',json_encode($list));
		$priceCmd->save();
		$consoCmd->setConfiguration('value', $price);
		$consoCmd->save();
		$consoCmd->event($price);
		log::add('stock', 'debug', 'calCost : ' . $value . ' ' . $price);
	}

	public function dailyDaily($type,$value,$histW) {
		//save value on daily
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(), $type.'-daily');
		$consoCmd->setConfiguration('value', $value);
		$consoCmd->setConfiguration($histW, $value);
		$consoCmd->save();
		$consoCmd->event($value);
		log::add('stock', 'debug', 'dailyDaily : ' . $type . ' ' . $value . ' ' . $histM);
	}

	public function dailyWeek($type) {
		//save value on weeklyCountinuous
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(), $type.'-weeklyCountinuous');
		$currentCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(), $type.'-current');
		$week = 0;
		for ($i=1; $i < 8; $i++) {
			$week = $week + $currentCmd->getConfiguration('W'.$i);
		}
		$stockCmd->setConfiguration('value', $week);
		$stockCmd->save();
		$stockCmd->event($week);
		log::add('stock', 'debug', 'dailyWeek : ' . $type . ' ' . $value . ' ' . $histM);
	}

	public function dailyWeekly($type,$value,$histW) {
		//save value on weekly
		$weekCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),$type.'-weekly');
		$value = $value + $weekCmd->getConfiguration('inprogress');
		if ($this->getConfiguration('week') == $histW) {
			//début de semaine, on met la valeur à jour
			$weekCmd->setConfiguration('value', $value);
			$weekCmd->setConfiguration('inprogress', 0);
			$weekCmd->event($value);
		} else {
			$weekCmd->setConfiguration('inprogress', $value);
		}
		$weekCmd->save();
		log::add('stock', 'debug', 'dailyWeekly : ' . $type . ' ' . $value . ' ' . $histM);
	}

	public function dailyMonthly($type,$value,$histM) {
		//save value on monthly
		$monthCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),$type.'-monthly');
		$value = $value + $monthCmd->getConfiguration('inprogress');
		if ($this->getConfiguration('week') == $histM) {
			//début de mois, on met la valeur à jour
			$monthCmd->setConfiguration('value', $value);
			$monthCmd->setConfiguration('inprogress', 0);
			$monthCmd->event($value);
		} else {
			$weekCmd->setConfiguration('inprogress', $value);
		}
		$weekCmd->save();
		log::add('stock', 'debug', 'dailyMonthly : ' . $type . ' ' . $value . ' ' . $histM);
	}

	public function dailyStock() {
		//jour en cours
		$jourW = date("N");
		//jour pour historisation
		if ($jourW == 1) {
			$histW = 'W7';
		} else {
			$histW = 'W' . ($jourW - 1);
		}
		$histM = date("n");
		log::add('stock', 'debug', 'dailyStock : conso');
		$currentCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(), 'conso-current');
		$value = $currentCmd->getConfiguration('value');
		$this->dailyDaily('conso',$value,$histW);
		$this->dailyWeek('conso');
		$this->dailyWeekly('conso',$value,$histW);
		$this->dailyMonthly('conso',$value,$histM);
		log::add('stock', 'debug', 'dailyStock : price');
		$currentCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(), 'price-current');
		$value = $currentCmd->getConfiguration('value');
		$this->dailyDaily('price',$value,$histW);
		$this->dailyWeek('price');
		$this->dailyWeekly('price',$value,$histW);
		$this->dailyMonthly('price',$value,$histM);
	}
}

class stockCmd extends cmd {
	public function preSave() {
		if ($this->getSubtype() == 'message') {
			$this->setDisplay('message_disable', 1);
		}
	}

	public function execute($_options = null) {
		log::add('stock', 'debug', 'execute : ' . $this->getType() . ' ' . $this->getConfiguration('id') . ' ' . $this->getConfiguration('type') . ' ' . $this->getLogicalId());
		if ($this->getType() == 'info') {
			return $this->getConfiguration('value');
			log::add('stock', 'debug', 'info : ' . $this->getConfiguration('value'));
		} else {
			$eqLogic = $this->getEqLogic();
			log::add('stock', 'debug', 'action : ' . $this->getConfiguration('id'));
			if ($this->getConfiguration('id') == 'add1') {
				$eqLogic->addStock('1');
			} elseif ($this->getConfiguration('id') == 'minus1'){
				$eqLogic->rmStock('1');
			} else {
				if (is_numeric(trim($_options['title']))) {
					switch ($this->getConfiguration('id')) {
						case 'addx':
						$eqLogic->addStock(trim($_options['title']));
						break;
						case 'minusx':
						$eqLogic->rmStock(trim($_options['title']));
						break;
						case 'stock':
						$eqLogic->setStock(trim($_options['title']));
						break;
						case 'price':
						$eqLogic->setPrice(trim($_options['title']));
						break;
					}
				}
			}
		}
	}
}
?>
