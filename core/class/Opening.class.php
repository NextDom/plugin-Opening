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

class Opening extends eqLogic {
	public static $_widgetPossibility = array('custom' => true);
	
	// Fonction de conversion d'equipement
	private function cmdToReplacement($cmdName,&$replace,$replaceName=null)
	{
		log::add('Opening', 'debug',  __METHOD__.' Enter');

		if(is_null($replaceName))
			$replaceName = $cmdName;
		
		$replace['#'.$replaceName.'ValueDate#'] = '';
		$replace['#'.$replaceName.'CollectDate#'] = '';
		$replace['#'.$replaceName.'Value#'] = '';
		if(trim($this->getConfiguration($cmdName)) != '')
		{
			$cmdEq = cmd::byId(str_replace('#', '', $this->getConfiguration($cmdName)));
				
			if (is_object($cmdEq))
			{
				$cmdEq->execCmd();
				$replace['#'.$replaceName.'ValueDate#'] = $cmdEq->getValueDate();
				$replace['#'.$replaceName.'CollectDate#'] = $cmdEq->getCollectDate();
			}else{
				return false;
			}
			$value = jeedom::evaluateExpression($this->getConfiguration($cmdName));
			$replace['#'.$replaceName.'Value#'] = $value;
		}else{
			return false;
		}
		return true;
	}
	
	// Fonction de gestion image inexistante
	private function imgToDefault($img, $def=null)
	{
		if(file_exists(__DIR__.'/../../../../'.$img))
			return $img;
		log::add('Opening', 'debug',  __METHOD__.' Image not foud: '.$img);
		if (is_null($def))
			return 'plugins/Opening/core/template/dashboard/images/FFFFFF-0.png';
		return $def;
	}
	private function imgToOtherState($img,$img2, $def=null)
	{
		if(file_exists(__DIR__.'/../../../../'.$img))
			return $img;
		log::add('Opening', 'debug',  __METHOD__.' Image not foud: '.$img);
		if (is_null($def))
			return $img2;
		return $def;
	}
	
	// Fonction d'actualisation
	public static function pull($_option)
	{
		$Opening = Opening::byId($_option['Opening_id']);
		if (is_object($Opening) && $Opening->getIsEnable() == 1)
		{
			$cache = cache::byKey('presence::' . $Opening->getId() . '::' .$_option['event_id'], false, true);
			if($cache->getValue() != $_option['value'])
			{
				cache::set('Opening::' . $Opening->getId() . '::' . $_option['event_id'],$_option['value'], 0);
				$Opening->execute($_option['Opening_id']);
			}
			else{
				log::add('Opening', 'debug', 'Pas de changement');
			}
        }
	}
    
	public function launch($_trigger_id, $_value)
	{
		return true;
	}
	
	public function postSave()
	{
	}
	
    public function postUpdate() 
    {
		if ($this->getIsEnable() == 1)
		{
			$listener = listener::byClassAndFunction('Opening', 'pull', array('Opening_id' => intval($this->getId())));
			if (!is_object($listener))
			{
				$listener = new listener();
			}
			$listener->setClass('Opening');
			$listener->setFunction('pull');
			$listener->setOption(array('Opening_id' => intval($this->getId())));
			$listener->emptyEvent();

			$listener->addEvent($this->getConfiguration('windowgcmdeqp'));
			$listener->addEvent($this->getConfiguration('windowdcmdeqp'));
			$listener->addEvent($this->getConfiguration('temperatureintcmdeqp'));
			$listener->addEvent($this->getConfiguration('temperatureextcmdeqp'));
			$listener->addEvent($this->getConfiguration('batterycmdeqp'));
			$listener->addEvent($this->getConfiguration('storecmdeqp'));
			$listener->addEvent($this->getConfiguration('motioncmdeqp'));
			$listener->addEvent($this->getConfiguration('weathercmdeqp'));
			$listener->addEvent($this->getConfiguration('alarmcmdeqp'));
			$listener->addEvent($this->getConfiguration('lockgcmdeqp'));
			$listener->addEvent($this->getConfiguration('lockdcmdeqp'));

			//add listener event on info cmd
			foreach ($this->getCmd(null, null, true) as $cmd) 
			{
				if ($cmd->getType() == 'info')
				{
					$cmdEq = cmd::byId(str_replace('#', '', $cmd->getConfiguration('cmdeq')));
					
					if (is_object($cmdEq))
					{
						$listener->addEvent($cmd->getConfiguration('cmdeq'));
					}
				}
			}

			$listener->save();

		} else {
			$listener = listener::byClassAndFunction('Opening', 'pull', array('Opening_id' => intval($this->getId())));
			if (is_object($listener))
			{
				$listener->remove();
			}
		}
		$mc = cache::byKey('OpeningWidgetmobile' . $this->getId());
		$mc->remove();
		$mc = cache::byKey('OpeningWidgetdashboard' . $this->getId());
		$mc->remove();
		$this->refreshWidget();
    }

