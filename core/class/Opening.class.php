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

class xplanet extends eqLogic
{

    //private $NAME = '';

    public static function cron($_options)
    {
        foreach (eqLogic::byType('xplanet') as $xplanet) {
            $xplanet->getInformations();
        }
    }

    /*     * ***********************Methode static*************************** */

    public static function updatexplanet()
    {
        log::remove('xplanet_update');
        $cmd = '/bin/bash ' . dirname(__FILE__) . '/../../3rdparty/install.sh';
        $cmd .= ' >> ' . log::getPathToLog('xplanet_update') . ' 2>&1 &';
        exec($cmd);
    }

    public static function resetxplanet()
    {
        log::remove('xplanet_reset');
        $cmd = '/bin/bash ' . dirname(__FILE__) . '/../../3rdparty/reset.sh';
        $cmd .= ' >> ' . log::getPathToLog('xplanet_reset') . ' 2>&1 &';
        exec($cmd);
    }

    public static function statusxplanet($serviceName)
    {
        log::remove('xplanet_status');
        $cmd = '/bin/bash ' . dirname(__FILE__) . '/../../3rdparty/status.sh ' . $serviceName;
        $cmd .= ' >> ' . log::getPathToLog('xplanet_status') . ' 2>&1 &';
        exec($cmd);
    }

    /*     * *********************Methode d'instance************************* */

    /* 	public function postInsert() {
      $this->setCategory('securite', 1);
      $cmd = '/bin/bash ' .dirname(__FILE__) . '/../../3rdparty/create.sh ' . $this->getConfiguration('name') . ' ' . $this->getConfiguration('ip') . ' ' . $this->getConfiguration('path');
      $cmd .= ' >> ' . log::getPathToLog('xplanet_create') . ' 2>&1 &';
      exec('echo Name : ' . $this->getConfiguration('name') . ' IP : ' . $this->getConfiguration('ip') . ' URL : ' . $this->getConfiguration('url') . ' >> ' . log::getPathToLog('xplanet_create') . ' 2>&1 &');
      exec($cmd);
      $this->setConfiguration('cameraPath','snapshot_' . $this->getConfiguration('name') . '.jpg');
      } */

    public function preUpdate()
    {

        if (!$this->getConfiguration('delay') == '') {
            if (!preg_match("#[0-9]$#", $this->getConfiguration('delay'))) {
                throw new Exception(__('Le champs Délai ne peut contenir autre chose que des chiffres', __FILE__));
            }
        }

        // Si l'url' ne commence pas par /
        if ($this->getConfiguration('capturePath') !== '' && substr($this->getConfiguration('capturePath'), 0, 1) !== "/") {
            throw new Exception(__('Le champs Emplacement des captures doit commencer par un /', __FILE__));
        }
        if ($this->getConfiguration('name') === '') {
            throw new Exception(__('Le champs Nom ne peut être vide', __FILE__));
        }
        // Si la chaîne contient des caractères spéciaux
        if (!preg_match("#[a-zA-Z0-9_-]$#", $this->getConfiguration('name'))) {
            throw new Exception(__('Le champs Nom ne peut contenir de caractères spéciaux', __FILE__));
        }
        // Si la chaîne contient des caractères spéciaux
        if (preg_match("/\\s/", $this->getConfiguration('name'))) {
            throw new Exception(__('Le champs Nom ne peut contenir d\'espaces', __FILE__));
        }
        if ($this->getConfiguration('size') == '') {
            throw new Exception(__('Le champs Dimension ne peut être vide', __FILE__));
        }
        // Si la chaîne contient des caractères spéciaux
        if (!preg_match($this->paternForPregMatch(), $this->getConfiguration('size'))) {
            if (!preg_match($this->paternForPregMatch(), $this->getConfiguration('size'))) {
                if (!preg_match($this->paternForPregMatch(), $this->getConfiguration('size'))) {
                    if (!preg_match($this->paternForPregMatch(), $this->getConfiguration('size'))) {
                        throw new Exception(__('Le champs Dimension doit être au format nombrexnombre (ex 500x500)', __FILE__));
                    }
                }
            }
        }
    }

    public function preSave()
    {
        if ($this->getConfiguration('delay') == '') {
            $this->setConfiguration('delay', 120);
        }
        if ($this->getConfiguration('targetFolder') == '') {
            $this->setConfiguration('targetFolder', '/tmp');
        }
        if (!$this->getConfiguration('lastName') == '') {
            if ($this->getConfiguration('name') !== $this->getConfiguration('lastName')) {
                exec('echo Remove Service Name : ' . $this->getConfiguration('lastName') . ' >> ' . log::getPathToLog('xplanet_delete') . ' 2>&1 &');
                $cmd = '/bin/bash ' . dirname(__FILE__) . '/../../3rdparty/delete.sh ' . $this->getConfiguration('lastName');
                $cmd .= ' >> ' . log::getPathToLog('xplanet_delete') . ' 2>&1 &';
                exec($cmd);
                sleep(2);
                $this->setConfiguration('lastName', $this->getConfiguration('name'));
                exec('echo Setting Last Service Name : ' . $this->getConfiguration('lastName') . ' >> ' . log::getPathToLog('xplanet_delete') . ' 2>&1 &');
            }
        }
//		if ($this->getIsEnable()) {
//			$this->setConfiguration('cameraPath','snapshot_' . $this->getConfiguration('name') . '.jpg');
//		}
        $this->setConfiguration('serviceName', $this->getConfiguration('name'));
    }

