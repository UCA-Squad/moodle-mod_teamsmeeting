Module d'activité Réunion Teams
==================================
Module permettant de créer une réunion (ou classe virtuelle) Teams depuis un cours moodle.

Objectifs
------------
Les objectifs de ce module étaient de pouvoir créer une réunion en ligne Teams à partir d'un cours moodle, d'accéder à celle-ci depuis le cours. 

Pré-requis
------------
- Moodle en version 3.7 ou plus récente.<br/>
-> Tests effectués sur des versions 3.7 à 3.11.0 (des tests sur des versions antérieures n'ont pas encore été effectués).<br/>
-> Tests sur la version 4 en cours.<br/>
- Composer installé sur votre machine/serveur.
- Avoir créé une application sur l'Active Directory Azure (ou avoir les droits nécessaires pour en créer une).

Création application Active Directory Azure
------------
- Tutorial: <a href="https://docs.microsoft.com/en-us/azure/active-directory/reports-monitoring/howto-configure-prerequisites-for-reporting-api" target="_blank">https://docs.microsoft.com/en-us/azure/active-directory/reports-monitoring/howto-configure-prerequisites-for-reporting-api</a> <br/>
Les informations Application (client) ID, Directory (tenant) ID et Object ID seront à utiliser dans la configuration du plugin moodle. 


Installation
------------
1. Installation du plugin

- Avec git:
> git clone https://github.com/UCA-Squad/moodle-mod_teamsmeeting.git mod/teamsmeeting

- Téléchargement:
> Télécharger le zip depuis <a href="https://github.com/UCA-Squad/moodle-mod_teamsmeeting/archive/refs/heads/main.zip" target="_blank">https://github.com/UCA-Squad/moodle-mod_teamsmeeting/archive/refs/heads/main.zip </a>, dézipper l'archive dans le dossier mod/ et renommer le si besoin en "teamsmeeting" ou installez-le depuis la page d'installation des plugins si vous possédez les droits suffisants.

2. Récupérer les librairies Microsoft Graph (https://packagist.org/packages/microsoft/microsoft-graph) utilisées dans le plugin. Pour cela placez-vous dans le dossier teams/ nouvellement créé et lancez la commande ```composer install```.<br/>
Vous pouvez également récupérer les versions les plus récentes de ces librairies en utilisant ```composer update```.
  
3. Aller sur la page de notifications pour finaliser l'installation du plugin.

4. Une fois l'installation terminée, plusieurs options d'administration seront à renseigner:

> Administration du site -> Plugins -> Modules d'activités -> Réunion Teams -> client_id<br/>
> Administration du site -> Plugins -> Modules d'activités -> Réunion Teams -> tenant_id<br/>
> Administration du site -> Plugins -> Modules d'activités -> Réunion Teams -> client_secret

Paramètres liés à l'application créée précédemment dans l'Active Directory Azure pour communiquer avec Teams.

> Administration du site -> Plugins -> Modules d'activités -> Réunion Teams -> notif_mail

Checkbox permettant d'indiquer si l'on souhaite qu'une notification soit envoyée à l'utilisateur après la création d'une réunion avec le lien direct vers celle-ci.

> Administration du site -> Plugins -> Modules d'activités -> Réunion Teams -> meeting_default_duration
 
Permet de choisir dans la liste pré-remplie la durée par défaut d'une réunion. Cette valeur est notamment utilisée lorsqu'une date de fin n'est pas renseignée via le formulaire. La date de fin sera alors calculée en ajoutant cette durée à la date de début de la réunion.


Présentation / Fonctionnalités
------------
- Création d'une réunion soit "permanente" soit "ponctuelle":
  - une réunion permanente ne requiert aucune information de date et sera accessible dès la création de celle-ci.
  - une réunion ponctuelle est définie sur un créneau. Elle reste accessible directement dès sa création via le lien direct ou via Teams mais un test effectué par rapport au créneau choisi si l'accès se fait via Moodle.
- Possibilité de fixer des dates de début et de fin pour la réunion qui remonteront au niveau du calendrier Moodle et du bloc "Evénements à venir" ainsi que dans le calendrier Teams.
- Possibilité de modifier les dates d'une réunion ponctuelle.
- Envoi possible d'une notification à la création de la réunion avec le lien direct vers cette réunion.

<p>Note: il ne sera pas possible de restaurer une réunion Teams. Si celle-ci est supprimée elle ne se retrouvera pas dans la corbeille du cours.<br/>

Pistes d'améliorations
-----
- Ajout d'options supplémentaires (si possible via l'API). Ex: Salle d'attente, Qui peut présenter...
- Prise en compte du préfixe dans l'édition inline du nom de l'activité.
- Restauration des réunions supprimées dans Moodle (si elles existent encore côté Teams).
<p>N'hésitez pas à nous proposer des améliorations et/ou des développements/pull requests pour enrichir le plugin.</p>  

A propos
------
<a href="https://www.uca.fr">Université Clermont Auvergne</a> - 2022.<br/>