	public function preRemove()
	{
		$listener = listener::byClassAndFunction('Opening', 'pull', array('Opening_id' => intval($this->getId())));
		if (is_object($listener))
		{
			$listener->remove();
		}
	}
	
	public function execute($_id) 
	{
		$mc = cache::byKey('OpeningWidgetmobile' . $this->getId());
		$mc->remove();
		$mc = cache::byKey('OpeningWidgetdashboard' . $this->getId());
		$mc->remove();
		$this->refreshWidget();
    }

	public function toHtml($_version = 'dashboard')
	{
		log::add('Opening', 'debug',  __METHOD__.' Start');
		$replace = $this->preToHtml($_version);
		if (!is_array($replace))
		{
			return $replace;
		}
		$version = jeedom::versionAlias($_version);
		if ($this->getDisplay('hideOn' . $version) == 1)
		{
			return '';
		}
		
		// **********************************************************
		// GENERAUX & OPTIONS
		// Recuperation version
		$replace['#version#'] = $_version;
		// Recuperation famille des modeles
		$replace['#familyModel#'] = $this->getConfiguration('familymodel','');
		// Recuperation option mise en majuscule
		$replace['#upperstates#'] = $this->getConfiguration('upperstates',0);
		$replace['#upperstatesweather#'] = $this->getConfiguration('upperstatesweather',0);
		$replace['#upperstatestemperature#'] = $this->getConfiguration('upperstatestemperature',0);
		// String divers
		$replace['#depuisValueString#'] = ' '.__("depuis", __FILE__).' ';
		$replace['#valeurduValueString#'] = ' '.__("Valeur du", __FILE__).' ';
		$replace['#collecteeleValueString#'] = ' '.__("collectée le", __FILE__).' ';
		// Si option majuscule
		if($replace['#upperstates#'] !='0'){$replace['#depuisValueString#'] = mb_strtoupper($replace['#depuisValueString#']);}
		if($replace['#upperstates#'] !='0'){$replace['#valeurduValueString#'] = mb_strtoupper($replace['#valeurduValueString#']);}
		if($replace['#upperstates#'] !='0'){$replace['#collecteeleValueString#'] = mb_strtoupper($replace['#collecteeleValueString#']);}
		
		// **********************************************************
		// BACK
		// Recuperation du modele
		$backModel = $this->getConfiguration('backmodel');
		// Choix de l'image
		$replace['#backModel#'] = ($backModel == '') ? '' : $this->imgToDefault('plugins/Opening/core/template/dashboard/images/'.$backModel,'');
		
		// **********************************************************
		// WEATHER
		// Initialisation de la conversion d'equipement
		$this->cmdToReplacement('weathercmdeqp',$replace,'weather');
		
		// Recuperation si activé
		$replace['#weatherActiv#'] = $this->getConfiguration('modeWeatherplugin',0);
		// Recuperation options affichage d'etats
		$replace['#weatherShowState#'] = $this->getConfiguration('weathershowstate',0);
		$replace['#weatherShowStateDuration#'] = $this->getConfiguration('weathershowstateduration',0);
		// Recuperation du modele
		$weatherModel = $this->getConfiguration('weathermodel');
		$replace['#weatherModel#'] = 'plugins/Opening/core/template/dashboard/images/FFFFFF-0.png';
		$replace['#weatherValueString#'] = '';
		if($weatherModel != '')
		{
			$replace['#weatherValue#'] = trim($replace['#weatherValue#'],'"');
			
			// Control si valeur meteo connue
			$weatherToIdx = array(
				// couvert
				"COUVERT" => 'Couvert',
				// gel
				"GEL" => 'Gel',
				"FREEZE"=> 'Gel',
				// neige
				"NEIGE" => 'Neige',
				"SNOW" => 'Neige',
				"GRÊLE" => 'Neige',
				//nuage blanc
				"NUAGE BLANC" => 'NuageBlanc',
				"NUAGE ÉPARS" => 'NuageBlanc',
				"PARTLY-CLOUDY" => 'NuageBlanc',
				"PARTLY-CLOUDY-DAY" => 'NuageBlanc',
				"PARTLY-CLOUDY-NIGHT" => 'NuageBlanc',
				//nuage gris
				"NUAGE GRIS" => 'NuageGris',
				"CLOUDY" => 'NuageGris',
				"NUAGEUX" => 'NuageGris',
				//nuage noir
				"NUAGE NOIR" => 'NuageNoir',
				//orage
				"ORAGE" => 'Orage',
				"THUNDERSTORM" => 'Orage',
				"ORAGEUX" => 'Orage',
				//pluie
				"PLUIE" => 'Pluie',
				"BRUINE" => 'Pluie',
				"RAIN" => 'Pluie',
				//tornade
				"TORNADE" => 'Tornade',
				//soleil
				"SOLEIL" => 'Soleil',
				"SUN" => 'Soleil',
				"SUNNY" => 'Soleil',
				"ENSOLEILLÉ" => 'Soleil',
			);
			// boucle de recherche des 10 premiers mots
			$shortest = -1;
			if($replace['#weatherValue#']!=''){
				$weatherValue = explode(" ", strtoupper($replace['#weatherValue#']));
				$weatherIdx = '';
				for ($i = 0; $i <= count($weatherValue)-1; $i++) {
					foreach($weatherToIdx as $word=>$idx)
					{
						$lev = levenshtein($weatherValue[$i], $word); // il y a d'autre fct de recherche de  similarité ...
						if ($lev == 0) 
						{
							$weatherIdx = $idx;
							$shortest = 0;
							break;
						}
						if ($lev <= $shortest || $shortest < 0)
						{
							$weatherIdx  = $idx;
							$shortest = $lev;
						}
					}
				}
				$replace['#weatherTrust#'] = $shortest; //0 is better
				$replace['#weatherIdx#'] = $weatherIdx; 
			}
			if ($shortest <= 4 && $shortest !=-1)
			{
				// Contruction etat textuel
				$replace['#weatherValueString#'] = $replace['#weatherIdx#'];
				// Si option majuscule
				if($replace['#upperstatesweather#'] !='0'){$replace['#weatherValueString#'] = mb_strtoupper($replace['#weatherValueString#']);}
				if($replace['#upperstatesweather#'] !='0'){$replace['#weatherValueDate#'] = mb_strtoupper($replace['#weatherValueDate#']);}
				// Initialisation modele 
				$replace['#weatherModel#'] =  $this->imgToDefault('plugins/Opening/core/template/dashboard/images/'.str_replace('Soleil',$weatherIdx,$weatherModel));
			}else{
				// Contruction etat textuel
				$replace['#weatherValueString#'] = '';
				$replace['#weatherModel#'] = 'plugins/Opening/core/template/dashboard/images/FFFFFF-0.png';
			}
		}
		// **********************************************************
		// STORE
		// Initialisation de la conversion d'equipement
		$this->cmdToReplacement('storecmdeqp',$replace,'store');
		
		// Recuperation si activé
		$replace['#storeActiv#'] = $this->getConfiguration('modeStoreplugin',0);
		// Recuperation du modele
		$storeModel = $this->getConfiguration('storemodel');
		// Recuperation mini/maxi
		$replace['#storeMinValue#'] = $this->getConfiguration('storeminvalue',0);
		$replace['#storeMaxValue#'] = $this->getConfiguration('storemaxvalue',99);
		// Recuperation options affichage d'etats
		$replace['#storeShowState#'] = $this->getConfiguration('storeshowstate',0);
		$replace['#storeShowStateDuration#'] = $this->getConfiguration('storeshowstateduration',0);
		// Recuperation option inversion d'etat
		$replace['#storeInvertState#'] = $this->getConfiguration('storeinvertstate',0);
	
		// Choix de l'image
		$replace['#storeModel#'] = ($storeModel == '') ? '' : $this->imgToDefault('plugins/Opening/core/template/dashboard/images/'.$storeModel,'');
		// Calcul etat
		if(trim($replace['#storeValue#']) !='')
		{
			$replace['#storePercent#'] = round((($replace['#storeMinValue#'] + $replace['#storeValue#'])*100) / ($replace['#storeMinValue#']+$replace['#storeMaxValue#']));
			if ($replace['#storeInvertState#']== 0)
			{
				$replace['#storePercent#'] = 100 - $replace['#storePercent#'];
			}
		}else{
			if ($replace['#storeInvertState#']== 0)
			{
				$replace['#storePercent#'] = 100;
			}else{
				$replace['#storePercent#'] = 0;
			}
		}
		$replace['#storeStep#'] = str_pad( ceil((100 - $replace['#storePercent#']) / 10) * 10, 3, '0', STR_PAD_LEFT);
		
		// Contruction etat textuel
		$replace['#storeValueString#'] =  __("Volet à", __FILE__).' '.(100-$replace['#storePercent#']).'%';
		// Ajustement echelle (total sliding is 200%)
		$replace['#storePercent#'] = $replace['#storePercent#'] *2; 
		// Gestion du deplacement
		if($replace['#familyModel#'] == '6'){
			$replace['#storePercentText#'] = 'left:-'.$replace['#storePercent#'].'%;top:0%';
		}else{
			$replace['#storePercentText#'] = 'top:-'.$replace['#storePercent#'].'%;left:0%';
		}
		// Si option majuscule
		if($replace['#upperstates#'] !='0'){$replace['#storeValueString#'] = mb_strtoupper($replace['#storeValueString#']);}
		if($replace['#upperstates#'] !='0'){$replace['#storeValueDate#'] = mb_strtoupper($replace['#storeValueDate#']);}
		
		// **********************************************************
		// LOCK
		// Initialisation de la conversion d'equipement
		$this->cmdToReplacement('lockgcmdeqp',$replace,'lockG');
		$this->cmdToReplacement('lockdcmdeqp',$replace,'lockD');
		// Recuperation si activé
		$replace['#lockActiv#'] = $this->getConfiguration('modeLockplugin',0);
		// Initialisation modele vide
		$replace['#lockGModel#'] = 'plugins/Opening/core/template/dashboard/images/FFFFFF-0.png';
		$replace['#lockDModel#'] = 'plugins/Opening/core/template/dashboard/images/FFFFFF-0.png';
		
		// Recuperation de la taille
		$replace['#lockSize#'] = $this->getConfiguration('locksize','42px');
		// Recuperation option position
		$replace['#lockGPosition#'] = $this->getConfiguration('lockgposition','CenterCenter');
		$replace['#lockDPosition#'] = $this->getConfiguration('lockdposition','CenterCenter');
		// Recuperation options affichage d'etats
		$replace['#lockShowState#'] = $this->getConfiguration('lockshowstate',0);
		$replace['#lockShowStateDuration#'] = $this->getConfiguration('lockshowstateduration',0);
		// Redimensionnement
		if (is_numeric($replace['#lockSize#'])) { $replace['#lockSize#'].='px';}
		
		// GAUCHE Si verrouillée
		if(trim($replace['#lockGValue#']) !='0' && $replace['#lockGValue#'] !='')
		{
			// Recuperation modele
			$lockGModel = $this->getConfiguration('lockmodel');
			// Choix de l'image
			$replace['#lockGModel#'] = ($lockGModel == '') ? '' : $this->imgToDefault('plugins/Opening/core/template/dashboard/images/'.$lockGModel,'');
		}
		
		// DROIT Si verrouillee
		if(trim($replace['#lockDValue#']) !='0' && $replace['#lockDValue#'] !='')
		{
			// Recuperation modele
			$lockDModel = $this->getConfiguration('lockmodel');
			// Choix de l'image
			$replace['#lockDModel#'] = ($lockDModel == '') ? '' : $this->imgToDefault('plugins/Opening/core/template/dashboard/images/'.$lockDModel,'');
		}
		
		// Calcul etat verrouillage global
		if(trim($replace['#lockDValue#'])=='' || trim($replace['#lockGValue#'])==''){
			if(trim($replace['#lockDValue#']=='')){
				$replace['#lockValue#'] = bindec($replace['#lockGValue#'].$replace['#lockGValue#']);
			}else if(trim($replace['#lockGValue#'])==''){
				$replace['#lockValue#'] = bindec($replace['#lockDValue#'].$replace['#lockDValue#']);
			}else{
				$replace['#lockValue#'] = 0;
			}
		}else{		
			$replace['#lockValue#'] = bindec($replace['#lockGValue#'].$replace['#lockDValue#']);
		}
		
		// Contruction etat textuel
		switch($replace['#lockValue#'])
		{
			case 0: //Déverrouillée
				$replace['#lockValueString#'] = __("Déverrouillé", __FILE__);
			break;
			case 1: //Verrouillée partiellement a gauche
				$replace['#lockValueString#'] = __("Verrouillé partiellement", __FILE__);
			break;
			case 2: //Verrouillée partiellement a droite
				$replace['#lockValueString#'] = __("Verrouillé partiellement", __FILE__);
			break;
			case 3: //Verrouillée
				$replace['#lockValueString#'] = __("Verrouillé", __FILE__);
			break;
			default:
				$replace['#lockValueString#'] = '';
		}

		// Determination de la duree d'etat la plus courte
		$replace['#lockValueDate#'] = max($replace['#lockGValueDate#'],$replace['#lockDValueDate#']);
		// Si option majuscule
		if($replace['#upperstates#'] !='0'){$replace['#lockValueString#'] = mb_strtoupper($replace['#lockValueString#']);}
		if($replace['#upperstates#'] !='0'){$replace['#lockValueDate#'] = mb_strtoupper($replace['#lockValueDate#']);}
		
		// **********************************************************
		// WINDOW
		// Initialisation de la conversion d'equipement
		$this->cmdToReplacement('windowgcmdeqp',$replace,'windowG');
		$this->cmdToReplacement('windowdcmdeqp',$replace,'windowD');
		
		// Recuperation du modele
		$windowModel = $this->getConfiguration('windowmodel');
		// Recuperation de la taille
		$replace['#windowSize#'] = $this->getConfiguration('windowsize',85);
		// Recuperation options affichage d'etats
		$replace['#windowShowState#'] = $this->getConfiguration('windowshowstate',0);
		$replace['#windowShowStateDuration#'] = $this->getConfiguration('windowshowstateduration',0);
		// Recuperation option mirroir
		$replace['#windowInvertPosition#'] = $this->getConfiguration('windowinvertposition',0);
		// Recuperation option inversion d'etat
		$replace['#windowInvertState#'] = $this->getConfiguration('windowinvertstate',0);
		// Recuperation rotations
		$replace['#windowPerscpective#'] = $this->getConfiguration('windowperscpective',0);
		$replace['#windowRotateX#'] = $this->getConfiguration('windowrotateX',0);
		$replace['#windowRotateX#'] = $replace['#windowRotateX#'] * -1;
		$replace['#windowRotateY#'] = $this->getConfiguration('windowrotateY',0);
		$replace['#windowRotateZ#'] = $this->getConfiguration('windowrotateZ',0);
		$replace['#windowTranslateY#'] = $this->getConfiguration('windowtranslateY',0);
		$replace['#windowRotate#'] = $this->getConfiguration('windowrotate',0);
		$replace['#RotateStates#'] = $this->getConfiguration('rotatestates',0);
		$replace['#RotateCmd#'] = $this->getConfiguration('rotatecmd',0);
		// Recuperation marges
		$replace['#statesMarge#'] = $this->getConfiguration('statesmarge',0);
		$replace['#temperaturesMarge#'] = $this->getConfiguration('temperaturesmarge',0);
		$replace['#cmdMarge#'] = $this->getConfiguration('cmdmarge',0);
		$replace['#globalMarge#'] = $this->getConfiguration('globalmarge',0);
		
		// Choix de l'image
		$replace['#windowModel#'] = $this->imgToDefault('plugins/Opening/core/template/dashboard/images/'.(($windowModel == '') ? 'FFFFFF-0.png' : $windowModel));

		// Si modele special
		if(stristr($replace['#windowModel#'], 'XState') === false) 
		{
			// Si inversion etat
			if($replace['#windowInvertState#'] == 1)
			{
				$replace['#windowGValue#'] = $replace['#windowGValue#']=='1' ? '0' : '1';
				$replace['#windowDValue#'] = $replace['#windowDValue#']=='1' ? '0' : '1';
			}
			// Calcul de l'etat double battant = battant droit + 2* batant gauche
			$replace['#windowValue#'] = $replace['#windowDValue#']+($replace['#windowGValue#']*2);
			// Limitation etat
			if($replace['#windowValue#'] > 3)
			{
				$replace['#windowValue#']='3';
			}
			// Gestion du cas etat=2
			if($replace['#windowValue#'] =='2') // c'est le 2eme batant qui est ouvert donc miror de State1
			{
				$replace['#windowInvertPosition#'] = ($replace['#windowInvertPosition#']=='1' ? '0' : '1');
			}else{
				$replace['#windowModel#'] = str_replace('State1','State'.$replace['#windowValue#'],$replace['#windowModel#']);
			}
			$replace['#windowModel#'] = $this->imgToOtherState($replace['#windowModel#'],str_replace('State'.$replace['#windowValue#'],'State1',$replace['#windowModel#']));
		}else{
			/* Gestion des paliers du store */ 
			$replace['#windowValue#'] = ($replace['#windowDValue#']+($replace['#windowGValue#']*2) > 0) ? '1' : '0';
			if($replace['#storeActiv#'] == 1)
			{
				$replace['#windowModel#'] = str_replace('State1000','State'.$replace['#windowValue#'].$replace['#storeStep#'],$replace['#windowModel#']);
			}else{
				
				$replace['#windowModel#'] = str_replace('State1000','State'.$replace['#windowValue#'].'000',$replace['#windowModel#']);
			}
			$replace['#storeModel#'] = 'plugins/Opening/core/template/dashboard/images/FFFFFF-0.png';
		}

		// Contruction etat textuel
		if($replace['#familyModel#'] == '6'){
			if($replace['#lockValue#']=='1')
			{
				$replace['#windowValueString#'] = __("Verrouillé", __FILE__);
			}else{
				$replace['#windowValueString#'] = (($replace['#windowValue#']!='0') ? __("Ouvert", __FILE__) : __("Fermé", __FILE__));
			}
		}else{
			if($replace['#lockValue#']=='1')
			{
				$replace['#windowValueString#'] = __("Verrouillée", __FILE__);
			}else{
				$replace['#windowValueString#'] = (($replace['#windowValue#']!='0') ? __("Ouverte", __FILE__) : __("Fermée", __FILE__));
			}
		}
		// Determination de la duree d'etat la plus courte
		$replace['#windowValueDate#'] = max($replace['#windowGValueDate#'],$replace['#windowDValueDate#']);
		// Si option majuscule
		if($replace['#upperstates#'] !='0'){$replace['#windowValueString#'] = mb_strtoupper($replace['#windowValueString#']);}
		if($replace['#upperstates#'] !='0'){$replace['#windowValueDate#'] = mb_strtoupper($replace['#windowValueDate#']);}
		
		// **********************************************************
		// BATTERIE
		// Initialisation de la conversion d'equipement
		$this->cmdToReplacement('batterycmdeqp',$replace,'battery');
		// Recuperation si activé
		$replace['#batteryActiv#'] = $this->getConfiguration('modeBatplugin',0);
		// Initialisation modele vide
		$replace['#batterytModel#'] = 'plugins/Opening/core/template/dashboard/images/FFFFFF-0.png';
		
		// Recuperation niveau alerte
		$replace['#batteryMinValue#'] = $this->getConfiguration('batteryminvalue',15);
		// Recuperation options affichage d'etats
		$replace['#batteryShowState#'] = $this->getConfiguration('batteryshowstate',0);
		$replace['#batteryShowStateDuration#'] = $this->getConfiguration('batteryshowstateduration',0);
		// Recuperation option position
		$replace['#batteryPosition#'] = $this->getConfiguration('batteryposition','CenterCenter');
		// Recuperation de la taille
		$replace['#batterySize#'] = $this->getConfiguration('batterysize','42px');
		
		// Si alerte batterie
		if((trim($replace['#batteryValue#']) !='') && ($replace['#batteryValue#']<= $replace['#batteryMinValue#']))
		{
			// Recuperation modele
			$batteryModel = $this->getConfiguration('batterymodel');
			// Choix de l'image
			$replace['#batterytModel#'] = ($batteryModel == '') ? '' : $this->imgToDefault('plugins/Opening/core/template/dashboard/images/'.$batteryModel,'');
		}
		// Redimensionnement		
		if (is_numeric($replace['#batterySize#'])) { $replace['#batterySize#'].='px';}
		// Contruction etat textuel
		$replace['#batteryValueString#'] =  __("Batterie à", __FILE__).' '.($replace['#batteryValue#']).'%';
		// Si option majuscule
		if($replace['#upperstates#'] !='0'){$replace['#batteryValueString#'] = mb_strtoupper($replace['#batteryValueString#']);}
		if($replace['#upperstates#'] !='0'){$replace['#batteryValueDate#'] = mb_strtoupper($replace['#batteryValueDate#']);}

		// **********************************************************
		// MOTION
		// Initialisation de la conversion d'equipement
		$this->cmdToReplacement('motioncmdeqp',$replace,'motion');
		// Recuperation si activé
		$replace['#motionActiv#'] = $this->getConfiguration('modeMotionplugin',0);
		// Initialisation modele vide
		$replace['#motionModel#'] = 'plugins/Opening/core/template/dashboard/images/FFFFFF-0.png';
		// Recuperation options affichage d'etats
		$replace['#motionShowState#'] = $this->getConfiguration('motionshowstate',0);
		$replace['#motionShowStateDuration#'] = $this->getConfiguration('motionshowstateduration',0);
		// Recuperation option position
		$replace['#motionPosition#'] = $this->getConfiguration('motionposition','CenterCenter');
		// Recuperation de la taille
		$replace['#motionSize#'] = $this->getConfiguration('motionsize','42px');
		
		// Si mouvement
		if(trim($replace['#motionValue#']) !='0' && $replace['#motionValue#'] !='')
		{
			// Recuperation modele
			$motionModel = $this->getConfiguration('motionmodel');
			// Choix de l'image
			$replace['#motionModel#'] = ($motionModel == '') ? '' : $this->imgToDefault('plugins/Opening/core/template/dashboard/images/'.$motionModel,'');
		}
		// Redimensionnement
		if (is_numeric($replace['#motionSize#'])) { $replace['#motionSize#'].='px';}
		// Contruction etat textuel
		$replace['#motionValueString#'] = (($replace['#motionValue#']!='0') ? __("Mouvement", __FILE__) : __("Pas de mouvement", __FILE__));
		// Si option majuscule
		if($replace['#upperstates#'] !='0'){$replace['#motionValueString#'] = mb_strtoupper($replace['#motionValueString#']);}
		if($replace['#upperstates#'] !='0'){$replace['#motionValueDate#'] = mb_strtoupper($replace['#motionValueDate#']);}

		// **********************************************************
		// ALARM
		// Initialisation de la conversion d'equipement
		$this->cmdToReplacement('alarmcmdeqp',$replace,'alarm');
		// Recuperation si activé
		$replace['#alarmActiv#'] = $this->getConfiguration('modeAlarmplugin',0);
		// Initialisation modele vide
		$replace['#alarmModel#'] = 'plugins/Opening/core/template/dashboard/images/FFFFFF-0.png';
		// Recuperation options affichage d'etats
		$replace['#alarmShowState#'] = $this->getConfiguration('alarmshowstate',0);
		$replace['#alarmShowStateDuration#'] = $this->getConfiguration('alarmshowstateduration',0);
		$replace['#alarmShowState1#'] = $this->getConfiguration('alarmshowstate1','0');
		// Recuperation option position
		$replace['#alarmPosition#'] = $this->getConfiguration('alarmposition','CenterCenter');
		// Recuperation de la taille
		$replace['#alarmSize#'] = $this->getConfiguration('alarmsize','42px');
		// Recuperation modele
		$alarmModel = $this->getConfiguration('alarmmodel');
		
		// Choix de l'image
		if ($alarmModel != '')
		{
			if(trim($replace['#alarmValue#']) == '2')
			{
				$replace['#alarmModel#'] = $this->imgToDefault('plugins/Opening/core/template/dashboard/images/'.str_replace('State1','State2',$alarmModel));
			}else{
				if(trim($replace['#alarmValue#']) == '1')
				{
					$replace['#alarmModel#'] = $this->imgToDefault('plugins/Opening/core/template/dashboard/images/'.$alarmModel);
				}else{
					// Si affichage de l'etat inactif
					if($replace['#alarmValue#'] == '0' && $replace['#alarmShowState1#'] == '1')
					{
						$replace['#alarmModel#'] = $this->imgToDefault('plugins/Opening/core/template/dashboard/images/'.str_replace('State1','State0',$alarmModel));
					}else{
						if($replace['#alarmValue#'] != '0' && $replace['#alarmValue#'] != '')
						{
							$replace['#alarmModel#'] = $this->imgToDefault('plugins/Opening/core/template/dashboard/images/'.str_replace('State1','State2',$alarmModel));
						}
					}
				}
			}
		}
		log::add('Opening', 'debug', 'replace[#alarmModel#] => '.$replace['#alarmModel#']);
		// Redimensionnement
		if (is_numeric($replace['#alarmSize#'])) { $replace['#alarmSize#'].='px';}
		// Contruction etat textuel
		if(trim($replace['#alarmValue#']) =='0')
		{
			$replace['#alarmValueString#'] = __("Alarme inactive", __FILE__);
		}else{
			$replace['#alarmValueString#'] = __("Alarme", __FILE__).' '.(($replace['#alarmValue#']!='2') ? __("activée", __FILE__) : __("déclenchée", __FILE__));
		}
		// Si option majuscule
		if($replace['#upperstates#'] !='0'){$replace['#alarmValueString#'] = mb_strtoupper($replace['#alarmValueString#']);}
		if($replace['#upperstates#'] !='0'){$replace['#alarmValueDate#'] = mb_strtoupper($replace['#alarmValueDate#']);}

		// **********************************************************
		// TEMPERATURES
		// Initialisation de la conversion d'equipement
		$this->cmdToReplacement('temperatureintcmdeqp',$replace,'temperatureInt');
		$this->cmdToReplacement('temperatureextcmdeqp',$replace,'temperatureExt');
		// Recuperation si activé
		$replace['#temperatureActiv#'] = $this->getConfiguration('modeTemperatureplugin',0);
		$replace['#topTemperature#'] = $this->getConfiguration('toppostemperature',0);
		$replace['#justifyTemperature#'] = $this->getConfiguration('justifytemperature',0);
		if(trim($replace['#justifyTemperature#']) =='1')
		{
			$replace['#justifyTemperature#'] = 'space-around';
		} else {
			$replace['#justifyTemperature#'] = 'space-between';
		}
		// Recuperation legendes
		$replace['#temperatureIntLegende#'] = $this->getConfiguration('IntLegend','T° Int.');
		$replace['#temperatureExtLegende#'] = $this->getConfiguration('ExtLegend','T° Ext.');
		
		
		log::add('Opening', 'debug', "replace['#temperatureIntValue#'] => ".$replace['#temperatureIntValue#']);
		log::add('Opening', 'debug', "replace['#temperatureExtValue#'] => ".$replace['#temperatureExtValue#']);
		
		
		// Contruction etat textuel
		if(trim($replace['#temperatureIntValue#']) !='')
		{
			$replace['#temperatureIntValueString#'] = $replace['#temperatureIntLegende#'].' '.trim($replace['#temperatureIntValue#']).__("°C", __FILE__);
		}
		if(trim($replace['#temperatureExtValue#']) !='')
		{
			$replace['#temperatureExtValueString#'] .= $replace['#temperatureExtLegende#'].' '.trim($replace['#temperatureExtValue#']).__("°C", __FILE__);
		}
		// Si option majuscule
		if($replace['#upperstatestemperature#'] !='0'){$replace['#temperatureIntValueString#'] = mb_strtoupper($replace['#temperatureIntValueString#']);}
		if($replace['#upperstatestemperature#'] !='0'){$replace['#temperatureExtValueString#'] = mb_strtoupper($replace['#temperatureExtValueString#']);}
		
		
		log::add('Opening', 'debug', "replace['#temperatureActiv#'] => ".$replace['#temperatureActiv#']);
		log::add('Opening', 'debug', "replace['#temperatureIntValueString#'] => ".$replace['#temperatureIntValueString#']);
		log::add('Opening', 'debug', "replace['#temperatureExtValueString#'] => ".$replace['#temperatureExtValueString#']);
		

		// **********************************************************
		// CMD
		$br_before = 0;
		foreach ($this->getCmd(null, null, true) as $cmd) 
		{
			if ($br_before == 0 && $cmd->getDisplay('forceReturnLineBefore', 0) == 1)
			{
				$cmd_html .= '<br/>';
			}
			if ($cmd->getType() == 'info')
			{
				log::add('Opening', 'debug', 'cmdeq => '.$cmd->getConfiguration('cmdeq'));
				$cmdEq = cmd::byId(str_replace('#', '', $cmd->getConfiguration('cmdeq')));

				if (is_object($cmdEq))
				{
					$cmdEq->execCmd();
					$value = jeedom::evaluateExpression($cmd->getConfiguration('cmdeq'));
					$cmd->setValue($value);
					$cmd->setValueDate($cmdEq->getValueDate());
					$cmd->setCollectDate($cmdEq->getCollectDate());
					$cmd->event($value);
					log::add('Opening', 'debug', $cmd->getConfiguration('cmdeq').' value => '.$cmdEq->getValue().' / '.$cmd->getValue().' vs '.json_encode($value).' on date '.$cmdEq->getValueDate());
				}
			}
			
			$cmd_html .= $cmd->toHtml($_version, '');

			$br_before = 0;
			if ($cmd->getDisplay('forceReturnLineAfter', 0) == 1)
			{
				$cmd_html .= '<br/>';
				$br_before = 1;
			}
		}
		$replace['#cmd#'] = $cmd_html;
		
		log::add('Opening', 'debug',  __METHOD__.' End');
		return template_replace($replace, getTemplate('core', $version, 'standard', 'Opening'));
	}
}

