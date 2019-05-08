<?php

##################################################################################
#    HOTELDRUID
#    Copyright (C) 2001-2019 by Marco Maria Francesco De Santis (marco@digitaldruid.net)
#
#    This program is free software: you can redistribute it and/or modify
#    it under the terms of the GNU Affero General Public License as published by
#    the Free Software Foundation, either version 3 of the License, or
#    any later version accepted by Marco Maria Francesco De Santis, which
#    shall act as a proxy as defined in Section 14 of version 3 of the
#    license.
#
#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU Affero General Public License for more details.
#
#    You should have received a copy of the GNU Affero General Public License
#    along with this program.  If not, see <http://www.gnu.org/licenses/>.
##################################################################################




function dati_tariffe ($tablenometariffe,$tariffa_sel="",$tablepersonalizza="",$tableregole="") {

$righe_tariffe = esegui_query("select * from $tablenometariffe where idntariffe < '11' order by idntariffe");
$dati_tariffe['num'] = risul_query($righe_tariffe,0,'nomecostoagg');

for ($num1 = 1 ; $num1 <= $dati_tariffe['num'] ; $num1++) {
if (!$tariffa_sel or $tariffa_sel == $num1) {
$dati_tariffe['tariffa'.$num1]['nome'] = risul_query($righe_tariffe,0,'tariffa'.$num1);
$dati_tariffe['tariffa'.$num1]['caparra_percent'] = risul_query($righe_tariffe,1,'tariffa'.$num1);
$dati_tariffe['tariffa'.$num1]['caparra_arrotond'] = risul_query($righe_tariffe,2,'tariffa'.$num1);
$dati_tariffe['tariffa'.$num1]['moltiplica'] = risul_query($righe_tariffe,3,'tariffa'.$num1);
$dati_tariffe['tariffa'.$num1]['tasse_percent'] = risul_query($righe_tariffe,4,'tariffa'.$num1);
$dati_tariffe['tariffa'.$num1]['imp_prezzi_int'] = risul_query($righe_tariffe,5,'tariffa'.$num1);
if ($dati_tariffe['tariffa'.$num1]['imp_prezzi_int']) {
$importa_prezzi_per = explode(">",$dati_tariffe['tariffa'.$num1]['imp_prezzi_int']);
$dati_tariffe['tariffa'.$num1]['num_per_importa'] = count($importa_prezzi_per);
for ($num2 = 0 ; $num2 < $dati_tariffe['tariffa'.$num1]['num_per_importa'] ; $num2++) {
$importa_prezzi = explode(";",$importa_prezzi_per[$num2]);
$dati_tariffe['tariffa'.$num1]['importa_prezzi'][$num2] = $importa_prezzi[0];
$dati_tariffe['tariffa'.$num1]['parte_prezzo'][$num2] = $importa_prezzi[1];
$dati_tariffe['tariffa'.$num1]['tipo_importa'][$num2] = $importa_prezzi[2];
$dati_tariffe['tariffa'.$num1]['val_importa'][$num2] = $importa_prezzi[3];
if ($importa_prezzi[2] == "p") $dati_tariffe['tariffa'.$num1]['arrotond_importa'][$num2] = $importa_prezzi[4];
if ($importa_prezzi[5]) {
$per_imp = explode("-",$importa_prezzi[5]);
$dati_tariffe['tariffa'.$num1]['periodo_importa_i'][$num2] = $per_imp[0];
$dati_tariffe['tariffa'.$num1]['periodo_importa_f'][$num2] = $per_imp[1];
} # fine if ($importa_prezzi[5])
if (!strstr(",".$dati_tariffe['tariffa'.$importa_prezzi[0]]['esporta_prezzi'],",$num1,")) $dati_tariffe['tariffa'.$importa_prezzi[0]]['esporta_prezzi'] .= "$num1,";
} # fine for $num2
} # fine if ($dati_tariffe['tariffa'.$num1]['imp_prezzi_int'])
} # fine if (!$tariffa_sel or $tariffa_sel == $num1)
} # fine for $num1

if ($tableregole) {
$regole = esegui_query("select * from $tableregole where (tariffa_commissioni is not NULL) or (tariffa_chiusa != '' and tariffa_chiusa is not NULL) order by iddatainizio ");
$num_regole = numlin_query($regole);
for ($num1 = 0 ; $num1 < $num_regole ; $num1++) {
$tariffa_commissioni = risul_query($regole,$num1,'tariffa_commissioni');
if (strcmp($tariffa_commissioni,"") and (!$tariffa_sel or $tariffa_sel == $tariffa_commissioni)) {
$comm_percent = risul_query($regole,$num1,'motivazione');
$comm_base = "t";
if (substr($comm_percent,0,1) == "s" or substr($comm_percent,0,1) == "c") {
$comm_base = substr($comm_percent,0,1);
$comm_percent = substr($comm_percent,1);
} # fine if (substr($comm_percent,0,1) == "s" or substr($comm_percent,0,1) == "c")
$iddataini = risul_query($regole,$num1,'iddatainizio');
if (!$iddataini) {
$dati_tariffe['tariffa'.$tariffa_commissioni]['commissioni_percent']['def'] = $comm_percent;
$dati_tariffe['tariffa'.$tariffa_commissioni]['commissioni_base']['def'] = $comm_base;
$dati_tariffe['tariffa'.$tariffa_commissioni]['commissioni_arrotond']['def'] = risul_query($regole,$num1,'motivazione2');
} # fine if (!$iddataini)
else {
$iddatafin = risul_query($regole,$num1,'iddatafine');
$dati_tariffe['tariffa'.$tariffa_commissioni]['commissioni_percent']["$iddataini-$iddatafin"] = $comm_percent;
$dati_tariffe['tariffa'.$tariffa_commissioni]['commissioni_base']["$iddataini-$iddatafin"] = $comm_base;
$dati_tariffe['tariffa'.$tariffa_commissioni]['commissioni_arrotond']["$iddataini-$iddatafin"] = risul_query($regole,$num1,'motivazione2');
} # fine else if (!$iddataini)
} # fine if (strcmp($tariffa_commissioni,"") and (!$tariffa_sel or...
$tariffa_chiusa = risul_query($regole,$num1,'tariffa_chiusa');
if ($tariffa_chiusa and (!$tariffa_sel or $tariffa_sel == substr($tariffa_chiusa,7))) {
$iddataini = risul_query($regole,$num1,'iddatainizio');
$iddatafine = risul_query($regole,$num1,'iddatafine');
for ($num2 = $iddataini ; $num2 <= $iddatafine ; $num2++) $dati_tariffe[$tariffa_chiusa]['chiusa'][$num2] = 1;
} # fine if ($tariffa_chiusa and (!$tariffa_sel or...
} # fine for $num1
} # fine if ($tableregole)

if ($tablepersonalizza) {
global $id_utente;
if ($id_utente) $id_utente_pers = $id_utente;
else $id_utente_pers = 1;
$arrotond_tasse = esegui_query("select * from $tablepersonalizza where idpersonalizza = 'arrotond_tasse' and idutente = '$id_utente_pers'");
$dati_tariffe['tasse_arrotond'] = (double) risul_query($arrotond_tasse,0,'valpersonalizza');
} # fine if ($tablepersonalizza)

return $dati_tariffe;

} # fine function dati_tariffe




