<?php

/**
 * @package   AnsprechpartnerBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2020
 */

// Übersetzen und betexten von in der /Resources/contao/dca/tl_bemod_abteilungen.php erstellten DCA Feldern

// Wir Betexten in dieser Datei jetzt Bereichsüberschriften, Felderlabels und Felderunterschriften - wir brauchen dafür wie auch in der DCA immer eine bestimmte Tabelle dieses DCA Feldes, die in der /Resources/contao/dca/tl_bemod_abteilungen.php erstellt haben. Da für den entsprechenden Backend Bereich immer Modular die gleiche Tabelle verwendet werden sollte, die auch in der gleichnamigen DCA Datei (diese Datei hier sollte auch den selben Namen haben) vorkommen, ist es besser wenn wir diese Tabelle hier zentral benennen. Man wird sehen der $strName kommt in dieser Datei oft vor. Wenn sich der Tabellenname ändern soll müssen wir das hier dann nur einmal konfigurieren.
$strName = 'tl_bemod_ansprechpartner';

// Betextung der neues Element anlegen Operations
$GLOBALS['TL_LANG'][$strName]['new'] = ['Neuen Ansprechpartner erstellen', 'Hier können Sie einen neuen Ansprechpartner anlegen'];
// Betextung der neues Element anlegen Operations
$GLOBALS['TL_LANG'][$strName]['editAbteilungen'] = ['Abteilungen verwalten', 'Hier können Sie die Abteilungen für die Ansprechpartner verwalten (anlegen/löschen/umbenennen und übersetzen)'];

// Betextung der Element Bearbeiten Operation (gelber Stift)
$GLOBALS['TL_LANG'][$strName]['edit'] = ['Ansprechpartner bearbeiten', 'Diesen Ansprechpartner ID %s bearbeiten.'];
// Betextung der Element Header Bearbeiten Operation (Zange Schraubendreher)
// $GLOBALS['TL_LANG'][$strName]['editheader'] = ['Ansprechpartner bearbeiten', 'Ansprechpartner ID %s bearbeiten'];
// Betextung der Element Kopieren Operation (Grünes Plus)
$GLOBALS['TL_LANG'][$strName]['copy'] = ['Ansprechpartner kopieren', 'Ansprechpartner ID %s kopieren'];
// Blauer Pfeil / Verschieben
// $GLOBALS['TL_LANG'][$strName]['cut'] = ['Ansprechpartner verschieben', 'Ansprechpartner ID %s verschieben'];
// Betextung der Element Löschen Operation (Rotes Kreuz)
$GLOBALS['TL_LANG'][$strName]['delete'] = ['Ansprechpartner löschen', 'Ansprechpartner ID %s löschen'];
// Betextung der Element Anzeigen / Ausblenden Operation (Grünes Auge)
$GLOBALS['TL_LANG'][$strName]['toggle'] = ['Ansprechpartner ein-/ausblenden', 'Ansprechpartner ID %s ein-/ausblenden'];
// Betextung der Element Informationen anzeigen Operation (Informationssymol)
$GLOBALS['TL_LANG'][$strName]['show'] = ['Informationen zum Ansprechpartner anzeigen', 'Informationen zum Ansprechpartner ID %s anzeigen'];

// Eigene Legenden / Bereichsüberschriften (Palettes in DCA.php) benennen / betexten
$GLOBALS['TL_LANG'][$strName]['name_legend'] = 'Anrede und Name';
$GLOBALS['TL_LANG'][$strName]['meta_legend'] = 'Weitere Daten zur Person';
$GLOBALS['TL_LANG'][$strName]['sorting_legend'] = 'Sortierpriorität';
$GLOBALS['TL_LANG'][$strName]['published_legend'] = 'Ansprechpartner veröffentlichen';

