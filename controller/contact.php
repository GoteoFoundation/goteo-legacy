<?php
/*
 *  Copyright (C) 2012 Platoniq y Fundación Goteo (see README for details)
 *	This file is part of Goteo.
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

namespace Goteo\Controller {

    use Goteo\Library\Page,
        Goteo\Core\Error,
        Goteo\Core\Redirection,
        Goteo\Core\View,
        Goteo\Library\Text,
        Goteo\Library\Message,
        Goteo\Library\Mail,
        Goteo\Library\Template;

    class Contact extends \Goteo\Core\Controller {
        
        public function index () {

            $page = Page::get('contact');

                $errors = array();

                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send'])) {

                // verificamos referer
                $URL = (NODE_ID != GOTEO_NODE) ? NODE_URL : SITE_URL;
                $referer = $URL.'/contact';
                
                // verificamos token
                if (!isset($_POST['msg_token']) || $_POST['msg_token']!=$_SESSION['msg_token'] || $_SERVER['HTTP_REFERER']!=$referer) {
                    header("HTTP/1.1 400 Bad request");
                    die('Token incorrect');
                }

                $name = $_POST['name'];

                    // si falta mensaje, email o asunto, error
                    if(empty($_POST['email'])) {
                        $errors['email'] = Text::get('error-contact-email-empty');
                    } elseif(!\Goteo\Library\Check::mail($_POST['email'])) {
                        $errors['email'] = Text::get('error-contact-email-invalid');
                    } else {
                        $email = $_POST['email'];
                    }

                    if(empty($_POST['subject'])) {
                        $errors['subject'] = Text::get('error-contact-subject-empty');
                    } else {
                        $subject = $_POST['subject'];
                    }

                    if(empty($_POST['message'])) {
                        $errors['message'] = Text::get('error-contact-message-empty');
                    } else {
                    $msg_content = nl2br(\strip_tags($_POST['message']));
                    }

                // verificamos el captcha
                require 'library/recaptchalib.php';
                $resp = recaptcha_check_answer (RECAPTCHA_PRIVATE_KEY, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response"]);
                if (!$resp->is_valid) {
                    $errors['recaptcha'] = Text::get('error-contact-captcha');
                  }                
                
                $data = array(
                        'subject' => $_POST['subject'],
                        'name'    => $_POST['name'],
                        'email'   => $_POST['email'],
                        'message' => $_POST['message']
                );
                
                if (empty($errors)) {

                // Obtenemos la plantilla para asunto y contenido
                $template = Template::get(1);

                // Sustituimos los datos
                $subject = str_replace('%SUBJECT%', $subject, $template->title);
                        $to = \GOTEO_CONTACT_MAIL;
                        $toName = \GOTEO_MAIL_NAME;

                    // En el contenido:
                    $search  = array('%TONAME%', '%MESSAGE%', '%USEREMAIL%');
                    $replace = array($toName, $msg_content, $email);
                    $content = \str_replace($search, $replace, $template->text);


                    $mailHandler = new Mail();

                    $mailHandler->to = $to;
                    $mailHandler->toName = $toName;
                    $mailHandler->subject = $subject;
                    $mailHandler->content = $content;
                    $mailHandler->reply = $email;
                    $mailHandler->html = true;
                    $mailHandler->template = $template->id;
                    if ($mailHandler->send($errors)) {
                        Message::Info('Mensaje de contacto enviado correctamente.');
                        $data = array();
                    } else {
                        Message::Error('Ha fallado al enviar el mensaje.');
                    }

                    unset($mailHandler);
                }

            }

            return new View(
                'view/about/contact.html.php',
                array(
                    'data'    => $data,
                    'errors'  => $errors
                )
            );

        }
        
    }
    
}