function dati_regole2 (&$dati_r2,&$app_regola2_predef,$tipotariffa,$idinizioperiodo,$idfineperiodo,&$id_periodo_corrente,$tipo_periodi,$anno,$tableregole) {

if (!is_array($dati_r2)) {
$dati_r2 = array();
$regole2 = esegui_query("select * from $tableregole where tariffa_per_app != ''");
$dati_r2['num'] = numlin_query($regole2);

for ($num1 = 0 ; $num1 < $dati_r2['num'] ; $num1++) {
$tariffa_r2 = risul_query($regole2,$num1,'tariffa_per_app');
$dati_r2[$tariffa_r2] = risul_query($regole2,$num1,'motivazione');
$dati_r2['l2'][$tariffa_r2] = risul_query($regole2,$num1,'motivazione2');
if (strcmp($dati_r2['l2'][$tariffa_r2],"")) {
$dati_r2['dini'][$tariffa_r2] = risul_query($regole2,$num1,'iddatainizio');
if (!$dati_r2['dini'][$tariffa_r2]) $dati_r2['dfine'][$tariffa_r2] = risul_query($regole2,$num1,'iddatafine');
} # fine if (strcmp($dati_r2['l2'][$tariffa_r2],""))
$napp = risul_query($regole2,$num1,'motivazione3');
if ($napp) {
if (substr($napp,0,1) == "v") {
$napp = substr($napp,1);
$dati_r2['napp']['v'][$tariffa_r2] = 1;
} # fine if (substr($napp,0,1) == "v")
$dati_r2['napp'][$tariffa_r2] = $napp;
} # fine if ($napp)
} # fine for $num1
} # fine if (!is_array($dati_r2))

$app_regola2_predef = "";
if ($tipotariffa) {
$lista_app = $dati_r2[$tipotariffa];
if (strcmp($dati_r2['l2'][$tipotariffa],"")) {
if (!$id_periodo_corrente) $id_periodo_corrente = calcola_id_periodo_corrente($anno);
$ngiorni_reg2b = $dati_r2['dini'][$tipotariffa];
if ($ngiorni_reg2b) $diff_giorni = $idinizioperiodo - $id_periodo_corrente + 1;
else {
$ngiorni_reg2b = $dati_r2['dfine'][$tipotariffa];
$diff_giorni = $idfineperiodo - $id_periodo_corrente + 1;
} # fine else if ($ngiorni_reg2b)
if ($tipo_periodi == "s") $diff_giorni = $diff_giorni * 7;
if ($diff_giorni < $ngiorni_reg2b) {
$app_regola2_predef = $dati_r2[$tipotariffa];
$lista_app = $dati_r2['l2'][$tipotariffa];
} # fine if ($diff_giorni < $ngiorni_reg2b)
} # fine if (strcmp($dati_r2['l2'][$tipotariffa],""))

return $lista_app;
} # fine if ($tipotariffa)

} # fine function dati_regole2




