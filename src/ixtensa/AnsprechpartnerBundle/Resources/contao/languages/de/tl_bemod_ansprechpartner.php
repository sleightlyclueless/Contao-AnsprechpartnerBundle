<?php

/**
 * @package   AnsprechpartnerBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2019
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
$GLOBALS['TL_LANG'][$strName]['published_legend'] = 'Element veröffentlichen';


// Eigene Felder (Fields in gleichnamiger DCA.php) benennen / betexten. Es gibt hier immer ein Array Format. An erster Stelle ist die Überschrift, an zweiter Stelle die 'Erklärung', die unter dem Feld steht.
$GLOBALS['TL_LANG'][$strName]['salutation'] = ['Anrede', 'Hier können Sie die gewünschte Anrede auswählen'];
$GLOBALS['TL_LANG'][$strName]['myselect']['options'] = ['Herr', 'Frau', 'Divers'];
$GLOBALS['TL_LANG'][$strName]['title'] = ['Titel', 'Hier können Sie falls gewünscht einen oder mehrere Titel der Kontaktperson eingeben'];
$GLOBALS['TL_LANG'][$strName]['name'] = ['Name', 'Geben Sie hier den Familiennamen / Nachnamen des Ansprechpartners ein'];
$GLOBALS['TL_LANG'][$strName]['firstname'] = ['Vorname', 'Geben Sie hier den Vornamen des Ansprechpartners ein'];
$GLOBALS['TL_LANG'][$strName]['email'] = ['E-Mail', 'Hier können Sie eine Mailadresse des Ansprechpartners hinterlegen'];
$GLOBALS['TL_LANG'][$strName]['phone'] = ['Telefon', 'Hier können Sie eine Telefonnummer des Ansprechpartners hinterlegen'];
$GLOBALS['TL_LANG'][$strName]['more'] = ['Sonstige Angaben', 'Hier können Sie weitere Angaben zur Person machen'];
$GLOBALS['TL_LANG'][$strName]['jobtitle'] = ['Berufsbezeichnung', 'Hier können Sie die Berufsbezeichnung / den Berufsnamen des Ansprechpartners eingeben'];
$GLOBALS['TL_LANG'][$strName]['departementCheckList'] = ['Abteilung', 'Hier müssen Sie mindestens eine Abteilung auswählen, zu der der Ansprechpartner hinzugehört'];
// $GLOBALS['TL_LANG'][$strName]['department_options'] = ['Leitung', 'Content', 'Grafik', 'Technik'];
$GLOBALS['TL_LANG'][$strName]['addImage'] = ['Bild hinzufügen', 'Hier können Sie auswählen, ob Sie ein Foto zur Kontaktperson hinzufügen wollen'];
$GLOBALS['TL_LANG'][$strName]['image'] = ['Bild auswählen', 'Hier können Sie ein Portrait Foto oder ähnliches zur Kontaktperson aus der Dateiverwaltung auswählen und hinzufügen'];
// $GLOBALS['TL_LANG'][$strName]['imagesize'] = ['Bildgröße', 'Hier können Sie zusätzliche Einstellungen zur Bildgröße / Format machen'];
// $GLOBALS['TL_LANG'][$strName]['mytrbl'] = ['Eigene Verschiebung', 'Ja ich weiß nicht, hier würden wahrscheinlich eigene Verschiebung mit Pixel Angaben rein kommen.'];
// $GLOBALS['TL_LANG'][$strName]['myinputunit'] = ['Eigenes Eingabefeld', 'Das hier ist ein eigenes Eingabefeld mit Select-Liste hintendran'];
$GLOBALS['TL_LANG'][$strName]['published'] = ['Ansprechpartner veröffentlichen', 'Setzen Sie hier bitte einen Haken, um den Ansprechpartner zu veröffentlichen'];
