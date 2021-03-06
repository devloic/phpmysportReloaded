<?php
# mise a jour de la version 1.0 a 1.1
# -> Traduction du script en anglais "English standard"


# la mise a jour se fait en plusieurs etapes:
# 1. l'utilisateur doit sauvegarder son fichier include/conf.php en local
# 2. suppression des fichiers du serveur
# 3. ajout des nouveaux fichiers
# 4. copie du fichier include/conf.php
# 5. Assistant de mise a jour


# renommage des tables de la base de donnees
$filename="update_1.0-1.1.txt";
if ($fd = fopen($filename, "r"))
{
 $sql_update_database="";
 $var['nb_table_ok']=0;
 $var['nb_table_pbm']=0;
 $nb_table=0;
 $db = sql_connect();
 while (!feof($fd))
 {
  $ligne=fgets ($fd, 4096);
  $sql_update_database.=$ligne;
 
  if(preg_match("/;/i",$ligne))
  {
    $sql_update_database=preg_replace ("/;/i","",$sql_update_database);
    if(sql_query($sql_update_database)) { $var['nb_table_ok']++; }   
    else { $var['nb_table_pbm']++; } 
    $sql_update_database="";  
    $nb_table++;
  }
 }  
 @fclose ($fd);
   
 if($var['nb_table_pbm'] != 0) { 
  $page['erreur'][$nb_erreur]['message']=$lang['installation']['E_update_database'];
  $nb_erreur++; 
 }
 else
 {
  $page['message'][$nb_message]['message']=$lang['installation']['update_database_ok']; 
  $nb_message++;
 }
}

# creation of .htaccess file
if(URL_REWRITE==1)
{
 $fichier_htaccess="htaccess.txt";
 $fichier_htaccess_site="../.htaccess";
 $contenu_htaccess=implode("",file($fichier_htaccess));

 @chmod($fichier_htaccess_site, 0777);
 if ($fd = @fopen($fichier_htaccess_site, "w"))
 {
  // cr�ation du fichier
  @fwrite($fd, $contenu_htaccess);
  @fclose($fd);
  $_SESSION['creation_htaccess']=1;  
 }
 else
 {
  $page['erreur'][$nb_erreur]['message']=$lang['installation']['E_creation_htaccess'];
  $nb_erreur++;
 }
 @chmod($fichier_htaccess_site, 0755); 
}

# creation of the new file conf.php
if($nb_erreur==0)
{
 $fichier_conf="conf.txt";
 $fichier_conf_site="../include/conf.php";
 $var['root']=RACINE;
 $var['url']=RACINE_URL; 
 $var['title']=TITRE_SITE;
 $var['club']=CLUB;
 $var['host']=SGBD_HOST;
 $var['user_base']=SGBD_USER;
 $var['pass_base']=SGBD_PWD;
 $var['name_base']=SGBD_NAME;
 $var['version']=VERSION_SITE;
 $var['max_file_size']=MAX_FILE_SIZE;
 $var['url_rewrite']=URL_REWRITE;
 $var['email']=EXPEDITEUR_EMAIL;
 $var['title']=EXPEDITEUR_NOM;
 $var['nb_player']=NB_MAX_TITULAIRE;
 #$var['site_open']=SITE_OUVERT;
 $var['lang']=LANG;

 $contenu_conf=implode("",file($fichier_conf));
 $contenu_conf=text_replace($contenu_conf,$var); 

 @chmod("../include/", 0777);
 @chmod($fichier_conf_site, 0777);
 if ($fd = @fopen($fichier_conf_site, "w"))
 {
  // mise a jour du fichier de connection
  @fwrite($fd, $contenu_conf);
  @fclose($fd);
  @chmod($fichier_conf_site, 0755);  
  @chmod("../include/", 0755);
  $page['message'][$nb_message]['message']=$lang['installation']['update_conf_ok'];
  $nb_message++;
 }
 else
 {
  $page['erreur'][$nb_erreur]['message']=$lang['installation']['E_update_conf'];
  $nb_erreur++; 
 }
}

?>