function calcola_commissioni ($dati_tariffe,$tipotariffa,$iddataini,$iddatafine,$tariffesettimanali,$sconto,$prezzo_costi_tot) {

$commissioni = (double) 0;
if (@is_array($dati_tariffe[$tipotariffa]['commissioni_percent'])) {
$costo_tariffa_corr = (double) 0;
$num_sett = 0;
$numsett = 0;
$tariffesettimanali = explode(";",$tariffesettimanali);
$tariffesettimanali = explode(",",$tariffesettimanali[0]);
$agg_sett_sconto = round((((double) $sconto * -1) / ($iddatafine - $iddataini + 1)),2);
$agg_sett_costi = round(((((double) $sconto * -1) + (double) $prezzo_costi_tot) / ($iddatafine - $iddataini + 1)),2);
for ($num1 = $iddataini ; $num1 <= $iddatafine ; $num1++) {

$costo_tariffa_sett = (double) $tariffesettimanali[$numsett];
$commissioni_percent = $dati_tariffe[$tipotariffa]['commissioni_percent']['def'];
$commissioni_arrotond = $dati_tariffe[$tipotariffa]['commissioni_arrotond']['def'];
$commissioni_base = $dati_tariffe[$tipotariffa]['commissioni_base']['def'];
reset($dati_tariffe[$tipotariffa]['commissioni_percent']);
foreach ($dati_tariffe[$tipotariffa]['commissioni_percent'] as $per_comm => $val_comm) {
if ($per_comm != "def") {
$ini_comm = explode("-",$per_comm);
$fine_comm = $ini_comm[1];
$ini_comm = $ini_comm[0];
if ($num1 >= $ini_comm and $num1 <= $fine_comm) {
$commissioni_percent = $val_comm;
$commissioni_arrotond = $dati_tariffe[$tipotariffa]['commissioni_arrotond'][$per_comm];
$commissioni_base = $dati_tariffe[$tipotariffa]['commissioni_base'][$per_comm];
break;
} # fine if ($num1 >= $ini_comm and $num1 <= $fine_comm)
} # fine if ($per_comm != "def")
} # fine foreach ($dati_tariffe[$tipotariffa]['commissioni_percent'] as $per_comm => $val_comm)

if ($num1 != $iddataini and ($ultime_comm_perc != $commissioni_percent or $ultime_comm_arr != $commissioni_arrotond or $ultime_comm_base != $commissioni_base)) {
if ($ultime_comm_perc) {
if ($ultime_comm_arr == "val") $commissioni_corr = $ultime_comm_perc * $num_sett;
else {
$costo_base = (double) $costo_tariffa_corr;
if ($ultime_comm_base == "s") $costo_base = $costo_base + ((double) $agg_sett_sconto * $num_sett);
if ($ultime_comm_base == "c") $costo_base = $costo_base + ((double) $agg_sett_costi * $num_sett);
$commissioni_corr = ($costo_base * (double) $ultime_comm_perc) / 100;
$commissioni_corr = $commissioni_corr / $ultime_comm_arr;
$commissioni_corr = floor(round($commissioni_corr));
$commissioni_corr = $commissioni_corr * $ultime_comm_arr;
} # fine else if ($commissioni_arrotond == "val")
$commissioni += (double) $commissioni_corr;
} # fine if ($ultime_comm_perc)
$costo_tariffa_corr = (double) 0;
$num_sett = 0;
} # fine if ($num1 != $iddataini and ($ultime_comm_perc != $commissioni_percent or...

$num_sett++;
$ultime_comm_perc = $commissioni_percent;
$ultime_comm_arr = $commissioni_arrotond;
$ultime_comm_base = $commissioni_base;
$costo_tariffa_corr += (double) $tariffesettimanali[$numsett];
$numsett++;
} # fine for $num1

if ($ultime_comm_perc) {
if ($ultime_comm_arr == "val") $commissioni_corr = $ultime_comm_perc * $num_sett;
else {
$costo_base = (double) $costo_tariffa_corr;
if ($ultime_comm_base == "s") $costo_base = $costo_base + ((double) $agg_sett_sconto * $num_sett);
if ($ultime_comm_base == "c") $costo_base = $costo_base + ((double) $agg_sett_costi * $num_sett);
$commissioni_corr = ($costo_base * (double) $ultime_comm_perc) / 100;
$commissioni_corr = $commissioni_corr / $ultime_comm_arr;
$commissioni_corr = floor(round($commissioni_corr));
$commissioni_corr = $commissioni_corr * $ultime_comm_arr;
} # fine else if ($commissioni_arrotond == "val")
$commissioni += (double) $commissioni_corr;
} # fine if ($ultime_comm_perc)
} # fine if (@is_array($dati_tariffe[$tipotariffa]['commissioni_percent']))

return $commissioni;

} # fine function calcola_commissioni




