<?php

/**
 * @package   EmployeeBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2020
 */

// DCA Erweiterungen: Backend von Contao um Data Container Arrays Erweitern: Zusätzliche Eingabefeleder für verschiedenste Bereiche erstellen und konfigurieren. z.B. Für Backend Module

// Namespace: Der eindeutige Pfad, der auf diese entsprechende PHP Datei zeigt, damit sie von anderen Orten aus eindeutig aufgerufen und oder referenziert werden kann.
namespace ixtensa\EmployeeBundle\dca\tl_content;

// Wir brauchen eigene erstellte Widgets Employee-Picker, Checkboxes und das AbtMenu für dieses DCA, das wir in der entsprechenden /Widget/Employeepicker.php, /Widget/EmployeeCheckboxes.php und /Widget/AbtMenu.php erstellt und konfiguriert haben. Wir holen diese uns also per Namespace hier zum verwenden. Wir machen das so, weil wenn die root Checkboxen im Core verändert werden, bleiben die eigenen Widgets unberührt.
use \ixtensa\EmployeeBundle\Widget\EmployeePicker;
use \ixtensa\EmployeeBundle\Widget\EmployeeCheckboxes;
use \ixtensa\EmployeeBundle\Widget\AbtMenu;

// Wir fügen jetzt wieder Felder, Labels und so weiter für unsere Module zu Contao hinzu - und brauchen dafür immer eine bestimmte Tabelle, die in der /Resources/contao/config/config.php schon instanziert wurde. In diesem Fall erweitern wir allerdings eine bereits bestehende Tabelle für die Inhaltselemente der Artikel - die tl_content. Da für den entsprechenden Backend Bereich immer Modular die gleiche Tabelle verwendet werden sollte ist es besser wenn wir diese hier zentral anlegen. Man wird sehen der $strName kommt in dieser Datei oft vor. Wenn sich der Tabellenname ändern soll müssen wir das hier dann nur einmal konfigurieren.
$strName = 'tl_content';

// Einen weiteren Selector hinzufügen und NICHT die anderen ÜBERSCHREIBEN: ['__selector__'][]
$GLOBALS['TL_DCA'][$strName]['palettes']['__selector__'][] = 'employeeType';

// So, wir haben jetzt mehrere Paletten, von denen eine eingefügt wird, je nach dem welche Value das Selector Feld employeeType trägt. Diese Werte sind in dem reference und options Tag vom DCA Feld vergeben und in der /Resources/contao/languages/de/default.php konfiguriert. Es ändert sich eigentlich immer nur ein Feld nach employeeType je nach dem welcher Modus zum einfügen gewählt wurde
// Default Felder
$GLOBALS['TL_DCA'][$strName]['palettes']['employee'] = '{type_legend},type;{contactPerson_legend},employeeType;{expert_legend:hide},guests,cssID;{template_legend:hide},customTpl;{invisible_legend:hide},invisible,start,stop;';
$GLOBALS['TL_DCA'][$strName]['palettes']['Einzeln'] = '{type_legend},type;{contactPerson_legend},employeeType,employeepicker;{expert_legend:hide},guests,cssID;{template_legend:hide},customTpl;{invisible_legend:hide},invisible,start,stop;';
$GLOBALS['TL_DCA'][$strName]['palettes']['Individuell'] = '{type_legend},type;{contactPerson_legend},employeeType,employeecheckboxes;{expert_legend:hide},guests,cssID;{template_legend:hide},customTpl;{invisible_legend:hide},invisible,start,stop;';
$GLOBALS['TL_DCA'][$strName]['palettes']['Abteilungen'] = '{type_legend},type;{contactPerson_legend},employeeType,departementcheckboxes;{expert_legend:hide},guests,cssID;{template_legend:hide},customTpl;{invisible_legend:hide},invisible,start,stop;';
$GLOBALS['TL_DCA'][$strName]['palettes']['Alle'] = '{type_legend},type;{contactPerson_legend},employeeType;{expert_legend:hide},guests,cssID;{template_legend:hide},customTpl;{invisible_legend:hide},invisible,start,stop;';


