<?php

if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('eqType', 'stock');
$eqLogics = eqLogic::byType('stock');

?>

<div class="row row-overflow">
  <div class="col-lg-2 col-md-3 col-sm-4">
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
    <div class="row">
      <div class="col-sm-6">
        <form class="form-horizontal">
          <fieldset>
            <legend><i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i>  {{Général}}
              <i class='fa fa-cogs eqLogicAction pull-right cursor expertModeVisible' data-action='configure'></i>
            </legend>
            <div class="form-group">
              <label class="col-md-2 control-label">{{Equipement stock}}</label>
              <div class="col-md-3">
                <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement stock}}"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-2 control-label" >{{Objet parent}}</label>
              <div class="col-md-3">
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
              <label class="col-md-2 control-label">{{Catégorie}}</label>
              <div class="col-md-8">
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
              <label class="col-sm-2 control-label" ></label>
              <div class="col-sm-9">
                <input type="checkbox" class="eqLogicAttr bootstrapSwitch" data-label-text="{{Activer}}" data-l1key="isEnable" checked/>
                <input type="checkbox" class="eqLogicAttr bootstrapSwitch" data-label-text="{{Visible}}" data-l1key="isVisible" checked/>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">{{Commentaire}}</label>
              <div class="col-md-8">
                <textarea class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="commentaire" ></textarea>
              </div>
            </div>

          </fieldset>

        </form>
      </div>

      <div id="infoNode" class="col-sm-6">
        <form class="form-horizontal">
          <fieldset>
            <legend><i class="fa fa-info-circle"></i>  {{Configuration}}</legend>


            <div class="form-group">
              <label class="col-md-2 control-label">{{Equipement local ou déporté ?}}</label>
              <div class="col-md-3">
                <select id="maitreesclave" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="maitreesclave"
                onchange="if(this.selectedIndex == 1) document.getElementById('deporte').style.display = 'block';
                else document.getElementById('deporte').style.display = 'none';">
                <option value="local">{{Local}}</option>
                <option value="deporte">{{Déporté}}</option>
              </select>
            </div>
          </div>
          <div id="deporte">
            <div class="form-group">
              <label class="col-md-3 control-label">{{Adresse IP}}</label>
              <div class="col-md-3">
                <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="addressip" type="text" placeholder="{{saisir l'adresse IP}}">
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label">{{Port SSH}}</label>
              <div class="col-md-3">
                <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="portssh" type="text" placeholder="{{saisir le port SSH}}">
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label">{{Identifiant}}</label>
              <div class="col-md-3">
                <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="user" type="text" placeholder="{{saisir le login}}">
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label">{{Mot de passe}}</label>
              <div class="col-md-3">
                <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="password" type="password" placeholder="{{saisir le password}}">
              </div>
            </div>
          </div>

        </fieldset>
      </form>
    </div>
  </div>

  <form class="form-horizontal">
    <fieldset>
      <div class="form-actions">
        <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
        <a class="btn btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
      </div>
    </fieldset>
  </form>
</br>

<legend><i class="fa fa-table"></i>  {{Commandes stock}}</legend>
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

<form class="form-horizontal">
  <fieldset>
    <div class="form-actions">
      <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
      <a class="btn btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
    </div>
  </fieldset>
</form>

</div>
</div>

<?php include_file('desktop', 'stock', 'js', 'stock'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>