function calcola_caparra ($dati_tariffe,$tipotariffa,$iddataini,$iddatafine,$costo_tariffa,$tariffesettimanali) {

$caparra = (double) 0;

$caparra_percent = $dati_tariffe[$tipotariffa]['caparra_percent'];
if ($caparra_percent) {
$caparra_arrotond = $dati_tariffe[$tipotariffa]['caparra_arrotond'];
if ($caparra_arrotond == "val") $caparra = $caparra_percent;
if ($caparra_arrotond == "gio") {
$lunghezza_periodo = ($iddatafine - $iddataini + 1);
if ($lunghezza_periodo <= $caparra_percent) $caparra = $costo_tariffa;
else {
$tariffesettimanali = explode(";",$tariffesettimanali);
$tariffesettimanali = explode(",",$tariffesettimanali[0]);
for ($num1 = 0 ; $num1 < $caparra_percent ; $num1++) $caparra += (double) $tariffesettimanali[$num1];
} # fine else if ($lunghezza_periodo >= $caparra_percent)
} # fine if ($caparra_arrotond == "gio")
if ($caparra_arrotond != "val" and $caparra_arrotond != "gio") {
$caparra = ($costo_tariffa * (double) $caparra_percent) / 100;
$caparra = $caparra / $caparra_arrotond;
$caparra = floor($caparra);
$caparra = $caparra * $caparra_arrotond;
if (!$caparra) {
$caparra = $caparra_arrotond;
if ($caparra > $costo_tariffa) $caparra = $costo_tariffa;
} # fine (!$caparra)
} # fine else if ($caparra_arrotond != "val" and $caparra_arrotond != "gio")
} # fine if ($caparra_percent)

return $caparra;

} # fine function calcola_caparra