// Selector Select Feld mit eine Auswahl mehrer Einfügearten der Mitarbeiter, in der /Resources/contao/languages/de/default.php konfiguriert und Folgepaletten werden entsprechend der Auswahl geladen.
$GLOBALS['TL_DCA'][$strName]['fields']['employeeType'] = array
(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['employeeType'],
    'exclude'                 => true,
    'filter'                  => true,
    'search'                  => false,
    'sorting'                 => false,
    'inputType'               => 'select',
    'options'                 => $GLOBALS['TL_LANG'][$strName]['employeeType']['options'],
    'reference'               => &$GLOBALS['TL_LANG']['CTE'],
    'eval'                    => array(
                                    'mandatory'=>true,
                                    'includeBlankOption'=>true,
                                    'submitOnChange'=>true,
                                    'tl_class'=>'clr'
                                ),
    'sql'                     => "blob NULL"
);

// Unser Element vom Typ 'EmployeePicker' erlaubt es uns über unser eigenes Widget per select Menü einen einzigen der erstellten Mitarbeiter an dieser Stelle einzufügen. Dieses Select Menü nimmt sich aus der Datenbank aller erstellten Mitarbeiter im Backend Modul die Namen und Listet diese in einem Auswahlmenü auf. Das Widget wurde unter /Widget/EmployeePicker.php erstellt und konfiguriert. Hier wird es entsprechend verwendet.
$GLOBALS['TL_DCA'][$strName]['fields']['employeepicker'] = array
(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['employeepicker'],
    'exclude'                 => true,
    'search'                  => true,
    'filter'                  => true,
    'sorting'                 => true,
    'flag'                    => 1,
    'inputType'               => 'EmployeePicker',
    'foreignKey'              => 'tl_ixe_employeedata',
    'eval'                    => array(
                                    'multiple'=>false,
                                    'includeBlankOption'=>true,
                                    'mandatory'=>true,
                                    'tl_class'=>'clr'
                                ),
    'options_callback'			  => array('ixtensa\EmployeeBundle\dca\tl_content\tl_content_employee', 'getEmployee'),
    'sql'                     => "blob NULL"
);

// Checkliste mit mehreren Employeen, die individuell zur Ausgabe hinzugefügt werden können.
$GLOBALS['TL_DCA'][$strName]['fields']['employeecheckboxes'] = array
(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['employeecheckboxes'],
    'exclude'                 => true,
    'search'                  => true,
    'sorting'                 => true,
    'filter'                  => true,
    'flag'                    => 1,
    'inputType'               => 'EmployeeCheckboxes',
    'foreignKey'              => 'tl_ixe_employeedata',
    'eval'                    => array(
                                    'feEditable'=>true,
                                    'feViewable'=>true,
                                    'feGroup'=>'qualifications',
                                    'multiple'=>true,
                                    'mandatory'=>true,
                                    'tl_class'=>'clr'
                                ),
    'options_callback'			  => array('ixtensa\EmployeeBundle\dca\tl_content\tl_content_employee', 'getEmployee'),
    'sql'                     => "blob NULL"
);

// Checkliste mit den Abteilungen, die individuell zur Ausgabe ihrer Employee hinzugefügt werden können.
$GLOBALS['TL_DCA'][$strName]['fields']['departementcheckboxes'] = array
(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['departementcheckboxes'],
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
                                    'mandatory'=>true,
                                    'tl_class'=>'clr'
                                ),
    'sql'                     => "blob NULL"
);




// Auch hier nehmen wir wieder ein paar Funktionen für unsere Elemente auf. Allerdings handelt es sich hierbei nur um die 'toggleIcon' und 'toggleVisibility' - Funktionen, die wir aus der /Resources/contao/dca/tl_ixe_employeedata.php bereits besser beschrieben kennen. Wird grob gesagt verwendet, um Elemente aus und einzublenden und Ihre Datenbank Einträge entsprechend anzupassen.
class tl_content_employee extends \Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


    // Wir zeigen im options_callback nur die veröffentlichten Employee, der rest kann nicht ausgewählt werden
    public function getEmployee() {
		$tmp = array();
		$query = $this->Database->prepare("SELECT * FROM tl_ixe_employeedata WHERE published = 1 ORDER BY `sortingIndex` DESC, `name` ASC")->execute();

		$res = $query->fetchAllAssoc();

		foreach ($res as $key => $value) {
            $id = $value['id'];
            $name = $value['name'];
            $fname = $value['firstname'];
            // Hier wird die ID verpackt mitgegeben, damit die Klassenfunktion /Classes/Employee.php und /Widget/AbsprechpartnerPicker.php die ID wieder aus den [ ] Klammern auspacken und für weitere Abfragen etc. in z.B. der Helperclass verwenden kann
            $text = '['.$id.'] ' . $name . ' ' . $fname;
			$tmp[] = $text;
		}
		return $tmp;
    }
}
