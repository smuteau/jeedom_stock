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

	}


}

class stockCmd extends cmd {

}

?>