// Eigene Felder (Fields in gleichnamiger DCA.php) benennen / betexten. Es gibt hier immer ein Array Format. An erster Stelle ist die Überschrift, an zweiter Stelle die 'Erklärung', die unter dem Feld steht.
$GLOBALS['TL_LANG'][$strName]['salutation'] = ['Anrede', 'Hier können Sie die gewünschte Anrede zu der Kontaktperson auswählen'];
$GLOBALS['TL_LANG'][$strName]['myselect']['options'] = ['Herr', 'Frau', 'Divers'];
$GLOBALS['TL_LANG'][$strName]['title'] = ['Titel', 'Hier können Sie falls gewünscht einen oder mehrere Titel der Kontaktperson eingeben (z.B. Dipl. Ing.)'];
$GLOBALS['TL_LANG'][$strName]['name'] = ['Name', 'Geben Sie hier den Familiennamen / Nachnamen des Ansprechpartners ein'];
$GLOBALS['TL_LANG'][$strName]['firstname'] = ['Vorname', 'Geben Sie hier den Vornamen des Ansprechpartners ein'];
$GLOBALS['TL_LANG'][$strName]['jobtitle'] = ['Berufsbezeichnung', 'Hier können Sie die Berufsbezeichnung / den Berufsnamen des Ansprechpartners eingeben'];
$GLOBALS['TL_LANG'][$strName]['email'] = ['E-Mail', 'Hier können Sie eine Mailadresse des Ansprechpartners hinterlegen'];
$GLOBALS['TL_LANG'][$strName]['emailLinktext'] = ['E-Mail Linktext', 'Hier können Sie optional einen darzustellenden Text für den Mailto Link eingeben z.B. per Mail kontaktieren'];
$GLOBALS['TL_LANG'][$strName]['phone'] = ['Telefon', 'Hier können Sie eine Telefonnummer des Ansprechpartners hinterlegen'];
$GLOBALS['TL_LANG'][$strName]['phoneLinktext'] = ['Telefon Linktext', 'Hier können Sie optional einen darzustellenden Text für den Tel Link eingeben z.B. Jetzt anrufen'];
$GLOBALS['TL_LANG'][$strName]['mobile'] = ['Mobilnummer', 'Hier können Sie eine Mobilnummer des Ansprechpartners hinterlegen'];
$GLOBALS['TL_LANG'][$strName]['mobileLinktext'] = ['Mobile Linktext', 'Hier können Sie optional einen darzustellenden Text für den Tel Link eingeben z.B. Jetzt anrufen'];
$GLOBALS['TL_LANG'][$strName]['fax'] = ['Faxnummer', 'Hier können Sie eine Faxnummer des Ansprechpartners hinterlegen'];
$GLOBALS['TL_LANG'][$strName]['departementCheckList'] = ['Abteilung', 'Hier müssen Sie mindestens eine Abteilung auswählen, zu der der Ansprechpartner hinzugehört. Abteilungen können separat unter Ansprechparnter -> Abteilungen verwalten im oberen Menü der Übersicht angelegt werden'];
$GLOBALS['TL_LANG'][$strName]['more'] = ['Sonstige Angaben', 'Hier können Sie weitere Angaben zur Person angeben'];
// $GLOBALS['TL_LANG'][$strName]['department_options'] = ['Leitung', 'Content', 'Grafik', 'Technik'];
$GLOBALS['TL_LANG'][$strName]['addImage'] = ['Bild hinzufügen', 'Hier können Sie auswählen, ob Sie ein Foto zur Kontaktperson hinzufügen wollen'];
$GLOBALS['TL_LANG'][$strName]['singleSRC'] = ['Bild auswählen', 'Hier können Sie ein Portrait Foto oder ähnliches zur Kontaktperson aus der Dateiverwaltung auswählen und hinzufügen'];
$GLOBALS['TL_LANG'][$strName]['alt'] = ['Alttext', 'Hier können Sie den Alttext des Bildes eingeben'];
$GLOBALS['TL_LANG'][$strName]['caption'] = ['Caption', 'Hier können Sie eine Caption für das Bild eingeben'];
$GLOBALS['TL_LANG'][$strName]['resize'] = ['Bildgröße', 'Hier können Sie zusätzliche Einstellungen zur Bildgröße / Format in natürlichen Zahlen eingeben'];
$GLOBALS['TL_LANG'][$strName]['margin'] = ['Margin', 'Hier können Sie eigene inline margins vergeben.'];
$GLOBALS['TL_LANG'][$strName]['sortingIndex'] = ['Sortierpriorität festlegen', 'Hier können Sie mit einer natürlichen Zahl die Sortierpriorität des Ansprechpartners angeben. Wenn in einem Modul mehrere Ansprechpartner eingebunden werden, werden Sie absteigend nach diesen Zahlen sortiert. Höhere Zahl = höhere Priorität. Bei Gleichstand wird der Nachname berücksichtigt'];
$GLOBALS['TL_LANG'][$strName]['published'] = ['Ansprechpartner veröffentlichen', 'Setzen Sie hier bitte einen Haken, um den Ansprechpartner zu veröffentlichen'];
