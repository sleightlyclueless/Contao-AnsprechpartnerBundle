<?php

/**
 * @package   AnsprechpartnerBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2019
 */

// DCA Erweiterungen: Backend von Contao um Data Container Arrays Erweitern: Zusätzliche Eingabefeleder für verschiedenste Bereiche erstellen und konfigurieren. z.B. Für Backend Module

// Namespace: Der eindeutige Pfad, der auf diese entsprechende PHP Datei zeigt, damit sie von anderen Orten aus eindeutig aufgerufen und oder referenziert werden kann.
namespace ixtensa\AnsprechpartnerBundle\dca\tl_content;

// Wir brauchen eigene erstellte Widgets Ansprechpartner-Picker, Checkboxes und das AbtMenu für dieses DCA, das wir in der entsprechenden /Widget/Ansprechpartnerpicker.php, /Widget/AnsprechPartnerCheckboxes.php und /Widget/AbtMenu.php erstellt und konfiguriert haben. Wir holen diese uns also per Namespace hier zum verwenden. Wir machen das so, weil wenn die root Checkboxen im Core verändert werden, bleiben die eigenen Widgets unberührt.
use \ixtensa\AnsprechpartnerBundle\Widget\AnsprechpartnerPicker;
use \ixtensa\AnsprechpartnerBundle\Widget\AnsprechPartnerCheckboxes;
use \ixtensa\AnsprechpartnerBundle\Widget\AbtMenu;

// Wir fügen jetzt wieder Felder, Labels und so weiter für unsere Module zu Contao hinzu - und brauchen dafür immer eine bestimmte Tabelle, die in der /Resources/contao/config/config.php schon instanziert wurde. In diesem Fall erweitern wir allerdings eine bereits bestehende Tabelle für die Inhaltselemente der Artikel - die tl_content. Da für den entsprechenden Backend Bereich immer Modular die gleiche Tabelle verwendet werden sollte ist es besser wenn wir diese hier zentral anlegen. Man wird sehen der $strName kommt in dieser Datei oft vor. Wenn sich der Tabellenname ändern soll müssen wir das hier dann nur einmal konfigurieren.
$strName = 'tl_content';

// Einen weiteren Selector hinzufügen und NICHT die anderen überschreiben: ['__selector__'][]
$GLOBALS['TL_DCA'][$strName]['palettes']['__selector__'][] = 'ansprechpartnerType';

// So, wir haben jetzt mehrere Paletten, von denen eine eingefügt wird, je nach dem welche Value das Selector Feld ansprechpartnerType trägt. Diese Werte sind in dem reference und options Tag vom DCA Feld vergeben und in der /Resources/contao/languages/de/default.php konfiguriert. Es ändert sich eigentlich immer nur ein Feld nach ansprechpartnerType je nach dem welcher Modus zum einfügen gewählt wurde
$GLOBALS['TL_DCA'][$strName]['palettes']['ansprechpartner'] = '{type_legend},type;{contactPerson_legend},ansprechpartnerType;{expert_legend:hide},guests,cssID;{grid_legend},grid_columns,grid_options;{invisible_legend:hide},invisible,start,stop;';
$GLOBALS['TL_DCA'][$strName]['palettes']['Einzeln'] = '{type_legend},type;{contactPerson_legend},ansprechpartnerType,ansprechpartnerpicker;{expert_legend:hide},guests,cssID;{grid_legend},grid_columns,grid_options;{invisible_legend:hide},invisible,start,stop;';
$GLOBALS['TL_DCA'][$strName]['palettes']['Individuell'] = '{type_legend},type;{contactPerson_legend},ansprechpartnerType,ansprechpartnercheckboxes;{expert_legend:hide},guests,cssID;{grid_legend},grid_columns,grid_options;{invisible_legend:hide},invisible,start,stop;';
$GLOBALS['TL_DCA'][$strName]['palettes']['Abteilungen'] = '{type_legend},type;{contactPerson_legend},ansprechpartnerType,departementcheckboxes;{expert_legend:hide},guests,cssID;{grid_legend},grid_columns,grid_options;{invisible_legend:hide},invisible,start,stop;';
$GLOBALS['TL_DCA'][$strName]['palettes']['Alle'] = '{type_legend},type;{contactPerson_legend},ansprechpartnerType;{expert_legend:hide},guests,cssID;{grid_legend},grid_columns,grid_options;{invisible_legend:hide},invisible,start,stop;';


