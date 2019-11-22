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

		$plugin = plugin::byId('Opening');
		sendVarToJS('eqType', $plugin->getId());
		$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow Openingconfig">
		<div class="eqLogicThumbnailDisplay">
			<legend><i class="fas fa-cog"></i>{{Gestion}}</legend>
			<div class="eqLogicThumbnailContainer">
				<div class="cursor eqLogicAction" data-action="gotoPluginConf" style="background-color : #ffffff; height : 130px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 140px;margin-left : 10px;">
					<center>
						<i class="fas fa-wrench" style="font-size : 5em;color:#33B8CC;margin-top : 20px;"></i>
					</center>
					<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#33B8CC"><center>{{Configuration}}</center></span>
				</div>
			</div>
			<legend><i class="icon jeedom-fenetre-ferme"></i>{{Mes Ouvertures}}</legend>
			<div class="eqLogicThumbnailContainer">
				<div class="cursor eqLogicAction" data-action="add" style="text-align: center; background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 140px;margin-left : 10px;" >
					<i class="fas fa-plus-circle" style="font-size : 7em;color:#33B8CC;margin-top : 25px;"></i>
					<br>
					<span style="font-size : 1.1em;position:relative; top : 10px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#33B8CC">{{Ajouter}}</span>
				</div>
				<?php
				foreach (eqLogic::byType('Opening') as $eqLogic) {
					echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="text-align: center; background-color : #ffffff; height : 200px;margin-bottom : 0px;padding : 5px;border-radius: 2px;width : 140px;margin-left : 10px;" >';
					if($eqLogic->getConfiguration('windowmodel','') == '')
					{
						echo '<img src="plugins/Opening/doc/images/Opening_icon2.png" height="100" width="115" style="margin-top : 10px;margin-bottom : 20px;" />';
					}else{
						echo '<span class="OpeningWrp family'.$eqLogic->getConfiguration('familymodel','').'" style="height:100px;width:100px;margin-top : 10px;">';
						echo '	<span class="layer back bg"><img src="plugins/Opening/core/template/dashboard/images/'.$eqLogic->getConfiguration('backmodel','FFFFFF-0.png').'"/></span>';
						if($eqLogic->getConfiguration('familymodel','') == '6')
						{
							echo '	<span class="layer store bg"><img style="left:-140%;top:0%;" src="plugins/Opening/core/template/dashboard/images/'.$eqLogic->getConfiguration('storemodel','FFFFFF-0.png').'"/></span>';
						}else{
							echo '	<span class="layer store bg"><img style="top:-140%;left:0%;" src="plugins/Opening/core/template/dashboard/images/'.$eqLogic->getConfiguration('storemodel','FFFFFF-0.png').'"/></span>';
						}
						echo '	<span class="layer window"><img src="plugins/Opening/core/template/dashboard/images/'.$eqLogic->getConfiguration('windowmodel','FFFFFF-0.png').'"/></span>';
						echo '</span>';
					}
					echo "<br>";
					echo '<span style="font-size : 1.1em;position:relative; top : 0px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">' . $eqLogic->getHumanName(true, true) . '</span>';
					echo '</div>';
				}
				?>
			</div>
		</div>

		<div class="eqLogic" style="display: none;">
		<a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fas fa-check-circle"></i>{{Sauvegarder}}</a>
		<a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fas fa-minus-circle"></i>{{Supprimer}}</a>
		<a class="btn btn-default eqLogicAction pull-right" data-action="copy"><i class="fas fa-files"></i>{{Dupliquer}}</a>
		<a class="btn btn-default eqLogicAction pull-right" data-action="configure"><i class="fas fa-cogs"></i>{{Configuration avancée}}</a>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-chevron-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer"></i>{{Equipement}}</a></li>
			<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i>{{Commandes}}</a></li>
		</ul>
		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<br/>
				<div class='row'>
					<div class="col-sm-7">
					<form class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<label class="col-sm-3 control-label">{{Nom de l'ouvrant}}</label>
							<div class="col-sm-8">
								<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
								<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'ouvrant}}"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" >{{Objet parent}}</label>
							<div class="col-sm-8">
								<select class="eqLogicAttr form-control" data-l1key="object_id">
									<option value="">{{Aucun}}</option>
									<?php
									foreach (object::all() as $object) {
										echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-sm-8">
								<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
								<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">{{Catégorie}}</label>
							<div class="col-sm-8">
								<?php
								foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
									echo '<label class="checkbox-inline">';
									echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
									echo '</label>';
								}
								?>
							</div>
						</div>
						<br>
						<br>
						<fieldset>
							<legend><i class="icon fas fa-cog"></i>{{Paramètres Généraux}}
							<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Paramètres Généraux}}"
								data-content="{{Cette section sert à selectionner la famille de l'ouvrant afin de filtrer les modèles et options qui suivent}}.<br>
								{{On vient aussi définir les options de géometrie de l'ouvrant}}."></i>
							</legend>
							<div class="form-group">
								<label class="col-sm-3 control-label" style="margin-top:10px">{{Famille}}</label>.
								<div class="col-sm-8">
									<div class="btn-group customselect form-control" style="width: 100%;">
										<input type="hidden" class="eqLogicAttr" data-l1key="configuration" data-l2key="familymodel">
										<div class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<span class="modelcaption"><img src="plugins/Opening/core/template/dashboard/images/PreviewError.png">{{Erreur}}</span>
											<span class="glyphicon glyphicon-chevron-down"></span>
										</div>
										<ul class="dropdown-menu form-control">
											<?php
											$families = array(
												'{{Fenêtre double, Fenetre & Velux carrée, Porte large (Largeur = Hauteur)}}',
												'{{Fenêtre simple verticale, Velux long (Largeur &#60; Hauteur)}}',
												'{{Fenêtre simple horizontale, Vasistas (Largeur &#62; Hauteur)}}',
												'{{Fenêtre double haute(Largeur &#60; Hauteur)}}',
												'{{Porte simple (Largeur &#60; Hauteur)}}',
												'{{Porte de garage (Largeur &#62; Hauteur)}}',
												'{{Portails (système d\'ouverture différent, Largeur &#62; Hauteur))}}',
												'{{Divers (frigo etc...)}}',
												'{{Fenêtre triple (Largeur &#62; Hauteur)}}',
												'{{Baies (Largeur = Hauteur)}}'
											);
											chdir(__DIR__."/../../core/template/dashboard/images/");
											foreach($families as $familyIdx=>$familyName)
											{
												$models = glob("Opening{$familyIdx}*State0.png");
												if($models===false || trim($models[0])=='')
												{
													$filename = 'PreviewNothing.png';
												}else{
													$filename = $models[0];
												}
												echo '<li data-value="'.$familyIdx.'"><img src="plugins/Opening/core/template/dashboard/images/'.$filename.'">'.str_pad($familyIdx, 2, '0', STR_PAD_LEFT).' '.$familyName.'</li>';
											}
											?>
										</ul>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Taille}}</label>
								<div class="col-sm-1">
									<div class="input-group">
										<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="windowsize" placeholder="85" value="85"/>
									</div>
								</div>
								<span>{{pixels}}</span>
								<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Taille}}"
								data-content="{{Saisissez ici la taille en pixel de l'image de votre ouvrant}}.<br>
								{{La valeur par défaut (ou si omise) est à}} 85 pixels.<br>
								{{Attention une pixelisation sera perceptible si la valeur saisie est supérieure à}} 128 pixels."></i>
							</div>
							<div class="form-group modeadvancedVisible">
								<label class="col-sm-3 control-label">{{Rotation 2D}}</label>
								<div class="col-sm-1">
									<div class="input-group">
										<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="windowrotate" placeholder="0" value="0"/>
									</div>
								</div>
								<span>{{degrés}}</span>
								<i class="icon fa fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Rotation 2D}}"
								data-content="{{Saisissez ici l'angle de rotation 2D de l'ouvrant en degrés}}.<br>
								{{La valeur par défaut (ou si omise) est à}} 0°.<br>
								{{La valeur peut être négative}}."></i>
							</div>
							<div class="form-group modeadvancedVisible">
								<label class="col-sm-3 control-label">{{Rotation 3D X}}</label>
								<div class="col-sm-1">
									<div class="input-group">
										<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="windowrotateX" placeholder="0" value="0"/>
									</div>
								</div>
								<span>{{degrés}}</span>
								<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Rotation 3D X}}"
								data-content="{{Saisissez ici l'angle de rotation 3D suivant l'axe X de l'ouvrant en degrés}}.<br>
								{{La valeur par défaut (ou si omise) est à}} 0°.<br>
								{{La valeur peut être négative}}.<br>
								{{Une valeur standard à associer à la perspective est de}} 30°.<br>
								{{Attention sans valeur de perspective 3D l'effet visuel risque de ne pas être interressant}}.<br>
								{{En revanche cette rotation peut être utilisée pour changer la hauteur de l'ouvrant à condition de mettre une perspective 3D nulle}}"></i>
							</div>
							<div class="form-group modeadvancedVisible">
								<label class="col-sm-3 control-label">{{Rotation 3D Y}}</label>
								<div class="col-sm-1">
									<div class="input-group">
										<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="windowrotateY" placeholder="0" value="0"/>
									</div>
								</div>
								<span>{{degrés}}</span>
								<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Rotation 3D Y}}"
								data-content="{{Saisissez ici l'angle de rotation 3D suivant l'axe Y de l'ouvrant en degrés}}.<br>
								{{La valeur par défaut (ou si omise) est à}} 0°.<br>
								{{La valeur peut être négative}}.><br>
								{{Une valeur standard à associer à la perspective est de}} 30°.<br>
								{{Attention sans valeur de perspective 3D l'effet visuel risque de ne pas être interressant}}.<br>
								{{En revanche cette rotation peut être utilisée pour changer la hauteur de l'ouvrant à condition de mettre une perspective 3D nulle}}"></i>
							</div>
							<div class="form-group modeadvancedVisible">
								<label class="col-sm-3 control-label">{{Rotation 3D Z}}</label>
								<div class="col-sm-1">
									<div class="input-group">
										<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="windowrotateZ" placeholder="0" value="0"/>
									</div>
								</div>
								<span>{{degrés}}</span>
								<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Rotation 3D Z}}"
								data-content="{{Saisissez ici l'angle de rotation 3D suivant l'axe Z de l'ouvrant en degrés}}.<br>
								{{La valeur par défaut (ou si omise) est à}} 0°.<br>
								{{La valeur peut être négative}}."></i>
							</div>
							<div class="form-group modeadvancedVisible">
								<label class="col-sm-3 control-label">{{Perspective 3D}}</label>
								<div class="col-sm-1">
									<div class="input-group">
										<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="windowperscpective" placeholder="0" value="0"/>
									</div>
								</div>
								<span>{{px}}</span>
								<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Perspective 3D}}"
								data-content="{{Saisissez ici la valeur de la perspective l'ouvrant}}.<br>
								{{La valeur par défaut (ou si omise) est à}} 0.<br>
								{{La valeur peut être négative}}.<br>
								{{Une valeur standard est de}} 50.<br>
								{{Attention la perspective est visible uniquement si la rotation 3D sur l'axe X ou Y est non nulle}}".></i>
							</div>
							<div class="form-group modeadvancedVisible">
								<label class="col-sm-3 control-label">{{Compensation de Perspective en Y}}</label>
								<div class="col-sm-1">
									<div class="input-group">
										<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="windowtranslateY" placeholder="0" value="0"/>
									</div>
								</div>
								<span>{{px}}</span>
								<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Compensation de Perspective en Y}}"
								data-content="{{Saisissez ici la compensation de perspective en Y l'ouvrant}}.<br>
								{{Elle sert à compenser verticalement de positionnement de l'ouvrant surtout en cas de perspective non nulle}}.<br>
								{{La valeur par défaut (ou si omise) est à}} 0.<br>
								{{La valeur peut être négative}}".></i>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-11">
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="modeadvancedplugin">
									<span>{{Mode avancé}}</span>
									<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Mode avancé}}"
									data-content="{{En cochant cette option, vous activez le mode avancé}}.<br>
									{{Ce mode fait apparaître des options de personnalisation supplémentaires}}."></i>
								</div>
							</div>
						</fieldset>
						<br>
						<br>
						<fieldset data-layer="window">
							<legend><i class="icon jeedom-fenetre-ouverte"></i>{{Ouverture}}
								<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Ouverture}}"
								data-content="{{Cette section sert à afficher l'image d'un ouvrant de la famille choisie}}.<br>
								{{Dans le cas d'un modèle d'ouverture avec store intégré, le modèle store ne sera pas pris en compte}}."></i>
							</legend>
							<div class="form-group">
								<label class="col-sm-3 control-label" style="margin-top:10px">{{Modèle}}</label>
								<div class="col-sm-8">
									<div class="btn-group modellist customselect form-control" style="width: 100%;">
										<input type="hidden" class="eqLogicAttr" data-l1key="configuration" data-l2key="windowmodel">
										<div class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<span class="modelcaption"><img src="plugins/Opening/core/template/dashboard/images/PreviewNothing.png">{{Erreur}}</span>
											<span class="glyphicon glyphicon-chevron-down"></span>
										</div>
										<ul class="dropdown-menu form-control">
											<li data-value="PreviewNothing.png"><img src="plugins/Opening/core/template/dashboard/images/PreviewNothing.png">{{Pas de modèle choisi}}</li>
											<?php
											chdir(__DIR__."/../../core/template/dashboard/images/");
											$files = glob("Opening[0-9]*State1{,000}\.png",GLOB_BRACE);
											natsort($files);
											foreach ($files as $filename)
											{
												$displayname = str_replace(array('Opening','State1.png','State1000.png','_'),array('','',' '),$filename);
												$famille = substr($displayname,0,1);
												$modele = substr($displayname,1);
												$withStore = (stristr($modele, 'X') === false) ? 0 : 1;
												$modele = str_replace('X',__(' Store Intégré',__FILE__),$modele); // Opening105XState1000.png

												$filenameClose =  str_replace('State1','State0',$filename);
												echo '<li data-family="'.$famille.'" data-value="'.$filename.'" data-withstore="'.$withStore.'"><img src="plugins/Opening/core/template/dashboard/images/'.$filename.'"><img src="plugins/Opening/core/template/dashboard/images/'.$filenameClose.'">'.$modele.'</li>';
											}
											?>
										</ul>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Équipement Droite}}</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input type="text" class="eqLogicAttr form-control tooltips" data-l1key="configuration" data-l2key="windowdcmdeqp"/>
										<span class="input-group-btn">
											<a class="btn btn-default selectEqInfo"><i class="fa fa-list-alt"></i></a>
										</span>
									</div>
								</div>
								<i class="icon fas fa-question-circle" style="margin-top:12px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Equipement Droite}}"
								data-content="{{Saisissez ici l'équipement ou le capteur associé à l'ouvrant droit}}.<br>
								{{Vous pouvez saisir qu'un seul équipement, l'état final de l'ouvrant sera une combinaison des 2 : droite + 2*gauche}}.<br>
								{{La valeur de l'état de chaque équipement devra être 0 ou 1}}.<br>
								{{Vous avez la possibilité d'utiliser un seul équipement ayant déjà une combinaison d'état}}."></i>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Équipement Gauche}}</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input type="text" class="eqLogicAttr form-control tooltips" data-l1key="configuration" data-l2key="windowgcmdeqp"/>
										<span class="input-group-btn">
											<a class="btn btn-default selectEqInfo"><i class="fa fa-list-alt"></i></a>
										</span>
									</div>
								</div>
								<i class="icon fas fa-question-circle" style="margin-top:12px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Equipement Gauche}}"
								data-content="{{Saisissez ici l'équipement ou le capteur associé à l'ouvrant gauche}}.<br>
								{{Vous pouvez saisir qu'un seul équipement, l'état final de l'ouvrant sera une combinaison des 2 :droite + 2*gauche}}.<br>
								{{La valeur de l'état de chaque équipement devra être 0 ou 1}}.<br>
								{{Vous avez la possibilité d'utiliser un seul équipement ayant déjà une combinaison d'état}}."></i>
							</div>
							<div class="form-group modeadvancedVisible">
								<div class="col-sm-offset-3 col-sm-6">
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="windowinvertstate">
									<span>{{Inversé l'état}}</span>
									<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Inversé l'état}}"
									data-content="{{En cochant cette case l'interprétation de l'état des équipements droite et gauche seront inversés}}.<br>
									{{Par défaut 0 signifie fermé, 1 signifie ouvert}}.<br>
									{{Coché, 0 signifie ouvert, 1 signifie fermé}}."></i>
									<br><br>
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="windowshowstate">
									<span>{{Afficher l'état}}</span>
									<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Afficher l'état}}"
									data-content="{{En cochant cette case une information reprenant l'état de l'ouvrant francisé sera affichée}}."></i>
									<br><br>
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="windowshowstateduration">
									<span>{{Afficher la durée de l'état}}</span>
									<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Afficher la durée de l'état}}"
									data-content="{{En cochant cette case l'information d'état francisée se voit completée par la durée depuis de dernier état}}."></i>
									<br><br>
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="windowinvertposition">
									<span>{{Miroir image}}</span>
									<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Miroir image}}"
									data-content="{{En cochant cette case l'image de l'ouvrant sera inversée façon miroir}}."></i>
									<br>
								</div>
							</div>
						</fieldset>
						<br>
						<br>
						<fieldset data-layer="store">
							<legend><i class="icon jeedom-volet-ouvert"></i>{{Volet}}
								<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Volet}}"
								data-content="{{Cette section sert à afficher l'image d'un volet positionné proportionnelement à la valeur de l'état d'un équipement volet}}.<br>
								{{Dans le cas d'un modèle d'ouverture avec store intégré, le modèle store ne sera pas pris en compte mais l'état de l'équipement sera utilisé}}."></i>
							</legend>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-8">
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="modeStoreplugin">
									<span>{{Activer}}</span>
									<i class="icon fas fa-exclamation-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Activer}}"
									data-content="{{Attention vous devez aussi selectionner un modèle et un équipement pour que cela s'affiche réellement}}."></i>
									<br>
								</div>
							</div>
							<div class="modeStoreVisible">
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Modèle}}</label>
									<div class="col-sm-8">
										<div class="btn-group modellist customselect form-control" style="width: 100%;">
											<input type="hidden" class="eqLogicAttr" data-l1key="configuration" data-l2key="storemodel">
											<div class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="modelcaption"><img src="plugins/Opening/core/template/dashboard/images/PreviewError.png">{{Erreur}}</span>
												<span class="glyphicon glyphicon-chevron-down"></span>
											</div>
											<ul class="dropdown-menu form-control">
												<li data-value="FFFFFF-0.png"><img src="plugins/Opening/core/template/dashboard/images/PreviewNothing.png">{{Pas de volet}}</li>
												<?php
												chdir(__DIR__."/../../core/template/dashboard/images/");
												foreach (glob("Store*.png") as $filename)
												{
													$displayname = str_replace(array('Store','.png','_'),array('','',' '),$filename);
													$famille = substr($displayname,0,1);
													$modele = substr($displayname,1);
													echo '<li data-family="'.$famille.'" data-value="'.$filename.'"><img src="plugins/Opening/core/template/dashboard/images/'.$filename.'">'.$modele.'</li>';
												}
												?>
											</ul>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Équipement}}</label>
									<div class="col-sm-7">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control tooltips" data-l1key="configuration" data-l2key="storecmdeqp"/>
											<span class="input-group-btn">
												<a class="btn btn-default selectEqInfo"><i class="fa fa-list-alt"></i></a>
											</span>
										</div>
									</div>
									<i class="icon fas fa-question-circle" style="margin-top:12px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Equipement}}"
									data-content="{{Saisissez ici l'équipement associé au volet}}."></i>
								</div>
								<div class="form-group modeadvancedVisible">
									<label class="col-sm-3 control-label">{{Valeur Mini de l'état}}</label>
									<div class="col-sm-1">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="storeminvalue" placeholder="0" value="0"/>
										</div>
									</div>
									<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Valeur Mini de l'état}}"
									data-content="{{Saisissez ici la valeur minimum que l'état du volet peut prendre}}.<br>
									{{La valeur par défaut (ou si omise) est à}} 0.<br>
									{{La valeur peut être négative et est sans unité définie}}."></i>
								</div>
								<div class="form-group modeadvancedVisible">
									<label class="col-sm-3 control-label">{{Valeur Maxi de l'état}}</label>
									<div class="col-sm-1">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="storemaxvalue" placeholder="99" value="99"/>
										</div>
									</div>
									<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Valeur Maxi de l'état}}"
									data-content="{{Saisissez ici la valeur maximum que l'état du volet peut prendre}}.<br>
									{{La valeur maximum DOIT être supérieure à la valeur minimum}}.<br>
									{{La valeur par défaut (ou si omise) est à}} 99.<br>
									{{La valeur peut être négative et est sans unité définie}}."></i>
								</div>
								<div class="form-group modeadvancedVisible">
									<div class="col-sm-offset-3 col-sm-8">
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="storeinvertstate">
										<span>{{Inversé l'état}}</span>
										<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
										title="{{Inversé l'état}}"
										data-content="{{En cochant cette case l'échelle de l'état du volet sera inversée}}.<br>
										{{Par défaut le mini à 0 signifie volet ouvert ou fermé a 0%, 99 signifie fermé à 100%}}.<br>
										{{Coché, 0 signifie fermé ou ouvert à 0%, 99 signifie ouvert à 100%}}."></i>
										<br><br>
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="storeshowstate">
										<span>{{Afficher l'état}}</span>
										<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
										title="{{Afficher l'état}}"
										data-content="{{En cochant cette case une information reprenant l'état du volet francisé sera affichée}}."></i>
										<br><br>
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="storeshowstateduration">
										<span>{{Afficher la durée de l'état}}</span>
										<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
										title="{{Afficher la durée de l'état}}"
										data-content="{{En cochant cette case l'information d'état francisée se voit completée par la durée depuis de dernier état}}."></i>
										<br>
									</div>
								</div>
							</div>
						</fieldset>
						<br>
						<br>
						<fieldset data-layer="batt">
							<legend><i class="icon jeedom-prise"></i>{{Batterie}}
								<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Batterie}}"
								data-content="{{Cette section sert à afficher via une icône une alerte de niveau faible de batterie d'un des équipements}}."></i>
							</legend>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-6">
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="modeBatplugin">
									<span>{{Activer}}</span>
									<i class="icon fas fa-exclamation-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Activer}}"
									data-content="{{Attention vous devez aussi selectionner un modèle et un équipement pour que cela s'affiche réellement}}."></i>
									<br>
								</div>
							</div>
							<div class="modeBatVisible">
								<div class="form-group">
									<label class="col-sm-3 control-label" style="margin-top:10px">{{Modèle}}</label>
									<div class="col-sm-8">
										<div class="btn-group modellist customselect form-control" style="width: 100%;">
											<input type="hidden" class="eqLogicAttr" data-l1key="configuration" data-l2key="batterymodel">
											<div class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="modelcaption"><img src="plugins/Opening/core/template/dashboard/images/PreviewError.png">{{Erreur}}</span>
												<span class="glyphicon glyphicon-chevron-down"></span>
											</div>
											<ul class="dropdown-menu form-control">
												<li data-value="FFFFFF-0.png"><img src="plugins/Opening/core/template/dashboard/images/PreviewNothing.png">{{Pas d'alerte batterie}}</li>
												<?php
												chdir(__DIR__."/../../core/template/dashboard/images/");
												foreach (glob("Bat*.png") as $filename)
												{
													$displayname = str_replace(array('Bat','.png','_'),array('','',' '),$filename);
													echo '<li data-value="'.$filename.'"><img src="plugins/Opening/core/template/dashboard/images/'.$filename.'">'.$displayname.'</li>';
												}
												?>
											</ul>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Équipement}}</label>
									<div class="col-sm-7">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control tooltips" data-l1key="configuration" data-l2key="batterycmdeqp"/>
											<span class="input-group-btn">
												<a class="btn btn-default selectEqInfo"><i class="fa fa-list-alt"></i></a>
											</span>
										</div>
									</div>
									<i class="icon fas fa-question-circle" style="margin-top:12px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Equipement}}"
									data-content="{{Saisissez ici l'équipement ou le capteur dont vous voulez surveillez la batterie}}."></i>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"  style="margin-top:10px">{{Positionnement}}</label>
									<div class="col-sm-7">
										<div class="btn-group customselect positionlist form-control"  style="width: 100%;">
											<input type="hidden" class="eqLogicAttr" data-l1key="configuration" data-l2key="batteryposition">
											<div class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="modelcaption"><img src="plugins/Opening/core/template/dashboard/images/PreviewError.png">{{Erreur}}</span>
												<span class="glyphicon glyphicon-chevron-down"></span>
											</div>
											<ul class="dropdown-menu form-control">
												<?php
												$positions = array(
													"TopLeft",
													"TopCenter",
													"TopRight",
													"CenterLeft",
													"CenterCenter",
													"CenterRight",
													"BottomLeft",
													"BottomCenter",
													"BottomRight",
												);
												foreach($positions as $posIdx=>$posName)
												{
													echo '<li data-value="'.$posName.'"><img src="plugins/Opening/core/template/dashboard/images/Position'.($posIdx+1).'.png">'.$posName.'</li>';
												}
												?>
											</ul>
										</div>
									</div>
									<i class="icon fas fa-question-circle" style="margin-top:20px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Positionnement}}"
									data-content="{{Choisissez ici la position de l'icône batterie dans l'image}}."></i>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Niveau d'alerte}}</label>
									<div class="col-sm-1">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="batteryminvalue" placeholder="15" value="15"/>
										</div>
									</div>
									<span>{{%}}</span>
									<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Niveau d'alerte}}"
									data-content="{{Saisissez ici le niveau d'alerte batterie faible en dessous duquel l'icône sera affichée}}.<br>
									{{La valeur par défaut (ou si omise) est à}} 15%."></i>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Taille}}</label>
									<div class="col-sm-1">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control layersize" data-l1key="configuration" data-l2key="batterysize" placeholder="42" value="42"/>
										</div>
									</div>
									<span>{{pixels}}</span>
									<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Taille}}"
									data-content="{{Saisissez ici la taille en pixel de l'icône batterie}}.<br>
									{{La valeur par défaut (ou si omise) est à}} 42 pixels.<br>
									{{Attention une pixelisation sera perceptible si la valeur saisie est supérieure}} 42 pixels."></i>
								</div>
								<div class="form-group modeadvancedVisible">
									<label class="col-sm-3 control-label">{{Marges}}</label>
									<div class="col-sm-1">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control layermarges" data-l1key="configuration" data-l2key="batterymarges" placeholder="0" value="0"/>
										</div>
									</div>
									<span>{{%}}</span>
									<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Marges}}"
									data-content="{{Saisissez ici le pourcentage de l'image de l'ouvrant à utiliser comme marge de l'icône batterie}}.<br>
									{{La valeur par défaut (ou si omise) est à}} 0%.<br>
									{{La marge sera appliquée par rapport au bord extérieur de l'image de l'ouvrant et suivant le position choisie de l'icône}}."></i>
								</div>
								<div class="form-group modeadvancedVisible">
									<div class="col-sm-offset-3 col-sm-6">
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="batteryshowstate">
										<span>{{Afficher l'état}}</span>
										<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
										title="{{Afficher l'état}}"
										data-content="{{En cochant cette case une information reprenant l'état la batterie francisé sera affichée}}."></i>
										<br><br>
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="batteryshowstateduration">
										<span>{{Afficher la durée de l'état}}</span>
										<i class="icon fa fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
										title="{{Afficher la durée de l'état}}"
										data-content="{{En cochant cette case l'information d'état francisée se voit completée par la durée depuis de dernier état}}."></i>
										<br>
									</div>
								</div>
							</div>
						</fieldset>
						<br>
						<br>
						<fieldset data-layer="motion">
							<legend><i class="icon jeedom-mouvement"></i>{{Motion}}
								<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Motion}}"
								data-content="{{Cette section sert à afficher via une icone l'état de capteurs de présence ou de mouvement}}."></i>
							</legend>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-6">
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="modeMotionplugin">
									<span>{{Activer}}</span>
									<i class="icon fas fa-exclamation-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Activer}}"
									data-content="{{Attention vous devez aussi selectionner un modèle et un équipement pour que cela s'affiche réellement}}."></i>
									<br>
								</div>
							</div>
							<div class="modeMotionVisible">
								<div class="form-group">
									<label class="col-sm-3 control-label" style="margin-top:10px">{{Modèle}}</label>
									<div class="col-sm-8">
										<div class="btn-group modellist customselect form-control" style="width: 100%;">
											<input type="hidden" class="eqLogicAttr" data-l1key="configuration" data-l2key="motionmodel">
											<div class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="modelcaption"><img src="plugins/Opening/core/template/dashboard/images/PreviewError.png">{{Erreur}}</span>
												<span class="glyphicon glyphicon-chevron-down"></span>
											</div>
											<ul class="dropdown-menu form-control">
												<li data-value="FFFFFF-0.png"><img src="plugins/Opening/core/template/dashboard/images/PreviewNothing.png">{{Pas de motion}}</li>
												<?php
												chdir(__DIR__."/../../core/template/dashboard/images/");
												foreach (glob("Motion*.png") as $filename)
												{
													$displayname = str_replace(array('Motion','.png','_'),array('','',' '),$filename);
													echo '<li data-value="'.$filename.'"><img src="plugins/Opening/core/template/dashboard/images/'.$filename.'">'.$displayname.'</li>';
												}
												?>
											</ul>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Équipement}}</label>
									<div class="col-sm-7">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control tooltips" data-l1key="configuration" data-l2key="motioncmdeqp"/>
											<span class="input-group-btn">
												<a class="btn btn-default selectEqInfo"><i class="fa fa-list-alt"></i></a>
											</span>
										</div>
									</div>
									<i class="icon fas fa-question-circle" style="margin-top:12px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Equipement}}"
									data-content="{{Saisissez ici l'équipement qui déclenchera des alertes de présence ou de mouvement}}.<br>
									{{Les valeurs prise en compte sont 0 pour pas de détection et donc icône cachée, toute autre valeur pour détection}}."></i>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" style="margin-top:10px">{{Positionnement}}</label>
									<div class="col-sm-7">
										<div class="btn-group customselect positionlist form-control" style="width: 100%;">
											<input type="hidden" class="eqLogicAttr" data-l1key="configuration" data-l2key="motionposition">
											<div class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="modelcaption"><img src="plugins/Opening/core/template/dashboard/images/PreviewError.png">{{Erreur}}</span>
												<span class="glyphicon glyphicon-chevron-down"></span>
											</div>
											<ul class="dropdown-menu form-control">
												<?php
												$positions = array(
													"TopLeft",
													"TopCenter",
													"TopRight",
													"CenterLeft",
													"CenterCenter",
													"CenterRight",
													"BottomLeft",
													"BottomCenter",
													"BottomRight",
												);
												foreach($positions as $posIdx=>$posName)
												{
													echo '<li data-value="'.$posName.'"><img src="plugins/Opening/core/template/dashboard/images/Position'.($posIdx+1).'.png">'.$posName.'</li>';
												}
												?>
											</ul>
										</div>
									</div>
									<i class="icon fas fa-question-circle" style="margin-top:20px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Positionnement}}"
									data-content="{{Choisissez ici la position de l'icône motion dans l'image}}."></i>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Taille}}</label>
									<div class="col-sm-1">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control layersize" data-l1key="configuration" data-l2key="motionsize" placeholder="42" value="42"/>
										</div>
									</div>
									<span>{{pixels}}</span>
									<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Taille}}"
									data-content="{{Saisissez ici la taille en pixel de l'icône motion}}.<br>
									{{La valeur par défaut (ou si omise) est à}} 42 pixels.<br>
									{{Attention une pixelisation sera perceptible si la valeur saisie est supérieure}} 42 pixels."></i>
								</div>
								<div class="form-group modeadvancedVisible">
									<label class="col-sm-3 control-label">{{Marges}}</label>
									<div class="col-sm-1">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control layermarges" data-l1key="configuration" data-l2key="motionmarges" placeholder="0" value="0"/>
										</div>
									</div>
									<span>{{%}}</span>
									<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Marges}}"
									data-content="{{Saisissez ici le pourcentage de l'image de l'ouvrant à utiliser comme marge de l'icône motion}}.<br>
									{{La valeur par défaut (ou si omise) est à}} 0%.<br>
									{{La marge sera appliquée par rapport au bord extérieur de l'image de l'ouvrant et suivant le position choisie de l'icône}}."></i>
								</div>
								<div class="form-group modeadvancedVisible">
									<div class="col-sm-offset-3 col-sm-6">
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="motionshowstate">
										<span>{{Afficher l'état}}</span>
										<i class="icon fa fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
										title="{{Afficher l'état}}"
										data-content="{{En cochant cette case une information reprenant l'état du motion francisé sera affichée}}."></i>
										<br><br>
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="motionshowstateduration">
										<span>{{Afficher la durée de l'état}}</span>
										<i class="icon fa fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
										title="{{Afficher la durée de l'état}}"
										data-content="{{En cochant cette case l'information d'état francisée se voit completée par la durée depuis de dernier état}}."></i>
										<br>
									</div>
								</div>
							</div>
						</fieldset>
						<br>
						<br>
						<fieldset data-layer="alarm">
							<legend><i class="icon jeedom-alerte2"></i>    {{Alarme}}
								<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Alarme}}"
								data-content="{{Cette section sert à afficher via une icone l'état d'activation d'un système d'alarme}}.<br>"></i>
							</legend>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-6">
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="modeAlarmplugin">
									<span>{{Activer}}</span>
									<i class="icon fas fa-exclamation-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Activer}}"
									data-content="{{Attention vous devez aussi selectionner un modèle et un équipement pour que cela s'affiche réellement}}."></i>
									<br>
								</div>
							</div>
							<div class="modeAlarmVisible">
								<div class="form-group">
									<label class="col-sm-3 control-label" style="margin-top:10px">{{Modèle}}</label>
									<div class="col-sm-8">
										<div class="btn-group modellist customselect form-control" style="width: 100%;">
											<input type="hidden" class="eqLogicAttr" data-l1key="configuration" data-l2key="alarmmodel">
											<div class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="modelcaption"><img src="plugins/Opening/core/template/dashboard/images/PreviewError.png">{{Erreur}}</span>
												<span class="glyphicon glyphicon-chevron-down"></span>
											</div>
											<ul class="dropdown-menu form-control">
												<li data-value="FFFFFF-0.png"><img src="plugins/Opening/core/template/dashboard/images/PreviewNothing.png">{{Pas d'alarme}}</li>
												<?php
												chdir(__DIR__."/../../core/template/dashboard/images/");
												foreach (glob("Alarm*State1.png") as $filename)
												{
													$displayname = str_replace(array('Alarm','State1.png','_'),array('','',' '),$filename);
													$filenameAlt = str_replace('State1.png','State2.png',$filename);
													echo '<li data-value="'.$filename.'"><img src="plugins/Opening/core/template/dashboard/images/'.$filename.'"><img src="plugins/Opening/core/template/dashboard/images/'.$filenameAlt.'">'.$displayname.'</li>';
												}
												?>
											</ul>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Équipement}}</label>
									<div class="col-sm-7">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control tooltips" data-l1key="configuration" data-l2key="alarmcmdeqp"/>
											<span class="input-group-btn">
												<a class="btn btn-default selectEqInfo"><i class="fa fa-list-alt"></i></a>
											</span>
										</div>
									</div>
									<i class="icon fas fa-question-circle" style="margin-top:12px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Equipement}}"
									data-content="{{Saisissez ici l'équipement associé au système d'alarme à monitorer}}.<br>
									{{Les valeurs prise en compte sont 0 pour inactive, 1 pour active, 2 pour déclenchée}}"></i>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" style="margin-top:10px">{{Positionnement}}</label>
									<div class="col-sm-7">
										<div class="btn-group customselect positionlist form-control" style="width: 100%;">
											<input type="hidden" class="eqLogicAttr" data-l1key="configuration" data-l2key="alarmposition">
											<div class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="modelcaption"><img src="plugins/Opening/core/template/dashboard/images/PreviewError.png">{{Erreur}}</span>
												<span class="glyphicon glyphicon-chevron-down"></span>
											</div>
											<ul class="dropdown-menu form-control">
												<?php
												$positions = array(
													"TopLeft",
													"TopCenter",
													"TopRight",
													"CenterLeft",
													"CenterCenter",
													"CenterRight",
													"BottomLeft",
													"BottomCenter",
													"BottomRight",
												);
												foreach($positions as $posIdx=>$posName)
												{
													echo '<li data-value="'.$posName.'"><img src="plugins/Opening/core/template/dashboard/images/Position'.($posIdx+1).'.png">'.$posName.'</li>';
												}
												?>
											</ul>
										</div>
									</div>
									<i class="icon fas fa-question-circle" style="margin-top:20px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Positionnement}}"
									data-content="{{Choisissez ici la position de l'icône alarme dans l'image}}."></i>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Taille}}</label>
									<div class="col-sm-1">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control layersize" data-l1key="configuration" data-l2key="alarmsize" placeholder="42" value="42"/>
										</div>
									</div>
									<span>{{pixels}}</span>
									<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Taille}}"
									data-content="{{Saisissez ici la taille en pixel de l'icône alarme}}.<br>
									{{La valeur par défaut (ou si omise) est à}} 42 pixels.<br>
									{{Attention une pixelisation sera perceptible si la valeur saisie est supérieure}} 42 pixels."></i>
								</div>
								<div class="form-group modeadvancedVisible">
									<label class="col-sm-3 control-label">{{Marges}}</label>
									<div class="col-sm-1">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control layermarges" data-l1key="configuration" data-l2key="alarmmarges" placeholder="0" value="0"/>
										</div>
									</div>
									<span>{{%}}</span>
									<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Marges}}"
									data-content="{{Saisissez ici le pourcentage de l'image de l'ouvrant à utiliser comme marge de l'icône alarme}}.<br>
									{{La valeur par défaut (ou si omise) est à}} 0%.<br>
									{{La marge sera appliquée par rapport au bord extérieur de l'image de l'ouvrant et suivant le position choisie de l'icône}}."></i>
								</div>
								<div class="form-group modeadvancedVisible">
									<div class="col-sm-offset-3 col-sm-6">
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="alarmshowstate1">
										<span>{{Afficher l'icône si alarme inactive}}</span>
										<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
										title="{{Afficher l'icône si alarme inactive}}"
										data-content="{{En cochant cette case l'icône de l'état alarme inactive sera affichée}}.<br>
										{{Sinon l'icône sera cachée}}."></i>
										<br><br>
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="alarmshowstate">
										<span>{{Afficher l'état}}</span>
										<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
										title="{{Afficher l'état}}"
										data-content="{{En cochant cette case une information reprenant l'état de l'alarme francisé sera affichée}}."></i>
										<br><br>
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="alarmshowstateduration">
										<span>{{Afficher la durée de l'état}}</span>
										<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
										title="{{Afficher la durée de l'état}}"
										data-content="{{En cochant cette case l'information d'état francisée se voit completée par la durée depuis de dernier état}}."></i>
										<br>
									</div>
								</div>
							</div>
						</fieldset>
						<br>
						<br>
						<fieldset data-layer="lock">
							<legend><i class="icon jeedom-lock-ferme"></i>{{Lock}}
								<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Lock}}"
								data-content="{{Cette section sert à afficher via une icone l'état des capteurs de verrouillage de l'ouvrant ou d'une serrure}}.<br>"></i>
							</legend>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-6">
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="modeLockplugin">
									<span>{{Activer}}</span>
									<i class="icon fa fa-exclamation-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Activer}}"
									data-content="{{Attention vous devez aussi selectionner un modèle et un équipement pour que cela s'affiche réellement}}."></i>
									<br>
								</div>
							</div>
							<div class="modeLockVisible">
								<div class="form-group">
									<label class="col-sm-3 control-label" style="margin-top:10px">{{Modèle}}</label>
									<div class="col-sm-8">
										<div class="btn-group modellist customselect form-control" style="width: 100%;">
											<input type="hidden" class="eqLogicAttr" data-l1key="configuration" data-l2key="lockmodel">
											<div class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="modelcaption"><img src="plugins/Opening/core/template/dashboard/images/PreviewError.png">{{Erreur}}</span>
												<span class="glyphicon glyphicon-chevron-down"></span>
											</div>
											<ul class="dropdown-menu form-control">
												<li data-value="FFFFFF-0.png"><img src="plugins/Opening/core/template/dashboard/images/PreviewNothing.png">{{Pas de lock}}</li>
												<?php
												chdir(__DIR__."/../../core/template/dashboard/images/");
												foreach (glob("Lock*.png") as $filename)
												{
													$displayname = str_replace(array('Lock','.png','_'),array('','',' '),$filename);
													echo '<li data-value="'.$filename.'"><img src="plugins/Opening/core/template/dashboard/images/'.$filename.'">'.$displayname.'</li>';
												}
												?>
											</ul>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Équipement Droite}}</label>
									<div class="col-sm-7">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control tooltips" data-l1key="configuration" data-l2key="lockdcmdeqp"/>
											<span class="input-group-btn">
												<a class="btn btn-default selectEqInfo"><i class="fa fa-list-alt"></i></a>
											</span>
										</div>
									</div>
									<i class="icon fas fa-question-circle" style="margin-top:12px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Equipement Droite}}"
									data-content="{{Saisissez ici l'équipement associé au verrouillage de l'ouvrant droit ou d'une serrure}}.<br>
									{{Les valeurs prises en compte sont 0 pour déverrouillé et donc icône cachée, toute autre valeur pour verrouillé}}.<br>
									{{Les équipement droite et gauche sont indépendant}}."></i>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Équipement Gauche}}</label>
									<div class="col-sm-7">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control tooltips" data-l1key="configuration" data-l2key="lockgcmdeqp"/>
											<span class="input-group-btn">
												<a class="btn btn-default selectEqInfo"><i class="fa fa-list-alt"></i></a>
											</span>
										</div>
									</div>
									<i class="icon fas fa-question-circle" style="margin-top:12px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Equipement Gauche}}"
									data-content="{{Saisissez ici l'équipement associé au verrouillage de l'ouvrant gauche ou d'une autre serrure}}.<br>
									{{Les valeurs prises en compte sont 0 pour déverrouillé et donc icône cachée, toute autre valeur pour verrouillé}}.<br>
									{{Les équipement droite et gauche sont indépendant}}."></i>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" style="margin-top:10px">{{Positionnement Droite}}</label>
									<div class="col-sm-7" data-layer="lockD">
										<div class="btn-group customselect positionlist form-control" style="width: 100%;">
											<input type="hidden" class="eqLogicAttr" data-l1key="configuration" data-l2key="lockdposition">
											<div class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="modelcaption"><img src="plugins/Opening/core/template/dashboard/images/PreviewError.png">{{Erreur}}</span>
												<span class="glyphicon glyphicon-chevron-down"></span>
											</div>
											<ul class="dropdown-menu form-control">
												<?php
												$positions = array(
													"TopLeft",
													"TopCenter",
													"TopRight",
													"CenterLeft",
													"CenterCenter",
													"CenterRight",
													"BottomLeft",
													"BottomCenter",
													"BottomRight",
												);
												foreach($positions as $posIdx=>$posName)
												{
													echo '<li data-value="'.$posName.'"><img src="plugins/Opening/core/template/dashboard/images/Position'.($posIdx+1).'.png">'.$posName.'</li>';
												}
												?>
											</ul>
										</div>
									</div>
									<i class="icon fas fa-question-circle" style="margin-top:20px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Positionnement Droite}}"
									data-content="{{Choisissez ici la position de l'icône de verrouillage droite dans l'image}}."></i>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" style="margin-top:10px">{{Positionnement Gauche}}</label>
									<div class="col-sm-7" data-layer="lockG">
										<div class="btn-group customselect positionlist form-control" style="width: 100%;">
											<input type="hidden" class="eqLogicAttr" data-l1key="configuration" data-l2key="lockgposition">
											<div class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="modelcaption"><img src="plugins/Opening/core/template/dashboard/images/PreviewError.png">{{Erreur}}</span>
												<span class="glyphicon glyphicon-chevron-down"></span>
											</div>
											<ul class="dropdown-menu form-control">
												<?php
												$positions = array(
													"TopLeft",
													"TopCenter",
													"TopRight",
													"CenterLeft",
													"CenterCenter",
													"CenterRight",
													"BottomLeft",
													"BottomCenter",
													"BottomRight",
												);
												foreach($positions as $posIdx=>$posName)
												{
													echo '<li data-value="'.$posName.'"><img src="plugins/Opening/core/template/dashboard/images/Position'.($posIdx+1).'.png">'.$posName.'</li>';
												}
												?>
											</ul>
										</div>
									</div>
									<i class="icon fas fa-question-circle" style="margin-top:20px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Positionnement}}"
									data-content="{{Choisissez ici la position de l'icône de verrouillage gauche dans l'image}}."></i>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Taille}}</label>
									<div class="col-sm-1">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control layersize" data-l1key="configuration" data-l2key="locksize" placeholder="42" value="42"/>
										</div>
									</div>
									<span>{{pixels}}</span>
									<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Taille}}"
									data-content="{{Saisissez ici la taille en pixel des icônes verrouillage}}.<br>
									{{La valeur par défaut (ou si omise) est à}} 42 pixels.<br>
									{{Attention une pixelisation sera perceptible si la valeur saisie est supérieure}} 42 pixels."></i>
								</div>
								<div class="form-group modeadvancedVisible">
									<label class="col-sm-3 control-label">{{Marges}}</label>
									<div class="col-sm-1">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control layermarges" data-l1key="configuration" data-l2key="lockmarges" placeholder="0" value="0"/>
										</div>
									</div>
									<span>{{%}}</span>
									<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Marges}}"
									data-content="{{Saisissez ici le pourcentage de l'image de l'ouvrant à utiliser comme marge des icônes de verrouillage}}.<br>
									{{La valeur par défaut (ou si omise) est à}} 0%.<br>
									{{La marge sera appliquée par rapport au bord extérieur de l'image de l'ouvrant et suivant le position choisie de chaque icône}}."></i>
								</div>
								<div class="form-group modeadvancedVisible">
									<div class="col-sm-offset-3 col-sm-6">
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="lockshowstate">
										<span>{{Afficher l'état}}</span>
										<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
										title="{{Afficher l'état}}"
										data-content="{{En cochant cette case une information reprenant et combinant les états des verrouillages francisé sera affichée}}."></i>
										<br><br>
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="lockshowstateduration">
										<span>{{Afficher la durée de l'état}}</span>
										<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
										title="{{Afficher la durée de l'état}}"
										data-content="{{En cochant cette case l'information d'état francisée se voit completée par la durée depuis de dernier état}}."></i>
										<br>
									</div>
								</div>
							</div>
						</fieldset>
						<br>
						<br>
						<fieldset data-layer="temperature">
							<legend><i class="icon divers-thermometer31"></i>{{Températures}}
								<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Options}}"
								data-content="{{Cette section sert à afficher une bande d'information au-dessus de l'ouvrant contenant des températures}}."></i>
							</legend>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-6">
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="modeTemperatureplugin">
									<span>{{Activer}}</span>
									<i class="icon fas fa-exclamation-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Activer}}"
									data-content="{{Attention vous devez aussi selectionner un équipement pour que cela s'affiche réellement}}."></i>
									<br>
								</div>
							</div>
							<div class="modeTemperatureVisible">
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Équipement intérieur}}</label>
									<div class="col-sm-7">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control tooltips" data-l1key="configuration" data-l2key="temperatureintcmdeqp"/>
											<span class="input-group-btn">
												<a class="btn btn-default selectEqInfo"><i class="fa fa-list-alt"></i></a>
											</span>
										</div>
									</div>
									<i class="icon fas fa-question-circle" style="margin-top:12px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Équipement température intérieure}}"
									data-content="{{Saisissez ici l'équipement fournissant l'information de température intérieure}}."></i>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Équipement extérieur}}</label>
									<div class="col-sm-7">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control tooltips" data-l1key="configuration" data-l2key="temperatureextcmdeqp"/>
											<span class="input-group-btn">
												<a class="btn btn-default selectEqInfo"><i class="fa fa-list-alt"></i></a>
											</span>
										</div>
									</div>
									<i class="icon fas fa-question-circle" style="margin-top:12px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Équipement température extérieure}}"
									data-content="{{Saisissez ici l'équipement fournissant l'information de température extérieure}}."></i>
								</div>
								<div class="form-group modeadvancedVisible">
									<label class="col-sm-3 control-label">{{Légende intérieure}}</label>
									<div class="col-sm-2">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="IntLegend" placeholder="T° Int." value="T° Int."/>
										</div>
									</div>
									<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Légende intérieure}}"
									data-content="{{Saisissez ici la légende de l'information température intérieure}}."></i>
								</div>
								<div class="form-group modeadvancedVisible">
									<label class="col-sm-3 control-label">{{Légende extérieure}}</label>
									<div class="col-sm-2">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ExtLegend" placeholder="T° Ext." value="T° Ext."/>
										</div>
									</div>
									<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Légende extérieure}}"
									data-content="{{Saisissez ici la légende de l'information température extérieure}}."></i>
								</div>
								<div class="form-group modeadvancedVisible">
									<div class="col-sm-offset-3 col-sm-6">
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="upperstatestemperature">
										<span>{{Afficher en MAJUSCULE les températures}}</span>
										<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
										title="{{Afficher en MAJUSCULE les températures}}"
										data-content="{{En cochant cette case les lettres des températures seront CAPITALISES, tout sera écrit en majuscule}}."></i>
										<br>
									</div>
								</div>
								<div class="form-group modeadvancedVisible">
									<div class="col-sm-offset-3 col-sm-6">
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="toppostemperature">
										<span>{{Afficher les températures en haut de l'ouvrant}}</span>
										<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
										title="{{Afficher les températures en haut de l'ouvrant}}"
										data-content="{{En cochant cette case les températures s'afficheront au-dessus de l'ouvrant, sinon elles seront juste en-dessous}}."></i>
										<br>
									</div>
								</div>
								<div class="form-group modeadvancedVisible">
									<div class="col-sm-offset-3 col-sm-6">
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="justifytemperature">
										<span>{{Position des températures justifiées}}</span>
										<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
										title="{{Position des températures justifiées}}"
										data-content="{{En cochant cette case les températures seront horizontalement justifiées}}.<br>
										{{Il faut comprendre qu'un espace automatique autour de chacun sera ajusté et qu'elle ne seront pas au bord de l'ouvrant}}.<br>
										{{Si une seule température est utilisée, elle sera centrée}}.<br>
										{{En décochant les températures seront alignées à gauche et à droite de l'ouvrant, à gauche si une seule température est utilisée}}."></i>
										<br>
									</div>
								</div>
							</div>
							<br>
							<br>
						</fieldset>
						<fieldset data-layer="weather">
							<legend><i class="icon divers-umbrella2"></i>{{Météo}}
								<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Météo}}"
								data-content="{{Cette section à afficher une image ou une icône représentant les conditions météorologiques}}."></i>
							</legend>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-6">
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="modeWeatherplugin">
									<span>{{Activer}}</span>
									<i class="icon fas fa-exclamation-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Activer}}"
									data-content="{{Attention vous devez aussi selectionner un modèle et un équipement pour que cela s'affiche réellement}}."></i>
									<br>
								</div>
							</div>
							<div class="modeWeatherVisible">
								<div class="form-group"  data-layer="weather">
									<label class="col-sm-3 control-label" style="margin-top:10px">{{Modèle météo}}</label>
									<div class="col-sm-8">
										<div class="btn-group modellist customselect form-control" style="width: 100%;">
											<input type="hidden" class="eqLogicAttr" data-l1key="configuration" data-l2key="weathermodel">
											<div class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="modelcaption"><img src="plugins/Opening/core/template/dashboard/images/PreviewError.png">{{Erreur}}</span>
												<span class="glyphicon glyphicon-chevron-down"></span>
											</div>
											<ul class="dropdown-menu form-control">
												<li data-value="FFFFFF-0.png"><img src="plugins/Opening/core/template/dashboard/images/PreviewNothing.png">{{Pas d'image météo}}</li>
												<?php
												chdir(__DIR__."/../../core/template/dashboard/images/");
												foreach (glob("Weather*Soleil.png") as $filename)
													{
														$displayname = str_replace(array('Weather','Soleil.png','_'),array('','',' '),$filename);
													echo '<li data-value="'.$filename.'"><img src="plugins/Opening/core/template/dashboard/images/'.$filename.'">'.$displayname.'</li>';
												}
												?>
											</ul>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Équipement météo}}</label>
									<div class="col-sm-7">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control tooltips" data-l1key="configuration" data-l2key="weathercmdeqp"/>
											<span class="input-group-btn">
												<a class="btn btn-default selectEqInfo"><i class="fa fa-list-alt"></i></a>
											</span>
										</div>
									</div>
									<i class="icon fas fa-question-circle" style="margin-top:12px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Équipement météo}}"
									data-content="{{Saisissez ici l'équipement qui fournira une information de l'état de la météo (actuelle ou décalée)}}.<br>
									{{Une recherche de mot clé sera effectuée dans cette information pour en déduire une image correspondante et suivant le modèle choisit}}."></i>
								</div>
								<div class="form-group modeadvancedVisible">
									<div class="col-sm-offset-3 col-sm-6">
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="weathershowstate">
										<span>{{Afficher les conditions}}</span>
										<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
										title="{{Afficher les conditions}}"
										data-content="{{En cochant cette case une information reprenant et combinant les états des conditions météorologique sera affiché}}."></i>
										<br><br>
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="weathershowstateduration">
										<span>{{Afficher la durée des conditions}}</span>
										<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
										title="{{Afficher la durée des conditions}}"
										data-content="{{En cochant cette case l'information d'état se voit completée par la durée depuis de dernier état}}."></i>
										<br><br>
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="upperstatesweather">
										<span>{{Afficher en MAJUSCULE les conditions}}</span>
										<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
										title="{{Afficher en MAJUSCULE les conditions}}"
										data-content="{{En cochant cette case les lettres des conditions seront CAPITALISES, tout sera écrit en majuscule}}."></i>
										<br>
									</div>
								</div>
							</div>
							<br>
							<br>
						</fieldset>
						<fieldset>
							<legend><i class="icon divers-vlc1"></i>{{Options}}
								<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Options}}"
								data-content="{{Cette section propose plusieurs options diverses et variées}}."></i>
							</legend>
							<div class="form-group" data-layer="back">
								<label class="col-sm-3 control-label" style="margin-top:10px">{{Modèle d'arrière plan}}</label>
								<div class="col-sm-8">
									<div class="btn-group modellist customselect form-control" style="width: 100%;">
										<input type="hidden" class="eqLogicAttr" data-l1key="configuration" data-l2key="backmodel">
										<div class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<span class="modelcaption"><img src="plugins/Opening/core/template/dashboard/images/PreviewError.png">{{Erreur}}</span>
											<span class="glyphicon glyphicon-chevron-down"></span>
										</div>
										<ul class="dropdown-menu form-control">
											<li data-value="FFFFFF-0.png"><img src="plugins/Opening/core/template/dashboard/images/PreviewNothing.png">{{Pas d'arrière plan}}</li>
											<?php
											chdir(__DIR__."/../../core/template/dashboard/images/");
											foreach (glob("Back*.png") as $filename)
												{
													$displayname = str_replace(array('Back','.png','_'),array('','',' '),$filename);
												echo '<li data-value="'.$filename.'"><img src="plugins/Opening/core/template/dashboard/images/'.$filename.'">'.$displayname.'</li>';
											}
											?>
										</ul>
									</div>
								</div>
							</div>
							<div class="form-group modeadvancedVisible">
								<label class="col-sm-3 control-label">{{Marge ouvrant}}</label>
								<div class="col-sm-1">
									<div class="input-group">
										<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="globalmarge" placeholder="0" value="0"/>
									</div>
								</div>
								<span>{{pixels}}</span>
								<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Marge ouvrant}}"
								data-content="{{Saisissez ici la marge autour de l'ouvrant}}.<br>
								{{La valeur par défaut (ou si omise) est à}} 0 pixels.<br>
								{{Cette marge ne s'applique pas aux Motion, Batterie, Lock et Alarme}}."></i>
							</div>
							<div class="form-group modeadvancedVisible">
								<label class="col-sm-3 control-label">{{Marge température}}</label>
								<div class="col-sm-1">
									<div class="input-group">
										<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="temperaturesmarge" placeholder="0" value="0"/>
									</div>
								</div>
								<span>{{pixels}}</span>
								<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Marge états}}"
								data-content="{{Saisissez ici la marge entre les sections images et température}}.<br>
								{{La valeur par défaut (ou si omise) est à}} 0°."></i>
							</div>
							<div class="form-group modeadvancedVisible">
								<label class="col-sm-3 control-label">{{Marge états}}</label>
								<div class="col-sm-1">
									<div class="input-group">
										<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="statesmarge" placeholder="0" value="0"/>
									</div>
								</div>
								<span>{{pixels}}</span>
								<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Marge états}}"
								data-content="{{Saisissez ici la marge entre les sections images et états}}.<br>
								{{La valeur par défaut (ou si omise) est à}} 0°."></i>
							</div>
							<div class="form-group modeadvancedVisible">
								<label class="col-sm-3 control-label">{{Marge commandes}}</label>
								<div class="col-sm-1">
									<div class="input-group">
										<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="cmdmarge" placeholder="0" value="0"/>
									</div>
								</div>
								<span>{{pixels}}</span>
								<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="right" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Marge commandes}}"
								data-content="{{Saisissez ici la marge entre les sections images et commandes ou état et commandes}}.<br>
								{{La valeur par défaut (ou si omise) est à}} 0°."></i>
							</div>
							<div class="form-group modeadvancedVisible">
								<div class="col-sm-offset-3 col-sm-6">
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="upperstates">
									<span>{{Afficher en MAJUSCULE les états}}</span>
									<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Afficher en MAJUSCULE les états}}"
									data-content="{{En cochant cette case les lettres des états textuels seront CAPITALISES, tout sera écrit en majuscule}}."></i>
									<br><br>
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="rotatestates">
									<span>{{Appliquer la rotation 2D aux états}}</span>
									<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Appliquer la rotation 2D aux états}}"
									data-content="{{En cochant cette case la rotation 2D sera appliqué à tout l'ouvrant, les images ET les états textuels}}.<br>
									{{Sinon la rotation sera seulement appliquée aux images, les états resteront eux horizontaux}}."></i>
									<br><br>
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration"  data-l2key="rotatecmd">
									<span>{{Appliquer la rotation 2D aux commandes}}</span>
									<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-placement="left" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
									title="{{Appliquer la rotation 2D aux commandes}}"
									data-content="{{En cochant cette case la rotation 2D sera appliqué aussi aux commandes}}.<br>
									{{Sinon la rotation sera seulement appliquée aux images, et éventuellement aux états}}."></i>
									<br>
								</div>
							</div>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
						</fieldset>
					</fieldset>
					</form>
					</div>
					<div class="col-sm-5 text-center">
						<fieldset class="preview" style="position: fixed;">
							<legend><i class="icon jeedom-clap_cinema"></i>{{Prévisualisations}}
							<i class="icon fas fa-question-circle" style="margin-top:12px;margin-left:10px" data-toggle="popover" data-trigger="hover" data-animation=true data-delay=200 data-html=true
								title="{{Prévisualisation}}"
								data-content="{{Cette section sert à prévisualiser en temps réel vos réglages}}.<br>
								{{Tous les éléments ne sont pas affichés pour ne pas surcharger le rendu}}.<br>
								{{On retrouve également une multitudes d'états possible}}.<br>"></i>
							</legend>
							<div class="row">
								<div class="col-sm-4 text-center">
									<div class="OpeningLayerWrp">
										<div class="OpeningWrp">
											<div class="layer back bg"><img/></div>
											<div class="layer weather bg modeWeatherVisible"><img/></div>
											<div class="layer store bg modeStoreVisible"><img class="percent0"/></div>
											<div class="layer window"><img class="closed" data-percent="000"/></div>
										</div>
										<span class="label label-default">{{Ouvert(e), Volet à 0%}}<br><br></span>
									</div>
									<div class="OpeningLayerWrp">
										<div class="OpeningWrp">
											<div class="layer back bg"><img/></div>
											<div class="layer store bg modeStoreVisible"><img class="percent30"/></div>
											<div class="layer window"><img class="closed" data-percent="030"/></div>
											<div class="layer batt modeBatVisible"><img src="plugins/Opening/core/template/dashboard/images/FFFFFF-0.png" class="positioned"/></div>
										</div>
										<span class="label label-default">{{Ouvert(e), Volet à 30%}}<br><br></span>
									</div>
									<div class="OpeningLayerWrp">
										<div class="OpeningWrp">
											<div class="layer back bg"><img/></div>
											<div class="layer store bg modeStoreVisible"><img class="percent60"/></div>
											<div class="layer window"><img class="closed" data-percent="060"/></div>
											<div class="layer alarm modeAlarmVisible"><img src="plugins/Opening/core/template/dashboard/images/FFFFFF-0.png" class="positioned"/></div>
										</div>
										<span class="label label-default">{{Ouvert(e), Volet à 60%}}<br><br></span>
									</div>
									<div class="OpeningLayerWrp">
										<div class="OpeningWrp">
											<div class="layer back bg"><img/></div>
											<div class="layer store bg modeStoreVisible"><img class="percent90"/></div>
											<div class="layer window"><img class="closed" data-percent="090"/></div>
										</div>
										<span class="label label-default">{{Ouvert(e), Volet à 90%}}<br><br></span>
									</div>
								</div>
								<div class="col-sm-4 text-center">
									<div class="OpeningLayerWrp">
										<div class="OpeningWrp">
											<div class="layer back bg"><img/></div>
											<div class="layer store bg modeStoreVisible"><img class="percent10"/></div>
											<div class="layer window"><img data-percent="010"/></div>
										</div>
										<span class="label label-default">{{Ouvert(e), Volet à 10%}}<br><br></span>
									</div>
									<div class="OpeningLayerWrp">
										<div class="OpeningWrp">
											<div class="layer back bg"><img/></div>
											<div class="layer store bg modeStoreVisible"><img class="percent40"/></div>
											<div class="layer window"><img data-percent="040"/></div>
											<div class="layer motion modeMotionVisible"><img src="plugins/Opening/core/template/dashboard/images/FFFFFF-0.png" class="positioned"/></div>
										</div>
										<span class="label label-default">{{Ouvert(e), Volet à 40%}}<br><br></span>
									</div>
									<div class="OpeningLayerWrp">
										<div class="OpeningWrp">
											<div class="layer back bg"><img/></div>
											<div class="layer store bg modeStoreVisible"><img class="percent70"/></div>
											<div class="layer window"><img data-percent="070"/></div>
										</div>
										<span class="label label-default">{{Ouvert(e), Volet à 70%}}<br><br></span>
									</div>
									<div class="OpeningLayerWrp">
										<div class="OpeningWrp">
											<div class="layer back bg"><img/></div>
											<div class="layer store bg modeStoreVisible"><img class="percent100"/></div>
											<div class="layer window"><img data-percent="100"/></div>
										</div>
										<span class="label label-default">{{Ouvert(e), Volet à 100%}}<br><br></span>
									</div>
								</div>
								<div class="col-sm-4 text-center">
									<div class="OpeningLayerWrp">
										<div class="OpeningWrp">
											<div class="layer back bg"><img/></div>
											<div class="layer store bg modeStoreVisible"><img class="percent20"/></div>
											<div class="layer window"><img class="closed2" data-percent="020"/></div>
										</div>
										<span class="label label-default">{{Ouvert(e), Volet à 20%}}<br><br></span>
									</div>
									<div class="OpeningLayerWrp">
										<div class="OpeningWrp">
											<div class="layer back bg"><img/></div>
											<div class="layer store bg modeStoreVisible"><img class="percent50"/></div>
											<div class="layer window"><img class="closed2" data-percent="050"/></div>
											<div class="layer lock lockG modeLockVisible"><img src="plugins/Opening/core/template/dashboard/images/FFFFFF-0.png" class="positioned"/></div>
											<div class="layer lock lockD modeLockVisible"><img src="plugins/Opening/core/template/dashboard/images/FFFFFF-0.png" class="positioned"/></div>
										</div>
										<span class="label label-default">{{Ouvert(e), Volet à 50%}}<br><br></span>
									</div>
									<div class="OpeningLayerWrp">
										<div class="OpeningWrp">
											<div class="layer back bg"><img/></div>
											<div class="layer store bg modeStoreVisible"><img class="percent80"/></div>
											<div class="layer window"><img class="closed2" data-percent="080"/></div>
										</div>
										<span class="label label-default">{{Ouvert(e), Volet à 80%}}<br><br></span>
									</div>
									<div class="OpeningLayerWrp">
										<div class="OpeningWrp">
											<div class="layer back bg"><img/></div>
											<div class="layer store bg modeStoreVisible"><img class="percent0"/></div>
											<div class="layer window"><img class="closed2" data-percent="000"/></div>
										</div>
										<span class="label label-default">{{Fermé(e), Volet à 0%}}<br><br></span>
									</div>
								</div>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="commandtab" style="margin-top:10px;">
				<a class="btn btn-primary cmdAction pull-right" id="bt_addVirtualAction"> <i class="fa fa-plus-circle"></i> {{Ajouter une Commande}}</a>
				<a class="btn btn-info cmdAction pull-right" id="bt_addVirtualInfo"> <i class="fa fa-plus-circle"></i> {{Ajouter une Info}}</a>
				<br/><br/>
				<table id="table_cmd" class="table table-bordered table-condensed" style="margin-top:10px;">
					<thead>
						<tr>
							<th style="width: 50px;">{{#}}</th>
							<th style="width: 250px;">{{Nom}}</th>
							<th style="width: 130px;">{{Type}}</th>
							<th>{{Commande}}</th>
							<th style="width: 130px;">{{Paramètres}}</th>
							<th style="width: 130px;">{{Afficher}}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php
		include_file('desktop', 'Opening', 'js', 'Opening');
		include_file('desktop', 'Opening', 'css', 'Opening');
		include_file('desktop', 'OpeningLayer', 'css', 'Opening');
		include_file('core', 'plugin.template', 'js');
?>
