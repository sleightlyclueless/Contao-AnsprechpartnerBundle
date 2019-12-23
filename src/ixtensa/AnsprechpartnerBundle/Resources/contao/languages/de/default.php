<?php

/**
 * @package   AnsprechpartnerBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2020
 */

// Wir Betexten in dieser Datei jetzt INhaltselente und Ihre Felderlabels wir brauchen auch hier für die DCA oft eine bestimmte Tabelle dieses Inhaltselementes - nämlich die tl_content in der die Inhaltselemente und Widgets abgespeichert sind, auch die eigenen. Da für den entsprechenden Backend Bereich immer Modular die gleiche Tabelle verwendet werden sollte, die auch in der gleichnamigen DCA Datei (diese Datei hier sollte auch den selben Namen haben) vorkommen, ist es besser wenn wir diese Tabelle hier zentral benennen. Man wird sehen der $strName kommt in dieser Datei oft vor. Wenn sich der Tabellenname ändern soll müssen wir das hier dann nur einmal konfigurieren.
$strName = 'tl_content';

// Zentrale Elemente die in Contao bereits existiert haben und nur leicht erweitert wurden werden in der default übersetzt (In diesem Fall eine neue Auswahl von Inhaltselementen unter Artikel - Elementtyp (erstellt in der Config unter TL_CTE))
// Die hinzugefügenen Options hier werden in der DCA abgefragt und unterschiedliche Paletten anhand der verschiedenen Options gewählt
$GLOBALS['TL_LANG']['CTE']['ansprechpartner'] = ['Ansprechpartner'];
$GLOBALS['TL_LANG']['CTE']['Einzeln'] = ['Einzeln', 'Fügen Sie einen einzigen Ansprechpartner per Selectfeld hinzu'];
$GLOBALS['TL_LANG']['CTE']['Individuell'] = ['Individuell', 'Wählen Sie individuelle Ansprechpartner für das Frontend in einer Checkbox aus'];
$GLOBALS['TL_LANG']['CTE']['Abteilungen'] = ['Abteilungen', 'Wählen Sie eine Abteilung aus um all Ihre Ansprechpartner für das Frontend auszugeben'];
$GLOBALS['TL_LANG']['CTE']['Alle'] = ['Alle', 'Geben Sie alle Ansprechpartner aus'];

// Übergeordnetes Select Field für die Palettes betexten
$GLOBALS['TL_LANG'][$strName]['ansprechpartnerType'] = ['Ansprechpartner Einfügemodus', 'Wählen Sie aus der gegebenen Liste den Einfügemodus für die Ansprechpartner. Einzeln: Einen einzigen Ansprechpartner zum einfügen auswählen. Individuell: Wählen Sie per Checkboxes die Ansprechpartner, die eingefügt werden sollen aus. Abteilungen: Wählen Sie die Abteilungen aus, zu denen die Ansprechpartner eingefügt werden sollen. Alle: Alle Ansprechpartner ausgeben.'];
// Die CTE Options müssen zur Abfrage für die Paletten im String gleich mit den vergebenen Options sein
$GLOBALS['TL_LANG'][$strName]['ansprechpartnerType']['options'] = ['Einzeln', 'Individuell', 'Abteilungen', 'Alle'];
// Unser eigenes Widget für das Inhaltselement - der AnsprechpartnerPicker muss hier noch eine betextung bekommen.
$GLOBALS['TL_LANG'][$strName]['ansprechpartnerpicker'] = ['Einzelnen Ansprechpartner', 'Wählen Sie aus dem gegebenen Select - Menü den Ansprechpartner, den Sie in das Frontend übernehmen wollen.'];
$GLOBALS['TL_LANG'][$strName]['ansprechpartnercheckboxes'] = ['Individuelle Ansprechpartner', 'Wählen Sie aus der gegebenen Checkliste die Ansprechpartner, die Sie in das Frontend übernehmen wollen. Die Sortierung erfolgt nach dem vergebenen Sortierindex und sekundär alphabetisch nach dem Nachnamen. Beachten Sie bitte auch, dass ein Ansprechpartner, der in der zentralen Verwaltung ausgeblendet ist oder später ausgeblendet wird, nicht im Frontend angezeigt wird!'];
$GLOBALS['TL_LANG'][$strName]['departementcheckboxes'] = ['Nach Abteilungen', 'Wählen Sie aus der gegebenen Liste die Abteilung, dessen Ansprechpartner Sie in das Frontend übernehmen wollen. Die Sortierung erfolgt nach dem vergebenen Sortierindex und sekundär alphabetisch nach dem Nachnamen. Beachten Sie bitte auch, dass ein Ansprechpartner, der in der zentralen Verwaltung ausgeblendet ist oder später ausgeblendet wird, nicht im Frontend angezeigt wird!'];

// Eigene Legenden / Bereichsüberschriften (Palettes in DCA.php) benennen / betexten
$GLOBALS['TL_LANG'][$strName]['headline_legend'] = 'Überschrift';
$GLOBALS['TL_LANG'][$strName]['contactPerson_legend'] = 'Ansprechpartner Auswahl';
