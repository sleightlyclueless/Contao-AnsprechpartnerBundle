<?php

/**
 * @package   AnsprechpartnerBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2019
 */

// Wir Betexten in dieser Datei jetzt INhaltselente und Ihre Felderlabels wir brauchen auch hier für die DCA oft eine bestimmte Tabelle dieses Inhaltselementes - nämlich die tl_content in der die Inhaltselemente und Widgets abgespeichert sind, auch die eigenen. Da für den entsprechenden Backend Bereich immer Modular die gleiche Tabelle verwendet werden sollte, die auch in der gleichnamigen DCA Datei (diese Datei hier sollte auch den selben Namen haben) vorkommen, ist es besser wenn wir diese Tabelle hier zentral benennen. Man wird sehen der $strName kommt in dieser Datei oft vor. Wenn sich der Tabellenname ändern soll müssen wir das hier dann nur einmal konfigurieren.
$strName = 'tl_content';

// Zentrale Elemente die in Contao bereits existiert haben und nur leicht erweitert wurden werden in der default übersetzt (In diesem Fall eine neue Auswahl von Inhaltselementen unter Artikel - Elementtyp (erstellt in der Config unter TL_CTE))
$GLOBALS['TL_LANG']['CTE']['ansprechpartner_einzeln'] = ['Ansprechpartner'];

// Unser eigenes Widget für das Inhaltselement - der AnsprechpartnerPicker muss hier noch eine betextung bekommen.
$GLOBALS['TL_LANG'][$strName]['ansprechpartnerpicker'] = ['Ansprechpartner Select Menü', 'Wählen Sie aus der gegebenen Liste den Ansprechpartner, den Sie in das Frontend übernehmen wollen. Die Sortierung erfolgt nach dem vergebenen Sortierindex und sekundär alphabetisch nach dem Nachnamen. Beachten Sie bitte auch, dass ein Ansprechpartner, der in der zentralen Verwaltung ausgeblendet ist, nicht im Frontend angezeigt wird!'];

// Eigene Legenden / Bereichsüberschriften (Palettes in DCA.php) benennen / betexten
$GLOBALS['TL_LANG'][$strName]['headline_legend'] = 'Überschrift';
$GLOBALS['TL_LANG'][$strName]['contactPerson_legend'] = 'Ansprechpartner Auswahl';
