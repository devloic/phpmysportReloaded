<?php
##################################
# field_state 
##################################

# variables
$page['L_message']="";
$page['form_action']=convert_url("index.php?r=".$lang['general']['idurl_match']."&v1=field_state_list");
$nb_erreur="0";
$page['erreur']=array();
$page['field_state']=array();

# form values
$page['value_id']="";
$page['value_name']="";

# cas d'une suppression 
if($right_user['field_state_list'] AND isset($_GET['v2']) AND isset($_GET['v3']) AND $_GET['v3']=="delete" AND (!isset($included) OR $included==0))
{
 $var['id']=$_GET['v2'];
 $sql_verif=sql_replace($sql['match']['verif_field_state'],$var);
 $sql_sup=sql_replace($sql['match']['sup_field_state'],$var);
 $sgbd = sql_connect();
 
 # verification
 if(sql_num_rows(sql_query($sql_verif))!="0") { $page['erreur'][$nb_erreur]['message']=$lang['match']['E_exist_field_state_match']; $nb_erreur++; }
 
 if($nb_erreur==0)
 {
  if(sql_query($sql_sup) != false) { $page['L_message']=$lang['match']['form_field_state_sup_1'];  }
  else { $page['L_message']=$lang['match']['form_field_state_sup_0']; }
 }
 else { $page['L_message']=$lang['match']['form_field_state_sup_0']; }
 sql_close($sgbd);
}

# case of add or edit
if(isset($_POST) AND !empty($_POST) AND (!isset($included) OR $included==0) AND $right_user['field_state_list'])
{
 # we format datas
 if(isset($_POST['name'])) $_POST['name']=format_txt($_POST['name']);

 # we check datas
 if(!isset($_POST['name']) OR empty($_POST['name'])) { $page['erreur'][$nb_erreur]['message']=$lang['match']['E_empty_name_field_state']; $nb_erreur++; }
 else
 {
  # we check if it does not already exist
   $sgbd = sql_connect();
   $sql_verif = sql_replace($sql['match']['verif_presence_field_state'],$_POST);
   $res_verif = sql_query($sql_verif);
   $nb_res = sql_num_rows($res_verif);
   sql_free_result($res_verif);
   sql_close($sgbd);
   if($nb_res!="0") { $page['erreur'][$nb_erreur]['message']=$lang['match']['E_exist_field_state']; $nb_erreur++; }
 }

 # there is no error in submited datas
 if($nb_erreur==0)
 {
  # case : new item to add
  if(!isset($_POST['id']) OR empty($_POST['id']))
  {
   $sql_add=sql_replace($sql['match']['insert_field_state'],$_POST);
   $sgbd = sql_connect();
   if(sql_query($sql_add) != false) { $page['L_message']=$lang['match']['form_field_state_add_1']; }
   else { $page['L_message']=$lang['match']['form_field_state_add_0']; }
   sql_close($sgbd);
  }
  # case : item to modify
  else
  {
   $sql_modification=sql_replace($sql['match']['edit_field_state'],$_POST);
   $sgbd = sql_connect();
   if(sql_query($sql_modification) != false) { $page['L_message']=$lang['match']['form_field_state_edit_1']; }
   else { $page['L_message']=$lang['match']['form_field_state_edit_0']; }
   sql_close($sgbd);
  }
 }
 else
 {
  # there is some errors: we show the datas again
  if(isset($_POST['id'])) $page['value_id']=$_POST['id'];
  if(isset($_POST['name'])) $page['value_name']=$_POST['name'];
 }
}


# listes des field_state
$sql_liste=$sql['match']['select_field_state'];
$sgbd = sql_connect();
$res_liste = sql_query($sql_liste);
$i="0";
while($ligne = sql_fetch_array($res_liste))
{
 $page['field_state'][$i]['id']=$ligne['field_state_id'];
 $page['field_state'][$i]['name']=$ligne['field_state_name'];
 
 if(isset($var['value_field_state']) AND $var['value_field_state']==$ligne['field_state_id']) { $page['field_state'][$i]['selected']="selected"; } else { $page['field_state'][$i]['selected']=""; } 
 
 $page['field_state'][$i]['form_action']=$page['form_action'];
 $page['field_state'][$i]['link_modification']=convert_url("index.php?r=".$lang['general']['idurl_match']."&v1=form_field_state&v2=".$ligne['field_state_id']);
  $page['field_state'][$i]['link_suppression']=convert_url("index.php?r=".$lang['general']['idurl_match']."&v1=field_state_list&v2=".$ligne['field_state_id']."&v3=delete");
  $page['field_state'][$i]['L_edit']=$lang['match']['edit'];
  $page['field_state'][$i]['L_delete']=$lang['match']['delete'];
   
 $i++;
}
sql_free_result($res_liste);
sql_close($sgbd);


$page['L_title']=$lang['match']['field_state_list'];
$page['L_liste']=$lang['match']['field_state_list'];
$page['L_add']=$lang['match']['add_field_state'];
$page['L_valider']=$lang['match']['submit'];
$page['L_erreur']=$lang['general']['E_erreur'];
$page['L_field_required']=$lang['general']['field_required'];

$page['meta_title']=$lang['match']['field_state_list'];
$page['template']=$tpl['match']['field_state_list'];
?>