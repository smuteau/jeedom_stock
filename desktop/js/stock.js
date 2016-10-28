
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

function addCmdToTable(_cmd) {
  if (!isset(_cmd)) {
    var _cmd = {configuration: {}};
  }

  var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
  tr += '<td>';
  tr += '<span class="cmdAttr" data-l1key="id"></span>';
  tr += '</td><td>';
  tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" style="width : 140px;" placeholder="{{Nom de la commande}}"></td>';
  tr += '</td><td>';
  tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label></span> ';
  if (_cmd.type == 'info') {
    tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
  }
  tr += '</td><td>';
  if (is_numeric(_cmd.id)) {
    tr += '<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure"><i class="fa fa-cogs"></i></a> ';
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
  }
  tr += '</tr>';

  if (_cmd.configuration.category == 'conso') {
    $('#table_conso tbody').append(tr);
    var tr = $('#table_conso tbody tr:last');
  } else if (_cmd.configuration.category == 'price') {
    $('#table_price tbody').append(tr);
    var tr = $('#table_price tbody tr:last');
  } else if (_cmd.configuration.category == 'stock') {
    $('#table_status tbody').append(tr);
    var tr = $('#table_status tbody tr:last');
  } else {
    $('#table_cmd tbody').append(tr);
    var tr = $('#table_cmd tbody tr:last');
  }
  jeedom.eqLogic.builSelectCmd({
    id: $(".li_eqLogic.active").attr('data-eqLogic_id'),
    filter: {type: 'info'},
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (result) {
      tr.find('.cmdAttr[data-l1key=value]').append(result);
      tr.setValues(_cmd, '.cmdAttr');
      jeedom.cmd.changeType(tr, init(_cmd.subType));
    }
  });

}

$('#bt_selectMailCmd').on('click', function () {
    jeedom.cmd.getSelectModal({cmd: {type: 'action', subType: 'message'}}, function (result) {
        $('.eqLogicAttr[data-l2key=alert]').atCaret('insert', result.human);
    });
});

$('#bt_selectActCmd').on('click', function () {
    jeedom.cmd.getSelectModal({cmd: {type: 'action', subType: 'other'}}, function (result) {
        $('.eqLogicAttr[data-l2key=alert]').atCaret('insert', result.human);
    });
});
