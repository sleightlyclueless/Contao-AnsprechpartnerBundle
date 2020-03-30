<?php

/**
 * @package   EmployeeBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2020
 */

// Wir Betexten in dieser Datei jetzt Inhaltselente und Ihre Felderlabels wir brauchen auch hier für die DCA oft eine bestimmte Tabelle dieses Inhaltselementes - nämlich die tl_content in der die Inhaltselemente und Widgets abgespeichert sind, auch die eigenen. Da für den entsprechenden Backend Bereich immer Modular die gleiche Tabelle verwendet werden sollte, die auch in der gleichnamigen DCA Datei (diese Datei hier sollte auch den selben Namen haben) vorkommen, ist es besser wenn wir diese Tabelle hier zentral benennen. Man wird sehen der 'tl_content' kommt in dieser Datei oft vor. Wenn sich der Tabellenname ändern soll müssen wir das hier dann nur einmal konfigurieren.

// Zentrale Elemente die in Contao bereits existiert haben und nur leicht erweitert wurden werden in der default übersetzt (In diesem Fall eine neue Auswahl von Inhaltselementen unter Artikel - Elementtyp (erstellt in der Config unter TL_CTE))
// Die hinzugefügenen Options hier werden in der DCA abgefragt und unterschiedliche Paletten anhand der verschiedenen Options gewählt
// /EmployeeBundle/Resources/contao/dca/tl_content.php
$GLOBALS['TL_LANG']['CTE']['employee'] = ['Mitarbeiter'];

$GLOBALS['TL_LANG']['tl_content']['contactPerson_legend'] = 'Mitarbeiter Auswahl';

// Übergeordnetes Select Field für die Palettes betexten
$GLOBALS['TL_LANG']['tl_content']['employeeType'] = ['Mitarbeiter Einfügemodus', 'Wählen Sie aus der gegebenen Liste den Einfügemodus für die Mitarbeiter. Einzeln: Einen einzigen Mitarbeiter zum einfügen auswählen. Individuell: Wählen Sie per Checkboxes die Mitarbeiter, die eingefügt werden sollen aus. Abteilungen: Wählen Sie die Abteilungen aus, zu denen die Mitarbeiter eingefügt werden sollen. Alle: Alle Mitarbeiter ausgeben'];
// Die CTE Options müssen zur Abfrage für die Paletten im String gleich mit den vergebenen Options sein
$GLOBALS['TL_LANG']['tl_content']['employeeType']['options'] = ['Einzeln', 'Individuell', 'Abteilungen', 'Alle'];
// Unser eigenes Widget für das Inhaltselement - der EmployeePicker muss hier noch eine Betextung bekommen.
$GLOBALS['TL_LANG']['tl_content']['employeepicker'] = ['Einzelnen Mitarbeiter', 'Wählen Sie aus dem gegebenen Select - Menü den Mitarbeiter, den Sie in das Frontend übernehmen wollen'];
$GLOBALS['TL_LANG']['tl_content']['employeecheckboxes'] = ['Individuelle Mitarbeiter', 'Wählen Sie aus der gegebenen Checkliste die Mitarbeiter, die Sie in das Frontend übernehmen wollen. Die Sortierung erfolgt nach dem vergebenen Sortierindex und sekundär alphabetisch nach dem Nachnamen. Beachten Sie bitte auch, dass ein Mitarbeiter, der in der zentralen Verwaltung ausgeblendet ist oder später ausgeblendet wird, nicht im Frontend angezeigt wird!'];
$GLOBALS['TL_LANG']['tl_content']['departementcheckboxes'] = ['Nach Abteilungen', 'Wählen Sie aus der gegebenen Liste die Abteilung, dessen Mitarbeiter Sie in das Frontend übernehmen wollen. Die Sortierung erfolgt nach dem vergebenen Sortierindex und sekundär alphabetisch nach dem Nachnamen. Beachten Sie bitte auch, dass ein Mitarbeiter, der in der zentralen Verwaltung ausgeblendet ist oder später ausgeblendet wird, nicht im Frontend angezeigt wird!'];


// Globals
// /EmployeeBundle/Classes/Employee.php
$GLOBALS['IX_EB']['MSC']['wildcard_header'] = '### Mitarbeiter ###';
$GLOBALS['IX_EB']['MSC']['disabled'] = ' (wurde ausgeblendet!)';
$GLOBALS['IX_EB']['MSC']['template_title_individual'] = 'Individuelle Mitarbeiter';
$GLOBALS['IX_EB']['MSC']['template_title_departements'] = 'Mitarbeiter aus den Abteilung(en): ';
$GLOBALS['IX_EB']['MSC']['template_title_all'] = 'Alle Mitarbeiter';
$GLOBALS['IX_EB']['MSC']['template_title_err'] = 'Es wurde kein Einfügemodus ausgewählt! Bitte versuchen Sie es erneut!';


// /EmployeeBundle/Helper/HelperClass.php
$GLOBALS['IX_EB']['MSC']['no_departement_defined'] = 'Es wurde keine Abteilung für diesen Mitarbeiter angegeben!';
$GLOBALS['IX_EB']['MSC']['no_departement_for_language'] = 'Es sind keine Übersetzungen für die verknüpften Abteilungen für die Sprache %s angegeben!';
$GLOBALS['IX_EB']['MSC']['no_language'] = '%s ist für die Sprache %s nicht angegeben!';
