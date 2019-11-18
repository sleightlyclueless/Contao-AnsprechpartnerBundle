<?php

// Übersetzen und betexten von in der /Resources/contao/dca/tl_bemod_abteilungen_lang.php erstellten DCA Feldern

// Wir Betexten in dieser Datei jetzt Bereichsüberschriften, Felderlabels und Felderunterschriften - wir brauchen dafür wie auch in der DCA immer eine bestimmte Tabelle dieses DCA Feldes, die in der /Resources/contao/dca/tl_bemod_abteilungen_lang.php erstellt haben. Da für den entsprechenden Backend Bereich immer Modular die gleiche Tabelle verwendet werden sollte, die auch in der gleichnamigen DCA Datei (diese Datei hier sollte auch den selben Namen haben) vorkommen, ist es besser wenn wir diese Tabelle hier zentral benennen. Man wird sehen der $strName kommt in dieser Datei oft vor. Wenn sich der Tabellenname ändern soll müssen wir das hier dann nur einmal konfigurieren.
$strName = 'tl_bemod_abteilungen_lang';

// Betextung der neues Element anlegen Operations
$GLOBALS['TL_LANG'][$strName]['new'] = ['Neue Übersetzung anlegen', 'Hier können Sie eine neue Übersetzung für die  gewählte Abteilung anlegen'];
// Betextung der Element Bearbeiten Operation (gelber Stift)
$GLOBALS['TL_LANG'][$strName]['edit'] = ['Übersetzung bearbeiten', 'Die Übersetzung ID %s bearbeiten.'];
// Betextung der Element Löschen Operation (Rotes Kreuz)
$GLOBALS['TL_LANG'][$strName]['delete'] = ['Übersetzung löschen', 'Übersetzung ID %s löschen'];
// Betextung der Element Informationen anzeigen Operation (Informationssymol)
$GLOBALS['TL_LANG'][$strName]['show'] = ['Informationen zur Übersetzung anzeigen', 'Informationen zur Übersetzung ID %s anzeigen'];


// Eigene Legenden / Bereichsüberschriften (Palettes in DCA.php) benennen / betexten
$GLOBALS['TL_LANG'][$strName]['name_legend'] = 'Übersetzungen';


// Eigene Felder (Fields in gleichnamiger DCA.php) benennen / betexten. Es gibt hier immer ein Array Format. An erster Stelle ist die Überschrift, an zweiter Stelle die 'Erklärung', die unter dem Feld steht.
$GLOBALS['TL_LANG'][$strName]['abtname_lang'] = ['Sprachkürzel', 'Bitte geben Sie die Sprache der Seite gemäß des ISO-639-1 Standards ein (z.B. "de" für Deutsch oder "de-CH" für Schweizerdeutsch, "en" für Englisch usw.)'];
$GLOBALS['TL_LANG'][$strName]['abtname_bez'] = ['Bezeichnung', 'Hier können Sie den gewünschten Abteilungsnamen in Ihrer Sprache eingeben, der später ausgegeben werden soll'];