function aggiorna_tariffe_esporta ($dati_tariffe,$tariffa_da,$idperiodo,$prezzoperiodo,$prezzoperiodop,$tableperiodi,&$agg_vett,&$num_agg) {
if ($dati_tariffe[$tariffa_da]['esporta_prezzi']) {

if (str_replace("-","",$idperiodo) != $idperiodo) {
$fine_per = explode("-",$idperiodo);
$ini_per = $fine_per[0];
$fine_per = $fine_per[1];
} # fine if (str_replace("-","",$idperiodo) != $idperiodo)
else {
$ini_per = $idperiodo;
$fine_per = $idperiodo;
} # fine else if (str_replace("-","",$idperiodo) != $idperiodo)

$tar_esporta = explode(",",$dati_tariffe[$tariffa_da]['esporta_prezzi']);
for ($num_tar = 0 ; $num_tar < (count($tar_esporta) - 1) ; $num_tar++) {
$tariffa = "tariffa".$tar_esporta[$num_tar];

if ($idperiodo == "opztariffa") {
$tablenometariffe = $prezzoperiodop;
$opztariffa = esegui_query("select * from $tableperiodi where $tariffa"."p is not NULL and $tariffa"."p != '0' ");
if (numlin_query($opztariffa)) $opztariffa = "p";
else $opztariffa = "s";
esegui_query("update $tablenometariffe set $tariffa = '$opztariffa' where idntariffe = '4' ");
} # fine if ($idperiodo == "opztariffa")
else {

$num_ord_prec = -1;
if (!$agg_vett[$tariffa]) $agg_vett[$tariffa] = array();
for ($num1 = $ini_per ; $num1 <= $fine_per ; $num1++) {

$num_ord = 0;
for ($num2 = 1 ; $num2 < $dati_tariffe[$tariffa]['num_per_importa'] ; $num2++) {
if ("tariffa".$dati_tariffe[$tariffa]['importa_prezzi'][$num2] == $tariffa_da and $dati_tariffe[$tariffa]['periodo_importa_f'][$num2] >= $num1 and $dati_tariffe[$tariffa]['periodo_importa_i'][$num2] <= $num1) {
$num_ord = $num2;
break;
} # fine if ("tariffa".$dati_tariffe[$tariffa]['importa_prezzi'][$num2] == $tariffa_da and...
} # fine for $num2
if ($num_ord > 0 or "tariffa".$dati_tariffe[$tariffa]['importa_prezzi'][0] == $tariffa_da) {

if ($num_ord != $num_ord_prec) {
$importa_percent = (double) $dati_tariffe[$tariffa]['val_importa'][$num_ord];
$importa_arrotond = (double) $dati_tariffe[$tariffa]['arrotond_importa'][$num_ord];
$tipo_percent = $dati_tariffe[$tariffa]['tipo_importa'][$num_ord];
if ($tipo_percent == "s" and !$agg_vett[$tariffa][$num_ord]) {
$agg_int = floor($importa_percent);
$resto_int = $importa_percent - (double) $agg_int;
$agg_gio = floor($agg_int / 7);
for ($num2 = 1 ; $num2 <= 7 ; $num2++) $agg_vett[$tariffa][$num_ord][$num2] = $agg_gio;
$resto = $agg_int - ($agg_gio * 7);
if ($resto >= 1) {
$agg_vett[$tariffa][$num_ord][1]++;
$resto--;
} # fine if ($resto >= 1)
for ($num2 = 7 ; $num2 > (7 - $resto) ; $num2--) $agg_vett[$tariffa][$num_ord][$num2]++;
$agg_vett[$tariffa][$num_ord][1] += $resto_int;
$num_agg[$tariffa][$num_ord]['s'] = 0;
$num_agg[$tariffa][$num_ord]['p'] = 0;
} # fine if ($tipo_percent == "s" and !$agg_vett[$tariffa][$num_ord])
if ($tipo_percent == "g") $perc = $importa_percent;
} # fine if ($num_ord != $num_ord_prec)

if ((string) $prezzoperiodo != "NO") {
if ($tipo_percent == "s") {
$num_agg[$tariffa][$num_ord]['s']++;
$perc = $agg_vett[$tariffa][$num_ord][$num_agg[$tariffa][$num_ord]['s']];
if ($num_agg[$tariffa][$num_ord]['s'] == 7) $num_agg[$tariffa][$num_ord]['s'] = 0;
} # fine if ($tipo_percent == "s")

$prezzo_a = (double) $prezzoperiodo;
if ($dati_tariffe[$tariffa]['parte_prezzo'][$num_ord] != "p") {
if ($tipo_percent == "p") $perc = (double) (($prezzo_a / 100.0) * $importa_percent);
if ($perc) {
if ($tipo_percent == "p") $perc = (round(($perc / $importa_arrotond),0) * $importa_arrotond);
$prezzo_a = $prezzo_a + $perc;
} # fine if ($perc)
} # fine if ($dati_tariffe[$tariffa]['parte_prezzo'][$num_ord] != "p")
if ($prezzo_a) esegui_query("update $tableperiodi set $tariffa = '$prezzo_a' where idperiodi = '$num1'");
else esegui_query("update $tableperiodi set $tariffa = NULL where idperiodi = '$num1'");
} # fine if ((string) $prezzoperiodo != "NO")

if ((string) $prezzoperiodop != "NO") {
if ($tipo_percent == "s") {
$num_agg[$tariffa][$num_ord]['p']++;
$perc = $agg_vett[$tariffa][$num_ord][$num_agg[$tariffa][$num_ord]['p']];
if ($num_agg[$tariffa][$num_ord]['p'] == 7) $num_agg[$tariffa][$num_ord]['p'] = 0;
} # fine if ($tipo_percent == "s")

$prezzo_a_p = (double) $prezzoperiodop;
if ($dati_tariffe[$tariffa]['parte_prezzo'][$num_ord] != "f") {
if ($tipo_percent == "p") $perc = (double) (($prezzo_a_p / 100.0) * $importa_percent);
if ($perc) {
if ($tipo_percent == "p") $perc = (round(($perc / $importa_arrotond),0) * $importa_arrotond);
$prezzo_a_p = $prezzo_a_p + $perc;
} # fine if ($perc)
} # fine if ($dati_tariffe[$tariffa]['parte_prezzo'][$num_ord] != "f")
if ($prezzo_a_p) esegui_query("update $tableperiodi set $tariffa"."p = '$prezzo_a_p' where idperiodi = '$num1'");
else esegui_query("update $tableperiodi set $tariffa"."p = NULL where idperiodi = '$num1'");
} # fine if ((string) $prezzoperiodop != "NO")

$num_ord_prec = $num_ord;
} # fine if ($num_ord > 0 or "tariffa".$dati_tariffe[$tariffa]['importa_prezzi'][0] == $tariffa_da)
} # fine for $num1
} # fine else if ($idperiodo == "opztariffa")
} # fine for $num_tar
} # fine if ($dati_tariffe[$tipotariffa]['esporta_prezzi'])

} # fine function aggiorna_tariffe_esporta




