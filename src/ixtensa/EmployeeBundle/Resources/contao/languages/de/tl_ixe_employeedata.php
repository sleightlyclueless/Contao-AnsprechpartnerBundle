<?php

/**
 * @package   EmployeeBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2020
 */

// Übersetzen und betexten von in der /Resources/contao/dca/tl_ixe_departement.php erstellten DCA Feldern

// Wir Betexten in dieser Datei jetzt Bereichsüberschriften, Felderlabels und Felderunterschriften - wir brauchen dafür wie auch in der DCA immer eine bestimmte Tabelle dieses DCA Feldes, die in der /Resources/contao/dca/tl_ixe_departement.php erstellt haben. Da für den entsprechenden Backend Bereich immer Modular die gleiche Tabelle verwendet werden sollte, die auch in der gleichnamigen DCA Datei (diese Datei hier sollte auch den selben Namen haben) vorkommen, ist es besser wenn wir diese Tabelle hier zentral benennen. Man wird sehen der $strName kommt in dieser Datei oft vor. Wenn sich der Tabellenname ändern soll müssen wir das hier dann nur einmal konfigurieren.
$strName = 'tl_ixe_employeedata';

// Betextung der neues Element anlegen Operations
$GLOBALS['TL_LANG'][$strName]['new'] = ['Neue Mitarbeiter anlegen', 'Hier können Sie einen neuen Mitarbeiter anlegen'];
// Betextung der neues Element anlegen Operations
$GLOBALS['TL_LANG'][$strName]['editAbteilungen'] = ['Abteilungen verwalten', 'Hier können Sie die Abteilungen für die Mitarbeiter verwalten'];

// Betextung der Element Bearbeiten Operation (gelber Stift)
$GLOBALS['TL_LANG'][$strName]['edit'] = ['Mitarbeiter bearbeiten', 'Diesen Mitarbeiter (ID %s) bearbeiten'];
// Betextung der Element Header Bearbeiten Operation (Zange Schraubendreher)
// $GLOBALS['TL_LANG'][$strName]['editheader'] = ['Mitarbeiter bearbeiten', 'Mitarbeiter ID %s bearbeiten'];
// Betextung der Element Kopieren Operation (Grünes Plus)
$GLOBALS['TL_LANG'][$strName]['copy'] = ['Mitarbeiter kopieren', 'Diesen Mitarbeiter (ID %s) kopieren'];
// Blauer Pfeil / Verschieben
// $GLOBALS['TL_LANG'][$strName]['cut'] = ['Mitarbeiter verschieben', 'Mitarbeiter ID %s verschieben'];
// Betextung der Element Löschen Operation (Rotes Kreuz)
$GLOBALS['TL_LANG'][$strName]['delete'] = ['Mitarbeiter löschen', 'Diesen Mitarbeiter (ID %s) löschen'];
// Betextung der Element Anzeigen / Ausblenden Operation (Grünes Auge)
$GLOBALS['TL_LANG'][$strName]['toggle'] = ['Mitarbeiter ein-/ausblenden', 'Diesen Mitarbeiter (ID %s) ein-/ausblenden'];
// Betextung der Element Informationen anzeigen Operation (Informationssymol)
$GLOBALS['TL_LANG'][$strName]['show'] = ['Informationen zum Mitarbeiter anzeigen', 'Informationen zum Mitarbeiter (ID %s) anzeigen'];

// Eigene Legenden / Bereichsüberschriften (Palettes in DCA.php) benennen / betexten
$GLOBALS['TL_LANG'][$strName]['salutation_legend'] = 'Anrede';
$GLOBALS['TL_LANG'][$strName]['name_legend'] = 'Name';
$GLOBALS['TL_LANG'][$strName]['meta_legend'] = 'Weitere Daten zur Arbeitsumgebung';
$GLOBALS['TL_LANG'][$strName]['contact_legend'] = 'Kontaktmöglichkeiten';
$GLOBALS['TL_LANG'][$strName]['more_legend'] = 'Weitere Angaben';
$GLOBALS['TL_LANG'][$strName]['image_legend'] = 'Bildeinstellungen';
$GLOBALS['TL_LANG'][$strName]['sorting_legend'] = 'Sortierpriorität';
$GLOBALS['TL_LANG'][$strName]['published_legend'] = 'Mitarbeiter veröffentlichen';

// Eigene Felder (Fields in gleichnamiger DCA.php) benennen / betexten. Es gibt hier immer ein Array Format. An erster Stelle ist die Überschrift, an zweiter Stelle die 'Erklärung', die unter dem Feld steht.
$GLOBALS['TL_LANG'][$strName]['translations_lang'] = ['Sprache', 'Hier können Sie die Sprache auswählen, zu der dieser Text ausgegeben werden soll'];

