<?php

/**
 * @package   EmployeeBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2020
 */

// DCA Erweiterungen: Backend von Contao um Data Container Arrays Erweitern: Zusätzliche Eingabefeleder für verschiedenste Bereiche erstellen und konfigurieren. z.B. Für Backend Module hier

// Namespace: Der eindeutige Pfad, der auf diese entsprechende PHP Datei zeigt, damit sie von anderen Orten aus eindeutig aufgerufen und oder referenziert werden kann.
namespace ixtensa\EmployeeBundle\dca\tl_ixe_departement_lang;

// Wir fügen Felder, Labels und so weiter für unsere Module jetzt zu Contao hinzu - und brauchen dafür immer eine bestimmte Tabelle, die in der /Resources/contao/config/config.php schon instanziert wurde. Da für den entsprechenden Backend Bereich immer Modular die gleiche Tabelle verwendet werden sollte ist es besser wenn wir diese hier zentral anlegen. Man wird sehen der $strName kommt in dieser Datei oft vor. Wenn sich der Tabellenname ändern soll müssen wir das hier dann nur einmal konfigurieren.
$strName = 'tl_ixe_departement_lang';

// Die spezielle Konfiguration der TL_DCA für die Tabelle beginnt nun ab hier. DCA Felder für unsere Tabelle kommen hinzu.
$GLOBALS['TL_DCA'][$strName] = array
(
	// Config – Die Config erstellt noch keine Felder, gibt aber Meta Informationen über das Inhaltselement, hier sind viele Einstellungen möglich
	'config' => array
	(
        // Wir sind im Spracheinstellungs - Untermenü von den Abteilungen. Deswegen gibt es hier eine Parent Table, die Abteilungen ansich. Ein kreuzverweis zu unserer Tabelle als child ist dort auch. Es kann NUR eine Parent Table geben!
        // Weitere Felder hier nachlesen: https://docs.contao.org/books/api/dca/reference.html#table-configuration
        'ptable'                      => 'tl_ixe_departement',
        'dataContainer'               => 'Table',
		'switchToEdit'                => true,
		'enableVersioning'            => true,
        // Tabellen Grund Konfigurationen. Wir wollen auch die pid der ptable wegen dem Kreuzverweis
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
                'pid' => 'index'
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
            'panelLayout'             => 'filter;departementname_lang, departementname_title',
			'fields'                  => array('departementname_lang')
		),

        // Elementanzeige im Backend. Zeige für das Element folgende DCA 'fields' in diesem 'format'
		'label' => array
		(
			'fields'                  => array('departementname_lang', 'departementname_title'),
			'format'                  => '<b>%s</b> - [%s]'
		),

        // Die Global Operations sind die Optionen für 'mehrere Bearbeiten' etc.
		'global_operations' => array
		(
            // Mehrere Bearbeiten etc. options
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),

        // Operations sind sachen wie Kopieren, Editieren, Löschen. Hier kann man die von Contao gegebenen Operationen für die Felder hinzufügen. Wenn man welche nicht dabei haben will darf man sie hier einfach nicht hinzuschreiben
		'operations' => array
		(
            // Bearbeiten des Inhalts des Elements (gelber Stift)
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG'][$strName]['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.svg'
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
		'default' => '{name_legend},departementname_lang,departementname_title;'
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

        // SONDERFALL: Wir haben einen Fremdschlüssel 'foreignKey' von einer parent Table, die eine Ebene höher in der /Resources/contao/dca/tl_ixe_departement.php konfiguriert und verknüpft wurde. Den Eintrag pid von dort zu dieser child table wollen wir hier dann für die Joins wieder entsprechend zurück auch haben.
        'pid' => array
        (
            'foreignKey'              => 'tl_ixe_departement.departementname',
            'relation'                => array('type'=>'belongsTo', 'load'=>'eager'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),

		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),


        // DCA Fields für das Element
        // Übersetzungs Sprache – Sprachkürzel nach ISO Norm Eingabefeld
        'departementname_lang' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['departementname_lang'],
        	'exclude'                 => true,
            'search'                  => true,
        	'sorting'                 => true,
			'filter'                  => true,
        	'inputType'               => 'text',
        	'eval'                    => array(
                                            'mandatory'=>true,
                                            'rgxp'=>'language',
                                            'maxlength'=>5,
                                            'nospace'=>true,
                                            'doNotCopy'=>true,
                                            'tl_class'=>'w50'
                                        ),
			'sql'                     => "varchar(5) NOT NULL default ''"
        ),

        // Textfeld mit Eingabemöglich für die Übersetzung zum Sprachkürzel
        'departementname_title' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['departementname_title'],
        	'exclude'                 => true,
            'search'                  => true,
        	'sorting'                 => false,
			'filter'                  => false,
        	'inputType'               => 'text',
        	'eval'                    => array(
                                            'mandatory'=>true,
                                            'maxlength'=>255,
                                            'preserveTags'=>true,
                                            'doNotCopy'=>true,
                                            'tl_class'=>'w50'
                                        ),
        	'sql'                     => "varchar(255) NOT NULL default ''"
        ),
	)
);
