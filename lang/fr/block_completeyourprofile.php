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
 * A simple block that encourages users to complete their profile
 *
 * Checks if all required "profile fields" (admin > users > accouts > profile fields)
 * are filled for the current user; if not, suggests him/her to take a few minutes
 * to complete his/her profile
 *
 * English and french versions included / versions anglaise et française incluses.
 *
 * @package    block_completeyourprofile
 * @category   blocks
 * @copyright  2016 Mathias Chouet, Tela Botanica
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['completeyourprofile:addinstance'] = 'Ajouter un bloc Complétez votre profil';
$string['completeyourprofile:myaddinstance'] = 'Ajouter un bloc Complétez votre profil à votre Tableau de bord';
$string['pluginname'] = 'Bloc Complétez votre profil';
$string['block_completeyourprofile_title'] = 'Complétez votre profil';
$string['complete_your_profile'] = "Merci de prendre quelques instants pour compléter votre profil";
$string['edit_profile'] = "Allons-y !";
$string['consider_empty_as_null'] = "Traiter vide comme NULL";
$string['consider_empty_as_null_desc'] = "Considérer que le champ n'est pas rempli si la valeur est vide ('')";
$string['consider_required_fields_only'] = "Champs requis seulement";
$string['consider_required_fields_only_desc'] = "Ne prendre en compte que les champs marqués comme requis : si un champ non marqué comme requis n'est pas rempli, le profil sera tout de même considéré comme complet";
$string['config_block_text'] = "Texte du bloc";
$string['config_block_text_desc'] = "Changer le texte du bloc (par défaut: \"Merci de prendre quelques instants pour compléter votre profil\")";
$string['config_button_text'] = "Texte du bouton";
$string['config_button_text_desc'] = "Changer le texte du bouton (par défaut: \"Allons-y !\")";