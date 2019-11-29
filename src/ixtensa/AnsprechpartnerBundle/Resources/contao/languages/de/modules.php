<?php

/**
 * @package   AnsprechpartnerBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2019
 */

// Übersetzen und betexten von in der /Resources/contao/config/config.php erstellten neuen Backend Modulen

// Wir Betexten in dieser Datei jetzt Bereichsüberschriften, Felderlabels und Felderunterschriften - wir brauchen dafür wie auch in der DCA immer eine bestimmte Tabelle dieses DCA Feldes, die in der /Resources/contao/dca/tl_bemod_abteilungen.php erstellt haben. Da für den entsprechenden Backend Bereich immer Modular die gleiche Tabelle verwendet werden sollte, die auch in der gleichnamigen DCA Datei (diese Datei hier sollte auch den selben Namen haben) vorkommen, ist es besser wenn wir diese Tabelle hier zentral benennen. Man wird sehen der $strName kommt in dieser Datei oft vor. Wenn sich der Tabellenname ändern soll müssen wir das hier dann nur einmal konfigurieren.
$GLOBALS['TL_LANG']['MOD']['zix_ansprechpartner'] = ['Ansprechpartner'];
$GLOBALS['TL_LANG']['MOD']['ansprechpartner'] = ['Ansprechpartner', 'Legen Sie hier zentrale Ansprechpartner und zugehörige Abteilungen an'];
// Wird zwar ausgeblendet, aber trotzdem noch übersetzt, falls es durch einen Fehler vielleicht doch angezeigt werden sollte.
$GLOBALS['TL_LANG']['MOD']['abteilungen'] = ['Abteilungen', 'Legen Sie hier zentrale Abteilungen für die Ansprechpartner an'];
