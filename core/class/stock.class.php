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
		$this->checkCmdOk('add1', 'action', 'Ajouter 1', 'other', 1, 'none');
		$this->checkCmdOk('minus1', 'action', 'Enlever 1', 'other', 1, 'none');
		$this->checkCmdOk('addx', 'action', 'Ajouter X', 'message', 1, 'none');
		$this->checkCmdOk('minusx', 'action', 'Enlever X', 'message', 1, 'none');
		$this->checkCmdOk('stock', 'action', 'Définir stock', 'message', 1, 'none');
		$this->checkCmdOk('price', 'action', 'Définir prix', 'message', 1, 'none');
		$this->checkCmdOk('stock', 'stock', 'Stock actuel', 'numeric', 1, 'badge');
		$this->checkCmdOk('percent', 'stock', 'Pourcentage', 'numeric', 1, 'badge');
		$this->checkCmdOk('price', 'stock', 'Prix actuel', 'numeric', 1, 'badge');
		$this->checkCmdOk('current', 'conso', 'Quantité du jour', 'numeric', 1, 'badge');
		$this->checkCmdOk('daily', 'conso', 'Quantité veille', 'numeric', 1, 'badge');
		$this->checkCmdOk('monthly', 'conso', 'Quantité mensuel', 'numeric', 0, 'badge');
		$this->checkCmdOk('weekly', 'conso', 'Quantité hebdomadaire', 'numeric', 0, 'badge');
		$this->checkCmdOk('weeklyCountinuous', 'conso', 'Quantité hebdomadaire continue', 'numeric', 0, 'badge');
		$this->checkCmdOk('current', 'price', 'Coût du jour', 'numeric', 0, 'badge');
		$this->checkCmdOk('daily', 'price', 'Coût veille', 'numeric', 0, 'badge');
		$this->checkCmdOk('monthly', 'price', 'Coût mensuel', 'numeric', 0, 'badge');
		$this->checkCmdOk('weekly', 'price', 'Coût hebdomadaire', 'numeric', 0, 'badge');
		$this->checkCmdOk('weeklyCountinuous', 'price', 'Coût hebdomadaire continu', 'numeric', 0, 'badge');
	}

	public function cronDaily() {
		log::add('stock', 'debug', 'calcul daily');
		foreach (eqLogic::byType('stock', true) as $stock) {
			$stock->dailyStock();
		}
	}

	public function checkCmdOk($id, $type, $name, $subtype, $visible, $template) {
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),$type . '-' . $id);
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande ' . $type . '-' . $id);
			$stockCmd = new stockCmd();
			$stockCmd->setName(__($name, __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId($type . '-' . $id);
			if ($subtype == 'numeric') {
				$stockCmd->setType('info');
				$stockCmd->setSubType('numeric');
			} else {
				$stockCmd->setType('action');
				if ($subtype == 'other') {
					$stockCmd->setSubType('other');
				} else {
					$stockCmd->setSubType('message');
				}
			}
			$stockCmd->setConfiguration('inprogress', 0);
			$stockCmd->setConfiguration('value', 0);
			$stockCmd->setConfiguration('type', $type);
			$stockCmd->setConfiguration('id', $id);
			if ($subtype == 'numeric') {
				$stockCmd->setIsHistorized(1);
			}
			if ($visible == 1) {
				$stockCmd->setIsVisible(1);
			}
			if ($template != 'none') {
				$stockCmd->setTemplate("mobile",$template );
	      $stockCmd->setTemplate("dashboard",$template );
			}
			$stockCmd->save();
			if ($subtype == 'numeric') {
				$stockCmd->event(0);
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
		}
	}

	public function addStock($value) {
		$priceCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'stock-price');
		//if price != 0, adding a list element
		if ($priceCmd->getConfiguration('value') != 0) {
			$this->addPrice($value);
		}
		$this->newStock(1,$value);
	}

	public function rmStock($value) {
		$priceCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'stock-price');
		//if price != 0, calculate a cost
		if ($priceCmd->getConfiguration('value') != 0) {
			$this->calCost($value);
		}
		$this->newConso($value);
		$this->newStock(0,$value);
	}

	public function setStock($value) {
		//calcultate if it's a add or rm Stock
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'stock-stock');
		if ($value > $stockCmd->getConfiguration('value')) {
			$this->addStock($value - $stockCmd->getConfiguration('value'));
		} else {
			$this->rmStock($stockCmd->getConfiguration('value') - $value);
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
		$this->setPercent();
	}

	public function newConso($value) {
		//change value of conso current
		$consoCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'conso-current');
		$conso = $value + $consoCmd->getConfiguration('value');
		$consoCmd->setConfiguration('value', $conso);
		$consoCmd->save();
		$consoCmd->event($conso);
	}

	public function setPrice($value) {
		//set actual price (value + event)
		//if before = 0, then create list with actual stock, else nothing on list
		$priceCmd = stockCmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'stock-price');
		if ($priceCmd->getConfiguration('value') == 0) {
			$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'stock-stock');
			$list[] = array('price' => $value, 'stock', $stockCmd->getConfiguration('value'));
			$priceCmd->setConfiguration('list',json_encode($list));
		}
		$priceCmd->setConfiguration($value);
		$priceCmd->save();
		$priceCmd->event($value);
	}

	public function addPrice($value) {
		//adding an element to list of prices
		$priceCmd = stockCmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'stock-price');
		$list = json_decode($priceCmd->getConfiguration('list'));
		$list[] = array('price' => $priceCmd->getConfiguration('value'), 'stock' => $value);
		$priceCmd->setConfiguration('list',json_encode($list));
		$priceCmd->save();
	}

	public function calCost($value) {
		$priceCmd = stockCmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'stock-price');
		$consoCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'price-current');
		//do some calculation from list values
		$list = json_decode($priceCmd->getConfiguration('list'));
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
	}

	public function dailyDaily($type,$value,$histW) {
		//save value on daily
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($eqLogic->getId(), $type.'-daily');
		$consoCmd->setConfiguration('value', $value);
		$consoCmd->setConfiguration($histW, $value);
		$consoCmd->save();
		$consoCmd->event($value);
	}

	public function dailyWeek($type) {
		//save value on weeklyCountinuous
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($eqLogic->getId(), $type.'-weeklyCountinuous');
		$currentCmd = stockCmd::byEqLogicIdAndLogicalId($eqLogic->getId(), $type.'-current');
		$week = 0;
		for ($i=1; $i < 8; $i++) {
			$week = $week + $currentCmd->getConfiguration('W'.$i);
		}
		$stockCmd->setConfiguration('value', $week);
		$stockCmd->save();
		$stockCmd->event($week);
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
		foreach (array('conso','price') as $type) {
			$currentCmd = stockCmd::byEqLogicIdAndLogicalId($eqLogic->getId(), $type.'-current');
			$value = $currentCmd->getConfiguration('value');
			$this->dailyDaily($type,$value,$histW);
			$this->dailyWeek($type);
			$this->dailyWeekly($type,$value,$histW);
			$this->dailyMonthly($type,$value,$histM);
		}
	}
}

class stockCmd extends cmd {
	public function preSave() {
		if ($this->getSubtype() == 'message') {
			$this->setDisplay('message_disable', 1);
		}
	}

	public function execute($_options = null) {
		log::add('stock', 'debug', 'execute : ' . $this->getConfiguration('id') . ' ' . $this->getConfiguration('type') . ' ' . $this->getLogicalId());
		if ($this->getType() == 'info') {
			return $this->getConfiguration('value');
		} else {
			$eqLogic = $this->getEqLogic();
			if ($this->getConfiguration('id') == 'plus1') {
				$eqLogic->addStock(1);
			} elseif ($this->getConfiguration('id') == 'minus1'){
				$eqLogic->rmStock(1);
			} else {
				if (is_numeric(trim($_options['title']))) {
					switch ($this->getConfiguration('id')) {
						case 'plusx':
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