// Selector Select Feld mit eine Auswahl mehrer Einfüge Arten der Ansprechpartner, in der /Resources/contao/languages/de/default.php konfiguriert und Paletten werden entsprechend geladen.
$GLOBALS['TL_DCA'][$strName]['fields']['ansprechpartnerType'] = array
(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['ansprechpartnerType'],
    'exclude'                 => true,
    'filter'                  => true,
    'inputType'               => 'select',
    'options'                 => $GLOBALS['TL_LANG'][$strName]['ansprechpartnerType']['options'],
    'reference'               => &$GLOBALS['TL_LANG']['CTE'],
    'eval'                    => array(
                                    'mandatory'=>true,
                                    'includeBlankOption'=>true,
                                    'submitOnChange'=>true,
                                    'tl_class'=>'clr'
                                ),
    'sql'                     => "blob NULL"
);

// Unser Element vom Typ 'AnsprechpartnerPicker' erlaubt es uns über unser eigenes Widget per select Menü einen einzigen der erstellten Ansprechpartner an dieser Stelle einzufügen. Dieses Select Menü nimmt sich aus der Datenbank aller erstellten Ansprechpartner im Backend Modul die Namen und Listet diese in einem Auswahlmenü auf. Das Widget wurde unter /Widget/AnsprechpartnerPicker.php erstellt und konfiguriert. Hier wird es entsprechend verwendet.
$GLOBALS['TL_DCA'][$strName]['fields']['ansprechpartnerpicker'] = array
(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['ansprechpartnerpicker'],
    'exclude'                 => true,
    'search'                  => true,
    'filter'                  => true,
    'sorting'                 => true,
    'flag'                    => 1,
    'inputType'               => 'AnsprechpartnerPicker',
    'foreignKey'              => 'tl_bemod_ansprechpartner.CONCAT(salutation," ",title," ",firstname," ",name)',
    'eval'                    => array(
                                    'multiple'=>false,
                                    'includeBlankOption'=>true,
                                    'mandatory'=>true,
                                    'tl_class'=>'clr'
                                ),
    'options_callback'			  => array('ixtensa\AnsprechpartnerBundle\dca\tl_content\tl_content_ansprechpartner', 'getAnsprechpartner'),
    'sql'                     => "blob NULL"
);

// Checkliste mit mehreren Ansprechpartnern, die individuell zur Ausgabe hinzugefügt werden können.
$GLOBALS['TL_DCA'][$strName]['fields']['ansprechpartnercheckboxes'] = array
(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['ansprechpartnercheckboxes'],
    'exclude'                 => true,
    'search'                  => true,
    'sorting'                 => true,
    'filter'                  => true,
    'flag'                    => 1,
    'inputType'               => 'AnsprechPartnerCheckboxes',
    'foreignKey'              => 'tl_bemod_ansprechpartner.CONCAT(salutation," ",title," ",firstname," ",name)',
    'eval'                    => array(
                                    'feEditable'=>true,
                                    'feViewable'=>true,
                                    'feGroup'=>'qualifications',
                                    'multiple'=>true,
                                    'mandatory'=>true,
                                    'tl_class'=>'clr'
                                ),
    'options_callback'			  => array('ixtensa\AnsprechpartnerBundle\dca\tl_content\tl_content_ansprechpartner', 'getAnsprechpartner'),
    // Es handelt sich hier um ein eigenes Widget. Um auch in der Datenbank übernehmen zu können, was wir eingegeben haben, damit das nicht wieder vergessen wird, müssen wir callback Funktionen für Speichern dieser Felder und Laden dieser Felder anlegen. Die Funktionen, die die Daten dann in Datenbnak und Contao abspeichern kommen ganz unten in DIESER Datei unter den Fieldconfigurations
    'sql'                     => "blob NULL"
);