function periodo_importato_tar ($tariffa,$idperiodo,$dati_tariffe) {
$imp_periodo = 0;
if ($dati_tariffe[$tariffa]['imp_prezzi_int']) {
if ($dati_tariffe[$tariffa]['importa_prezzi'][0]) $imp_periodo = $dati_tariffe[$tariffa]['importa_prezzi'][0];
for ($num1 = 1 ; $num1 < $dati_tariffe[$tariffa]['num_per_importa'] ; $num1++) {
if ($dati_tariffe[$tariffa]['periodo_importa_f'][$num1] >= $idperiodo and $dati_tariffe[$tariffa]['periodo_importa_i'][$num1] <= $idperiodo) {
$imp_periodo = $dati_tariffe[$tariffa]['importa_prezzi'][$num1];
break;
} # fine if ($dati_tariffe[$tariffa]['periodo_importa_f'][$num1] >= $idinizioperiodo and...
} # fine for $num1
} # fine if ($dati_tariffe[$tariffa]['imp_prezzi_int'])
return $imp_periodo;
} # fine function periodo_importato_tar




function dati_cat_pers ($id_utente,$tablepersonalizza,$lingua_mex,$priv_ins_num_persone="s",$dati_perc=1,$altre_lingue=0) {

$dati_cat_pers = array();
if ($priv_ins_num_persone != "n") {
$cat_pers = esegui_query("select * from $tablepersonalizza where idpersonalizza = 'num_categorie_persone' and idutente = '$id_utente' ");
if ($dati_perc) $perc_cat_persone = explode(";",risul_query($cat_pers,0,'valpersonalizza'));
$dati_cat_pers['num'] = risul_query($cat_pers,0,'valpersonalizza_num');
if ($dati_cat_pers['num'] > 1) {
for ($num1 = 0 ; $num1 < $dati_cat_pers['num'] ; $num1++) $dati_cat_pers[$num1] = array();
if ($dati_perc) {
$dati_cat_pers[0]['osp_princ'] = "s";
$dati_cat_pers[0]['perc'] = "100";
} # fine if ($dati_perc)
$nomi_cat_pers = esegui_query("select valpersonalizza from $tablepersonalizza where idpersonalizza = 'nomi_cat_pers_".aggslashdb($lingua_mex)."' and idutente = '$id_utente'");
if (!numlin_query($nomi_cat_pers)) {
$nomi_cat_pers = esegui_query("select valpersonalizza from $tablepersonalizza where idpersonalizza = 'nomi_cat_pers_en' and idutente = '$id_utente'");
if (!numlin_query($nomi_cat_pers)) {
$nomi_cat_pers = esegui_query("select valpersonalizza from $tablepersonalizza where idpersonalizza = 'nomi_cat_pers_ita' and idutente = '$id_utente'");
$dati_cat_pers['lang'] = "ita";
} # fine if (!numlin_query($nomi_cat_pers))
else $dati_cat_pers['lang'] = "en";
} # fine if (!numlin_query($nomi_cat_pers))
else $dati_cat_pers['lang'] = $lingua_mex;
$nomi_cat_pers = explode("<",risul_query($nomi_cat_pers,0,'valpersonalizza'));

if ($altre_lingue) {
if ($dati_cat_pers['lang'] != 'ita') {
$n_cat_pers = esegui_query("select valpersonalizza from $tablepersonalizza where idpersonalizza = 'nomi_cat_pers_ita' and idutente = '$id_utente' ");
$n_cat_pers = explode("<",risul_query($n_cat_pers,0,'valpersonalizza'));
for ($num1 = 0 ; $num1 < $dati_cat_pers['num'] ; $num1++) {
$n_cat_pers_v = explode(">",$n_cat_pers[$num1]);
$dati_cat_pers[$num1]['langs']['ita']['n_s'] = $n_cat_pers_v[0];
$dati_cat_pers[$num1]['langs']['ita']['n_p'] = $n_cat_pers_v[1];
} # fine for $num1
} # fine if ($dati_cat_pers['lang'] != 'ita')
$lang_dir = opendir("./includes/lang/");
while ($ini_lingua = readdir($lang_dir)) {
if ($ini_lingua != "." and $ini_lingua != ".." and strlen($ini_lingua) < 4 and $ini_lingua != $dati_cat_pers['lang'] and @is_file("./includes/lang/$ini_lingua/l_n")) {
$n_cat_pers = esegui_query("select valpersonalizza from $tablepersonalizza where idpersonalizza = 'nomi_cat_pers_".aggslashdb($ini_lingua)."' and idutente = '$id_utente' ");
if (numlin_query($n_cat_pers)) {
$n_cat_pers = explode("<",risul_query($n_cat_pers,0,'valpersonalizza'));
for ($num1 = 0 ; $num1 < $dati_cat_pers['num'] ; $num1++) {
$n_cat_pers_v = explode(">",$n_cat_pers[$num1]);
$dati_cat_pers[$num1]['langs'][$ini_lingua]['n_s'] = $n_cat_pers_v[0];
$dati_cat_pers[$num1]['langs'][$ini_lingua]['n_p'] = $n_cat_pers_v[1];
} # fine for $num1
} # fine if (numlin_query($n_cat_pers))
} # fine if ($ini_lingua != "." and $ini_lingua != ".." and strlen($ini_lingua) < 4 and...
} # fine while ($file = readdir($lang_dig))
closedir($lang_dir);
} # fine if ($altre_lingue)

for ($num1 = 0 ; $num1 < $dati_cat_pers['num'] ; $num1++) {
$nomi_cat_pers[$num1] = explode(">",$nomi_cat_pers[$num1]);
$dati_cat_pers[$num1]['n_sing'] = $nomi_cat_pers[$num1][0];
$dati_cat_pers[$num1]['n_plur'] = $nomi_cat_pers[$num1][1];
if ($altre_lingue) {
$dati_cat_pers[$num1]['langs'][$dati_cat_pers['lang']]['n_s'] = $nomi_cat_pers[$num1][0];
$dati_cat_pers[$num1]['langs'][$dati_cat_pers['lang']]['n_p'] = $nomi_cat_pers[$num1][1];
} # fine if ($altre_lingue)
if ($dati_perc and $num1 > 0) {
$dati_cat_pers[$num1]['perc'] = substr($perc_cat_persone[($num1 - 1)],2);
if ($num1 == 1) {
$perc_corr = explode("r",$dati_cat_pers[$num1]['perc']);
$dati_cat_pers[$num1]['perc'] = $perc_corr[0];
$dati_cat_pers['arrotond'] = $perc_corr[1];
} # fine if ($num1 == 1)
$dati_cat_pers[$num1]['osp_princ'] = substr($perc_cat_persone[($num1 - 1)],0,1);
} # fine if ($dati_perc and $num1 > 0)
} # fine for $num1
} # fine if ($dati_cat_pers['num'] > 1)
else $dati_cat_pers['num'] = 0;
} # fine if ($priv_ins_num_persone != "n")
else $dati_cat_pers['num'] = 0;

return $dati_cat_pers;

} # fine function dati_cat_pers




