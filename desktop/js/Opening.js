
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
$('[data-toggle="popover"]').popover();
//customselect
$('body').on( 'click','.customselect li', function() {
	var $customselect = $(this).closest('.customselect');
	$(this).siblings('li').removeClass('active');
	$(this).addClass('active');
	$customselect.find('.dropdown-toggle .modelcaption').html($(this).html());
	$customselect.find('input:first').val($(this).data('value'));
});

$('body').on( 'change','.customselect input[data-l2key]', function() {
	if($(this).val()=='')
		$(this).closest('.customselect').find('li:first').click();
	else
		$(this).closest('.customselect').find('li[data-value="'+$(this).val()+'"]').click();
});

//customselect special case
$('body').on( 'click','.customselect:has(input[data-l2key="familymodel"]) li', function(event) {
	$('body .modellist li[data-family]').hide();
	$('body .modellist li[data-family="'+$(this).data('value')+'"]').show();
	$('body .preview .OpeningWrp').removeClass('family0 family1 family2 family3 family4 family5 family6 family7 family8 family9').addClass('family'+$(this).data('value'));
	if($.inArray( $(this).data('value'), [1,2,3,4,5,8,9] ) > -1)
	{
		$('body [data-layer="store"] .modellist li[data-family="0"]').show();
	}
	if(typeof event.originalEvent == 'object')
	{
		$('body [data-layer="window"] .modellist li:first').click();
		$('body [data-layer="store"] .modellist li:first').click();
	}
});

$('body').on( 'click','.customselect:has(input[data-l2key="windowmodel"]) li', function(event) {
	if($(this).data('withstore') == '1')
	{
		$('body [data-layer="store"] .modellist li:first').click();
	}
});

//positionlist
$('body').on( 'click','.positionlist li', function() {
	$('body .preview .OpeningWrp .'+$(this).closest('[data-layer]').data('layer')+' img').removeClass('TopLeft TopCenter TopRight CenterLeft CenterCenter CenterRight BottomLeft BottomCenter BottomRight').addClass($(this).data('value'));
});

//modellist
$('body').on( 'click','.modellist li', function() {
	var $wrp = $(this).closest('[data-layer]');
	if($wrp.data('layer'))
	{
		if($wrp.data('layer') == 'window')
		{
			refreshWindowStore($(this).data('value'));
		}else{
			$('body .preview .OpeningWrp .'+$wrp.data('layer')+' img').attr('src','plugins/Opening/core/template/dashboard/images/'+ $(this).data('value'));
		}
	}
});

//customposition
$('body').on( 'change','.customposition [data-l2key]', function() {	
	$(this).closest('.customposition').find('[data-value="'+$(this).val()+'"]').click();
});

$('body').on( 'click','.customposition [data-value]', function() {
	var $customposition = $(this).closest('.customposition');
	$customposition.find('[data-value]').removeClass('active');
	$(this).addClass('active');
	$customposition.find('input:first').val($(this).data('value'));
});

//size
$('body').on( 'change','input[data-l2key].layersize', function() {
	var $wrp = $(this).closest('[data-layer]');
	if($wrp.data('layer'))
	{
		var $elm =$('body .preview .OpeningWrp .'+$wrp.data('layer')+' img');
		var size = $(this).val();
		if(size=='')
		{
			size = '42px';
		}else{
			if($.isNumeric( size ))
				size = size+'px';
		}
		$elm.css({'height':size,'width':size});
	}
});

//marges
$('body').on( 'change','input[data-l2key].layermarges', function() {
	var $wrp = $(this).closest('[data-layer]');
	if($wrp.data('layer'))
	{
		var $elm =$('body .preview .OpeningWrp .'+$wrp.data('layer')+' img');
		var marges = $(this).val();
		if(marges=='')
		{
			marges = '0%';
		}else{
			if($.isNumeric( marges ))
				marges = marges+'%';
		}
		$elm.css({'top':marges,'left':marges});
	}
});