// Checkliste mit den Abteilungen, die individuell zur Ausgabe ihrer Ansprechpartner hinzugefügt werden können.
$GLOBALS['TL_DCA'][$strName]['fields']['departementcheckboxes'] = array
(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['departementcheckboxes'],
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
    'sql'                     => "blob NULL"
);




// Auch hier nehmen wir wieder ein paar Funktionen für unsere Elemente auf. Allerdings handelt es sich hierbei nur um die 'toggleIcon' und 'toggleVisibility' - Funktionen, die wir aus der /Resources/contao/dca/tl_bemod_ansprechpartner.php bereits besser beschrieben kennen. Wird grob gesagt verwendet, um Elemente aus und einzublenden und Ihre Datenbank Einträge entsprechend anzupassen.
class tl_content_ansprechpartner extends \Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	/**
	 * Return the "toggle visibility" button
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
		if (strlen(Input::get('tid')))
		{
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->hasAccess('tl_content::invisible', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;id='.Input::get('id').'&amp;tid='.$row['id'].'&amp;state='.$row['invisible'];

		if ($row['invisible'])
		{
			$icon = 'invisible.gif';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label, 'data-state="' . ($row['invisible'] ? 0 : 1) . '"').'</a> ';
	}

	/**
	 * Toggle the visibility of an element
	 *
	 * @param integer       $intId
	 * @param boolean       $blnVisible
	 * @param DataContainer $dc
	 */
	public function toggleVisibility($intId, $blnVisible, DataContainer $dc=null)
	{
		// Set the ID and action
		Input::setGet('id', $intId);
		Input::setGet('act', 'toggle');

		if ($dc)
		{
			$dc->id = $intId; // see #8043
		}

		$this->checkPermission();

		// Check the field access
		if (!$this->User->hasAccess('tl_content::invisible', 'alexf'))
		{
			$this->log('Not enough permissions to publish/unpublish content element ID "'.$intId.'"', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// The onload_callbacks vary depending on the dynamic parent table (see #4894)
		if (is_array($GLOBALS['TL_DCA']['tl_content']['config']['onload_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$this->{$callback[0]}->{$callback[1]}(($dc ?: $this));
				}
				elseif (is_callable($callback))
				{
					$callback(($dc ?: $this));
				}
			}
		}

		$objVersions = new Versions('tl_content', $intId);
		$objVersions->initialize();

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_content']['fields']['invisible']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_content']['fields']['invisible']['save_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, ($dc ?: $this));
				}
				elseif (is_callable($callback))
				{
					$blnVisible = $callback($blnVisible, ($dc ?: $this));
				}
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_content SET tstamp=". time() .", invisible='" . ($blnVisible ? '' : 1) . "' WHERE id=?")
					   ->execute($intId);

		$objVersions->create();
	}




    // Custom Functions

    // Wir zeigen im options_callback nur die veröffentlichten Ansprechpartner, der rest kann nicht ausgewählt werden
    public function getAnsprechpartner() {
		$tmp = array();
		$query = $this->Database->prepare("SELECT * FROM tl_bemod_ansprechpartner WHERE published = 1 ORDER BY `sortingIndex` DESC, `name` ASC")->execute();

		$res = $query->fetchAllAssoc();

		foreach ($res as $key => $value) {
            $id = $value['id'];
            $sal = $value['salutation'];
            $title = $value['title'];
            $name = $value['name'];
            $fname = $value['firstname'];
            // Hier wird die ID verpackt mitgegeben, damit die Klassenfunktion /Classes/Ansprechpartner.php und /Widget/AbsprechpartnerPicker.php die ID wieder aus den [ ] Klammern auspacken und für weitere Abfragen etc. in z.B. der Helperclass verwenden kann
            $text = '['.$id.'] ' . $sal . ' ' . $title . ' ' . $name . ' ' . $fname;
			$tmp[] = $text;
		}
        // array(2) { [0]=> string(24) "[id] Herr Herr Zill Sebastian" [1]=> string(25) "[id] Herr Herr Genschow Siegie" }

		// $res = serialize($tmp);
        // string(40) "a:2:{i:0;s:4:"Zill";i:1;s:8:"Genschow";}"
        // dump($tmp); exit;
		return $tmp;
    }
}
