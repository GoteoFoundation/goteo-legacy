<?php
/*
 *  Copyright (C) 2012 Platoniq y Fundación Goteo (see README for details)
 *  This file is part of Goteo.
 *
 *  Goteo is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  Goteo is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with Goteo.  If not, see <http://www.gnu.org/licenses/agpl.txt>.
 *
 */
namespace Goteo\Controller\Admin {

    use Goteo\Core\View,
        Goteo\Core\Redirection,
        Goteo\Core\Error,
		Goteo\Library\Text,
        Goteo\Library\Message,
		Goteo\Library\Template,
        Goteo\Library\Newsletter as Boletin,
		Goteo\Library\Sender;

    class Newsletter {

        public static function process ($action = 'list', $id = null, $filters = array()) {

            switch ($action) {
                case 'init':
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                        // plantilla
                        $template = $_POST['template'];

                        // destinatarios
                        if ($_POST['test']) {
                            $users = Boletin::getTesters();
                        } elseif ($template == 33) {
                            // los destinatarios de newsletter
                            $users = Boletin::getReceivers();
                        } elseif ($template == 35) {
                            // los destinatarios para testear a subscriptores
                            $users = Boletin::getReceivers();
                        }

                        // sin idiomas
                        $nolang = $_POST['nolang'];
                        if ($nolang) {
                            $receivers[LANG] = $users;
                        } else {
                            // separamos destinatarios en idiomas
                            $receivers = array();
                            foreach ($users as $usr) {
                                if (empty($usr->lang) || $usr->lang == LANG) {
                                    $receivers[LANG][] = $usr;
                                } else {
                                    $receivers[$usr->lang][] = $usr;
                                }
                            }
                        }


                        // idiomas que vamos a enviar
                        $langs = array_keys($receivers);


                        // para cada idioma
                        foreach ($langs as $lang) {

                            // destinatarios
                            $recipients = $receivers[$lang];

                            // datos de la plantilla
                            $tpl = Template::get($template, $lang);

                            // contenido de newsletter
                            $content = ($template == 33) ? Boletin::getContent($tpl->text, $lang) : $content = $tpl->text;

                            // asunto
                            $subject = $tpl->title;

                            // creamos instancia
                            $sql = "INSERT INTO mail (id, email, html, template) VALUES ('', :email, :html, :template)";
                            $values = array (
                                ':email' => 'any',
                                ':html' => $content,
                                ':template' => $template
                            );
                            $query = \Goteo\Core\Model::query($sql, $values);
                            $mailId = \Goteo\Core\Model::insertId();

                            // inicializamos el envío
                            if (Sender::initiateSending($mailId, $subject, $recipients, true)) {
                                // ok...
                            } else {
                                Message::Error('No se ha podido iniciar el mailing con asunto "'.$subject.'"');
                            }
                        }

                    }

                    throw new Redirection('/admin/newsletter');

                    break;
                case 'activate':
                    if (Sender::activateSending($id)) {
                        Message::Info('Se ha activado un nuevo envío automático');
                    } else {
                        Message::Error('No se pudo activar el envío. Iniciar de nuevo');
                    }
                    throw new Redirection('/admin/newsletter');
                    break;
                case 'detail':

                    $mailing = Sender::getSending($id);
                    $list = Sender::getDetail($id, $filters['show']);

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'newsletter',
                            'file' => 'detail',
                            'detail' => $filters['show'],
                            'mailing' => $mailing,
                            'list' => $list
                        )
                    );
                    break;
                default:
                    $list = Sender::getMailings();

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'newsletter',
                            'file' => 'list',
                            'list' => $list
                        )
                    );
            }

        }
    }

}
