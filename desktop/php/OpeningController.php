<?php
/* This file is part of NextDom Software.
 *
 * NextDom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * NextDom Software is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with NextDom Software. If not, see <http://www.gnu.org/licenses/>.
 *
 * @Support <https://www.nextdom.org>
 * @Email   <admin@nextdom.org>
 * @Authors/Contributors: Sylvaner, cyrilphoenix71, slobberbone, Virux54
 */

use NextDom\Controller\BaseController;
use NextDom\Managers\PluginManager;
use NextDom\Managers\EqLogicManager;
use NextDom\Managers\JeeObjectManager;
use NextDom\Helpers\Utils;
use NextDom\Helpers\Render;

/**
 * Class ApiController
 * @package NextDom\Controller\Admin
 */
class OpeningController extends BaseController
{
    /**
     * Render Opening plugin page
     *
     * @param array $pageData Page data
     *
     * @return string Content of API page
     *
     * @throws \Exception
     */
    public static function get(&$pageData): string
    {
				// Plugin path
				$pluginId = 'Opening';
				$plugin = PluginManager::byId($pluginId);
				$pluginPath = NEXTDOM_ROOT . '/plugins/' . $pluginId . '/desktop/';

				// Get plugin eqLogics
				$pageData['eqLogics'] = [];
				$eqLogics = EqLogic::byType($plugin->getId());
				foreach ($eqLogics as $eqLogic) {
						$eqLogicData = [];
						$eqLogicData['id'] = $eqLogic->getId();
						$eqLogicData['name'] = $eqLogic->getHumanName(true, true);
						$eqLogicData['windowmodel'] = $eqLogic->getConfiguration('windowmodel','');
						$eqLogicData['familymodel'] = $eqLogic->getConfiguration('familymodel','');
						$eqLogicData['backmodel'] = $eqLogic->getConfiguration('backmodel','FFFFFF-0.png');
						$eqLogicData['storemodel'] = $eqLogic->getConfiguration('storemodel','FFFFFF-0.png');
						$pageData['eqLogics'][] = $eqLogicData;
				}

				// Get all objects (rooms)
				$pageData['rooms'] = [];
				$rooms = JeeObjectManager::all();
				foreach ($rooms as $room) {
						$roomData = [];
						$roomData['id'] = $room->getId();
						$roomData['name'] = $room->getName();
						$pageData['rooms'][] = $roomData;
				}

				// Get eqLogic categories
				$pageData['categories'] = [];
				foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
						$categoriesData = [];
						$categoriesData['key'] = $key;
						$categoriesData['name'] = $value['name'];
				}

				// Pools
				Utils::sendVarsToJS([
					'eqType', $pluginId
				]);
				$pageData['CSS_POOL'][] = $pluginPath . 'css/' . $pluginId . '.css';
				$pageData['CSS_POOL'][] = $pluginPath . 'css/' . $pluginId . 'Layer.css';
        $pageData['JS_END_POOL'][] = $pluginPath . 'js/' . $pluginId . '.js';
				$pageData['JS_END_POOL'][] = $pluginPath . 'js/' . $pluginId . '.js';
				$pageData['JS_END_POOL'][] = NEXTDOM_ROOT . '/public/js/core/plugin.template.js';

				// Render
        return Render::getInstance()->get($pluginId . '.html.twig', $pageData);
    }
}
