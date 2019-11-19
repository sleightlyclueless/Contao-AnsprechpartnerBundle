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

// Wir brauchen unser Widget AnsprechpartnerPicker für dieses DCA, das wir in der entsprechenden /Widget/Ansprechpartnerpicker.php erstellt und konfiguriert haben. Wir holen uns es also per Namespace hier zum verwenden
use \ixtensa\AnsprechpartnerBundle\Widget\AnsprechpartnerPicker;

// Wir fügen jetzt wieder Felder, Labels und so weiter für unsere Module zu Contao hinzu - und brauchen dafür immer eine bestimmte Tabelle, die in der /Resources/contao/config/config.php schon instanziert wurde. In diesem Fall erweitern wir allerdings  eine bereits bestehende Tabelle für die Inhaltselemente der Artikel - die tl_content. Da für den entsprechenden Backend Bereich immer Modular die gleiche Tabelle verwendet werden sollte ist es besser wenn wir diese hier zentral anlegen. Man wird sehen der $strName kommt in dieser Datei oft vor. Wenn sich der Tabellenname ändern soll müssen wir das hier dann nur einmal konfigurieren.
$strName = 'tl_content';

// Wieder einmal die toggleIcon Funktion (ist in der /Resources/contao/dca/tl_bemod_ansprechpartner.php besser beschrieben) um Elemente aus und einzublenden und Ihre Datenbank Einträge entsprechend anzupassen.
$GLOBALS['TL_DCA'][$strName]['list']['operations']['toggle']['button_callback'] = array('tl_content', 'toggleIcon');
// Die Meta Fields des Elements übernehmen wir wie es in jedem Inhaltselement der Fall sein sollte auch in dieses.
$GLOBALS['TL_DCA'][$strName]['list']['sorting']['headerFields'] = array('title', 'tstamp', 'language');

// Paletten für unser spezielles Inhaltselement unter dem Namen 'ansprechpartner_einzeln' werden nun um folgende Felder erweitert. Viele Felder und Legends hiervon gab es bereits und sie wurden anhand vorheriger Elemente entsprechend wieder verwendet.
$GLOBALS['TL_DCA'][$strName]['palettes']['ansprechpartner_einzeln'] =  '{type_legend},type;{headline_legend},headline;{contactPerson_legend},ansprechpartnerpicker;{expert_legend:hide},guests,cssID;{grid_legend},grid_columns,grid_options;{invisible_legend:hide},invisible,start,stop;';

// Unser Element vom Typ 'AnsprechpartnerPicker' erlaubt es uns über unser eigenes Widget per select Menü einen einzigen der erstellten Ansprechpartner an dieser Stelle einzufügen. Dieses Select Menü nimmt sich aus der Datenbank aller erstellten Ansprechpartner im Backend Modul die Namen und Listet diese in einem Auswahlmenü auf. Das Widget wurde unter /Widget/AnsprechpartnerPicker.php erstellt und konfiguriert. Hier wird es entsprechend verwendet.
$GLOBALS['TL_DCA'][$strName]['fields']['ansprechpartnerpicker'] = array
(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['ansprechpartnerpicker'],
    'exclude'                 => true,
    'filter'                  => true,
    'sorting'                 => true,
    'flag'                    => 1,
    'inputType'               => 'AnsprechpartnerPicker',
    'foreignKey'              => 'tl_bemod_ansprechpartner.CONCAT(name," | ",firstname)',
    'eval'                    => array('multiple'=>false),
    // 'sql'                     => "varchar(255) NOT NULL default ''"
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
}
