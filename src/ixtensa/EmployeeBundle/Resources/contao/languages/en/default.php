<?php

/**
 * @package   EmployeeBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2020
 */

// Comments and Descriptions only available in de Version at the moment
$GLOBALS['TL_LANG']['CTE']['employee'] = ['Employee'];

$GLOBALS['TL_LANG']['tl_content']['contactPerson_legend'] = 'Employee Choice';

// Übergeordnetes Select Field für die Palettes betexten
$GLOBALS['TL_LANG']['tl_content']['employeeType'] = ['Employee insert mode', 'Choose the Employee you want to display from the given select menue below. Einzeln: Choose a single Employee to display. Individuell: Choose individual Employees to display with the checkbox menue. Abteilungen: Chose the departement(s) whose Employees are supposed to be shown. Alle: Simply display all Employees.'];

$GLOBALS['TL_LANG']['tl_content']['employeeType']['options'] = ['Single', 'Individual', 'Departements', 'All'];

$GLOBALS['TL_LANG']['tl_content']['employeepicker'] = ['Single Employee', 'Choose a single Employee to display from the select - menue below'];
$GLOBALS['TL_LANG']['tl_content']['employeecheckboxes'] = ['Individual Employees', 'Choose individual Employees to display with the checkbox menue below. On output they will be sorted with their given sorting index and alphabeticall order of their last names. Also pay in mind that employees which are not published in the backend will not be shown in the list below'];
$GLOBALS['TL_LANG']['tl_content']['departementcheckboxes'] = ['Departements', 'Choose the Departements whoose Employees are going to be shown with the checkbox menue below. On output they will be sorted with their given sorting index and alphabeticall order of their last names. Also pay in mind that employees which are not published in the backend will not be shown in the list below'];


// Globals
// /EmployeeBundle/Classes/Employee.php
$GLOBALS['IX_EB']['MSC']['wildcard_header'] = '### Employee ###';
$GLOBALS['IX_EB']['MSC']['disabled'] = ' (is not published!)';
$GLOBALS['IX_EB']['MSC']['template_title_individual'] = 'Individual Employees';
$GLOBALS['IX_EB']['MSC']['template_title_departements'] = 'Employees from departement(s): ';
$GLOBALS['IX_EB']['MSC']['template_title_all'] = 'All Employees';
$GLOBALS['IX_EB']['MSC']['template_title_err'] = 'No insert mode was chosen! Please try again!';


// /EmployeeBundle/Helper/HelperClass.php
$GLOBALS['IX_EB']['MSC']['no_departement_defined'] = 'No departement for this Employee defined!';
$GLOBALS['IX_EB']['MSC']['no_departement_for_language'] = 'There are no departement translations for the language %s defined!';
$GLOBALS['IX_EB']['MSC']['no_language'] = '%s is not defined for the language %s!';
