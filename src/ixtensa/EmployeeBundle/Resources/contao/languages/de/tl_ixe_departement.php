<?php

/**
 * @package   EmployeeBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2020
 */

// Übersetzen und betexten von in der /Resources/contao/dca/tl_ixe_departement.php erstellten DCA Feldern

// Wir Betexten in dieser Datei jetzt Bereichsüberschriften, Felderlabels und Felderunterschriften - wir brauchen dafür wie auch in der DCA immer eine bestimmte Tabelle dieses DCA Feldes, die in der /Resources/contao/dca/tl_ixe_departement.php erstellt haben. Da für den entsprechenden Backend Bereich immer Modular die gleiche Tabelle verwendet werden sollte, die auch in der gleichnamigen DCA Datei (diese Datei hier sollte auch den selben Namen haben) vorkommen, ist es besser wenn wir diese Tabelle hier zentral benennen. Man wird sehen der $strName kommt in dieser Datei oft vor. Wenn sich der Tabellenname ändern soll müssen wir das hier dann nur einmal konfigurieren.
$strName = 'tl_ixe_departement';

// Betextung der neues Element anlegen Operations
$GLOBALS['TL_LANG'][$strName]['new'] = ['Neue Abteilung erstellen', 'Hier können Sie eine neue Abteilung erstellen'];
// Betextung der Globalen Operation zurück zu den Employeen
$GLOBALS['TL_LANG'][$strName]['backToEmployee'] = ['Zurück', 'Hier können Sie zurück zur Mitarbeiter Verwaltung gelangen'];

// Betextung der Element Bearbeiten Operation (gelber Stift)
$GLOBALS['TL_LANG'][$strName]['edit'] = ['Abteilungsübersetzungen bearbeiten', 'Die Übersetzungen der Abteilung ID %s bearbeiten.'];
// Betextung der Element Header Bearbeiten Operation (Zange Schraubendreher)
$GLOBALS['TL_LANG'][$strName]['editheader'] = ['Abteilung bearbeiten', 'Diese Abteilung ID %s bearbeiten.'];
// Betextung der Element Löschen Operation (Rotes Kreuz)
$GLOBALS['TL_LANG'][$strName]['delete'] = ['Abteilung löschen', 'Diese Abteilung ID %s löschen'];
// Betextung der Element Informationen anzeigen Operation (Informationssymol)
$GLOBALS['TL_LANG'][$strName]['show'] = ['Informationen zur Abteilung anzeigen', 'Informationen zur Abteilung ID %s anzeigen'];

// Eigene Legenden / Bereichsüberschriften (Palettes in DCA.php) benennen / betexten
$GLOBALS['TL_LANG'][$strName]['name_legend'] = 'Abteilung';

// Eigene Felder (Fields in gleichnamiger DCA.php) benennen / betexten. Es gibt hier immer ein Array Format. An erster Stelle ist die Überschrift, an zweiter Stelle die 'Erklärung', die unter dem Feld steht.
$GLOBALS['TL_LANG'][$strName]['departementname'] = ['Abteilungsname', 'Hier können Sie den gewünschten übergeordneten Abteilungsnamen eingeben'];