class OpeningCmd extends cmd {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */

 	public function execute($_options = array())
 	{
		log::add('Opening', 'debug', __METHOD__.'('.json_encode($_options).') ');
		log::add('Opening', 'debug', __METHOD__.' '.$this->getType().' '.$this->getLogicalId() .' cmdeq '.$this->getConfiguration('cmdeq'));
	
		switch ($this->getType())
		{
			case 'info':
				log::add('Opening', 'debug', __METHOD__.' doing info');
				
				try {
					$result = jeedom::evaluateExpression($this->getConfiguration('cmdeq'));
					if ($this->getSubType() == 'numeric') {
						if (is_numeric($result)) {
							$result = number_format($result, 2);
						} else {
							$result = str_replace('"', '', $result);
						}
						if (strpos($result, '.') !== false) {
							$result = str_replace(',', '', $result);
						} else {
							$result = str_replace(',', '.', $result);
						}
					}
					
					log::add('Opening', 'debug', __METHOD__.' result: '.$result);
					return $result;
				} catch (Exception $e) {
					log::add('Opening', 'info', $e->getMessage());
					return jeedom::evaluateExpression($this->getConfiguration('cmdeq'));
				}
				
				break;
			case 'action':
				log::add('Opening', 'debug', __METHOD__.' doing action ');
				// can have multiple commande at once
				$cmds = explode('&&', $this->getConfiguration('cmdeq'));
				if (is_array($cmds))
				{
					foreach ($cmds as $cmd_id)
					{
						$cmd = cmd::byId(str_replace('#', '', $cmd_id));
						if (is_object($cmd))
						{
							$cmd->execCmd($_options);
						}
					}
					return;
				} else {
					$cmd = cmd::byId(str_replace('#', '', $this->getConfiguration('cmdeq')));
					return $cmd->execCmd($_options);
				}
				break;
		}
		return false;
	}

	/*     * **********************Getteur Setteur*************************** */
}