//mode avancé
$('body').on( 'change','input[data-l2key="modeadvancedplugin"]', function() {
	$('body .Openingconfig').toggleClass('modeadvanced', $(this)[0].checked);
});
$('body').on( 'change','input[data-l2key="modeStoreplugin"]', function() {
	$('body .Openingconfig').toggleClass('modeStore', $(this)[0].checked);
	refreshWindowStore($('input[data-l2key="windowmodel"]').val());
});
$('body').on( 'change','input[data-l2key="modeBatplugin"]', function() {
	$('body .Openingconfig').toggleClass('modeBat', $(this)[0].checked);
});
$('body').on( 'change','input[data-l2key="modeLockplugin"]', function() {
	$('body .Openingconfig').toggleClass('modeLock', $(this)[0].checked);
});
$('body').on( 'change','input[data-l2key="modeAlarmplugin"]', function() {
	$('body .Openingconfig').toggleClass('modeAlarm', $(this)[0].checked);
});
$('body').on( 'change','input[data-l2key="modeMotionplugin"]', function() {
	$('body .Openingconfig').toggleClass('modeMotion', $(this)[0].checked);
});
$('body').on( 'change','input[data-l2key="modeWeatherplugin"]', function() {
	$('body .Openingconfig').toggleClass('modeWeather', $(this)[0].checked);
});
$('body').on( 'change','input[data-l2key="modeTemperatureplugin"]', function() {
	$('body .Openingconfig').toggleClass('modeTemperature', $(this)[0].checked);
});

// Taillage widget
$('body').on( 'change','input[data-l2key="windowsize"]', function() {
	$('body .preview .OpeningWrp').css({'height':$(this).val()+'px','width':$(this).val()+'px',});
});

// Miroir window
$('body').on( 'change','input[data-l2key="windowinvertposition"]', function() {
	$('body .layer.window img').css('transform', ($(this)[0].checked) ? 'scaleX(-1)' : '');
});

// Rotation widget
$('body').on( 'change','input[data-l2key="windowrotateX"]', function() {
	refreshTransform();
});
$('body').on( 'change','input[data-l2key="windowrotateY"]', function() {
	refreshTransform();
});
$('body').on( 'change','input[data-l2key="windowrotateZ"]', function() {
	refreshTransform();
});
$('body').on( 'change','input[data-l2key="windowperscpective"]', function() {
	refreshTransform();
});
$('body').on( 'change','input[data-l2key="windowrotate"]', function() {
	refreshTransform();
});
$('body').on( 'change','input[data-l2key="rotatestates"]', function() {
	refreshTransform();
});
function refreshTransform()
{
	var transform ='';
	if($('body input[data-l2key="rotatestates"]')[0].checked)
	{
		transform += ' perspective('+(($('body input[data-l2key="windowperscpective"]').val() || 0))+'px)';
		transform += ' rotateX('+(($('body input[data-l2key="windowrotateX"]').val() || 0)*-1)+'deg)';
		transform += ' rotateY('+($('body input[data-l2key="windowrotateY"]').val() || 0)+'deg)';
		transform += ' rotateZ('+($('body input[data-l2key="windowrotateZ"]').val() || 0)+'deg)';
		transform += ' translateY('+($('body input[data-l2key="windowtranslateY"]').val() || 0)+'px)';
		$('body .preview .OpeningLayerWrp .OpeningWrp').css('transform', transform);
		$('body .preview .OpeningLayerWrp .OpeningWrp').css('-webkit-transform', transform);
		$('body .preview .OpeningLayerWrp .OpeningWrp').css('-moz-transform', transform);
		$('body .preview .OpeningLayerWrp .OpeningWrp').css('-ms-transform', transform);
		$('body .preview .OpeningLayerWrp .OpeningWrp').css('-o-transform', transform);
		$('body .preview .OpeningLayerWrp').css('transform', ' rotate('+($('body input[data-l2key="windowrotate"]').val() || 0)+'deg)');
		$('body .preview .OpeningLayerWrp').css('-webkit-transform', ' rotate('+($('body input[data-l2key="windowrotate"]').val() || 0)+'deg)');
		$('body .preview .OpeningLayerWrp').css('-moz-transform', ' rotate('+($('body input[data-l2key="windowrotate"]').val() || 0)+'deg)');
		$('body .preview .OpeningLayerWrp').css('-ms-transform', ' rotate('+($('body input[data-l2key="windowrotate"]').val() || 0)+'deg)');
		$('body .preview .OpeningLayerWrp').css('-o-transform', ' rotate('+($('body input[data-l2key="windowrotate"]').val() || 0)+'deg)');
	} else {
		transform += ' rotate('+($('body input[data-l2key="windowrotate"]').val() || 0)+'deg)';
		transform += ' perspective('+(($('body input[data-l2key="windowperscpective"]').val() || 0))+'px)';
		transform += ' rotateX('+(($('body input[data-l2key="windowrotateX"]').val() || 0)*-1)+'deg)';
		transform += ' rotateY('+($('body input[data-l2key="windowrotateY"]').val() || 0)+'deg)';
		transform += ' rotateZ('+($('body input[data-l2key="windowrotateZ"]').val() || 0)+'deg)';
		transform += ' translateY('+($('body input[data-l2key="windowtranslateY"]').val() || 0)+'px)';
		$('body .preview .OpeningLayerWrp .OpeningWrp').css('transform', transform);
		$('body .preview .OpeningLayerWrp .OpeningWrp').css('-webkit-transform', transform);
		$('body .preview .OpeningLayerWrp .OpeningWrp').css('-moz-transform', transform);
		$('body .preview .OpeningLayerWrp .OpeningWrp').css('-ms-transform', transform);
		$('body .preview .OpeningLayerWrp .OpeningWrp').css('-o-transform', transform);
		$('body .preview .OpeningLayerWrp').css('transform', ' rotate(0deg)');
		$('body .preview .OpeningLayerWrp').css('-webkit-transform', ' rotate(0deg)');
		$('body .preview .OpeningLayerWrp').css('-moz-transform', ' rotate(0deg)');
		$('body .preview .OpeningLayerWrp').css('-ms-transform', ' rotate(0deg)');
		$('body .preview .OpeningLayerWrp').css('-o-transform', ' rotate(0deg)');
	}
}

