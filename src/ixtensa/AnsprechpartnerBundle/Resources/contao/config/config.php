<?php

/**
 * @package   AnsprechpartnerBundle
 * @author    (c) IXTENSA GmbH & Co. KG Internet und Webagentur -- Sebastian Zill
 * @license   GNU LGPL 3+
 * @copyright (c) 2020
 */

 // BACK END FORM FIELDS, z.B. genutzt für eigene Widgets in Inhaltselementen
 // Wir haben in der src/ixtensa/AnsprechpartnerBundle/Widget/AbtMenu.php und src/ixtensa/AnsprechpartnerBundle/Widget/AnsprechpartnerPicker.php ein zusätzliches Widgets (InputType Feld für Contao DCA), abgeleitet von der Contao Checkbox und dem Contao Select Menü erstellt. Damit wir diese Felder erstellen und verwenden können müssen wir als aller erstes die neuen Dateien Contao "zeigen", damit Sie verwendet und geparst werden. Das machen wir hier über ['BE_FFL']['WidgetName'] = Namespace, wo das Widget konfiguriert wird (Namespace muss dann entsprechend auch in der AbtMenu.php stehen)
 // Back end form fields kommen aus einem globalen Array $GLOBALS['BE_FFL']['WidgetName']
 $GLOBALS['BE_FFL']['AbtMenu']  = 'ixtensa\\AnsprechpartnerBundle\\Widget\\AbtMenu';
 $GLOBALS['BE_FFL']['AnsprechPartnerCheckboxes']  = 'ixtensa\\AnsprechpartnerBundle\\Widget\\AnsprechPartnerCheckboxes';
 $GLOBALS['BE_FFL']['AnsprechpartnerPicker']  = 'ixtensa\\AnsprechpartnerBundle\\Widget\\AnsprechpartnerPicker';


// BACK END MODULES
// Wir erstellen jetzt neue Backend Module, in denen wir unsere Ansprechpartner konfigurieren und später übers Frontend ausgeben können. Schema:
// $GLOBALS['Backend Modul']['Überschrift für Modulbereich (wird in languages datei entsprechend übersetzt)']['modulname (wird ebenfalls noch per languages übersetzt)'] = array
// Backend Module sind in der Linken Navigation vom Contao Backend
// Abteilungen – Zentrale Abteilungen für Ansprechpartner anlegen und dort per Check Widget wählen
 $GLOBALS['BE_MOD']['zix_ansprechpartner']['abteilungen'] = array
 (
    // Wir wollen DCA Konfigurationen (zusätzliche Felder etc.) anlegen. Dafür benötigen wir eigene Tabellen in denen wir rumhantieren können, für jede Ebene der Erweiterung. Deswegen konfigurieren wir hier das Anlegen von 2 zusätzlichen Tabellen. Eine für das Anlegen der Abteilungen und eine child Tabelle für die jeweiligen Übersetzungen verschiedener Sprachen für die Parent ID
    'tables'            => array('tl_bemod_abteilungen', 'tl_bemod_abteilungen_lang'),
    // hideInNavigation versteckt das dann im Backend - um Dopplungen im Navigationsmenü zu verhindern , denn wir rufen dieses Backend Modul dann über eine Operation in der Ansprechpartner DCA auf und wollen nicht für eine Erweiterung zwei Navigationspunkte erstellen - nicht übersichtlich und abhängig
    // Manchmal nimmt Contao / Browser / Cache / Composer (???) aus der den hideInNavigation => true nicht. Deswegen wird dieser in /src/ixtensa/AnsprechpartnerBundle/Resources/public/css/be.css noch einmal ausgeblendet
    'hideInNavigation'  => true,

    // 'callback'     => 'ClassName',
    // 'key'          => array('Class', 'method'),
    // 'icon'         => 'path/to/icon.gif',
    // 'stylesheet'   => '/Resources/public/css/zix_ansprechpartner.css',
    // 'javascript'   => 'path/to/javascript.js'
 );
