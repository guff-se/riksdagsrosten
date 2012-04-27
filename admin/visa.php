<?php 
error_reporting(E_ALL);
ini_set('display_errors','On');

  // start a session //
  session_start(); 
  
  // include the database Class //
  require_once '../classes/Database.class.php';

	$db=new Database();
	$db->connectSQL();
	
	
	$dok_id=$_GET['dok_id'];

include("header.php");

$parameters="";

if(!isset($_GET['showAll']))
	$parameters="AND Utskottsforslag.visible = 1";

$row = $db->executeSQL("SELECT Utskottsforslag.*, Organ.* FROM Utskottsforslag, Organ
        WHERE Utskottsforslag.punkt = 1
        AND Utskottsforslag.organ = Organ.organ
		AND Utskottsforslag.dok_id = '$dok_id'","SELECT");

?>

<form class="form-horizontal" id="postform" method="post" action="post.php">
  <fieldset>
	<input type="hidden" id="dok_id" name="dok_id" value="<?=$row->dok_id?>">
	<input type="hidden" id="titel_orig" name="titel_orig" value="<?print(htmlspecialchars($row->titel_orig));?>">
	<input type="hidden" id="bik_orig" name="bik_orig" value="<?print(htmlspecialchars($row->bik_orig));?>">
    <legend>Redigera votering: <a href="/votering/<?=$row->dok_id?>"><?=$row->dok_id?></a></legend>
    <div class="control-group">
      <label class="control-label" for="input01">Fulltext</label>
      <div class="controls">
	 	<p class="help-block"><a href="http://data.riksdagen.se/dokument/<?=$row->dok_id?>">http://data.riksdagen.se/dokument/<?=$row->dok_id?></a></p>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="input01">Riksdagens titel</label>
      <div class="controls">
	 	<p class="help-block"><?=$row->titel_orig?></p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="titel">Riksdagsröstens titel</label>
      <div class="controls">
        <input type="text" class="span9 input-xlarge" id="titel" name="titel" value="<?=$row->titel?>">
		<input class="btn" type="button" id="titel_ater" value="&Aring;terst&auml;ll">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="titel">Fråga</label>
      <div class="controls">
        <input type="text" class="span9 input-xlarge" id="fraga" name="fraga" value="<?=$row->fraga?>">
      </div>
    </div>

    <div class="control-group">
      <label class="control-label">Publiceringsdatum</label>
      <div class="controls">
	 	<p class="help-block"><?=$row->publicerad?></p>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label">Beslutsdatum</label>
      <div class="controls">
	 	<p class="help-block"><?=$row->beslut_datum?></p>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label">Riksdagens röster</label>
      <div class="controls">
	 	<p class="help-block">
<?			if(abs($row->roster_ja-$row->roster_nej)<10) { ?>
				<font style="color:red;">
<?			} ?>
				Ja: <?=$row->roster_ja?> / Nej: <?=$row->roster_nej?> /
				Avstår: <?=$row->roster_avstar?> / Frånvaro: <?=$row->roster_franvarande?></font>
		</p>
      </div>
    </div>
	    <div class="control-group">
	      <label class="control-label">Folkets röster</label>
	      <div class="controls">
		 	<p class="help-block">
					Ja: <?=$row->folket_ja?> / Nej: <?=$row->folket_nej?>
			</p>
	      </div>
	    </div>
    <div class="control-group">
      <label class="control-label" for="bik">Textarea</label>
      <div class="controls">
        <textarea name="bik" class="input-xlarge span9" id="bik" rows="9"><?=$row->bik?></textarea>
		<input class="btn" type="button" id="bik_ater" value="&Aring;terst&auml;ll">
      </div>
    </div>
    <div class="form-actions">
<?		if($row->visible) { ?>
			<button name="visible" class="btn" type="submit" value="0">G&ouml;m</button>
<?		} else { ?>
			<button name="visible" class="btn btn-success" type="submit" value="1">Visa</button>
<? 		} ?>
		<button name="spara" type="button" class="btn btn-primary" id="spara" value="1">Spara &auml;ndringar</button>
		<button type="button" class="btn" id="cancel">Avbryt</button>
    </div>
  </fieldset>
</form>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
	<script src="admin.js" type="text/javascript"></script>
  </body>
</html>