function dati_cat_pers_p (&$query_prenota,$ord_prenota,$dati_cat_pers,$num_persone,$lingua_mex="",$dati_perc=1) {

$dati_cat_pers_p = array();
$dati_cat_pers_p['num'] = 0;
if ($ord_prenota == -1) $dati_cat_pers_p['int'] = $query_prenota;
else $dati_cat_pers_p['int'] = risul_query($query_prenota,$ord_prenota,'cat_persone');
if ($dati_cat_pers_p['int']) {
$cat_pers_vett = explode("<",$dati_cat_pers_p['int']);
$dati_cat_pers_p['num'] = (count($cat_pers_vett) - 1);
if ($dati_perc) $dati_cat_pers_p['arrotond'] = $cat_pers_vett[0];
else $dati_cat_pers_p['int'] = "";
for ($num1 = 0 ; $num1 < $dati_cat_pers_p['num'] ; $num1++) {
$cat_pers_corr = explode(">",$cat_pers_vett[($num1 + 1)]);
$cat_pers = $cat_pers_corr[0];
$dati_cat_pers_p['ord'][$num1] = $cat_pers;
$dati_cat_pers_p[$num1]['molt'] = $cat_pers_corr[1];
$dati_cat_pers_p[$num1]['lang'] = $cat_pers_corr[4];
$dati_cat_pers_p[$num1]['n_sing'] = $cat_pers_corr[5];
$dati_cat_pers_p[$num1]['n_plur'] = $cat_pers_corr[6];
$dati_cat_pers_p[$num1]['n_sing_orig'] = $cat_pers_corr[5];
$dati_cat_pers_p[$num1]['n_plur_orig'] = $cat_pers_corr[6];
if ($lingua_mex) {
if ($cat_pers_corr[1] > 1) $dati_cat_pers_p[$num1]['n_corr'] = $cat_pers_corr[6];
else $dati_cat_pers_p[$num1]['n_corr'] = $cat_pers_corr[5];
} # fine if ($lingua_mex)
if ($dati_cat_pers[$cat_pers]['langs'][$cat_pers_corr[4]]['n_p'] == $cat_pers_corr[6]) {
$esist = 1;
if ($lingua_mex and $lingua_mex != $cat_pers_corr[4]) {
if ($cat_pers_corr[1] > 1) $dati_cat_pers_p[$num1]['n_corr'] = $dati_cat_pers[$cat_pers]['langs'][$lingua_mex]['n_p'];
else $dati_cat_pers_p[$num1]['n_corr'] = $dati_cat_pers[$cat_pers]['langs'][$lingua_mex]['n_s'];
} # fine if ($lingua_mex and $lingua_mex != $cat_pers_corr[4])
} # fine if ($dati_cat_pers[$cat_pers]['langs'][$cat_pers_corr[4]]['n_p'] == $cat_pers_corr[6])
else $esist = 0;
if ($dati_perc) {
$dati_cat_pers_p[$num1]['osp_princ'] = $cat_pers_corr[2];
$dati_cat_pers_p[$num1]['perc'] = $cat_pers_corr[3];
if ($esist and strcmp($dati_cat_pers[$cat_pers]['perc'],"") and $dati_cat_pers[$cat_pers]['perc'] != $cat_pers_corr[3]) {
$esist = 0;
$dati_cat_pers_p[$num1]['n_sing'] .= " (".$cat_pers_corr[3]."%)";
$dati_cat_pers_p[$num1]['n_plur'] .= " (".$cat_pers_corr[3]."%)";
if ($lingua_mex) $dati_cat_pers_p[$num1]['n_corr'] .= " (".$cat_pers_corr[3]."%)";
} # fine if ($esist and strcmp($dati_cat_pers[$cat_pers]['perc'],"") and...
} # fine if ($dati_perc)
else $dati_cat_pers_p['int'] .= "<".$cat_pers.">".$cat_pers_corr[1].">".$cat_pers_corr[4].">".$cat_pers_corr[5].">".$cat_pers_corr[6];
if ($esist) {
$dati_cat_pers_p['esist'][$num1] = 1;
if (!$dati_cat_pers_p[$cat_pers]['esist']) {
$dati_cat_pers_p[$cat_pers]['esist'] = ($num1 + 1);
$dati_cat_pers_p[$cat_pers]['ncp'] = $num1;
} # fine if (!$dati_cat_pers_p[$cat_pers]['esist'])
} # fine if ($esist)
} # fine for $num1
} # fine if ($dati_cat_pers_p['int'])
else {
if ($dati_cat_pers['num'] and $num_persone) {
$dati_cat_pers_p['num'] = 1;
$dati_cat_pers_p['ord'][0] = 0;
$dati_cat_pers_p[0]['molt'] = $num_persone;
$dati_cat_pers_p[0]['lang'] = $dati_cat_pers['lang'];
$dati_cat_pers_p[0]['n_sing'] = $dati_cat_pers[0]['n_sing'];
$dati_cat_pers_p[0]['n_plur'] = $dati_cat_pers[0]['n_plur'];
$dati_cat_pers_p[0]['n_sing_orig'] = $dati_cat_pers[0]['n_sing'];
$dati_cat_pers_p[0]['n_plur_orig'] = $dati_cat_pers[0]['n_plur'];
if ($lingua_mex) {
if ($num_persone > 1) $dati_cat_pers_p[0]['n_corr'] = $dati_cat_pers[0]['n_plur'];
else $dati_cat_pers_p[0]['n_corr'] = $dati_cat_pers[0]['n_sing'];
} # fine if ($lingua_mex)
if (!$dati_perc) $dati_cat_pers_p['int'] = "<0>$num_persone>".$dati_cat_pers['lang'].">".$dati_cat_pers[0]['n_sing'].">".$dati_cat_pers[0]['n_plur'];
else {
$dati_cat_pers_p['int'] = $dati_cat_pers['arrotond']."<0>$num_persone>s>100>".$dati_cat_pers['lang'].">".$dati_cat_pers[0]['n_sing'].">".$dati_cat_pers[0]['n_plur'];
$dati_cat_pers_p['arrotond'] = $dati_cat_pers['arrotond'];
$dati_cat_pers_p[0]['osp_princ'] = "s";
$dati_cat_pers_p[0]['perc'] = 100;
} # fine else if (!$dati_perc)
$dati_cat_pers_p[0]['esist'] = 1;
$dati_cat_pers_p[0]['ncp'] = 0;
$dati_cat_pers_p['esist'][0] = 1;
} # fine elseif ($dati_cat_pers['num'] and $num_persone)
else $dati_cat_pers_p['arrotond'] = $dati_cat_pers['arrotond'];
} # fine if else ($dati_cat_pers_p['int'])

return $dati_cat_pers_p;

} # fine function dati_cat_pers_p




?>