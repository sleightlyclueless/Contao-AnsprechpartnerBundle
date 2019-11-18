<?php

// DCA Erweiterungen: Backend von Contao um Data Container Arrays Erweitern: Zusätzliche Eingabefeleder für verschiedenste Bereiche erstellen und konfigurieren. z.B. Für Backend Module

// Namespace: Der eindeutige Pfad, der auf diese entsprechende PHP Datei zeigt, damit sie von anderen Orten aus eindeutig aufgerufen und oder referenziert werden kann.
namespace ixtensa\AnsprechpartnerBundle\dca\tl_content;

use \ixtensa\AnsprechpartnerBundle\Widget\AnsprechpartnerPicker;
use \ixtensa\AnsprechpartnerBundle\Widget\AbtMenu;

// Wir fügen Felder, Labels und so weiter für unsere Module jetzt zu Contao hinzu - und brauchen dafür immer eine bestimmte Tabelle, die in der /Resources/contao/config/config.php schon instanziert wurde. Da für den entsprechenden Backend Bereich immer Modular die gleiche Tabelle verwendet werden sollte ist es besser wenn wir diese hier zentral anlegen. Man wird sehen der $strName kommt in dieser Datei oft vor. Wenn sich der Tabellenname ändern soll müssen wir das hier dann nur einmal konfigurieren.
$strName = 'tl_content';


$GLOBALS['TL_DCA'][$strName]['list']['operations']['toggle']['button_callback'] = array('tl_content', 'toggleIcon');
$GLOBALS['TL_DCA'][$strName]['list']['sorting']['headerFields'] = array('title', 'tstamp', 'language');


// Palettes
$GLOBALS['TL_DCA'][$strName]['palettes']['ansprechpartner_einzeln'] =  '{type_legend},type;{headline_legend},headline;{contactPerson_legend},ansprechpartnerpicker;{expert_legend:hide},guests,cssID;{grid_legend},grid_columns,grid_options;{invisible_legend:hide},invisible,start,stop;';

$GLOBALS['TL_DCA'][$strName]['fields']['ansprechpartnerpicker'] = array
(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['ansprechpartnerpicker'],
    'exclude'                 => true,
    'filter'                  => true,
    'sorting'                 => true,
    'flag'                    => 1,
    'inputType'               => 'AnsprechpartnerPicker',
    'foreignKey'              => 'tl_bemod_ansprechpartner.CONCAT(name," | ",firstname)',
    // 'foreignKey'              => 'tl_bemod_ansprechpartner.name',
    'eval'                    => array('multiple'=>false),
    // 'sql'                     => "varchar(255) NOT NULL default ''"
    'sql'                     => "blob NULL"
);



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
