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
			$stockCmd->setIsHistorized(1);
			$stockCmd->setConfiguration('value', 0);
			$stockCmd->setConfiguration('type', 'cmd');
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'percent');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande percent');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Pourcentage disponible', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('percent');
			$stockCmd->setType('info');
			$stockCmd->setSubType('numeric');
			$stockCmd->setIsHistorized(1);
			$stockCmd->setConfiguration('value', 0);
			$stockCmd->setConfiguration('type', 'cmd');
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
			$stockCmd->setIsHistorized(1);
			$stockCmd->setConfiguration('value', 0);
			$stockCmd->setConfiguration('type', 'cmd');
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'plus1');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande plus1');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Ajouter 1', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('plus1');
			$stockCmd->setType('action');
			$stockCmd->setSubType('other');
			$stockCmd->setConfiguration('type', 'cmd');
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
			$stockCmd->setConfiguration('type', 'cmd');
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'minus1');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande minus1');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Enlever 1', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('minus1');
			$stockCmd->setType('action');
			$stockCmd->setSubType('other');
			$stockCmd->setConfiguration('type', 'cmd');
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
			$stockCmd->setConfiguration('type', 'cmd');
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
			$stockCmd->setConfiguration('type', 'cmd');
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
			$stockCmd->setConfiguration('type', 'cmd');
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'progressConso');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande dailyConso');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Consommation jour en cours', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('progressConso');
			$stockCmd->setType('info');
			$stockCmd->setSubType('numeric');
			$stockCmd->setConfiguration('value', 0);
			$stockCmd->setConfiguration('type', 'conso');
			$stockCmd->setIsHistorized(1);
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
			$stockCmd->setConfiguration('value', 0);
			$stockCmd->setConfiguration('type', 'conso');
			$stockCmd->setIsHistorized(1);
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
			$stockCmd->setConfiguration('inprogress', 0);
			$stockCmd->setConfiguration('value', 0);
			$stockCmd->setConfiguration('type', 'conso');
			$stockCmd->setIsHistorized(1);
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
			$stockCmd->setConfiguration('inprogress', 0);
			$stockCmd->setConfiguration('value', 0);
			$stockCmd->setConfiguration('type', 'conso');
			$stockCmd->setIsHistorized(1);
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'monthlyContinuousConso');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande monthlyContinuousConso');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Consommation mensuelle continue', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('monthlyContinuousConso');
			$stockCmd->setType('info');
			$stockCmd->setSubType('numeric');
			$stockCmd->setConfiguration('inprogress', 0);
			$stockCmd->setConfiguration('value', 0);
			$stockCmd->setConfiguration('day', 0);
			$stockCmd->setConfiguration('type', 'conso');
			$stockCmd->setIsHistorized(1);
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'weeklyCountinuousConso');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande weeklyCountinuousConso');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Consommation hebdomadaire continue', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('weeklyCountinuousConso');
			$stockCmd->setType('info');
			$stockCmd->setSubType('numeric');
			$stockCmd->setConfiguration('inprogress', 0);
			$stockCmd->setConfiguration('value', 0);
			$stockCmd->setConfiguration('type', 'conso');
			$stockCmd->setIsHistorized(1);
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'progressPrice');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande progressPrice');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Dépense jour en cours', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('progressPrice');
			$stockCmd->setType('info');
			$stockCmd->setSubType('numeric');
			$stockCmd->setConfiguration('value', 0);
			$stockCmd->setConfiguration('type', 'price');
			$stockCmd->setIsHistorized(1);
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
			$stockCmd->setConfiguration('value', 0);
			$stockCmd->setConfiguration('type', 'price');
			$stockCmd->setIsHistorized(1);
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'monthlyPrice');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande monthlyPrice');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Dépense mensuelle en cours', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('monthlyPrice');
			$stockCmd->setType('info');
			$stockCmd->setSubType('numeric');
			$stockCmd->setConfiguration('inprogress', 0);
			$stockCmd->setConfiguration('value', 0);
			$stockCmd->setConfiguration('type', 'price');
			$stockCmd->setIsHistorized(1);
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'weeklyPrice');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande weeklyPrice');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Dépense hebdomadaire en cours', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('weeklyPrice');
			$stockCmd->setType('info');
			$stockCmd->setSubType('numeric');
			$stockCmd->setConfiguration('inprogress', 0);
			$stockCmd->setConfiguration('value', 0);
			$stockCmd->setConfiguration('type', 'price');
			$stockCmd->setIsHistorized(1);
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'monthlyContinuousPrice');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande monthlyContinuousPrice');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Dépense mensuelle continue', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('monthlyContinuousPrice');
			$stockCmd->setType('info');
			$stockCmd->setSubType('numeric');
			$stockCmd->setConfiguration('inprogress', 0);
			$stockCmd->setConfiguration('value', 0);
			$stockCmd->setConfiguration('day', 0);
			$stockCmd->setConfiguration('type', 'price');
			$stockCmd->setIsHistorized(1);
			$stockCmd->save();
		}
		$stockCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'weeklyCountinuousPrice');
		if (!is_object($stockCmd)) {
			log::add('stock', 'debug', 'Création de la commande weeklyCountinuousPrice');
			$stockCmd = new stockCmd();
			$stockCmd->setName(__('Dépense hebdomadaire continue', __FILE__));
			$stockCmd->setEqLogic_id($this->id);
			$stockCmd->setEqType('stock');
			$stockCmd->setLogicalId('weeklyCountinuousPrice');
			$stockCmd->setType('info');
			$stockCmd->setSubType('numeric');
			$stockCmd->setConfiguration('inprogress', 0);
			$stockCmd->setConfiguration('value', 0);
			$stockCmd->setConfiguration('type', 'price');
			$stockCmd->setIsHistorized(1);
			$stockCmd->save();
		}
	}

	public function cronDaily() {
		log::add('stock', 'debug', 'calcul daily');
		foreach (eqLogic::byType('stock', true) as $stock) {
			$stock->dailyStock();
		}
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

		//récupération de la conso jour précédent
		$consoCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'progressConso');
		$conso = $consoCmd->getConfiguration('value');
		$consoCmd->setConfiguration('value', 0);
		$stockCmd->save();
		$consoCmd->event(0);
		$consoCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'dailyConso');
		$consoW = $consoCmd->getConfiguration($histW);
		$consoM = $consoCmd->getConfiguration($histM);
		$consoCmd->setConfiguration('value', $conso);
		$consoCmd->setConfiguration($histW, $conso);
		$stockCmd->save();
		$consoCmd->event($conso);
		//prix daily
		$priceCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'progressPrice');
		$price = $priceCmd->getConfiguration('inprogress');
		$priceCmd->setConfiguration('value', 0);
		$priceCmd->save();
		$priceCmd->event(0);
		$priceCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'dailyPrice');
		$priceW = $priceCmd->getConfiguration($histW);
		$priceM = $priceCmd->getConfiguration($histM);
		$priceCmd->setConfiguration('value', $price);
		$priceCmd->setConfiguration($histW, $price);
		$priceCmd->save();
		$priceCmd->event($price);
		//calcul conso de la semaine roulante
		$weekCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'weeklyCountinuousConso');
		$weekConso = 0;
		for ($i=1; $i < 8; $i++) {
			$weekConso = $weekConso + $consoCmd->getConfiguration('W'.$i);
		}
		$weekCmd->setConfiguration('value', $weekConso);
		$weekCmd->save();
		$weekCmd->event($weekConso);
		//calcul prix de la semaine roulante
		$weekCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'weeklyCountinuousPrice');
		$weekPrice = 0;
		for ($i=1; $i < 8; $i++) {
			$weekPrice = $weekPrice + $priceCmd->getConfiguration('W'.$i);
		}
		$weekCmd->setConfiguration('value', $weekPrice);
		$weekCmd->save();
		$weekCmd->event($weekPrice);
		//calcul conso de la semaine
		$weekCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'weeklyConso');
		if ($this->getConfiguration('week') == $jourW) {
			//début de semaine, on met la valeur à jour
			$weekCmd->setConfiguration('value', $weekConso);
			$weekCmd->save();
			$weekCmd->event($weekConso);
		}
		//calcul prix de la semaine
		$weekCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'weeklyPrice');
		if ($this->getConfiguration('week') == $jourW) {
			//début de semaine, on met la valeur à jour
			$weekCmd->setConfiguration('value', $weekPrice);
			$weekCmd->save();
			$weekCmd->event($weekPrice);
		}
		//calcul conso du mois roulant (30 jours)
		$monthCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'monthlyContinuousConso');
		$month = $monthCmd->getConfiguration('value') + $conso;
		if ($monthCmd->getConfiguration('days') > 29) {
			$month = $month - $monthCmd->getConfiguration('lastday');
		} else {
			$newday = $monthCmd->getConfiguration('days') + 1;
			$monthCmd->setConfiguration('days', $newday);
		}
		$monthCmd->setConfiguration('value', $month);
		$monthCmd->setConfiguration('lastday', $conso);
		$monthCmd->save();
		$monthCmd->event($month);
		//calcul conso du mois roulant (30 jours)
		$monthCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'monthlyContinuousPrice');
		$month = $monthCmd->getConfiguration('value') + $price;
		if ($monthCmd->getConfiguration('days') > 29) {
			$month = $month - $monthCmd->getConfiguration('lastday');
		} else {
			$newday = $monthCmd->getConfiguration('days') + 1;
			$monthCmd->setConfiguration('days', $newday);
		}
		$monthCmd->setConfiguration('value', $month);
		$monthCmd->setConfiguration('lastday', $price);
		$monthCmd->save();
		$monthCmd->event($month);
		//calcul conso du mois
		$monthCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'monthlyConso');
		$month = $monthCmd->getConfiguration('inprogress') + $conso;
		if (1 == $jourM) {
			//début de mois, on met la valeur à jour
			$monthCmd->setConfiguration('value', $month);
			$monthCmd->setConfiguration('inprogress', 0);
			$monthCmd->save();
			$monthCmd->event($month);
		} else {
			//mois en cours, on ajoute juste la conso du jour précédent
			$monthCmd->setConfiguration('inprogress', $month);
			$monthCmd->save();
		}
		//calcul prix du mois
		$monthCmd = stockCmd::byEqLogicIdAndLogicalId($this->getId(),'monthlyPrice');
		$month = $monthCmd->getConfiguration('inprogress') + $price;
		if (1 == $jourM) {
			//début de mois, on met la valeur à jour
			$monthCmd->setConfiguration('value', $month);
			$monthCmd->setConfiguration('inprogress', 0);
			$monthCmd->save();
			$monthCmd->event($month);
		} else {
			//mois en cours, on ajoute juste la conso du jour précédent
			$monthCmd->setConfiguration('inprogress', $month);
			$monthCmd->save();
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
		switch ($this->getType()) {
			case 'info' :
			return $this->getConfiguration('value');
			break;

			case 'action' :
			$eqLogic = $this->getEqLogic();
			if ($this->getLogicalId() == 'setPrice') {
				if (is_numeric(trim($_options['title']))) {
					$priceCmd = stockCmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'price');
					$priceCmd->setConfiguration('value',trim($_options['title']));
					if ($priceCmd->getConfiguration('value') == '') {
						$stockCmd = stockCmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'stock');
						$priceCmd->setConfiguration('list',trim($_options['title']) . ':' . $stockCmd->getConfiguration('value'));
					}
					$priceCmd->save();
					$priceCmd->event(trim($_options['title']));
				} else {
					log::add('stock', 'debug', 'veuillez saisir une valeur numérique');
				}
			} else {
				$stockCmd = stockCmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'stock');
				$value = $stockCmd->getConfiguration('value');
				$consoCmd = stockCmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'progressConso');
				$conso = $consoCmd->getConfiguration('value');
				$percentCmd = stockCmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'percent');
				$priceCmd = stockCmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'prix');
				$price = $priceCmd->getConfiguration('value');
				$pricelist = $priceCmd->getConfiguration('complete');
				$addprice = 0;
				$minprice = 0;

				switch ($this->getLogicalId()) {
					case 'plus1':
					$value = $value + 1;
					$addprice = 1;
					break;
					case 'plusx':
					if (is_numeric(trim($_options['title']))) {
						$value = $value + trim($_options['title']);
						$addprice = trim($_options['title']);
					} else {
						log::add('stock', 'debug', 'veuillez saisir une valeur numérique');
					}
					break;
					case 'minus1':
					$value = $value - 1;
					$conso = $conso + 1;
					$minprice = 1;
					break;
					case 'minusx':
					if (is_numeric(trim($_options['title']))) {
						$value = $value - trim($_options['title']);
						$conso = $conso + trim($_options['title']);
						$addprice = trim($_options['title']);
					} else {
						log::add('stock', 'debug', 'veuillez saisir une valeur numérique');
					}
					break;
					case 'setStock':
					if (is_numeric(trim($_options['title']))) {
						if ($value > trim($_options['title'])) {
							$conso = $conso + $value - trim($_options['title']);
							$minprice = $value - trim($_options['title']);
						} else {
							$addprice = trim($_options['title']) - $value;
						}
						$value = trim($_options['title']);
					} else {
						log::add('stock', 'debug', 'veuillez saisir une valeur numérique');
					}
					break;
				}
				$stockCmd->setConfiguration('value',$value);
				$stockCmd->save();
				$stockCmd->event($value);

				if ($consoCmd->getConfiguration('value') != $conso) {
					// il y a eu consommation, modification inprogress
					$consoCmd->setConfiguration('value',$conso);
					$consoCmd->save();
					$consoCmd->event($conso);
				}

				if ($price != '') {
					/*$priceCmd = stockCmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'prix');
					$price = $priceCmd->getConfiguration('value');
					$pricelist = $priceCmd->getConfiguration('complete');
					$addprice = 0;
					$minprice = 0;*/
					if ($addprice > 0) {
						// il y a eu ajout dans le stock
						$priceCmd->setConfiguration('complete',$pricelist);
						$priceCmd->save();
						$priceCmd->event($pricelist);
					}
					if ($minprice > 0) {
						// il y a eu suppression dans le stock
						$priceCmd->setConfiguration('complete',$pricelist);
						$priceCmd->save();
						$priceCmd->event($pricelist);
					}
					if ($consoCmd->getConfiguration('value') != $conso) {
						// il y a eu consommation
						$priceCmd = stockCmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'progressPrice');
						$priceCmd->setConfiguration('value',$conso);
						$priceCmd->save();
						$priceCmd->event($conso);
					}
				}

				if ($eqLogic->getConfiguration('maximum') != '' && $eqLogic->getConfiguration('maximum') != '0' && is_numeric($eqLogic->getConfiguration('maximum'))) {
					$percentConf = $eqLogic->getConfiguration('maximum');
					$percent = $value * $percentConf / 100;
					$percentCmd->setConfiguration('value',$percent);
					$percentCmd->save();
					$percentCmd->event($percent);
					log::add('stock', 'debug', 'execute : ' . $value . ' ' . $percent . '% ' . $conso);
				}

			}
			return true;
			break;
		}
	}

}

?>
