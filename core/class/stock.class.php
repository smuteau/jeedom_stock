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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class stock extends eqLogic {

	public function postUpdate() {
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'stock');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande stock');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Stock actuel', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('stock');
			$stockCmd->setType('info');
			$stockCmd->setSubType('numeric');
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'percent');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande percent');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('% disponible', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('percent');
			$stockCmd->setType('info');
			$stockCmd->setSubType('numeric');
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'prix');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande prix');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Prix actuel', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('prix');
			$stockCmd->setType('info');
			$stockCmd->setSubType('numeric');
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'plus1');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande plus1');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Ajouter un', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('plus1');
			$stockCmd->setType('action');
			$stockCmd->setSubType('other');
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'plusx');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande plusx');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Ajouter X', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('plusx');
			$stockCmd->setType('action');
			$stockCmd->setSubType('message');
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'minus1');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande minus1');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Enlever un', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('minus1');
			$stockCmd->setType('action');
			$stockCmd->setSubType('other');
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'minusx');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande minusx');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Enlever X', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('minusx');
			$stockCmd->setType('action');
			$stockCmd->setSubType('other');
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'setStock');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande setStock');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Définir stock', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('set');
			$stockCmd->setLogicalId('setStock');
			$stockCmd->setType('action');
			$stockCmd->setSubType('message');
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'setPrice');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande setPrice');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Définir prix', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('set');
			$stockCmd->setLogicalId('setPrice');
			$stockCmd->setType('action');
			$stockCmd->setSubType('message');
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'dailyConso');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande dailyConso');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Consommation journalière', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('dailyConso');
			$stockCmd->setType('info');
			$stockCmd->setSubType('numeric');
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'monthlyConso');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande monthlyConso');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Consommation mensuelle', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('monthlyConso');
			$stockCmd->setType('info');
			$stockCmd->setSubType('numeric');
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'weeklyConso');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande weeklyConso');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Consommation hebdomadaire', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('weeklyConso');
			$stockCmd->setType('info');
			$stockCmd->setSubType('numeric');
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'dailyPrice');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande dailyPrice');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Dépense journalière', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('dailyPrice');
			$stockCmd->setType('info');
			$stockCmd->setSubType('numeric');
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'monthlyPrice');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande monthlyPrice');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Dépense mensuelle', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('monthlyPrice');
			$stockCmd->setType('info');
			$stockCmd->setSubType('numeric');
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'weeklyPrice');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande weeklyPrice');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Dépense hebdomadaire', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('weeklyPrice');
			$stockCmd->setType('info');
			$stockCmd->setSubType('numeric');
			$stockCmd->save();
		}
	}

	public function cron() {
		define('', '');
	}
}

class stockCmd extends cmd {
	public function preSave() {
		if ($this->getSubtype() == 'message') {
			$this->setDisplay('message_disable', 1);
		}
	}

	public function execute($_options = null) {
		switch ($this->getType()) {
			case 'info' :
			return $this->getConfiguration('value');
			break;

			case 'action' :
			$eqLogic = $this->getEqLogic();
			if ($this->getLogicalId() == 'setPrice') {
				$priceCmd = stockCmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'price');
				$priceCmd->setConfiguration('value',trim($_options['title']));
				$priceCmd->save();
				$priceCmd->event(trim($_options['title']));
			} else {
				$stockCmd = stockCmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'stock');
				$percentCmd = stockCmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'percent');
				$percentConf = $eqLogic->getConfiguration('maximum');
				switch ($this->getLogicalId()) {
					case 'plus1':
					$value = $value + 1;
					break;
					case 'plusx':
					$value = $value + trim($_options['title']);
					break;
					case 'minus1':
					$value = $value - 1;
					break;
					case 'minusx':
					$value = $value - trim($_options['title']);
					break;
					case 'setStock':
					$value = trim($_options['title']);
					break;
				}
				$stockCmd->setConfiguration('value',$value);
				$stockCmd->save();
				$stockCmd->event($value);
				$percent = $value / $percentConf / 100;
				$percentCmd->setConfiguration('value',$percent);
				$percentCmd->save();
				$percentCmd->event($percent);
			}
			return true;
			break;
		}
	}

}

?>
