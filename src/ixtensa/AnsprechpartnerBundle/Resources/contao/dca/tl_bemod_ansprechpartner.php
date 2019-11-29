<?php

/**
 * @package   AnsprechpartnerBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2019
 */

// DCA Erweiterungen: Backend von Contao um Data Container Arrays Erweitern: Zusätzliche Eingabefeleder für verschiedenste Bereiche erstellen und konfigurieren. z.B. Für Backend Module
// Du bist hier jetzt im Haupt DCA Monster der Erweiterung angekommen. Der zentralen Verwaltung im Backend Modul der Ansprechpartner. Von hier aus gibt es auch einen kreuzverweis zu einem eigenen Widget AbtMenu und querverweis zu dem in der Navigation verstecken Abteilungs Backend Modul

// Namespace: Der eindeutige Pfad, der auf diese entsprechende PHP Datei zeigt, damit sie von anderen Orten aus eindeutig aufgerufen und oder referenziert werden kann.
namespace ixtensa\AnsprechpartnerBundle\dca\tl_bemod_ansprechpartner;

// Wir fügen Felder, Labels und so weiter für unsere Module jetzt zu Contao hinzu - und brauchen dafür immer eine bestimmte Tabelle, die in der /Resources/contao/config/config.php schon instanziert wurde. Da für den entsprechenden Backend Bereich immer Modular die gleiche Tabelle verwendet werden sollte ist es besser wenn wir diese hier zentral anlegen. Man wird sehen der $strName kommt in dieser Datei oft vor. Wenn sich der Tabellenname ändern soll müssen wir das hier dann nur einmal konfigurieren.
$strName = 'tl_bemod_ansprechpartner';

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
            // Link zu Abteilungs Erweiterung – Abteilungen verwalten
            'editAbteilungen' => array
			(
				'label'               => &$GLOBALS['TL_LANG'][$strName]['editAbteilungen'],
                'href'                => 'do=abteilungen',
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

        // Operations sind sachen wie Kopieren, Editieren, Löschen. Hier kann man die von Contao gegebenen Operationen für die Felder hinzufügen. Wenn man welche nicht dabei haben will darf man sie hier einfach nicht hinzuschreiben (oder auskommentieren)
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
                'button_callback'     => array('ixtensa\AnsprechpartnerBundle\dca\tl_bemod_ansprechpartner\tl_bemod_ansprechpartner', 'toggleIcon')
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
        '__selector__'                => array('addImage'),
		'default' => '{name_legend},salutation,title,name,firstname;{meta_legend},jobtitle,email,phone,mobile,departementCheckList,more,addImage;{sorting_legend},sortingIndex;{published_legend},published;'
	),

    // Subpalettes: Subpalettes sind die Unter - Überschriften, die weitere Elemente beinhalten. In diesem Fall ist 'addImage' eine Checkbox und wenn man diese angehakt hat, sieht man erst das 'image' Feld
	'subpalettes' => array
	(
        'addImage'                    => 'singleSRC,alt,caption,resize,margin',
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
        // Select Drop Down Menü für Anrede
        'salutation' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['salutation'],
            'exclude'                 => true,
			'search'                  => false,
            'filter'                  => false,
            'sorting'                 => false,
            // InputType ist ein Select Menü mit den folgenden Optionen. Die Optionen sind dann in der Languages Datei /Resources/contao/languages/.../tl_bemod_ansprechpartner.php konfiguriert
            'inputType'               => 'select',
            'options'                 => $GLOBALS['TL_LANG'][$strName]['myselect']['options'],
            'eval'                    => array(
                                            'includeBlankOption'=>true,
                                            'tl_class'=>'clr w50'
                                        ),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        // Textfeld für Titel
        'title' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['title'],
        	'exclude'                 => true,
            'search'                  => false,
			'filter'                  => false,
        	'sorting'                 => false,
        	'inputType'               => 'text',
        	'eval'                    => array(
                                            'maxlength'=>255,
                                            'preserveTags'=>true,
                                            'tl_class'=>'w50'
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
                                            'tl_class'=>'clr w50'
                                        ),
        	'sql'                     => "varchar(255) NOT NULL default ''"
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
                                            'tl_class'=>'w50'
                                        ),
        	'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        // Textfeld für Berufsbezeichnung
        'jobtitle' => array
        (
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['jobtitle'],
        	'exclude'                 => true,
            'search'                  => true,
        	'sorting'                 => false,
			'filter'                  => false,
        	'inputType'               => 'text',
        	'eval'                    => array(
                                            'maxlength'=>255,
                                            // 'helpwizard'=>true,
                                            'preserveTags'=>true,
                                            'tl_class'=>'clr w50'
                                        ),
        	'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        // Textfeld für Email mit überprüfung, ob es eine Mailadresse ist
        'email' => array
        (
        	'label'                   => &$GLOBALS['TL_LANG'][$strName]['email'],
        	'exclude'                 => true,
        	'search'                  => true,
            'sorting'                 => true,
            'filter'                  => true,
            'flag'                    => 1,
        	'inputType'               => 'text',
        	'eval'                    => array(
                                            'rgxp'=>'email',
                                            'maxlength'=>255,
                                            'decodeEntities'=>true,
                                            'tl_class'=>'w50'
                                        ),
        	'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        // Textfeld für Telefonnummer mit überprüfung, ob es eine Telefonnummer ist
        'phone' => array
        (
        	'label'                   => &$GLOBALS['TL_LANG'][$strName]['phone'],
        	'exclude'                 => true,
        	'search'                  => false,
            'sorting'                 => false,
			'filter'                  => false,
        	'inputType'               => 'text',
        	'eval'                    => array(
                                            'maxlength'=>64,
                                            'rgxp'=>'phone',
                                            'decodeEntities'=>true,
                                            'tl_class'=>'clr w50'
                                            ),
        	'sql'                     => "varchar(64) NOT NULL default ''"
        ),
        // Textfeld für Mobilnummer mit überprüfung, ob es eine solche ist
        'mobile' => array
        (
        	'label'                   => &$GLOBALS['TL_LANG'][$strName]['mobile'],
        	'exclude'                 => true,
        	'search'                  => false,
            'sorting'                 => false,
			'filter'                  => false,
        	'inputType'               => 'text',
        	'eval'                    => array(
                                            'maxlength'=>64,
                                            'rgxp'=>'phone',
                                            'decodeEntities'=>true,
                                            'tl_class'=>'w50'
                                            ),
        	'sql'                     => "varchar(64) NOT NULL default ''"
        ),
        // SONDERFALL:
        // Eigenes Widget Checkbox Menü Feld mit Abteilungen aus Abteilungserweiterung, die in Checklistenformat ausgegeben werden (Dateien: config, tl_bemod_abteilungen, /Widget/Abtmenu.php)
        'departementCheckList' => array
		(
			'label'                   => &$GLOBALS['TL_LANG'][$strName]['departementCheckList'],
			'exclude'                 => true,
			'search'                  => true,
            'sorting'                 => true,
			'filter'                  => true,
            'flag'                    => 1,
            'inputType'               => 'AbtMenu',
            'foreignKey'              => 'tl_bemod_abteilungen.abtname',
            'eval'                    => array(
                                            'feEditable'=>true,
                                            'feViewable'=>true,
                                            'feGroup'=>'qualifications',
                                            'multiple'=>true,
                                            'mandatory'=>true,
                                            'tl_class'=>'clr'
                                        ),
            // Es handelt sich hier um ein eigenes Widget. Um auch in der Datenbank übernehmen zu können, was wir eingegeben haben, damit das nicht wieder vergessen wird, müssen wir callback Funktionen für Speichern dieser Felder und Laden dieser Felder anlegen. Die Funktionen, die die Daten dann in Datenbnak und Contao abspeichern kommen ganz unten in DIESER Datei unter den Fieldconfigurations
            'save_callback'			  => array($strName, 'saveAbteilungen'),
			'load_callback'			  => array($strName, 'getSelectedAbteilungen'),
			'sql'                     => "blob NULL"
		),
        // Textarea für weitere Angaben mit tinyMCE Formatierungen
        'more' => array
        (
        	'label'                   => &$GLOBALS['TL_LANG'][$strName]['more'],
        	'exclude'                 => true,
        	'search'                  => false,
			'sorting'                 => false,
			'filter'                  => false,
        	'inputType'               => 'textarea',
        	'eval'                    => array(
                                            'rte'=>'tinyMCE'
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
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
        // Sortierpriorität für Module mit mehreren Ansprechpartnern. Nach dieser NATÜRLICHEN ZAHL wird ein ORDER BY DESC gemacht, bei einem Gleichstand gewinnt der Anfangsbuchstabe des Nachnamen alphabetisch
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
                                            'rgxp'=>'natural'
                                        ),
        	'sql'                     => "int(100) unsigned NOT NULL default '0'"
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
                                        		'extensions' => 'jpg,png,gif',
                                        		'tl_class'=>'clr'
                                    		),
            'save_callback'			  => array($strName, 'storeFileMetaInformation'),
			'load_callback'			  => array($strName, 'setSingleSrcFlags'),
            'sql'       		      => "binary(16) NULL"

        ),
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
		'resize' => array
		(
			'label'                   => &$GLOBALS['TL_LANG'][$strName]['resize'],
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
		'margin' => array
		(
			'label'                   => &$GLOBALS['TL_LANG'][$strName]['margin'],
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
class tl_bemod_ansprechpartner extends \Backend
{
    /**
	 * Den Backend User importieren
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('\BackendUser', 'User');
	}

    /**
	 * Die vom Contao größtenteils übernommene Funktion vom toggleIcon wird ausgelöst, wenn man in der Element Übersicht auf das Augen - toggle Symbol klickt. Wenn man diese Funktion auslöst, wird das Icon entsprechend verändert wird gleichzeitig auch noch die toggleVisibility Funktion ausgelöst, um im Element selber das Feld entsprechend des Icons in der Datenbank zu ändern
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		// Toggle id aus dem Element ziehen - Marker welches Element gerade getoggelt wurde
		if (\mb_strlen(\Input::get('tid')))
		{
			$this->toggleVisibility(\Input::get('tid'), (\Input::get('state') == 1), (@func_get_arg(12) ?: null));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->hasAccess('tl_bemod_ansprechpartner::published', 'alexf'))
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

    /**
	 * Die vom Contao größtenteils übernommene Funktion vom toggleVisibility. Wenn man diese Funktion auslöst, wird das entsprechende Element, für dieses diese Funktion ausgelöst wurde geupdated. Das Feld published kriegt nämlich den entsprechend anderen Zustand - ein oder ausgeblendet und die Datenbank entsprechend geupdated
	 *
	 * @param integer       $intId
	 * @param boolean       $blnVisible
	 * @param \DataContainer $dc
	 */
    public function toggleVisibility($intId, $blnVisible, \DataContainer $dc=null)
	{
		// Set the ID and action
		\Input::setGet('id', $intId);
		\Input::setGet('act', 'toggle');

        // Funktionen haben einen anderen Scope als die Klasse – $strName muss noch einmal neu für die Funktion deklariert werden
        $strName = 'tl_bemod_ansprechpartner';

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
			$objRow = $this->Database->prepare("SELECT * FROM tl_bemod_ansprechpartner WHERE id=?")
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

		// Update the database
		$this->Database->prepare("UPDATE tl_bemod_ansprechpartner SET tstamp=$time, published='" . ($blnVisible ? '1' : '') . "' WHERE id=?")
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


    // Die Abteilungen des eigenen Widgets AbtMenu beim Öffnen eines Ansprechpartners laden. Dieses Callback ist für neue Widgets nämlich noch nicht definiert und lässt man es weg werden diese Felder einfach weder gespeichert noch geladen
    public function getSelectedAbteilungen($varValue, DataContainer $dc) {
		$tmp = array();
		$abtId = $dc->id;
		$query = $this->Database->prepare("SELECT id FROM tl_bemod_abteilungen WHERE id = ?")->execute($abtId);

		$res = $query->fetchAllAssoc();

		foreach ($res as $key => $value) {
			$tmp[] = $value['id'];
		}

		$res = serialize($tmp);

		return $res;
	}

    // Die Abteilungen beim Speichern eines Ansprechpartners in die Datenbank in das entsprechende Feld eintragen. Dieses Callback ist für neue Widgets nämlich noch nicht definiert und lässt man es weg werden diese Felder einfach weder gespeichert noch geladen
    public function saveAbteilungen($varValue, DataContainer $dc)
    {
        $abtId = $dc->id;
    	$data = unserialize($varValue);

    	if (isset($data)) {
    		$this->Database->prepare("DELETE FROM tl_bemod_abteilungen WHERE id = $abtId")->execute();
    		foreach ($data as $value) {

    			$arrData = array(
    				'tstamp'		=> time(),
    				'id'			=> $dc->id,
    				'abtname'		=> $value
    			);

				$this->Database->prepare("INSERT INTO tl_bemod_abteilungen %s")
					->set($arrData)
					->execute();

    		}
    	}
    	return $varValue;
    }


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

	public function storeFileMetaInformation($varValue, DataContainer $dc)
	{
		if ($dc->activeRecord->singleSRC != $varValue)
		{
			$this->addFileMetaInformationToRequest($varValue, ($dc->activeRecord->ptable ?: 'tl_article'), $dc->activeRecord->pid);
		}

		return $varValue;
	}
}
