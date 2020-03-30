<?php

/**
 * @package   EmployeeBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2020
 */

// DCA Erweiterungen: Backend von Contao um Data Container Arrays Erweitern: Zusätzliche Eingabefeleder für verschiedenste Bereiche erstellen und konfigurieren. z.B. Für Backend Module
// Du bist hier jetzt im Haupt DCA Monster der Erweiterung angekommen. Der zentralen Verwaltung im Backend Modul der Mitarbeiter. Von hier aus gibt es auch einen Querverweis zu einem eigenen Widget AbtMenu und Querverweis zu dem in der Navigation verstecken Abteilungs Backend Modul

// Namespace: Der eindeutige Pfad, der auf diese entsprechende PHP Datei zeigt, damit sie von anderen Orten aus eindeutig aufgerufen und oder referenziert werden kann.
namespace ixtensa\EmployeeBundle\dca\tl_ixe_employeedata;

use Symfony\Component\Intl\Intl;
use MenAtWork\MultiColumnWizardBundle\Contao\Widgets\MultiColumnWizard;

// Wir fügen Felder, Labels und so weiter für unsere Module jetzt zu Contao hinzu - und brauchen dafür immer eine bestimmte Tabelle, die in der /Resources/contao/config/config.php schon instanziert wurde. Da für den entsprechenden Backend Bereich immer Modular die gleiche Tabelle verwendet werden sollte ist es besser wenn wir diese hier zentral anlegen. Man wird sehen der $strName kommt in dieser Datei oft vor. Wenn sich der Tabellenname ändern soll müssen wir das hier dann nur einmal konfigurieren.
$strName = 'tl_ixe_employeedata';

