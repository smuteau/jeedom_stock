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

	public function preUpdate() {
		if ($this->getConfiguration('maitreesclave') == '') {
			throw new Exception(__('Merci de remplir le type de lecteur',__FILE__));
		}
	}

	public function postUpdate() {
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'command');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande type');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Commande recue', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('command');
			$stockCmd->setType('info');
			$stockCmd->setSubType('string');
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'value');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande valeur');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Valeur recue', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('value');
			$stockCmd->setType('info');
			$stockCmd->setSubType('string');
			$stockCmd->save();
		}

	}


}

class stockCmd extends cmd {

}

?>
