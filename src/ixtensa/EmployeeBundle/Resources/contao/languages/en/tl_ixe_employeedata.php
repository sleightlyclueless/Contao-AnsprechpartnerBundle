<?php

/**
 * @package   EmployeeBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2020
 */

// Comments and Descriptions only available in de Version at the moment
$strName = 'tl_ixe_employeedata';

$GLOBALS['TL_LANG'][$strName]['new'] = ['Add new Employee', 'Here you cann add a new Employee'];
$GLOBALS['TL_LANG'][$strName]['editAbteilungen'] = ['Edit departements', 'Here you can edit the departements for the employees'];

$GLOBALS['TL_LANG'][$strName]['edit'] = ['Edit employee', 'Edit employee (ID %s)'];
$GLOBALS['TL_LANG'][$strName]['copy'] = ['Copy employee', 'Copy employee (ID %s)'];
$GLOBALS['TL_LANG'][$strName]['delete'] = ['Delete employee', 'Delete employee (ID %s) löschen'];
$GLOBALS['TL_LANG'][$strName]['toggle'] = ['Publish/unpublish employee', 'Publish/unpublish employee (ID %s)'];
$GLOBALS['TL_LANG'][$strName]['show'] = ['Show information for employee', 'Show information for employee (ID %s)'];

$GLOBALS['TL_LANG'][$strName]['salutation_legend'] = 'Salutation';
$GLOBALS['TL_LANG'][$strName]['name_legend'] = 'Name';
$GLOBALS['TL_LANG'][$strName]['meta_legend'] = 'Further information from working environment';
$GLOBALS['TL_LANG'][$strName]['contact_legend'] = 'Contact information';
$GLOBALS['TL_LANG'][$strName]['more_legend'] = 'Further information';
$GLOBALS['TL_LANG'][$strName]['image_legend'] = 'Image settings';
$GLOBALS['TL_LANG'][$strName]['sorting_legend'] = 'Sorting priority';
$GLOBALS['TL_LANG'][$strName]['published_legend'] = 'Publish employee';

$GLOBALS['TL_LANG'][$strName]['translations_lang'] = ['Language', 'Here you can choose the language you wish the content is shown for'];

// Salutation
$GLOBALS['TL_LANG'][$strName]['salutation'] = ['Salutation settings', 'Here you can add the desired salutation for the employee. If this field is supposed to be empty for a certain language please add the fields for this language anyway and simply keep them empty or add a non break space ([nbsp])'];
$GLOBALS['TL_LANG'][$strName]['salutation_content'] = ['Salutation', 'Enter the desired salutation here'];
$GLOBALS['TL_LANG'][$strName]['title'] = ['Title settings', 'Here you can add one or more titles of the employee. If this field is supposed to be empty for a certain language please add the fields for this language anyway and simply keep them empty or add a non break space ([nbsp])'];
$GLOBALS['TL_LANG'][$strName]['title_content'] = ['Title', 'Enter the desired titles here'];

// Name
$GLOBALS['TL_LANG'][$strName]['name'] = ['Family name', 'Enter the family name of the contact person here'];
$GLOBALS['TL_LANG'][$strName]['firstname'] = ['First name', 'Enter the first name of the contact person here'];

// Further information from working environment
$GLOBALS['TL_LANG'][$strName]['addDepartement'] = ['Add departements', 'Here you can choose if you wish to add departements to the employee'];
$GLOBALS['TL_LANG'][$strName]['departementCheckList'] = ['Departement', 'Here you can add the departements the employee belongs to. Departements can be managed separately under Employee management -> Departements'];
$GLOBALS['TL_LANG'][$strName]['jobtitle'] = ['Jobtitle settings', 'Here you can add jobtitles for the employee. If this field is supposed to be empty for a certain language please add the fields for this language anyway and simply keep them empty or add a non break space ([nbsp])'];
$GLOBALS['TL_LANG'][$strName]['jobtitle_content'] = ['Jobtitle', 'Enter the desired jobtitles for the employee here'];