// Ansprechpartner – Ansprechpartner als Backend Modul mit den Abteilungen in der Dca als Link integriert und vielen weiteren DCA Feldern konfigurieren und für Frontend Ausgabe vorbereiten
 $GLOBALS['BE_MOD']['zix_ansprechpartner']['ansprechpartner'] = array
 (
    // Wieder Tabellen für DCA Anpassungen des zusätzlichen Backend Moduls
    'tables'      => array('tl_bemod_ansprechpartner'),
 );

 // CONTENT ELEMENTS  erweitern das Frontend Artikel Inhaltselemente
 // Content elements are stored in a global array called "TL_CTE". You can add your own content elements by adding them to the array.
 $GLOBALS['TL_CTE']['Ixtensa']['ansprechpartner'] = 'ixtensa\\AnsprechpartnerBundle\\Classes\\Ansprechpartner';


 // Backend CSS und JS Dateien hinzufügen
 // Manchmal nimmt Contao / Browser / Cache / Composer (???) aus der den hideInNavigation => true nicht. Deswegen wird dieser in /src/ixtensa/AnsprechpartnerBundle/Resources/public/css/be.css noch einmal ausgeblendet
  if (TL_MODE == 'BE') {
      $GLOBALS['TL_CSS'][] = 'bundles/ansprechpartner/css/be.css';
  }

 // INFOS zu weiteren möglichen Konfigurationen
 // =====================================================================================================================================


 // FRONT END MODULES
 // Front end modules are stored in a global array called "FE_MOD". You can add
 // $GLOBALS['FE_MOD'] = array
 // (
 //    'group_1' => array
 //    (
 //       'module_1' => 'ModuleClass1',
 //       'module_2' => 'ModuleClass2'
 //    )
 // );


 // FRONT END FORM FIELDS
 // Front end form fields are stored in a global array called "TL_FFL". You can add your own form fields by adding them to the array.
 // $GLOBALS['TL_FFL'] = array
 // (
 //    'input'  => 'FieldClass1',
 //    'select' => 'FieldClass2'
 // );
 // The keys (like "input") are the field names, which are e.g. stored in the database and used to find the corresponding translations. The values (like "FieldClass1") are the names of the classes, which will be loaded when the field is rendered. The class "FieldClass1" has to be stored in a file named "FieldClass1.php" in your module folder.


 // PAGE TYPES
 // Page types are stored in a global array called "TL_PTY". You can add your own page types by adding them to the array.
 // $GLOBALS['TL_PTY'] = array
 // (
 //    'type_1' => 'PageType1',
 //    'type_2' => 'PageType2'
 // );
 // The keys (like "type_1") are the field names, which are e.g. stored in the database and used to find the corresponding translations. The values (like "PageType1") are the names of the classes, which will be loaded when the page is rendered. The class "PageType1" has to be stored in a file named "PageType1.php" in your module folder.


 // MODEL MAPPINGS
 // Model names are usually built from the table names, e.g. "tl_user_group" becomes "UserGroupModel". There might be situations, however, where you need to specify a custom mapping, e.g. when you are using nested namespaces.
 // $GLOBALS['TL_MODELS'] = array
 // (
 //    'tl_user'       => 'Vendor\Application\UserModel',
 //    'tl_user_group' => 'Vendor\Application\UserGroupModel'
 // );
 // You can register your mappings in the config.php file of your extension.


 // MAINTENANCE MODULES
 // Maintenance modules are stored in a global array called "TL_MAINTENANCE". You can add your own maintenance modules by adding them to the array.
 // $GLOBALS['TL_MAINTENANCE'] = array
 // (
 //    'ClearCache',
 //    'RebuildSearchIndex'
 // );
 // Take a look at the system/modules/core/classes/PurgeData.php file to see how maintenance modules are set up. The class "ClearCache" has to be stored in a file named "ClearCache.php" in your module folder.


 // PURGE JOBS
 // Purge jobs are stored in a global array called "TL_PURGE". You can add your own purge jobs by adding them to the array.
 // $GLOBALS['TL_PURGE'] = array
 // (
 //    'job_1' => array
 //    (
 //       'tables' => array
 //       (
 //          'index' => array
 //          (
 //             'callback' => array('Automator', 'purgeSearchTables'),
 //             'affected' => array('tl_search', 'tl_search_index')
 //          ),
 //       )
 //   );
 // There are three categories: "tables" stores jobs which truncate database tables, "folders" stores jobs which purge folders and "custom" stores jobs which only trigger a callback function.


 // CRON JOBS
 // Cron jobs are stored in a global array called "TL_CRON". You can add your own cron jobs by adding them to the array.
 // $GLOBALS['TL_CRON'] = array
 // (
 //    'monthly' => array
 //    (
 //       array('Automator', 'purgeImageCache')
 //    ),
 //    'weekly'   => array(),
 //    'daily'    => array(),
 //    'hourly'   => array(),
 //    'minutely' => array()
 // );
 // Note that this is rather a command scheduler than a cron job, which does not guarantee an exact execution time. You can replace the command scheduler with a real cron job though.


 // HOOKS
 // Hooks are stored in a global array called "TL_HOOKS". You can register your own functions by adding them to the array.
 // $GLOBALS['TL_HOOKS'] = array
 // (
 //    'hook_1' => array
 //    (
 //       array('MyClass', 'myPostLogin'),
 //       array('MyClass', 'myPostLogout')
 //    )
 // );
 // Hooks allow you to add functionality to the core without having to modify the source code by registering callback functions to be executed on a particular event. For more information see https://contao.org/manual.html.


 // AUTO ITEMS
 // Auto items are stored in a global array called "TL_AUTO_ITEM". You can register your own auto items by adding them to the array.
 // $GLOBALS['TL_AUTO_ITEM'] = array('items', 'events');
 // Auto items are keywords, which are used as parameters by certain modules.
 // When rebuilding the search index URLs, Contao needs to know about these
 // keywords so it can handle them properly.