// Anrede
$GLOBALS['TL_LANG'][$strName]['salutation'] = ['Anrede Einstellungen', 'Hier können Sie die gewünschte Anrede zu der Kontaktperson eingeben (z.B. Herr, Frau, Divers auf deutsch). Wenn das Feld für eine gewisse Sprache leer sein soll fügen Sie die Sprache dennoch hinzu und lassen Sie das Feld leer oder befüllen es mit einem non break space ([nbsp])'];
$GLOBALS['TL_LANG'][$strName]['salutation_content'] = ['Anrede', 'Gebe Sie hier die gewünschte Anrede zu der Kontaktperson ein, (z.B. Herr, Frau, Divers auf deutsch)'];
$GLOBALS['TL_LANG'][$strName]['title'] = ['Titel Einstellungen', 'Hier können Sie einen oder mehrere Titel der Kontaktperson eingeben (z.B. Dipl. Ing.). Wenn das Feld für eine gewisse Sprache leer sein soll fügen Sie die Sprache dennoch hinzu und lassen Sie das Feld leer oder befüllen es mit einem non break space ([nbsp])'];
$GLOBALS['TL_LANG'][$strName]['title_content'] = ['Titel', 'Geben Sie hier einen oder mehrere Titel der Kontaktperson ein (z.B. Dipl. Ing.)'];

// Name
$GLOBALS['TL_LANG'][$strName]['firstname'] = ['Vorname', 'Geben Sie hier den Vornamen des Mitarbeiters ein'];
$GLOBALS['TL_LANG'][$strName]['name'] = ['Nachname', 'Geben Sie hier den Familiennamen, bzw. Nachnamen des Mitarbeiters ein'];

// Weitere Daten zur Arbeitsumgebung
$GLOBALS['TL_LANG'][$strName]['addDepartement'] = ['Abteilung hinzufügen', 'Hier können Sie einstellen, ob Abteilungen zu dem Mitarbeiter hinzugefügt werden soll'];
$GLOBALS['TL_LANG'][$strName]['departementCheckList'] = ['Abteilung', 'Hier können Sie Abteilungen auswählen, zu denen der Mitarbeiter hinzugehört. Abteilungen können separat unter Mitarbeiterverwaltung -> Abteilungen im oberen Menü der Übersicht angelegt und verwaltet werden'];
$GLOBALS['TL_LANG'][$strName]['jobtitle'] = ['Berufsbezeichnung Einstellungen', 'Hier können Sie die Berufsbezeichnung / den Berufsnamen des Mitarbeiters eingeben. Wenn das Feld für eine gewisse Sprache leer sein soll fügen Sie die Sprache dennoch hinzu und lassen Sie das Feld leer oder befüllen es mit einem non break space ([nbsp])'];
$GLOBALS['TL_LANG'][$strName]['jobtitle_content'] = ['Berufsbezeichnung', 'Geben Sie hier die Berufsbezeichnung / den Berufsnamen des Mitarbeiters eingeben'];

