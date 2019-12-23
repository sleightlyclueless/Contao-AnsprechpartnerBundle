# Contao-AnsprechpartnerBundle
Erweitert Contao um sowohl zwei Backend Module zum Anlegen und verwalten zentraler Ansprechpartner / Kontaktpersonen und Abteilungen, als auch ein Inhaltselement zum Einfügen dieser Ansprechpartner mit unterschiedlichen Einstellungsmöglichkeiten in die Artikel für das Frontend von Contao
* Version: 1.0
* Contao version: 4


### What is this bundle for? ###

* DE: Erweitert Contao um sowohl zwei Backend Module zum Anlegen und verwalten zentraler Ansprechpartner und Kontaktpersonen mit diversen zugehörigen Kontaktdaten, sowie eine separate Verwaltungsoberfläche zu deren zugehörigen Abteilungen, als auch um ein Inhaltselement zum Einfügen dieser Ansprechpartner mit unterschiedlichen Einfügemöglichkeiten in die Artikel für das Frontend von Contao.
* EN: Extension for contao to add contact person management where you, in two separate backend modules, add various contact persions with their contact data as well as their departements. Then via a content element for the frontend of your contao website, display those contact persons and departements within article elements this extension also adds adds


### How do I get setup? ###

1. Insert autoload configuration of the downloaded root composer.json (same level of the src folder: l. 39 - 49) into the root composer.json of your contao installation. If there are already other extensions installed only add the contents of the classmap files -> Update dependencies
2. Add src folder (or only its content folders) to your installation, depending on if this folder already existed
3. Do a composer update, something like so: '# /path/to/php -d memory_limit=-1 /path/to/composer.phar install'
4. Refresh the database via contao install tool
5. All set for the backend!


### How does it work? ###

1. After the successful Contao Setup, you can navigate to 'Ansprechpartner' in the Contao Backend modules
2. First you should create and save some departements  by clicking on 'Abteilungen verwalten' up top, because otherwise you wont be able to save the contact person – we designed on a way to have them savely belong to one or more departements.
3. When you created some departements you need you can go back ('Zurück or click on the backend module in the left panel again') to the contact person management and create your first (and second and so on) contact person.
* Dont forget to publish them if you want to show them in the frontend!
4. Now you can insert into an article the element 'Ansprechpartner' and select a mode you wish to insert the contact person(s) with. Einzeln = Singular, Individuell = Individual, Abteilung = By departements, Alle = All.
5. Refresh Cache if needed and reload frontend – there you go.


### Final words ###
If you happen to use this extension I wish you that it brings you more joy and comfort than errors and hope you have a nice day and that everything will go as you expect and wish it to. Cheers!

### Change log ###

2019-11-08 start development
