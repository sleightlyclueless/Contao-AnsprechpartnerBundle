# README #

* Contao version: 4.8


## What is this bundle for? ##
### ENGLISH ###
Extension for contao giving it an interface for managing contact persons as well as departements they might belong to. Furthermore you can use a content element included in this extension to insert the contact persons into the articles of your contao website.

### DEUTSCH ###
Erweitert Contao um Backend Module zum Anlegen und Verwalten zentraler Mitarbeiter / Kontaktpersonen und separat dazu deren zugehörige Abteilungen. Diese Erweiterung bringt ebenfalls ein Inhaltselement zum Einfügen dieser Mitarbeiter mit unterschiedlichen Einfügemöglichkeiten in die Artikel von Contao.


## How do I get setup? ##
### Via Manager ###
1. Add bundle to your dependencies in the root composer.json into both the require and repositories object.
2. Run Composer Update
3. Refresh Database
4. Backup Navigation has been enhanced with a new navigation point to get to the interface of this extension


### Manual ###
1. Insert autoload configuration of the downloaded root composer.json (same level of the src folder: l. 39 - 49) of your contao installation. If there are already other extensions installed only add the contents of the classmap files -> Update dependencies
2. Upload repository content into /src/ixtensa/ to your installation, depending on if this folder already existed just dump the lower folders into it
3. Run Composer Update
4. Refresh Database
5. Backup Navigation has been enhanced with a new navigation point to get to the interface of this extension


## How does it work? ##
1. After the successful Contao Setup, you can navigate to 'Employee' in the Contao Backend modules navigation
2. First you should create and save some departements if you want to add those to your contact persons on the upper navigation you can get to the according interface on the contao typical buttons. When you then edit a contact person you can connect the departements to those persons – they may belong to one or even multiple departements. However you could also do that afterwards and disconnect / connect other departements to the contact person.
3. When you created some departements you need you can go back ('Zurück or click on the backend module in the left panel again') to get to the contact person management interface and create your first contact person. You do that by clicking on the according green plus icon as you would with the creation of other contao elements.
* Dont forget to publish them afterwards if you want to show them in the frontend!
4. After successful creation of a contact person you can insert them into an article as an content element 'Employee' and select a mode you wish to insert the contact person(s) with. Einzeln = Singular, Individuell = Individual, Abteilung = By departements, Alle = All.
5. Refresh Cache if needed and reload frontend – there you go, hopefully.


## Final words ##
I hope that this extension brings you more pleasure and comfort, and that everything goes as you expect and wish. Cheers!


## Change log ##
2020-03-09 Start development