// Kontaktmöglichkeiten
$GLOBALS['TL_LANG'][$strName]['phone'] = ['Telefon Einstellungen', 'Hier können Sie eine Telefonnummer des Mitarbeiters hinterlegen. Wenn das Feld für eine gewisse Sprache leer sein soll fügen Sie die Sprache dennoch hinzu und lassen Sie das Feld leer oder befüllen es mit einem non break space ([nbsp])'];
$GLOBALS['TL_LANG'][$strName]['phone_content'] = ['Telefonnummer', 'Geben Sie hier eine Telefonnummer des Mitarbeiters ein'];
$GLOBALS['TL_LANG'][$strName]['phoneLinktext'] = ['Telefon Linktext', 'Hier können Sie optional einen darzustellenden Text für den Telefon - Link eingeben z.B. Jetzt anrufen'];
$GLOBALS['TL_LANG'][$strName]['mobile'] = ['Mobil Einstellungen', 'Hier können Sie eine Mobilnummer des Mitarbeiters hinterlegen. Wenn das Feld für eine gewisse Sprache leer sein soll fügen Sie die Sprache dennoch hinzu und lassen Sie das Feld leer oder befüllen es mit einem non break space ([nbsp])'];
$GLOBALS['TL_LANG'][$strName]['mobile_content'] = ['Mobilnummer', 'Geben Sie hier eine Mobilnummer des Mitarbeiters ein'];
$GLOBALS['TL_LANG'][$strName]['mobileLinktext'] = ['Mobile Linktext', 'Hier können Sie optional einen darzustellenden Text für den Mobile - Link eingeben z.B. Mobil kontaktieren'];
$GLOBALS['TL_LANG'][$strName]['fax'] = ['Fax Einstellungen', 'Hier können Sie eine Faxnummer des Mitarbeiters hinterlegen. Wenn das Feld für eine gewisse Sprache leer sein soll fügen Sie die Sprache dennoch hinzu und lassen Sie das Feld leer oder befüllen es mit einem non break space ([nbsp])'];
$GLOBALS['TL_LANG'][$strName]['fax_content'] = ['Faxnummer', 'Geben Sie hier eine Faxnummer des Mitarbeiters ein'];
$GLOBALS['TL_LANG'][$strName]['faxLinktext'] = ['Fax Linktext', 'Hier können Sie optional einen darzustellenden Text für den Fax - Link eingeben z.B. Fax schicken'];
$GLOBALS['TL_LANG'][$strName]['email'] = ['E-Mail Einstellungen', 'Hier können Sie eine Mailadresse des Mitarbeiters hinterlegen. Wenn das Feld für eine gewisse Sprache leer sein soll fügen Sie die Sprache dennoch hinzu und lassen Sie das Feld leer oder befüllen es mit einem non break space ([nbsp])'];
$GLOBALS['TL_LANG'][$strName]['email_content'] = ['E-Mail', 'Geben Sie hier eine Mailadresse des Mitarbeiters ein'];
$GLOBALS['TL_LANG'][$strName]['emailLinktext'] = ['E-Mail Linktext', 'Hier können Sie optional einen darzustellenden Text für den Mailto Link eingeben z.B. per Mail kontaktieren'];

// Weitere Angaben zur Person
$GLOBALS['TL_LANG'][$strName]['text'] = ['Sonstige Angaben', 'Hier können Sie weitere Angaben zur Person angeben (werden auf allen Sprachversionen angezeigt - iflng Inserttags für sprachenselektiven Inhalt verwenden)'];

// Bildeinstellungen
$GLOBALS['TL_LANG'][$strName]['addImage'] = ['Bild hinzufügen', 'Hier können Sie auswählen, ob Sie ein Bild zur Kontaktperson hinzufügen wollen'];
$GLOBALS['TL_LANG'][$strName]['singleSRC'] = ['Bild auswählen', 'Hier können Sie ein Portrait oder ähnliches zur Kontaktperson aus der Dateiverwaltung hinzufügen'];
$GLOBALS['TL_LANG'][$strName]['size'] = ['Bildgröße', 'Hier können Sie zusätzliche Einstellungen zur Bildgröße eingeben (manuelles Format in natürlichen Zahlen eingeben)'];
$GLOBALS['TL_LANG'][$strName]['imagemargin'] = ['Margin', 'Hier können Sie eigene inline margins vergeben'];
$GLOBALS['TL_LANG'][$strName]['overwriteMeta'] = ['Metadaten überschreiben', 'Hier können Sie auswählen, ob Sie die Metadaten des Bildes überschreiben wollen'];
$GLOBALS['TL_LANG'][$strName]['alt'] = ['Bild Alttext', 'Hier können Sie den Alttext des Bildes eingeben'];
$GLOBALS['TL_LANG'][$strName]['caption'] = ['Bild Caption', 'Hier können Sie eine angezeigte Caption für das Bild eingeben'];
$GLOBALS['TL_LANG'][$strName]['imageUrl'] = ['Link', 'Hier können Sie ein Verlinkung für das Bild wählen, bzw. eingeben'];
$GLOBALS['TL_LANG'][$strName]['imageTitle'] = ['Bild Titel', 'Hier können Sie einen Titel für das Bild eingeben'];

// Sortierpriorit
$GLOBALS['TL_LANG'][$strName]['sortingIndex'] = ['Sortierpriorität festlegen', 'Hier können Sie mit einer natürlichen Zahl die Sortierpriorität des Mitarbeiters angeben. Wenn in einem Modul mehrere Mitarbeiter gleichzeitig eingebunden werden (Einfügemodus Individuell, Abteilungen und Alle), werden Sie absteigend nach diesen Zahlen sortiert. Höhere Zahl = höhere Priorität. Bei Gleichstand wird der Nachname berücksichtigt'];

// Veröffentlichen
$GLOBALS['TL_LANG'][$strName]['published'] = ['Mitarbeiter veröffentlichen', 'Setzen Sie hier einen Haken, um den Mitarbeiter zu veröffentlichen'];
