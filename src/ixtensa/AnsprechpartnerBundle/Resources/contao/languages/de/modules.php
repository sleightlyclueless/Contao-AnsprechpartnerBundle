<?php

/**
 * @package   AnsprechpartnerBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2020
 */

// Übersetzen und betexten von in der /Resources/contao/config/config.php erstellten neuen Backend Modulen

// Wir Betexten in dieser Datei jetzt Bereichsüberschriften, Felderlabels und Felderunterschriften eines Backend Moduls – dafür wurde keine spezielle Tabelle angelegt, weswegen wir hier keine zentrale $strName verwenden müssen
$GLOBALS['TL_LANG']['MOD']['zix_ansprechpartner'] = ['Ansprechpartner'];
$GLOBALS['TL_LANG']['MOD']['ansprechpartner'] = ['Ansprechpartner', 'Legen Sie hier zentrale Ansprechpartner und zugehörige Abteilungen an'];
// Wird zwar ausgeblendet, aber trotzdem noch übersetzt, falls es durch einen Fehler vielleicht doch angezeigt werden sollte.
$GLOBALS['TL_LANG']['MOD']['abteilungen'] = ['Abteilungen', 'Legen Sie hier zentrale Abteilungen für die Ansprechpartner an'];
