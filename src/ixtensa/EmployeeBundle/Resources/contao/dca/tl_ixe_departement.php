<?php

/**
 * @package   EmployeeBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2020
 */

// DCA Erweiterungen: Backend von Contao um Data Container Arrays Erweitern: Zusätzliche Eingabefeleder für verschiedenste Bereiche erstellen und konfigurieren. z.B. Für Backend Module

// Namespace: Der eindeutige Pfad, der auf diese entsprechende PHP Datei zeigt, damit sie von anderen Orten aus eindeutig aufgerufen und oder referenziert werden kann.
namespace ixtensa\EmployeeBundle\dca\tl_ixe_departement;

// Wir fügen Felder, Labels und so weiter für unsere Module jetzt zu Contao hinzu - und brauchen dafür immer eine bestimmte Tabelle, die in der /Resources/contao/config/config.php schon instanziert wurde. Da für den entsprechenden Backend Bereich immer Modular die gleiche Tabelle verwendet werden sollte ist es besser wenn wir diese hier zentral anlegen. Man wird sehen der $strName kommt in dieser Datei oft vor. Wenn sich der Tabellenname ändern soll müssen wir das hier dann nur einmal konfigurieren.
$strName = 'tl_ixe_departement';


// Die spezielle Konfiguration der TL_DCA für die Tabelle beginnt nun ab hier. DCA Felder für unsere Tabelle kommen hinzu.
$GLOBALS['TL_DCA'][$strName] = array
(
	// Config – Die Config erstellt noch keine Felder, gibt aber Meta Informationen über das Inhaltselement, hier sind alle möglichen Einstellungen
	'config' => array
	(
        // SONDERFALL Wir sind im Spracheinstellungs - Hauptmenü von den Abteilungen. Deswegen gibt es hier eine Child Table, die Übersetzungen der Abteilungen. Ein kreuzverweis zu unserer Tabelle als parent ist dort auch. Es kann mehrere Child Tables geben!
        // Weitere Felder hier nachlesen: https://docs.contao.org/books/api/dca/reference.html#table-configuration
        'ctable'                      => array('tl_ixe_departement_lang'),
        'dataContainer'               => 'Table',
		'switchToEdit'                => true,
		'enableVersioning'            => true,
        // Tabellen Grund Konfigurationen.
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)
	),

    // Listen – Anzeigekonfigurationen der Element Übersicht (also nicht die Bearbeitungs ansicht)
    // Weitere Felder für sorting, list, und die operations kannst du hier nachlesen:
    // https://docs.contao.org/dev/reference/dca/list/
	'list' => array
	(
        // Sortiermodus Konfigurationen der Elemente in der Übersicht
		'sorting' => array
		(
			'mode'                    => 2,
            'flag'                    => 1,
            'panelLayout'             => 'filter;departementname',
			'fields'                  => array('departementname')
		),

        // Elementanzeige im Backend. Zeige für das Element folgende DCA 'fields' in diesem 'format'
		'label' => array
		(
			'fields'                  => array('departementname'),
			'format'                  => '<b>%s</b>'
		),

        // Die Global Operations sind die Optionen für 'mehrere Bearbeiten' etc.
		'global_operations' => array
		(
            // Link direkt zurück zur Employee Verwaltungsoberfläche
            'backToEmployee' => array
			(
				'label'               => &$GLOBALS['TL_LANG'][$strName]['backToEmployee'],
                'href'                => 'do=employee',
				'class'               => 'header_back',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			),

            // Mehrere Bearbeiten etc. options
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			),
		),

        // Operations sind sachen wie Kopieren, Editieren, Löschen. Hier kann man die von Contao gegebenen Operationen für die Felder hinzufügen. Wenn man welche nicht dabei haben will darf man sie hier einfach nicht hinzuschreiben
		'operations' => array
		(
            // Bearbeiten des Inhalts des Elements (gelber Stift)
			'edit' => array
			(
                // SONDERFALL mit mehreren Tables - Link zum Bearbeiten auf child Table damit man quasi in die nächste DCA Ebene kommt um die child Elemente zu bearbeiten
				'label'               => &$GLOBALS['TL_LANG'][$strName]['edit'],
				'href'                => 'table=tl_ixe_departement_lang',
				'icon'                => 'edit.svg'
			),

            // Bearbeiten des Inhalts des Elements (gelber Stift)
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG'][$strName]['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.svg'
			),

            // Element löschen
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG'][$strName]['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
			),

            // Metainfos anzeigen
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG'][$strName]['show'],
				'href'                => 'act=show',
				'icon'                => 'show.svg'
			)
		)
	),

    // Palettes: Paletten in Contao sind die Layout Einstellungen, in denen die Felder dann im Backend für die Elemente generiert und angezeigt werden.
    // Das heißt wir sind jetzt nicht mehr in der Element Übersicht sondern in der eigentlichen Element Bearbeitungs- Erstellungsansicht. Die Paletten geben die Bereiche und die Reihenfolgen der Felder in den Bereichen vor. Die Felder kommen gleich im Menüpunkt 'fields'
    // https://docs.contao.org/dev/reference/dca/palettes/
	'palettes' => array
	(
		'default' => '{name_legend},departementname;'
	),


    // Fields: Jetzt definieren wir alle Felder die für das Element im DCA vorkommen müssen (Meta Fields) und sollen. Ich kann hier z.B. konfigurieren, dass ich ein Textfeld und ein Selectmenü, mit denen ich die Enträge in die Datenbank abspreichere, haben will, damit ich sie dann mit Frontend Templates / Modules ausgeben kann.
    // https://docs.contao.org/dev/reference/dca/fields/
    // https://easysolutionsit.de/artikel/vorlagen-f%C3%BCr-dca-felder.html
	'fields' => array
	(
        // Meta Fields - wir brauchen für eine vollständige Datenbank ohne Anomalien natürlich IDs und evtl ein Timestamp
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),

        // DCA Fields für das Element
        // Der Abteilungsname ist ein Textfeld für die Abteilung, die wir frei erstellen können. Danach werden die child Elemente im nächsten DCA Modul tl_ixe_departement_lang 'übersetzt'
        'departementname' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['departementname'],
        	'exclude'                 => true,
            'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
        	'inputType'               => 'text',
        	'eval'                    => array(
                                            'maxlength'=>255,
                                            'preserveTags'=>true,
                                            'doNotCopy'=>true,
                                            'mandatory'=>true
                                        ),
        	'sql'                     => "varchar(255) NOT NULL default ''"
        ),
	)
);