    public function postSave()
    {
        foreach (eqLogic::byType('xplanet') as $xplanet) {
            $xplanet->getInformations();
        }
        if ($this->getIsEnable()) {
            //$URL = str_replace("/","\\/",$this->getConfiguration('url'));
            //$URL = str_replace("&","\\&",$URL);
            $URL = escapeshellarg($this->getConfiguration('url'));
            $URL = str_replace("&", "\\&", $URL);
            $cmd = '/bin/bash ' . dirname(__FILE__) . '/../../3rdparty/create.sh ' . $this->getConfiguration('name') . ' ' . str_replace("/", "\\/", rtrim($this->getConfiguration('targetFolder'), "/")) . ' ' . $this->getConfiguration('type') . ' ' . $this->getConfiguration('delay') . ' ' . $this->getConfiguration('size');
            $cmd .= ' >> ' . log::getPathToLog('xplanet_create') . ' 2>&1 &';
            exec('echo Create/Update Service Name : ' . $this->getConfiguration('name') . ' IP : ' . $this->getConfiguration('ip') . ' Emplacement : ' . str_replace("/", "\\/", rtrim($this->getConfiguration('targetFolder'), "/")) . ' Type : ' . $this->getConfiguration('type') . ' Delay : ' . $this->getConfiguration('delay') . ' Size : ' . $this->getConfiguration('size') . ' >> ' . log::getPathToLog('xplanet_create') . ' 2>&1 &');
            exec($cmd);
        } else {
            $cmd = '/bin/bash ' . dirname(__FILE__) . '/../../3rdparty/stop.sh ' . $this->getConfiguration('name');
            $cmd .= ' >> ' . log::getPathToLog('xplanet_status') . ' 2>&1 &';
            exec($cmd);
        }
    }

    public function preRemove()
    {
        $cmd = '/bin/bash ' . dirname(__FILE__) . '/../../3rdparty/delete.sh ' . $this->getConfiguration('name');
        $cmd .= ' >> ' . log::getPathToLog('xplanet_delete') . ' 2>&1 &';
        exec('echo Delete Service Name : ' . $this->getConfiguration('name') . ' >> ' . log::getPathToLog('xplanet_delete') . ' 2>&1 &');
        exec($cmd);
    }

    public function toHtml($_version = 'dashboard')
    {
        if ($this->getIsEnable() != 1) {
            return '';
        }
        if (!$this->hasRight('r')) {
            return '';
        }
        $_version = jeedom::versionAlias($_version);
        if ($this->getDisplay('hideOn' . $_version) == 1) {
            return '';
        }
        $mc = cache::byKey('xplanetWidget' . $_version . $this->getId());
        if ($mc->getValue() != '') {
            return preg_replace("/" . preg_quote(self::UIDDELIMITER) . "(.*?)" . preg_quote(self::UIDDELIMITER) . "/", self::UIDDELIMITER . mt_rand() . self::UIDDELIMITER, $mc->getValue());
        }

        $replace = array(
            '#id#'               => $this->getId(),
            '#type#'             => $this->getConfiguration('type'),
            '#name#'             => $this->getConfiguration('name'),
            '#background_color#' => $this->getBackgroundColor($_version),
            '#eqLink#'           => ($this->hasRight('w')) ? $this->getLinkToConfiguration() : '#',
            '#uid#'              => 'xplanet' . $this->getId() . self::UIDDELIMITER . mt_rand() . self::UIDDELIMITER,
        );


        $parameters = $this->getDisplay('parameters');
        if (is_array($parameters)) {
            foreach ($parameters as $key => $value) {
                $replace['#' . $key . '#'] = $value;
            }
        }

        if ($this->getConfiguration('modeImage', 0) == 1) {
            $replace['#visibilityIcon#']  = "none";
            $replace['#visibilityImage#'] = "block";
        } else {
            $replace['#visibilityIcon#']  = "block";
            $replace['#visibilityImage#'] = "none";
        }

        $html = template_replace($replace, getTemplate('core', $_version, 'current', 'xplanet'));
        cache::set('xplanetWidget' . $_version . $this->getId(), $html, 0);
        return $html;
    }

    public function getInformations()
    {

        foreach ($this->getCmd() as $cmd) {

            $state = exec("sudo /etc/init.d/xplanet-service-$name status");

            $cmd->event($state);
        }
        if (is_object($state)) {
            return $state;
        } else {
            return '';
        }
    }

    private function paternForPregMatch()
    {
        return "#[0-9][0-9][0-9]x[0-9][0-9][0-9][0-9]$#";
    }

}

class xplanetCmd extends cmd
{
    /*     * *********************Methode d'instance************************* */

    public function execute($_options = null)
    {
        $ip   = $this->getConfiguration('ip');
        $name = $this->getConfiguration('name');

        $state = exec("/etc/init.d/xplanet-service-$name status");

        $cmd->event($state);
        if (is_object($state)) {
            return $state;
        } else {
            return '';
        }
    }

}
