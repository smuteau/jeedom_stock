<?php

if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('eqType', 'stock');
$eqLogics = eqLogic::byType('stock');

?>

<div class="row row-overflow">
  <div class="col-lg-2 col-sm-3 col-sm-4">
    <div class="bs-sidebar">
      <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
        <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter un équipement}}</a>
        <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
        <?php
        foreach ($eqLogics as $eqLogic) {
          echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
        }
        ?>
      </ul>
    </div>
  </div>

  <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
    <legend><i class="fa fa-table"></i>  {{Mes Stocks}}
    </legend>
    <div class="eqLogicThumbnailContainer">
      <div class="cursor eqLogicAction" data-action="add" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
        <center>
          <i class="fa fa-plus-circle" style="font-size : 7em;color:#00979c;"></i>
        </center>
        <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>Ajouter</center></span>
      </div>
      <?php
      $dir = dirname(__FILE__) . '/../../doc/images/';
      $files = scandir($dir);
      foreach ($eqLogics as $eqLogic) {
        $opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
        echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff ; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
        echo "<center>";
        $test = $eqLogic->getConfiguration('type') . '.png';
        if (in_array($test, $files)) {
          $path = $eqLogic->getConfiguration('type');
        } else {
          $path = 'stock_icon';
        }
        echo '<img src="plugins/stock/doc/images/' . $path . '.png" height="105" width="95" />';
        echo "</center>";
        echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
        echo '</div>';
      }
      ?>
    </div>
  </div>


  <div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">

    <a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
    <a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>

    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Equipement}}</a></li>
      <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
      <li role="presentation"><a href="#statustab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Statut}}</a></li>
      <li role="presentation"><a href="#consotab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Consommation}}</a></li>
      <li role="presentation"><a href="#pricetab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Dépense}}</a></li>
    </ul>

    <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
      <div role="tabpanel" class="tab-pane active" id="eqlogictab">
        <form class="form-horizontal">
          <fieldset>
            <legend><i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i>  {{Général}}
              <i class='fa fa-cogs eqLogicAction pull-right cursor expertModeVisible' data-action='configure'></i>
            </legend>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{Equipement stock}}</label>
              <div class="col-sm-3">
                <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement stock}}"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label" >{{Objet parent}}</label>
              <div class="col-sm-3">
                <select class="form-control eqLogicAttr" data-l1key="object_id">
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
            <div class="form-group">
              <label class="col-sm-3 control-label" ></label>
              <div class="col-sm-8">
                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">{{Commentaire}}</label>
              <div class="col-sm-3">
                <textarea class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="commentaire" ></textarea>
              </div>
            </div>



            <div class="form-group">
              <label class="col-sm-3 control-label">{{Taille Maximum}}</label>
              <div class="col-sm-3">
                <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="maximum" type="text" placeholder="{{saisir la quantité maximum du stock}}">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{Jour de début de la semaine}}</label>
              <div class="col-sm-3">
                <select class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="week">
                  <option value="2">{{Lundi}}</option>
                  <option value="3">{{Mardi}}</option>
                  <option value="4">{{Mercredi}}</option>
                  <option value="5">{{Jeudi}}</option>
                  <option value="6">{{Vendredi}}</option>
                  <option value="7">{{Samedi}}</option>
                  <option value="1">{{Dimanche}}</option>
                </select>
              </div>
            </div>
            <div class="form-group">
            <label class="col-sm-3 control-label">{{Commande Notifications}}</label>
            <div class="col-sm-3">
              <div class="input-group">
                <input type="text"  class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="alert" />
                <span class="input-group-btn">
                  <a class="btn btn-default cursor" title="Rechercher une commande" id="bt_selectMailCmd"><i class="fa fa-list-alt"></i></a>
                </span>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">{{Notification sur consommation journalière supérieure à}}</label>
            <div class="col-sm-3">
              <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="notifDaily" type="text" placeholder="{{saisir la quantité pour laquelle vous serez notifier}}">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">{{Notification sur consommation hebdomadaire supérieure à}}</label>
            <div class="col-sm-3">
              <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="notifWeekly" type="text" placeholder="{{saisir la quantité pour laquelle vous serez notifier}}">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">{{Notification sur consommation mensuelle supérieure à}}</label>
            <div class="col-sm-3">
              <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="notifWeekly" type="text" placeholder="{{saisir la quantité pour laquelle vous serez notifier}}">
            </div>
          </div>
          <div class="form-group">
          <label class="col-sm-3 control-label">{{Commande de seuil}}</label>
          <div class="col-sm-3">
            <div class="input-group">
              <input type="text"  class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="alert" />
              <span class="input-group-btn">
                <a class="btn btn-default cursor" title="Rechercher une commande" id="bt_selectActCmd"><i class="fa fa-list-alt"></i></a>
              </span>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label">{{Commande à activer sur seuil atteint}}</label>
          <div class="col-sm-3">
            <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="notifStock" type="text" placeholder="{{saisir la quantité pour laquelle la commande sera éxécuter}}">
          </div>
        </div>

        </fieldset>
      </form>
    </div>
  <div role="tabpanel" class="tab-pane" id="commandtab">
<table id="table_cmd" class="table table-bordered table-condensed">
  <thead>
    <tr>
      <th style="width: 50px;">#</th>
      <th style="width: 300px;">{{Nom}}</th>
      <th style="width: 160px;">{{Sous-Type}}</th>
      <th style="width: 200px;">{{Paramètres}}</th>
      <th style="width: 100px;"></th>
    </tr>
  </thead>
  <tbody>

  </tbody>
</table>
</div>
<div role="tabpanel" class="tab-pane" id="statustab">
<table id="table_conso" class="table table-bordered table-condensed">
<thead>
  <tr>
    <th style="width: 50px;">#</th>
    <th style="width: 300px;">{{Nom}}</th>
    <th style="width: 160px;">{{Sous-Type}}</th>
    <th style="width: 200px;">{{Paramètres}}</th>
    <th style="width: 100px;"></th>
  </tr>
</thead>
<tbody>

</tbody>
</table>

</div>
<div role="tabpanel" class="tab-pane" id="consotab">
<table id="table_conso" class="table table-bordered table-condensed">
<thead>
  <tr>
    <th style="width: 50px;">#</th>
    <th style="width: 300px;">{{Nom}}</th>
    <th style="width: 160px;">{{Sous-Type}}</th>
    <th style="width: 200px;">{{Paramètres}}</th>
    <th style="width: 100px;"></th>
  </tr>
</thead>
<tbody>

</tbody>
</table>

</div>
<div role="tabpanel" class="tab-pane" id="pricetab">
<table id="table_price" class="table table-bordered table-condensed">
<thead>
  <tr>
    <th style="width: 50px;">#</th>
    <th style="width: 300px;">{{Nom}}</th>
    <th style="width: 160px;">{{Sous-Type}}</th>
    <th style="width: 200px;">{{Paramètres}}</th>
    <th style="width: 100px;"></th>
  </tr>
</thead>
<tbody>

</tbody>
</table>

</div>
</div>
</div>
</div>

<?php include_file('desktop', 'stock', 'js', 'stock'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>
