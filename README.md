Mod Teams Meeting
==================================
This moodle mod creates and displays a Teams online meeting (or virtual classroom) from a Moodle course.

Goals
------------
Goals of this plugin were to create a Teams online meeting from a Moodle course, to access to it.

Requirements
------------
- Moodle 3.7 or later.<br/>
-> Tests on Moodle 3.7 to 3.11.0 (tests on older moodle versions not made yet).<br/>
-> Tests on Moodle 4.x in progress<br/>
- Composer on your computer/server
- Have an Azure Active Directory web application registred (or rights to create one).

Create Azure Active Directory web application
------------
- Tutorial: <a href="https://docs.microsoft.com/en-us/azure/active-directory/reports-monitoring/howto-configure-prerequisites-for-reporting-api" target="_blank">https://docs.microsoft.com/en-us/azure/active-directory/reports-monitoring/howto-configure-prerequisites-for-reporting-api</a> <br/>
Application (client) ID, Directory (tenant) ID and Object ID will be needed in the moodle plugin configuration.

Installation
------------
1. Local plugin installation

- With git:
> git clone https://github.com/UCA-Squad/moodle-mod_teamsmeeting.git mod/teamsmeeting

- Download way:
> Download the zip from <a href="https://github.com/UCA-Squad/moodle-mod_teamsmeeting/archive/refs/heads/main.zip" target="_blank">https://github.com/UCA-Squad/moodle-mod_teamsmeeting/archive/refs/heads/main.zip </a>, unzip it in mod/ folder and rename it "teamsmeeting" if necessary or install it from the "Install plugin" page if you have the right permissions.
 
2. Get Microsoft Graph libs (https://packagist.org/packages/microsoft/microsoft-graph) used in the plugin. Go to the new teams/ folder and use the command ```composer install```.<br/>
You can also get the latest libs versions by using ```composer update```. 
  
3. Then visit your Admin Notifications page to complete the installation.

4. Once installed, you should see new administration options:

> Site administration -> Plugins -> Activity modules -> Teams meeting -> client_id<br/>
> Site administration -> Plugins -> Activity modules -> Teams meeting -> tenant_id<br/>
> Site administration -> Plugins -> Activity modules -> Teams meeting -> client_secret

Parameters from the Azure Active Directory web application created previously and use to communicate with Teams.

> Site administration -> Plugins -> Activity modules -> Teams meeting -> notif_mail

If checked a notification will be send to the user after an online meeting creation with a direct link to this meeting.

> Site administration -> Plugins -> Activity modules -> Teams meeting -> meeting_default_duration

Parameter to choose in the given list the default meeting duration. This value will be used if the closedate of the meeting is empty in the form. This closedate will be deducted from the startdate and this selected duration.


Présentation / Features
------------
- Create a "permanent" or a "one shot" online meeting:
  - a permanent meeting does not need any informations about dates and will be accessible since its creation.
  - a one shot meeting is defined on a specific time slot. It will be accessible since its creation with direct url or in Teams but tests on dates will be made on Moodle to redirect or not to the meeting.
- Fix start date and end date for a meeting. These dates will be visible on the Moodle calendar, the "Upcoming events" block and on your Teams calendar.
- Possible editing of the dates for a one shot meeting.
- Possible sending of a notification after the meeting creation with the direct link to this meeting.

<p>Note: it won't be possible to restore a Teams meeting. If this has been deleted it won't be in the course recycle bin.</p>

Possible improvements
-----
- Add more options (if possible with the API). Ex: Waiting lobby, Who can present...
- Add admin setting to select resource types it will be possible to add with the plugin. 
- Use the prefix when we edit inline the resource name. 
- Restore deleted meetings (if they still exist in Teams).
<p>Feel free to propose some improvements and/or developments/pull requests to improve this plugin.</p>  

About us
------
<a href="https://www.uca.fr">Université Clermont Auvergne</a> - 2022.<br/>