// Contact information
$GLOBALS['TL_LANG'][$strName]['phone'] = ['Phone settings', 'Here you can add a phone number for the employee. If this field is supposed to be empty for a certain language please add the fields for this language anyway and simply keep them empty or add a non break space ([nbsp])'];
$GLOBALS['TL_LANG'][$strName]['phone_content'] = ['Phone number', 'Enter the phone numer of the employee in here'];
$GLOBALS['TL_LANG'][$strName]['phoneLinktext'] = ['Phone linktext', 'Optional: You can add a text which will be shown as the link for the phone number in here'];
$GLOBALS['TL_LANG'][$strName]['mobile'] = ['Mobile settings', 'Here you can add a mobile number for the employee. If this field is supposed to be empty for a certain language please add the fields for this language anyway and simply keep them empty or add a non break space ([nbsp])'];
$GLOBALS['TL_LANG'][$strName]['mobile_content'] = ['Mobile number', 'Enter the mobile number of the employee in here'];
$GLOBALS['TL_LANG'][$strName]['mobileLinktext'] = ['Mobile Linktext', 'Optional: You can add a text which will be shown as the link for the mobile number in here'];
$GLOBALS['TL_LANG'][$strName]['fax'] = ['Fax settings', 'Here you can add a fax number for the employee. If this field is supposed to be empty for a certain language please add the fields for this language anyway and simply keep them empty or add a non break space ([nbsp])'];
$GLOBALS['TL_LANG'][$strName]['fax_content'] = ['Fax number', 'Enter the fax number of the employee in here'];
$GLOBALS['TL_LANG'][$strName]['faxLinktext'] = ['Fax Linktext', 'Optional: You can add a text which will be shown as the link for the fax number in here'];
$GLOBALS['TL_LANG'][$strName]['email'] = ['E-Mail settings', 'Here you can add a email address for the employee. If this field is supposed to be empty for a certain language please add the fields for this language anyway and simply keep them empty or add a non break space ([nbsp])'];
$GLOBALS['TL_LANG'][$strName]['email_content'] = ['E-Mail', 'Enter the email address of the employee in here'];
$GLOBALS['TL_LANG'][$strName]['emailLinktext'] = ['E-Mail Linktext', 'Optional: You can add a text which will be shown as the link for the mailto link in here'];

// Further information
$GLOBALS['TL_LANG'][$strName]['text'] = ['Further information', 'Here you can put additional information for the employee. Note that the content of this textarea will be shown in all language versions of the employee. For selection of languages in here use iflng Inserttags'];

// Image settings
$GLOBALS['TL_LANG'][$strName]['addImage'] = ['Add image', 'Here you can choose if you wish to add an image to the employee'];
$GLOBALS['TL_LANG'][$strName]['singleSRC'] = ['Select image', 'Here you can add an image (e.g. portrait) to the employee from the file tree'];
$GLOBALS['TL_LANG'][$strName]['size'] = ['Imagesize', 'You can add further settings for the image size in here'];
$GLOBALS['TL_LANG'][$strName]['imagemargin'] = ['Margin', 'You can add inline margins for the image'];
$GLOBALS['TL_LANG'][$strName]['overwriteMeta'] = ['Overwrite meta', 'Here you can choose if you wish to overwrite meta data of the image'];
$GLOBALS['TL_LANG'][$strName]['alt'] = ['Alttext', 'You can enter the alttext of the image in here'];
$GLOBALS['TL_LANG'][$strName]['caption'] = ['Caption', 'You can enter the caption of the image in here'];
$GLOBALS['TL_LANG'][$strName]['imageUrl'] = ['Link', 'You can enter or choose a href link for the image in here'];
$GLOBALS['TL_LANG'][$strName]['imageTitle'] = ['Title', 'You can add a title for the image in here'];

// Sorting priority
$GLOBALS['TL_LANG'][$strName]['sortingIndex'] = ['Add sorting priority', 'You can add a natural number in here to determine the sorting priority of this employee. When multiple employees are entered into an article via the insert modes "Individual", "Departements" and "All" they will be sorted descendingly for these numbers. Higher number will resilt in higher sorting priority. Should there be a tie we will also use the family name in alphabetical order'];

// Publish employee
$GLOBALS['TL_LANG'][$strName]['published'] = ['Publish employee', 'Check this box to publish the employee for frontend output'];