// window specifik
function refreshWindowStore(windowImgSrc)
{
	var $elm =$('body .preview .OpeningWrp .window img');
	var XState = (windowImgSrc.indexOf('XState') !== -1);
	if(XState)
	{
		$('body .preview .OpeningWrp .layer.store').hide(); //hide store
	}
	
	$elm.each(function(index){
		windowImg = windowImgSrc;
		if($(this).hasClass('closed'))
			windowImg = windowImg.replace("State1", "State0");
		if($(this).hasClass('closed2'))
			windowImg = windowImg.replace("State1", "State3");
		if(XState && $(this).closest('.Openingconfig').hasClass('modeStore'))
		{
			windowImg = windowImg.replace("000.png", $(this).data('percent')+".png");
		}
		console.log( XState,$(this).closest('.Openingconfig').hasClass('modeStore'),$(this),$(this).data('percent'),windowImg);
		$(this).attr('src','plugins/Opening/core/template/dashboard/images/'+windowImg);
	});
}

//system
 $("#bt_addVirtualInfo").on('click', function (event) {
    var _cmd = {type: 'info'};
    addCmdToTable(_cmd);
});

 $("#bt_addVirtualAction").on('click', function (event) {
    var _cmd = {type: 'action'};
    addCmdToTable(_cmd);
});

$("#table_cmd").delegate(".listEquipementInfo", 'click', function () {
    var el = $(this);
    jeedom.cmd.getSelectModal({cmd: {type: 'info'}}, function (result) {
        var calcul = el.closest('tr').find('.cmdAttr[data-l1key=configuration][data-l2key=' + el.data('input') + ']');
        calcul.atCaret('insert', result.human);
    });
});

 $("#table_cmd").delegate(".listEquipementAction", 'click', function () {
    var el = $(this);
    var subtype = $(this).closest('.cmd').find('.cmdAttr[data-l1key=subType]').value();
    jeedom.cmd.getSelectModal({cmd: {type: 'action', subType: subtype}}, function (result) {
        var calcul = el.closest('tr').find('.cmdAttr[data-l1key=configuration][data-l2key=' + el.attr('data-input') + ']');
        calcul.atCaret('insert', result.human);
    });
});

$('body').on( 'click','.selectEqInfo', function() {
	var el = $(this);
	jeedom.cmd.getSelectModal({cmd: {type: 'info'}}, function(result) {
		var calcul = el.closest('tr').find('.cmdAttr[data-l1key=configuration][data-l2key=calcul]');
		el.closest('.input-group').find('input').value(result.human);
	});
});

$('body').on( 'click','.selectEqAction', function() {
	var el = $(this);
	jeedom.cmd.getSelectModal({cmd: {type: 'action'}}, function(result) {
		el.closest('.input-group').find('input').value(result.human);
	});
});

$('#table_cmd').on( 'click','.selectEqAuto', function() {
	var el = $(this);
	var type = el.closest('tr.cmd').find('select[data-l1key="type"]').val();
		jeedom.cmd.getSelectModal({cmd: {type: type}}, function(result) {
		el.closest('.input-group').find('input').value(result.human);
	});
});