// Die spezielle Konfiguration der TL_DCA für die Tabelle beginnt nun ab hier. DCA Felder für unsere Tabelle kommen hinzu.
$GLOBALS['TL_DCA'][$strName] = array
(
	// Config – Die Config erstellt noch keine Felder, gibt aber Meta Informationen über das Inhaltselement, hier sind alle möglichen Einstellungen
    // Weitere Felder hier nachlesen: https://docs.contao.org/books/api/dca/reference.html#table-configuration
	'config' => array
	(
        'dataContainer'      => 'Table',
        'enableVersioning'   => true,
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
        // Sortiermodus Konfigurationen der Elemente in der Übersicht - die Überschriften
		'sorting' => array
		(
			'mode'                    => 2,
            'flag'                    => 1,
            'panelLayout'             => 'filter;name,firstname',
			'fields'                  => array('name')
		),

        // Elementanzeige im Backend. Zeige für das Element folgende DCA 'fields' in diesem 'format' als Elementübersicht (die kurzen Elementkarteikarten in der Backend Modul übersicht der angelegten Elemente)
		'label' => array
		(
            'fields'                  => array('name','firstname'),
			'format'                  => '<b>%s</b>, %s'
		),

        // Die Global Operations sind die Optionen für 'mehrere Bearbeiten' etc.
		'global_operations' => array
		(
            // Link zu Abteilungs Erweiterung – Abteilungen verwalten (wurde in /Resources/contao/config/config.php benannt)
            'editAbteilungen' => array
			(
				'label'               => &$GLOBALS['TL_LANG'][$strName]['editAbteilungen'],
                'href'                => 'do=departement',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			),

            // Mehrere Bearbeiten etc. options
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),

        // Operations sind Sachen wie Kopieren, Editieren, Löschen. Hier kann man die von Contao gegebenen Operationen für die Felder hinzufügen. Wenn man welche nicht dabei haben will darf man sie hier einfach nicht hinzuschreiben (oder auskommentieren)
		'operations' => array
		(
            // Bearbeiten des Inhalts des Elements (gelber Stift)
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG'][$strName]['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.svg',
			),

            // Element kopieren
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG'][$strName]['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.svg'
			),

            // Element löschen
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG'][$strName]['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),

            // Element verstecken/ anzeigen
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG'][$strName]['toggle'],
				'icon'                => 'visible.svg',
                'showInHeader'        => true,
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				// Muss als namespace verlinkt werden! Wir sind außerhalb von dem Scope der class Funktionen, auch wenn die Funktion in dieser Datei weiter unten kommt
                'button_callback'     => array('ixtensa\EmployeeBundle\dca\tl_ixe_employeedata\tl_ixe_employeedata', 'toggleIcon')
			),
            // Informationen zeigen
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
        '__selector__'                => array('addDepartement','addImage', 'overwriteMeta'),
		'default' => '{salutation_legend},salutation,title;{name_legend},firstname,name;{meta_legend},addDepartement,jobtitle;{contact_legend},phone,mobile,fax,email;{more_legend},text;{image_legend},addImage;{sorting_legend},sortingIndex;{published_legend},published;'
	),

    // Subpalettes: Subpalettes sind die Unter - Überschriften, die weitere Elemente beinhalten. In diesem Fall ist 'addImage' eine Checkbox und wenn man diese angehakt hat, sieht man erst das 'image' Feld
	'subpalettes' => array
	(
		'addDepartement'              => 'departementCheckList',
        'addImage'                    => 'singleSRC,size,imagemargin,overwriteMeta',
		'overwriteMeta'               => 'alt,caption,imageUrl,imageTitle',
	),

    // Fields: Jetzt definieren wir alle Felder die für das Element im DCA vorkommen müssen (Meta Fields) und sollen. Ich kann hier z.B. konfigurieren, dass ich ein Textfeld und ein Selectmenü, mit denen ich die Enträge in die Datenbank abspreichere, haben will, damit ich sie dann mit Frontend Templates / Modules ausgeben kann.
    // https://docs.contao.org/dev/reference/dca/fields/
    // https://easysolutionsit.de/artikel/vorlagen-f%C3%BCr-dca-felder.html
	'fields' => array
	(
        // Meta Fields
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
        'sorting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),

        // DCA Fields
        // MultiColumnWizard Textfeld für Anrede pro Sprache
        'salutation' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['salutation'],
        	'exclude'                 => true,
            'search'                  => false,
			'filter'                  => false,
        	'sorting'                 => false,
		    'inputType'               => 'multiColumnWizard',
            'eval'      => array
            (
		        'columnFields' => array
                (
                    'salutation_lang' => array
                    (
						'label'                   => &$GLOBALS['TL_LANG'][$strName]['translations_lang'],
						'exclude'                 => true,
                        'search'                  => false,
            			'filter'                  => false,
                    	'sorting'                 => false,
						'inputType'               => 'select',
						'eval'                    => array(
                                                        'style'=>'width:100%;',
                                                        'chosen'=>true,
                                                        'tl_class'=>'w40'
                                                    ),
                        'options_callback'        => array('ixtensa\EmployeeBundle\dca\tl_ixe_employeedata\tl_ixe_employeedata', 'getLanguageOptions'),
                        'load_callback'			  => array(array('ixtensa\EmployeeBundle\dca\tl_ixe_employeedata\tl_ixe_employeedata', 'setLangDefault'))
					),
                    'salutation_content' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG'][$strName]['salutation_content'],
                        'exclude'                 => true,
            			'search'                  => false,
                        'filter'                  => false,
                        'sorting'                 => false,
                        'inputType'               => 'text',
                    	'eval'                    => array(
                                                        'maxlength'=>255,
                                                        'preserveTags'=>true
                                                    )
                    ),
		        ),
		    ),
		    'sql'       => 'blob NULL',
        ),


        // MultiColumnWizard Textfeld für Titel pro Sprache
        'title' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['title'],
        	'exclude'                 => true,
            'search'                  => false,
			'filter'                  => false,
        	'sorting'                 => false,
		    'inputType'               => 'multiColumnWizard',
            'eval'      => array
            (
		        'columnFields' => array
                (
                    'title_lang' => array
                    (
						'label'                   => &$GLOBALS['TL_LANG'][$strName]['translations_lang'],
						'exclude'                 => true,
                        'search'                  => false,
            			'filter'                  => false,
                    	'sorting'                 => false,
						'inputType'               => 'select',
						'eval'                    => array(
                                                        'style'=>'width:100%;',
                                                        'chosen'=>true,
                                                        'tl_class'=>'w40'
                                                    ),
                        'options_callback'        => array('ixtensa\EmployeeBundle\dca\tl_ixe_employeedata\tl_ixe_employeedata', 'getLanguageOptions'),
                        'load_callback'			  => array(array('ixtensa\EmployeeBundle\dca\tl_ixe_employeedata\tl_ixe_employeedata', 'setLangDefault'))
					),
                    'title_content' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG'][$strName]['title_content'],
                    	'exclude'                 => true,
                        'search'                  => false,
            			'filter'                  => false,
                    	'sorting'                 => false,
                    	'inputType'               => 'text',
                    	'eval'                    => array(
                                                        'maxlength'=>255,
                                                        'preserveTags'=>true
                                                    )
                    ),
		        ),
		    ),
		    'sql'       => 'blob NULL',
        ),

		// Textfeld für Vorname
        'firstname' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['firstname'],
        	'exclude'                 => true,
            'search'                  => true,
        	'sorting'                 => true,
            'filter'                  => true,
            'flag'                    => 1,
        	'inputType'               => 'text',
        	'eval'                    => array(
                                            'maxlength'=>255,
                                            'preserveTags'=>true,
                                            'mandatory'=>true,
                                            'tl_class'=>'clr w50'
                                        ),
        	'sql'                     => "varchar(255) NOT NULL default ''"
        ),

        // Textfeld für Nachname
        'name' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['name'],
        	'exclude'                 => true,
            'search'                  => true,
        	'sorting'                 => true,
            'filter'                  => true,
            'flag'                    => 1,
        	'inputType'               => 'text',
        	'eval'                    => array(
                                            'maxlength'=>255,
                                            'preserveTags'=>true,
                                            'mandatory'=>true,
                                            'tl_class'=>'w50'
                                        ),
        	'sql'                     => "varchar(255) NOT NULL default ''"
        ),

        // SONDERFALL:
        // Eigenes Widget Checkbox Menü Feld mit Abteilungen aus Abteilungserweiterung, die in Checklistenformat ausgegeben werden (Dateien: /Resources/contao/config/config.php, Eingaben aus /Resources/contao/dca/tl_ixe_departement.php, /Widget/Abtmenu.php)
		'addDepartement' => array
		(
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['addDepartement'],
			'exclude'                 => true,
            'search'      		      => false,
			'sorting'     		      => false,
            'filter'     		      => false,
			'inputType'               => 'checkbox',
			'eval'                    => array(
											'submitOnChange'=>true,
                                            'tl_class'=>'clr'
                                        ),
			'sql'                     => "char(1) NOT NULL default ''"
		),

        'departementCheckList' => array
		(
			'label'                   => &$GLOBALS['TL_LANG'][$strName]['departementCheckList'],
			'exclude'                 => true,
			'search'                  => true,
            'sorting'                 => true,
			'filter'                  => true,
            'flag'                    => 1,
            'inputType'               => 'AbtMenu',
            'foreignKey'              => 'tl_ixe_departement.departementname',
            'eval'                    => array(
                                            'feEditable'=>true,
                                            'feViewable'=>true,
                                            'feGroup'=>'qualifications',
                                            'multiple'=>true,
                                            'tl_class'=>'clr'
                                        ),
            // Es handelt sich hier um ein eigenes Widget. Um auch in der Datenbank übernehmen zu können, was wir eingegeben haben, damit das nicht wieder vergessen wird, müssen wir callback Funktionen für Speichern dieser Felder und Laden dieser Felder anlegen. Die Funktionen, die die Daten dann in Datenbnak und Contao abspeichern kommen ganz unten in DIESER Datei unter den Fieldconfigurations
            'save_callback'			  => array($strName, 'saveAbteilungen'),
			'load_callback'			  => array($strName, 'getSelectedAbteilungen'),
			'sql'                     => "blob NULL"
		),

		// MultiColumnWizard Textfeld für Jobtitel pro Sprache
        'jobtitle' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['jobtitle'],
        	'exclude'                 => true,
            'search'                  => false,
			'filter'                  => false,
        	'sorting'                 => false,
		    'inputType'               => 'multiColumnWizard',
            'eval'      => array
            (
		        'columnFields' => array
                (
                    'jobtitle_lang' => array
                    (
						'label'                   => &$GLOBALS['TL_LANG'][$strName]['translations_lang'],
						'exclude'                 => true,
                        'search'                  => false,
            			'filter'                  => false,
                    	'sorting'                 => false,
						'inputType'               => 'select',
						'eval'                    => array(
                                                        'style'=>'width:100%;',
                                                        'chosen'=>true,
                                                        'tl_class'=>'w40'
                                                    ),
                        'options_callback'        => array('ixtensa\EmployeeBundle\dca\tl_ixe_employeedata\tl_ixe_employeedata', 'getLanguageOptions'),
                        'load_callback'			  => array(array('ixtensa\EmployeeBundle\dca\tl_ixe_employeedata\tl_ixe_employeedata', 'setLangDefault'))
					),
                    'jobtitle_content' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG'][$strName]['jobtitle_content'],
                    	'exclude'                 => true,
                        'search'                  => true,
                    	'sorting'                 => false,
            			'filter'                  => false,
                    	'inputType'               => 'text',
                    	'eval'                    => array(
                                                        'maxlength'=>255,
                                                        'preserveTags'=>true
                                                    ),
                    	'sql'                     => "varchar(255) NOT NULL default ''"
                    ),
		        ),
		    ),
		    'sql'       => 'blob NULL',
        ),

		// MultiColumnWizard Textfeld für Telefonnummer und Linktext pro Sprache
        'phone' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['phone'],
        	'exclude'                 => true,
            'search'                  => false,
			'filter'                  => false,
        	'sorting'                 => false,
		    'inputType'               => 'multiColumnWizard',
            'eval'      => array
            (
		        'columnFields' => array
                (
                    'phone_lang' => array
                    (
						'label'                   => &$GLOBALS['TL_LANG'][$strName]['translations_lang'],
						'exclude'                 => true,
                        'search'                  => false,
            			'filter'                  => false,
                    	'sorting'                 => false,
						'inputType'               => 'select',
						'eval'                    => array(
                                                        'style'=>'width:100%;',
                                                        'chosen'=>true,
                                                        'tl_class'=>'w30'
                                                    ),
                        'options_callback'        => array('ixtensa\EmployeeBundle\dca\tl_ixe_employeedata\tl_ixe_employeedata', 'getLanguageOptions'),
                        'load_callback'			  => array(array('ixtensa\EmployeeBundle\dca\tl_ixe_employeedata\tl_ixe_employeedata', 'setLangDefault'))
					),
                    // Textfeld für Telefonnummer mit Überprüfung, ob es eine Telefonnummer ist
                    'phone_content' => array
                    (
                    	'label'                   => &$GLOBALS['TL_LANG'][$strName]['phone_content'],
                    	'exclude'                 => true,
                    	'search'                  => false,
                        'sorting'                 => false,
            			'filter'                  => false,
                    	'inputType'               => 'text',
                    	'eval'                    => array(
                                                        'maxlength'=>64,
                                                        // Uncommented these for version 3.0 so [nbsp] can be added to the input field to avoid the not defined message
                                                        // 'rgxp'=>'phone',
                                                        'decodeEntities'=>true
                                                        )
                    ),
                    // Textfeld für Telefonlinktext
                    'phoneLinktext' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG'][$strName]['phoneLinktext'],
                    	'exclude'                 => true,
                        'search'                  => true,
                    	'sorting'                 => false,
            			'filter'                  => false,
                    	'inputType'               => 'text',
                    	'eval'                    => array(
                                                        'maxlength'=>255,
                                                        'preserveTags'=>true
                                                    )
                    ),
		        ),
		    ),
		    'sql'       => 'blob NULL',
        ),

		// MultiColumnWizard Textfeld für Mobilnummer und Linktext pro Sprache
        'mobile' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['mobile'],
        	'exclude'                 => true,
            'search'                  => false,
			'filter'                  => false,
        	'sorting'                 => false,
		    'inputType'               => 'multiColumnWizard',
            'eval'      => array
            (
		        'columnFields' => array
                (
                    'mobile_lang' => array
                    (
						'label'                   => &$GLOBALS['TL_LANG'][$strName]['translations_lang'],
						'exclude'                 => true,
                        'search'                  => false,
            			'filter'                  => false,
                    	'sorting'                 => false,
						'inputType'               => 'select',
						'eval'                    => array(
                                                        'style'=>'width:100%;',
                                                        'chosen'=>true,
                                                        'tl_class'=>'w30'
                                                    ),
                        'options_callback'        => array('ixtensa\EmployeeBundle\dca\tl_ixe_employeedata\tl_ixe_employeedata', 'getLanguageOptions'),
                        'load_callback'			  => array(array('ixtensa\EmployeeBundle\dca\tl_ixe_employeedata\tl_ixe_employeedata', 'setLangDefault'))
					),
                    // Textfeld für Mobilnummer mit überprüfung, ob es eine solche ist
                    'mobile_content' => array
                    (
                    	'label'                   => &$GLOBALS['TL_LANG'][$strName]['mobile_content'],
                    	'exclude'                 => true,
                    	'search'                  => false,
                        'sorting'                 => false,
            			'filter'                  => false,
                    	'inputType'               => 'text',
                    	'eval'                    => array(
                                                        'maxlength'=>64,
                                                        // Uncommented these for version 3.0 so [nbsp] can be added to the input field to avoid the not defined message
                                                        // 'rgxp'=>'phone',
                                                        'decodeEntities'=>true
                                                        )
                    ),
                    // Textfeld für Handylinktext
                    'mobileLinktext' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG'][$strName]['mobileLinktext'],
                    	'exclude'                 => true,
                        'search'                  => true,
                    	'sorting'                 => false,
            			'filter'                  => false,
                    	'inputType'               => 'text',
                    	'eval'                    => array(
                                                        'maxlength'=>255,
                                                        'preserveTags'=>true
                                                    )
                    ),
		        ),
		    ),
		    'sql'       => 'blob NULL',
        ),

		// MultiColumnWizard Textfeld für Faxnummer und Linktext pro Sprache
        'fax' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['fax'],
        	'exclude'                 => true,
            'search'                  => false,
			'filter'                  => false,
        	'sorting'                 => false,
		    'inputType'               => 'multiColumnWizard',
            'eval'      => array
            (
		        'columnFields' => array
                (
                    'fax_lang' => array
                    (
						'label'                   => &$GLOBALS['TL_LANG'][$strName]['translations_lang'],
						'exclude'                 => true,
                        'search'                  => false,
            			'filter'                  => false,
                    	'sorting'                 => false,
						'inputType'               => 'select',
						'eval'                    => array(
                                                        'style'=>'width:100%;',
                                                        'chosen'=>true,
                                                        'tl_class'=>'w30'
                                                    ),
                        'options_callback'        => array('ixtensa\EmployeeBundle\dca\tl_ixe_employeedata\tl_ixe_employeedata', 'getLanguageOptions'),
                        'load_callback'			  => array(array('ixtensa\EmployeeBundle\dca\tl_ixe_employeedata\tl_ixe_employeedata', 'setLangDefault'))
					),
                    // Textfeld für Faxnummer mit überprüfung, ob es eine solche ist
                    'fax_content' => array
                    (
                    	'label'                   => &$GLOBALS['TL_LANG'][$strName]['fax_content'],
                    	'exclude'                 => true,
                    	'search'                  => false,
                        'sorting'                 => false,
            			'filter'                  => false,
                    	'inputType'               => 'text',
                    	'eval'                    => array(
                                                        'maxlength'=>64,
                                                        // Uncommented these for version 3.0 so [nbsp] can be added to the input field to avoid the not defined message
                                                        // 'rgxp'=>'phone',
                                                        'decodeEntities'=>true
                                                        )
                    ),
                    // Textfeld für Fax Linktext
                    'faxLinktext' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG'][$strName]['faxLinktext'],
                    	'exclude'                 => true,
                        'search'                  => true,
                    	'sorting'                 => false,
            			'filter'                  => false,
                    	'inputType'               => 'text',
                    	'eval'                    => array(
                                                        'maxlength'=>255,
                                                        'preserveTags'=>true
                                                    )
                    ),
		        ),
		    ),
		    'sql'       => 'blob NULL',
        ),

		// MultiColumnWizard Textfeld für E-Mail und Linktext pro Sprache
        'email' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['email'],
        	'exclude'                 => true,
            'search'                  => false,
			'filter'                  => false,
        	'sorting'                 => false,
		    'inputType'               => 'multiColumnWizard',
            'eval'      => array
            (
		        'columnFields' => array
                (
                    'email_lang' => array
                    (
						'label'                   => &$GLOBALS['TL_LANG'][$strName]['translations_lang'],
						'exclude'                 => true,
                        'search'                  => false,
            			'filter'                  => false,
                    	'sorting'                 => false,
						'inputType'               => 'select',
						'eval'                    => array(
                                                        'style'=>'width:100%;',
                                                        'chosen'=>true,
                                                        'tl_class'=>'w30'
                                                    ),
                        'options_callback'        => array('ixtensa\EmployeeBundle\dca\tl_ixe_employeedata\tl_ixe_employeedata', 'getLanguageOptions'),
                        'load_callback'			  => array(array('ixtensa\EmployeeBundle\dca\tl_ixe_employeedata\tl_ixe_employeedata', 'setLangDefault'))
					),
                    // Textfeld für Email mit überprüfung, ob es eine Mailadresse ist
                    'email_content' => array
                    (
                    	'label'                   => &$GLOBALS['TL_LANG'][$strName]['email_content'],
                    	'exclude'                 => true,
                    	'search'                  => true,
                        'sorting'                 => true,
                        'filter'                  => true,
                        'flag'                    => 1,
                    	'inputType'               => 'text',
                    	'eval'                    => array(
                                                        // Uncommented these for version 3.0 so [nbsp] can be added to the input field to avoid the not defined message
                                                        // 'rgxp'=>'email',
                                                        'maxlength'=>255,
                                                        'decodeEntities'=>true
                                                    )
                    ),
                    // Textfeld für Email Links
                    'emailLinktext' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG'][$strName]['emailLinktext'],
                    	'exclude'                 => true,
                        'search'                  => true,
                    	'sorting'                 => false,
            			'filter'                  => false,
                    	'inputType'               => 'text',
                    	'eval'                    => array(
                                                        'maxlength'=>255,
                                                        'preserveTags'=>true
                                                    )
                    ),
		        ),
		    ),
		    'sql'       => 'blob NULL',
        ),

        // Textarea für weitere Angaben für alle Sprachen mit tinyMCE Formatierungen
        'text' => array
        (
        	'label'                   => &$GLOBALS['TL_LANG'][$strName]['text'],
        	'exclude'                 => true,
        	'search'                  => false,
			'sorting'                 => false,
			'filter'                  => false,
        	'inputType'               => 'textarea',
        	'eval'                    => array(
                                            'rte'=>'tinyMCE',
                                            'tl_class'=>'clr'
                                        ),
        	'sql'                     => "mediumtext NULL"
        ),

		// Subpalettes Headline checkbox zum einblenden zum hinzufügen des image dca Feldes
        'addImage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG'][$strName]['addImage'],
			'exclude'                 => true,
			'search'                  => false,
			'sorting'                 => false,
			'filter'                  => false,
			'inputType'               => 'checkbox',
			'eval'                    => array(
											'submitOnChange'=>true,
											'tl_class'=>'clr'
										),
			'sql'                     => "char(1) NOT NULL default ''"
		),

        // Bild Filetree Element zum hinzufügen von Bildern
        'singleSRC' => array
        (
            'label'				      => &$GLOBALS['TL_LANG'][$strName]['singleSRC'],
			'exclude'			      => true,
			'search'      		      => false,
			'sorting'     		      => false,
			'filter'     		      => false,
			'inputType'			      => 'fileTree',
			'eval'				      => array(
                                        		'filesOnly'=>true,
                                        		'fieldType'=>'radio',
                                        		'extensions' => 'jpg,jpeg,gif,png,svg',
                                        		'tl_class'=>'clr'
                                    		),
            'save_callback'			  => array($strName, 'storeFileMetaInformation'),
			'load_callback'			  => array($strName, 'setSingleSrcFlags'),
            'sql'       		      => "binary(16) NULL"

        ),

		// Bildgröße Auswahlfeld
		'size' => array
		(
			'label'                   => &$GLOBALS['TL_LANG'][$strName]['size'],
            'exclude'			      => true,
			'search'      		      => false,
			'sorting'     		      => false,
			'filter'     		      => false,
			'inputType'               => 'imageSize',
            'options'                 => \System::getImageSizes(),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array(
                                            'rgxp'=>'natural',
                                            'includeBlankOption'=>true,
                                            'nospace'=>true,
                                            'helpwizard'=>true,
                                            'tl_class'=>'w50'
                                        ),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),

		// Bilder Abstände Eingabefelder
		'imagemargin' => array
		(
			'label'                   => &$GLOBALS['TL_LANG'][$strName]['imagemargin'],
            'exclude'			      => true,
			'search'      		      => false,
			'sorting'     		      => false,
			'filter'     		      => false,
			'inputType'               => 'trbl',
			'options'                 => $GLOBALS['TL_CSS_UNITS'],
			'eval'                    => array(
                                            'includeBlankOption'=>true,
                                            'tl_class'=>'w50'
                                        ),
			'sql'                     => "varchar(128) NOT NULL default ''"
		),

		// Overwrite Meta Checkbox Selector
        'overwriteMeta' => array
		(
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['overwriteMeta'],
			'exclude'                 => true,
            'search'      		      => false,
			'sorting'     		      => false,
            'filter'     		      => false,
			'inputType'               => 'checkbox',
			'eval'                    => array(
											'submitOnChange'=>true,
											'tl_class'=>'clr'
                                        ),
			'sql'                     => "char(1) NOT NULL default ''"
		),

		// Textfeld zur Eingabe des Alttextes
        'alt' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['alt'],
            'exclude'			      => true,
            'search'      		      => false,
            'sorting'     		      => false,
            'filter'     		      => false,
            'inputType'               => 'text',
            'eval'                    => array(
                                            'maxlength'=>255,
                                            'tl_class'=>'w50'
                                        ),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),

		// Textfeld zur Eingabe der Captions
        'caption' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['caption'],
            'exclude'			      => true,
            'search'      		      => false,
            'sorting'     		      => false,
            'filter'     		      => false,
            'inputType'               => 'text',
            'eval'                    => array(
                                            'maxlength'=>255,
                                            'tl_class'=>'w50'
                                        ),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),

		// Link Auswahlfeld für Link um das Bild herum
        'imageUrl' => array
		(
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['imageUrl'],
			'exclude'                 => true,
			'search'                  => true,
            'sorting'     		      => false,
            'filter'     		      => false,
			'inputType'               => 'text',
			'eval'                    => array(
											'rgxp'=>'url',
											'decodeEntities'=>true,
											'maxlength'=>255,
											'dcaPicker'=>true,
											'addWizardClass'=>false,
											'tl_class'=>'w50'
										),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),

		// Textfeld zur Eingabe des Bildtitels
        'imageTitle' => array
		(
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['imageTitle'],
			'exclude'                 => true,
			'search'                  => true,
            'sorting'     		      => false,
            'filter'     		      => false,
			'inputType'               => 'text',
			'eval'                    => array(
											'maxlength'=>255,
											'tl_class'=>'w50'
										),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),

		// Sortierpriorität für Module mit mehreren Mitarbeitern. Nach dieser NATÜRLICHEN ZAHL wird ein ORDER BY DESC gemacht, bei einem Gleichstand gewinnt der Anfangsbuchstabe des Nachnamen alphabetisch
        'sortingIndex' => array
        (
        	'label'                   => &$GLOBALS['TL_LANG'][$strName]['sortingIndex'],
        	'exclude'                 => true,
			'search'                  => false,
			'sorting'                 => false,
			'filter'                  => false,
        	'inputType'               => 'text',
        	'eval'                    => array(
                                            'maxlength'=>100,
                                            'rgxp'=>'natural',
                                            'tl_class'=>'w50'
                                        ),
        	'sql'                     => "int(100) unsigned NOT NULL default '0'"
        ),

        // Veröffentlichungs Checkbox, die in die Datenbank eine 1 oder NULL schreibt
        'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG'][$strName]['published'],
			'exclude'                 => true,
			'search'      		      => true,
			'sorting'     		      => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array(
                                            'doNotCopy'=>true,
                                            'tl_class'=>'clr'
                                        ),
			'sql'                     => "char(1) NOT NULL default ''"
		),
	)
);



