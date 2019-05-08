<?php

##################################################################################
#    HOTELDRUID
#    Copyright (C) 2001-2018 by Marco Maria Francesco De Santis (marco@digitaldruid.net)
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

$mess_dati_form = "";
if ($manda_cognome != "NO") {
$mess_dati_form .= "<input type=\"hidden\" name=\"cognome\" value=\"".htmlspecialchars($cognome)."\">
<input type=\"hidden\" name=\"nome\" value=\"".htmlspecialchars($nome)."\">";
} # fine if ($manda_cognome != "NO")
for ($n_t = 1 ; $n_t <= $num_tipologie ; $n_t++) {
if ($manda_dati_assegnazione != "NO") {
$mess_dati_form .= "<input type=\"hidden\" name=\"inizioperiodo$n_t\" value=\"".htmlspecialchars(${"inizioperiodo".$n_t})."\">
<input type=\"hidden\" name=\"fineperiodo$n_t\" value=\"".htmlspecialchars(${"fineperiodo".$n_t})."\">
<input type=\"hidden\" name=\"appartamento$n_t\" value=\"".htmlspecialchars(${"appartamento".$n_t})."\">
<input type=\"hidden\" name=\"nometipotariffa$n_t\" value=\"".htmlspecialchars(${"nometipotariffa".$n_t})."\">
<input type=\"hidden\" name=\"numpersone$n_t\" value=\"".htmlspecialchars(${"numpersone".$n_t})."\">
<input type=\"hidden\" name=\"assegnazioneapp$n_t\" value=\"".htmlspecialchars(${"assegnazioneapp".$n_t})."\">
<input type=\"hidden\" name=\"num_app_richiesti$n_t\" value=\"".htmlspecialchars(${"num_app_richiesti".$n_t})."\">
<input type=\"hidden\" name=\"lista_app$n_t\" value=\"".htmlspecialchars(${"lista_app".$n_t})."\">
<input type=\"hidden\" name=\"prenota_vicine$n_t\" value=\"".htmlspecialchars(${"prenota_vicine".$n_t})."\">
<input type=\"hidden\" name=\"spezzetta$n_t\" value=\"".htmlspecialchars(${"spezzetta".$n_t})."\">";
} # fine if ($manda_dati_assegnazione != "NO")
for ($num_catp = 0 ; $num_catp < $num_categorie_persone ; $num_catp++) {
$mess_dati_form .= "<input type=\"hidden\" name=\"cat$num_catp"."_numpers$n_t\" value=\"".htmlspecialchars(${"cat$num_catp"."_numpers".$n_t})."\">";
} # fine $num_catp
$mess_dati_form .= "<input type=\"hidden\" name=\"tipo_sconto$n_t\" value=\"".htmlspecialchars(${"tipo_sconto".$n_t})."\">
<input type=\"hidden\" name=\"sconto$n_t\" value=\"".htmlspecialchars(${"sconto".$n_t})."\">
<input type=\"hidden\" name=\"tipo_val_sconto$n_t\" value=\"".htmlspecialchars(${"tipo_val_sconto".$n_t})."\">
<input type=\"hidden\" name=\"conferma_prenota$n_t\" value=\"".htmlspecialchars(${"conferma_prenota".$n_t})."\">
<input type=\"hidden\" name=\"caparra$n_t\" value=\"".htmlspecialchars(${"caparra".$n_t})."\">
<input type=\"hidden\" name=\"tipo_val_caparra$n_t\" value=\"".htmlspecialchars(${"tipo_val_caparra".$n_t})."\">
<input type=\"hidden\" name=\"commissioni$n_t\" value=\"".htmlspecialchars(${"commissioni".$n_t})."\">
<input type=\"hidden\" name=\"tipo_val_commissioni$n_t\" value=\"".htmlspecialchars(${"tipo_val_commissioni".$n_t})."\">
<input type=\"hidden\" name=\"giorno_stima_checkin$n_t\" value=\"".htmlspecialchars(${"giorno_stima_checkin".$n_t})."\">
<input type=\"hidden\" name=\"ora_stima_checkin$n_t\" value=\"".htmlspecialchars(${"ora_stima_checkin".$n_t})."\">
<input type=\"hidden\" name=\"min_stima_checkin$n_t\" value=\"".htmlspecialchars(${"min_stima_checkin".$n_t})."\">
<input type=\"hidden\" name=\"num_commenti$n_t\" value=\"".htmlspecialchars(${"num_commenti".$n_t})."\">";
for ($num_comm = 1 ; $num_comm <= ${"num_commenti".$n_t} ; $num_comm++) {
$mess_dati_form .= "<input type=\"hidden\" name=\"tipo_commento$num_comm"."_$n_t\" value=\"".htmlspecialchars(${"tipo_commento".$num_comm."_".$n_t})."\">";
if (!@get_magic_quotes_gpc()) $mess_dati_form .= "<input type=\"hidden\" name=\"commento$num_comm"."_$n_t\" value=\"".htmlspecialchars(${"commento".$num_comm."_".$n_t})."\">";
else $mess_dati_form .= "<input type=\"hidden\" name=\"commento$num_comm"."_$n_t\" value=\"".htmlspecialchars(stripslashes(${"commento".$num_comm."_".$n_t}))."\">";
} # fine for $num_comm
if (!@get_magic_quotes_gpc()) $mess_dati_form .= "<input type=\"hidden\" name=\"met_paga_caparra$n_t\" value=\"".htmlspecialchars(${"met_paga_caparra".$n_t})."\">";
else $mess_dati_form .= "<input type=\"hidden\" name=\"met_paga_caparra$n_t\" value=\"".htmlspecialchars(stripslashes(${"met_paga_caparra".$n_t}))."\">";
if (!@get_magic_quotes_gpc()) $mess_dati_form .= "<input type=\"hidden\" name=\"origine_prenota$n_t\" value=\"".htmlspecialchars(${"origine_prenota".$n_t})."\">";
else $mess_dati_form .= "<input type=\"hidden\" name=\"origine_prenota$n_t\" value=\"".htmlspecialchars(stripslashes(${"origine_prenota".$n_t}))."\">";
for ($numca = 1 ; $numca <= $numcostiagg ; $numca++) {
$costoagg = "costoagg".$numca."_".$n_t;
$idcostoagg = "idcostoagg".$numca."_".$n_t;
$numsettimane = "numsettimane".$numca."_".$n_t;
$nummoltiplica_ca = "nummoltiplica_ca".$numca."_".$n_t;
$id_periodi_costo = "id_periodi_costo".$numca."_".$n_t;
$catpers_ca = "catpers_ca".$numca."_".$n_t;
$mess_dati_form .= "<input type=\"hidden\" name=\"$idcostoagg\" value=\"".htmlspecialchars($$idcostoagg)."\">
<input type=\"hidden\" name=\"$costoagg\" value=\"".htmlspecialchars($$costoagg)."\">
<input type=\"hidden\" name=\"$numsettimane\" value=\"".htmlspecialchars($$numsettimane)."\">
<input type=\"hidden\" name=\"$nummoltiplica_ca\" value=\"".htmlspecialchars($$nummoltiplica_ca)."\">";
if ($$id_periodi_costo) $mess_dati_form .= "<input type=\"hidden\" name=\"$id_periodi_costo\" value=\"".htmlspecialchars($$id_periodi_costo)."\">";
if (strcmp($$catpers_ca,"")) $mess_dati_form .= "<input type=\"hidden\" name=\"$catpers_ca\" value=\"".htmlspecialchars($$catpers_ca)."\">";
} # fine for $numca
} # fine for $n_t
$mess_dati_form .= "<input type=\"hidden\" name=\"numcostiagg\" value=\"".htmlspecialchars($numcostiagg)."\">
<input type=\"hidden\" name=\"id_utente_ins\" value=\"$id_utente_ins\">
<input type=\"hidden\" name=\"mos_tut_dat\" value=\"$mos_tut_dat\">
<input type=\"hidden\" name=\"num_tipologie\" value=\"$num_tipologie\">";
if ($manda_dati_assegnazione != "NO") $mess_dati_form .= "<input type=\"hidden\" name=\"prenota_vicine\" value=\"".htmlspecialchars($prenota_vicine)."\">";
else $mess_dati_form .= "<input type=\"hidden\" name=\"id_transazione\" value=\"$id_transazione\">";
if ($idmessaggi) $mess_dati_form .= "<input type=\"hidden\" name=\"idmessaggi\" value=\"".htmlspecialchars($idmessaggi)."\">";
if ($num_categorie_persone > 1) echo "<input type=\"hidden\" name=\"num_categorie_persone\" value=\"".htmlspecialchars($num_categorie_persone)."\">";

if ($echo_dati_form != "NO") echo $mess_dati_form;

?>