<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'teams_meeting', language 'fr'
 *
 * @package   mod_teams_meeting
 * @copyright 2022 Anthony Durif, Université Clermont Auvergne
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['modulename'] = 'Réunion Teams';
$string['modulename_help'] = 'Module permettant de créer une réunion Teams pour votre cours et d\'afficher le lien vers celle-ci.';
$string['modulenameplural'] = 'Réunion Teams ';
$string['pluginname'] = 'Réunion Teams';
$string['pluginadministration'] = 'Réunion Teams';
$string['teams:addinstance'] = 'Ajouter une réunion Teams';
$string['teams:view'] = 'Visualiser une réunion Teams';
$string['privacy:metadata'] = 'Le module Réunion Teams ne stocke aucune donnée personnelle.';
$string['notunique'] = 'L\'adresse email n\'est pas unique dans l\'Active Directory Azure.';
$string['notfound'] = 'L\'adresse email n\'a pas pu être reliée à un utilisateur dans l\'Active Directory Azure.';

$string['name'] = 'Nom de la réunion Teams';
$string['name_help'] = 'Nom de la réunion Teams qui sera affiché sur la page de votre cours.';
$string['desc'] = '<div class="alert alert-info">La création d\'une équipe Teams depuis la plateforme Moodle est une action qui peut prendre quelques secondes.<br/>
                            Il est également possible que vous n\'ayez pas accès de suite à la team que vous avez créé et que cette mise à jour d\'accès prenne quelques minutes. Merci de votre compréhension.</div>';
$string['noaccount'] = '<p>Il semblerait que vous n\'ayez pas encore de compte microsoft enregistré, vous ne pouvez donc pour l\'instant pas créer de team.</p>
                                <p>Une fois votre compte créé vous pourrez ensuite revenir pour créer votre team depuis moodle.</p>';
$string['teamserror'] = '<p>Il semblerait qu\'une erreur se soit produite lors de votre tentative de création de la team: "{$a}".</p>
                                <p>Retentez l\'opération, si le souci persiste, contactez le support.</p>';
$string['back'] = 'Revenir au cours';

$string['opendate'] = 'Début de la réunion';
$string['opendate_help'] = 'Date à partir de laquelle la réunion sera disponible. Si cette option n\'est pas activée la réunion sera disponible dès sa création.';
$string['opendate_session'] = ' (Début réunion Teams)';
$string['closedate'] = 'Fin de la réunion';
$string['closedate_help'] = 'Date à partir de laquelle la ressource ne plus sera disponible. Si cette option n\'est pas activée la réunion restera disponible sans autre action d\'un des organisateurs.';
$string['closedate_session'] = ' (Fin réunion Teams)';
$string['dates_help'] = '<div class="alert alert-info"><strong>Attention, la réunion créée depuis cette interface ne sera pas accessible depuis le Calendrier Teams. Vous ne pourrez y accéder que depuis Moodle. Les étudiants ne recevront pas de notification de cette réunion sur leur messagerie.</strong>';
$string['dates_help'] = '<div class="alert alert-info"><strong>Attention, les étudiants et autres utilisateurs ne recevront pas de notification mail pour la participation à cette réunion.</strong>
                            <ul><li>Réunion ponctuelle: <ul><li>Elle est visible uniquement dans le calendrier Teams de son créateur. Les étudiants voient la réunion ponctuelle depuis la section de cours où elle a été créée, depuis le bloc "Evénements à venir" et le bloc Calendrier de Moodle (pensez à rajouter ces blocs dans le cours, si besoin)</li>
                            <li>Si vous n’activez pas les dates de début ou fin de réunion, un créneau sera attribué par défaut (à partir de l’heure de création de la réunion à laquelle on ajoutera la durée de réunion par défaut configurée dans l\'administration moodle). Des tests seront effectués au moment de l\'accès à l\'activité depuis le cours pour vérifier la disponibilité de celle-ci par rapport à cette période et si vous devez donc être redirigés ou non vers la réunion.</li></ul></li>
                            <li>Réunion permanente: Elle est visible et disponible uniquement depuis la section de cours où elle est ajoutée et utilisable dès sa création.</li></ul>
                            <p>Important: Tous les changements concernant les équipes et les réunions classe virtuelle réalisés directement depuis Teams (changement du nom, des dates de la réunion...) ne se répercuteront pas dans Moodle.</p>';
$string['dates_between'] = 'entre le %s et le %s';
$string['dates_from'] = 'à partir du %s';
$string['dates_until'] = 'jusqu\'au %s';
$string['error_dates'] = 'La date de fin doit être postérieure que la date d\'ouverture.';
$string['error_dates_past'] = 'La période définie ne pas correspondre à une période déjà passée.';
$string['meetingnotfound'] = 'L\'accès à cette réunion semble impossible. Un problème est peut-être survenu sur le serveur Microsoft, auquel cas retentez de vous connecter un peu plus tard. Il est également possible que cette réunion ait été supprimée par un des organisateurs.';
$string['meetingnotavailable'] = 'L\'accès à cette réunion (classe virtuelle) n\'est actuellement pas disponible.%s En cas de difficultés, contactez directement votre enseignant(e).';
$string['description'] = 'Equipe créée dans le cadre du cours "%s".';
$string['meetingavailable'] = ' La réunion Teams est disponible %s.';
$string['copy_link'] = 'Copier le lien dans le presse-papier';
$string['create_mail_content'] = 'Bonjour,\nVous venez de créer la réunion Teams "%s" depuis votre cours moodle "%s".\nRetrouvez celle-ci en cliquant sur le lien ci-après : ';
$string['create_mail_title'] = 'Création de votre réunion Teams';
$string['messageprovider:meetingconfirm'] = 'Confirmation de la création de réunion Teams';
$string['notif_mail'] = 'Notification de création de réunion';
$string['notif_mail_help'] = 'Envoyer une notification suite à la création d\'une réunion avec le lien vers celle-ci.';

$string['reuse_meeting'] = 'Utilisation ?';
$string['reuse_meeting_help'] = 'Type d\'utilisation de la réunion:
                                <ul><li>Permanente: le lien de la réunion généré sera accessible pour les inscrits à votre cours dès sa création (hors restriction d\'accès moodle).</li>
                                <li>Ponctuelle: la réunion est directement accessible à son créateur. Pour les autres utilisateurs moodle fera un contrôle par rapport à la période d\'ouverture définie avant de faire la redirection vers la réunion.</li></ul>';
$string['reuse_meeting_no'] = 'Ponctuelle';
$string['reuse_meeting_yes'] = 'Permanente';
$string['meeting_default_duration'] = 'Durée par défaut de la réunion si une date de fin n\'est pas renseignée';
$string['meeting_default_duration_help'] = 'Permet de renseigner une durée par défaut pour les réunions crées via le module si une date de fin n\'est pas renseignée. La date de fin par défaut sera calculée par rapport à la date de début rénseignée et en ajoutant cette durée.';

$string['gotoresource'] = 'Accéder à la ressource Teams';
$string['title_courseurl'] = 'Revenir au cours';