// Klassen Funktionen für das Backend zum constructen, abspeichern und laden unserer eigenen Widgets / Inputtypen
class tl_ixe_employeedata extends \Backend
{
    /**
	 * Den Backend User importieren
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('\BackendUser', 'User');
	}

    // Die vom Contao größtenteils übernommene Funktion vom toggleIcon wird ausgelöst, wenn man in der Element Übersicht auf das Augen - toggle Symbol klickt. Wenn man diese Funktion auslöst, wird das Icon entsprechend verändert und gleichzeitig auch noch die toggleVisibility Funktion ausgelöst, um im Element selber die Felder zum Veröffentlichen in der Datenbank zu ändern
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		// Toggle id aus dem Element ziehen - Marker welches Element gerade getoggelt wurde
		if (\mb_strlen(\Input::get('tid')))
		{
			$this->toggleVisibility(\Input::get('tid'), (\Input::get('state') == 1), (@func_get_arg(12) ?: null));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->hasAccess('tl_ixe_employeedata::published', 'alexf'))
		{
			return '';
		}

		// Verlinkung und toggle Bild hinzufügen und verändern
		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.svg';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.\StringUtil::specialchars($title).'"'.$attributes.'>'.\Image::getHtml($icon, $label, 'data-state="' . ($row['published'] ? 1 : 0) . '"').'</a> ';
	}

    // Die vom Contao größtenteils übernommene Funktion vom toggleVisibility. Wenn man diese Funktion auslöst, wird das entsprechende Element, für dieses diese Funktion ausgelöst wurde geupdated. Das Feld published kriegt nämlich den entsprechend anderen Zustand - ein oder ausgeblendet und die Datenbank entsprechend geupdated
    public function toggleVisibility($intId, $blnVisible, \DataContainer $dc=null)
	{
		// Set the ID and action
		\Input::setGet('id', $intId);
		\Input::setGet('act', 'toggle');

        // Funktionen haben einen anderen Scope als die Klasse – $strName muss noch einmal neu für die Funktion deklariert werden
        $strName = 'tl_ixe_employeedata';

		if ($dc)
		{
			$dc->id = $intId; // see #8043
		}

		// Trigger the onload_callback
		if (\is_array($GLOBALS['TL_DCA'][$strName]['config']['onload_callback']))
		{
			foreach ($GLOBALS['TL_DCA'][$strName]['config']['onload_callback'] as $callback)
			{
				if (\is_array($callback))
				{
					$this->import($callback[0]);
					$this->{$callback[0]}->{$callback[1]}($strName);
				}
				elseif (\is_callable($callback))
				{
					$callback($dc);
				}
			}
		}

		// Check the field access
		if (!$this->User->hasAccess($strName.'::published', 'alexf'))
		{
			throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to publish/unpublish form field ID ' . $intId . '.');
		}

		// Set the current record
		if ($dc)
		{
			$objRow = $this->Database->prepare("SELECT * FROM tl_ixe_employeedata WHERE id=?")
									 ->limit(1)
									 ->execute($intId);

			if ($objRow->numRows)
			{
				$dc->activeRecord = $objRow;
			}
		}

		$objVersions = new \Versions($strName, $intId);
		$objVersions->initialize();

		// Trigger the save_callback
		if (\is_array($GLOBALS['TL_DCA'][$strName]['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA'][$strName]['fields']['published']['save_callback'] as $callback)
			{
				if (\is_array($callback))
				{
					$this->import($callback[0]);
					$blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, $dc);
				}
				elseif (\is_callable($callback))
				{
					$blnVisible = $callback($blnVisible, $dc);
				}
			}
		}

		$time = time();

		// Datenbankeintrag zur Veröffentlichung abändern
		$this->Database->prepare("UPDATE tl_ixe_employeedata SET tstamp=$time, published='" . ($blnVisible ? '1' : '') . "' WHERE id=?")
					   ->execute($intId);

		if ($dc)
		{
			$dc->activeRecord->tstamp = $time;
			$dc->activeRecord->published = ($blnVisible ? '1' : '');
		}

		// Trigger the onsubmit_callback
		if (\is_array($GLOBALS['TL_DCA'][$strName]['config']['onsubmit_callback']))
		{
			foreach ($GLOBALS['TL_DCA'][$strName]['config']['onsubmit_callback'] as $callback)
			{
				if (\is_array($callback))
				{
					$this->import($callback[0]);
					$this->{$callback[0]}->{$callback[1]}($dc);
				}
				elseif (\is_callable($callback))
				{
					$callback($dc);
				}
			}
		}
		$objVersions->create();
	}

    // Sprache Selectfeld mit den Optionen der Symfony Intl Erweiterung befüllen
    public function getLanguageOptions()
	{
		return Intl::getLanguageBundle()->getLanguageNames();
	}

    // Sprache Selectfeld automatisch mit default Wert ('de') befüllen
    public function setLangDefault($varValue, MultiColumnWizard $mcw)
    {
        return (empty($varValue) ? 'de' : $varValue);
    }

	// Die Abteilungen beim Speichern eines Mitarbeiters in die Datenbank des Mitarbeiters aktualisieren. Dieses Callback ist für neue Widgets nämlich noch nicht definiert und lässt man es weg werden diese Felder einfach weder gespeichert noch geladen
    public function saveAbteilungen($varValue, DataContainer $dc)
    {
        $abtId = $dc->id;
    	$data = unserialize($varValue);

    	if (isset($data)) {
    		$this->Database->prepare("DELETE FROM tl_ixe_departement WHERE id = ?")->execute($abtId);
    		foreach ($data as $value) {

    			$arrData = array(
    				'tstamp'		=> time(),
    				'id'			=> $dc->id,
    				'departementname'		=> $value
    			);

				$this->Database->prepare("INSERT INTO tl_ixe_departement %s")
					->set($arrData)
					->execute();

    		}
    	}
    	return $varValue;
    }

    // Die Abteilungen des eigenen Widgets AbtMenu beim Öffnen eines Mitarbeiters laden. Dieses Callback ist für neue Widgets nämlich noch nicht definiert und lässt man es weg werden diese Felder einfach weder gespeichert noch geladen
    public function getSelectedAbteilungen($varValue, DataContainer $dc) {
		$tmp = array();
		$abtId = $dc->id;
		$query = $this->Database->prepare("SELECT id FROM tl_ixe_departement WHERE id = ?")->execute($abtId);

		$res = $query->fetchAllAssoc();

		foreach ($res as $key => $value) {
			$tmp[] = $value['id'];
		}

		$res = serialize($tmp);

		return $res;
	}

	// Save singleSRC / Image function from Contao
	public function storeFileMetaInformation($varValue, DataContainer $dc)
	{
		if ($dc->activeRecord->singleSRC != $varValue)
		{
			$this->addFileMetaInformationToRequest($varValue, ($dc->activeRecord->ptable ?: 'tl_article'), $dc->activeRecord->pid);
		}
		return $varValue;
	}

    // Set SrcFlags function from Contao
	public function setSingleSrcFlags($varValue, DataContainer $dc)
	{
		if ($dc->activeRecord)
		{
			switch ($dc->activeRecord->type)
			{
				case 'text':
				case 'hyperlink':
				case 'image':
				case 'accordionSingle':
					$GLOBALS['TL_DCA'][$dc->table]['fields'][$dc->field]['eval']['extensions'] = Config::get('validImageTypes');
					break;

				case 'download':
					$GLOBALS['TL_DCA'][$dc->table]['fields'][$dc->field]['eval']['extensions'] = Config::get('allowedDownload');
					break;
			}
		}
		return $varValue;
	}
}