$("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});

 function addCmdToTable(_cmd) {
    if (!isset(_cmd)) {
        var _cmd = {configuration: {}};
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }
    if (init(_cmd.logicalId) == 'refresh') {
     return;
 }

 if (init(_cmd.type) == 'info') {
	var disabled = (init(_cmd.configuration.virtualAction) == '1') ? 'disabled' : '';
	var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '" virtualAction="' + init(_cmd.configuration.virtualAction) + '">';
	tr += '<td>';
	tr += '<span class="cmdAttr" data-l1key="id"></span>';
	tr += '</td>';
	tr += '<td>';
	tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" style="width : 140px;" placeholder="Nom"></td>';
	tr += '<td>';
	tr += '<input class="cmdAttr form-control type input-sm" data-l1key="type" value="info" disabled style="margin-bottom : 5px;" />';
	tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
	tr += '</td>';
	tr += '<td><textarea class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="cmdeq" style="height : 33px;margin-bottom : 5px;width : 70%; display : inline-block;" ' + disabled + ' placeholder="Nom information ou Calcul"></textarea>';
	tr += '<a class="btn btn-default cursor listEquipementInfo btn-sm" data-input="cmdeq"><i class="fa fa-list-alt "></i> Rechercher équipement</a>';
	tr += '</td>';
	tr += '<td>';
	tr += '<input class="cmdAttr form-control input-sm" data-l1key="unite" style="width : 90px;margin-bottom : 5px;" placeholder="Unité">';
	tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="Min" title="Min" style="width : 40%;display : inline-block;"> ';
	tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" placeholder="Max" title="Max" style="width : 40%;display : inline-block;">';
	tr += '</td>';
	tr += '<td>';
	tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>Afficher</label></span> ';
	tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>Historiser</label></span> ';
	tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr expertModeVisible" data-l1key="display" data-l2key="invertBinary"/>Inverser</label></span><br/>';
	tr += '</td>';
	tr += '<td>';
	if (is_numeric(_cmd.id)) {
		tr += '<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure"><i class="fa fa-cogs"></i></a> ';
		tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> Tester</a>';
	}
	tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
	tr += '</tr>';
	$('#table_cmd tbody').append(tr);
	$('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
	if (isset(_cmd.type)) {
		$('#table_cmd tbody tr:last .cmdAttr[data-l1key=type]').value(init(_cmd.type));
	}
	jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
}

if (init(_cmd.type) == 'action') {
    var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
    tr += '<td>';
    tr += '<span class="cmdAttr" data-l1key="id"></span>';
    tr += '</td>';
    tr += '<td>';
    tr += '<div class="row">';
    tr += '<div class="col-sm-6">';
    tr += '<a class="cmdAction btn btn-default btn-sm" data-l1key="chooseIcon"><i class="fa fa-flag"></i> Icône</a>';
    tr += '<span class="cmdAttr" data-l1key="display" data-l2key="icon" style="margin-left : 10px;"></span>';
    tr += '</div>';
    tr += '<div class="col-sm-6">';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="name">';
    tr += '</div>';
    tr += '</div>';
    tr += '</td>';
    tr += '<td>';
    tr += '<input class="cmdAttr form-control type input-sm" data-l1key="type" value="action" disabled style="margin-bottom : 5px;" />';
    tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
    tr += '<input class="cmdAttr" data-l1key="configuration" data-l2key="virtualAction" value="1" style="display:none;" >';
    tr += '</td>';
    tr += '<td>';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="cmdeq" placeholder="Nom information" style="margin-bottom : 5px;width : 70%; display : inline-block;" />';
    tr += '<a class="btn btn-default btn-sm cursor listEquipementAction" data-input="cmdeq" style="margin-left : 5px;"><i class="fa fa-list-alt "></i> Rechercher équipement</a>';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="value" placeholder="Valeur" style="margin-bottom : 5px;width : 50%; display : inline-block;" />';
    tr += '<a class="btn btn-default btn-sm cursor listEquipementInfo" data-input="value" style="margin-left : 5px;"><i class="fa fa-list-alt "></i> Rechercher équipement</a>';
    tr += '</td>';
    tr += '<td>';
	tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="Min" title="Min" style="width : 40%;display : inline-block;" /> ';
    tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" placeholder="Max" title="Max" style="width : 40%;display : inline-block;" />';
	tr += '</td>';
    tr += '<td>';
    tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>Afficher</label></span> ';
    tr += '</td>';
    tr += '<td>';
    if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure"><i class="fa fa-cogs"></i></a> ';
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> Tester</a>';
    }
    tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
    tr += '</tr>';

    $('#table_cmd tbody').append(tr);
    $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    var tr = $('#table_cmd tbody tr:last');
    jeedom.eqLogic.builSelectCmd({
        id: $(".li_eqLogic.active").attr('data-eqLogic_id'),
        filter: {type: 'info'},
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (result) {
            tr.find('.cmdAttr[data-l1key=value]').append(result);
            tr.find('.cmdAttr[data-l1key=configuration][data-l2key=updateCmdId]').append(result);
            tr.setValues(_cmd, '.cmdAttr');
            jeedom.cmd.changeType(tr, init(_cmd.subType));
        }
    });
}
}
