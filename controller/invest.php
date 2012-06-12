<?php
/*
 *  Copyright (C) 2012 Platoniq y Fundación Fuentes Abiertas (see README for details)
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

    use Goteo\Core\ACL,
        Goteo\Core\Error,
        Goteo\Core\Redirection,
        Goteo\Model,
		Goteo\Library\Feed,
		Goteo\Library\Text,
        Goteo\Library\Mail,
        Goteo\Library\Template,
        Goteo\Library\Message,
        Goteo\Library\Paypal,
        Goteo\Library\Tpv;

    class Invest extends \Goteo\Core\Controller {

        /*
         *  La manera de obtener el id del usuario validado cambiará al tener la session
         */
        public function index ($project = null) {
            if (empty($project))
                throw new Redirection('/discover', Redirection::TEMPORARY);

            $message = '';

            $projectData = Model\Project::get($project);
            $methods = Model\Invest::methods();

            // si no está en campaña no pueden esta qui ni de coña
            if ($projectData->status != 3) {
                throw new Redirection('/project/'.$project, Redirection::TEMPORARY);
            }

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $errors = array();
                $los_datos = $_POST;

                if (empty($_POST['amount'])) {
                    Message::Error(Text::get('invest-amount-error'));
                    throw new Redirection("/project/$project/invest/?confirm=fail", Redirection::TEMPORARY);
                }

                // dirección de envio para las recompensas
                // o datoas fiscales del donativo
                $address = array(
                    'name'     => $_POST['fullname'],
                    'nif'      => $_POST['nif'],
                    'address'  => $_POST['address'],
                    'zipcode'  => $_POST['zipcode'],
                    'location' => $_POST['location'],
                    'country'  => $_POST['country']
                );

                if ($projectData->owner == $_SESSION['user']->id) {
                    Message::Error(Text::get('invest-owner-error'));
                    throw new Redirection("/project/$project/invest/?confirm=fail", Redirection::TEMPORARY);
                }

                // añadir recompensas que ha elegido
                
                $rewards = array();
                if (isset($_POST['resign']) && $_POST['resign'] == 1) {
                    // renuncia a las recompensas, bien por el/ella
                } else {
                    foreach ($_POST as $key=>$value) {
                        if (substr($key, 0, strlen('reward_')) == 'reward_') {

                            $id = \str_replace('reward_', '', $key);

                            //no darle las recompensas que no entren en el rango del aporte por mucho que vengan marcadas
                            if ($projectData->individual_rewards[$id]->amount <= $_POST['amount']) {
                                $rewards[] = $id;
                            }
                        }
                    }
                }

                // insertamos los datos personales del usuario si no tiene registro aun
                Model\User::setPersonal($_SESSION['user']->id, $address, false);

                $invest = new Model\Invest(
                    array(
                        'amount' => $_POST['amount'],
                        'user' => $_SESSION['user']->id,
                        'project' => $project,
                        'method' => $_POST['method'],
                        'status' => '-1',               // aporte en proceso
                        'invested' => date('Y-m-d'),
                        'anonymous' => $_POST['anonymous'],
                        'resign' => $_POST['resign']
                    )
                );
                $invest->rewards = $rewards;
                $invest->address = (object) $address;

                if ($invest->save($errors)) {

                    switch($_POST['method']) {
                        case 'tpv':
                            // redireccion al tpv
                            if (Tpv::preapproval($invest, $errors)) {
                                die;
                            } else {
                                Message::Error(Text::get('invest-tpv-error_fatal'));
                            }
                            break;
                        case 'paypal':
                            // Petición de preapproval y redirección a paypal
                            if (Paypal::preapproval($invest, $errors)) {
                                die;
                            } else {
                                Message::Error(Text::get('invest-paypal-error_fatal'));
                            }
                            break;
                        case 'cash':
                            $invest->setStatus('0');
                            // En betatest aceptamos cash para pruebas
                            throw new Redirection("/invest/confirmed/{$project}/{$invest->id}");
                            break;
                    }
                } else {
                    Message::Error(Text::get('invest-create-error'));
                }
			} else {
                Message::Error(Text::get('invest-data-error'));
            }

            throw new Redirection("/project/$project/invest/?confirm=fail");
            //throw new Redirection("/project/$project/invest");
        }


        public function confirmed ($project = null, $invest = null) {
            if (empty($project) || empty($invest)) {
                Message::Error(Text::get('invest-data-error'));
                throw new Redirection('/discover', Redirection::TEMPORARY);
            }

            $confirm = Model\Invest::get($invest);
            $projectData = Model\Project::getMini($project);

            // email de agradecimiento al cofinanciador
            // primero monto el texto de recompensas
            if ($confirm->resign) {
                $txt_rewards = Text::get('invest-resign');
                $template = Template::get(28); // plantilla de donativo
            } else {
                $rewards = $confirm->rewards;
                array_walk($rewards, function (&$reward) { $reward = $reward->reward; });
                $txt_rewards = implode(', ', $rewards);
                $template = Template::get(10); // plantilla de agradecimiento
            }

            // Agradecimiento al cofinanciador
            // Sustituimos los datos
            $subject = str_replace('%PROJECTNAME%', $projectData->name, $template->title);

            // En el contenido:
            $search  = array('%USERNAME%', '%PROJECTNAME%', '%PROJECTURL%', '%AMOUNT%', '%REWARDS%');
            $replace = array($_SESSION['user']->name, $projectData->name, SITE_URL.'/project/'.$projectData->id, $confirm->amount, $txt_rewards);
            $content = \str_replace($search, $replace, $template->text);

            $mailHandler = new Mail();

            $mailHandler->to = $_SESSION['user']->email;
            $mailHandler->toName = $_SESSION['user']->name;
            $mailHandler->subject = $subject;
            $mailHandler->content = $content;
            $mailHandler->html = true;
            $mailHandler->template = $template->id;
            if ($mailHandler->send($errors)) {
                Message::Info(Text::get('project-invest-thanks_mail-success'));
            } else {
                Message::Error(Text::get('project-invest-thanks_mail-fail'));
                Message::Error(implode('<br />', $errors));
            }

            unset($mailHandler);
            

            // Notificación al autor
            $template = Template::get(29);
            // Sustituimos los datos
            $subject = str_replace('%PROJECTNAME%', $projectData->name, $template->title);

            // En el contenido:
            $search  = array('%OWNERNAME%', '%USERNAME%', '%PROJECTNAME%', '%SITEURL%', '%AMOUNT%', '%MESSAGEURL%');
            $replace = array($projectData->user->name, $_SESSION['user']->name, $projectData->name, SITE_URL, $confirm->amount, SITE_URL.'/user/profile/'.$_SESSION['user']->id.'/message');
            $content = \str_replace($search, $replace, $template->text);

            $mailHandler = new Mail();

            $mailHandler->to = $projectData->user->email;
            $mailHandler->toName = $projectData->user->name;
            $mailHandler->subject = $subject;
            $mailHandler->content = $content;
            $mailHandler->html = true;
            $mailHandler->template = $template->id;
            $mailHandler->send();

            unset($mailHandler);





            if ($confirm->method == 'paypal') {

                // hay que cambiarle el status a 0
                $confirm->setStatus('0');

                /*
                 * Evento Feed
                 */
                $log = new Feed();
                $log->title = 'Aporte PayPal';
                $log->url = '/admin/invests';
                $log->type = 'money';
                $log_text = "%s ha aportado %s al proyecto %s mediante PayPal";
                $items = array(
                    Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                    Feed::item('money', $confirm->amount.' &euro;'),
                    Feed::item('project', $projectData->name, $projectData->id)
                );
                $log->html = \vsprintf($log_text, $items);
                $log->add($errors);

                    // evento público
                if ($confirm->anonymous) {
                    $log->title = Text::get('regular-anonymous');
                    $log->url = '/user/profile/anonymous';
                    $log->image = 1;
                } else {
                    $log->title = $_SESSION['user']->name;
                    $log->url = '/user/profile/'.$_SESSION['user']->id;
                    $log->image = $_SESSION['user']->avatar->id;
                }
                $log->scope = 'public';
                $log->type = 'community';
                $log->html = Text::html('feed-invest',
                                    Feed::item('money', $confirm->amount.' &euro;'),
                                    Feed::item('project', $projectData->name, $projectData->id));
                $log->add($errors);

                unset($log);
            }

            // mandarlo a la pagina de gracias
            throw new Redirection("/project/$project/invest/?confirm=ok", Redirection::TEMPORARY);
        }

        /*
         * @params project id del proyecto
         * @params is id del aporte
         */
        public function fail ($project = null, $id = null) {
            if (empty($project))
                throw new Redirection('/discover', Redirection::TEMPORARY);

            if (empty($id))
                throw new Redirection("/project/$project/invest", Redirection::TEMPORARY);

            // quitar el preapproval y cancelar el aporte
            $invest = Model\Invest::get($id);
            $invest->cancel();

            // mandarlo a la pagina de aportar para que lo intente de nuevo
            throw new Redirection("/project/$project/invest/?confirm=fail", Redirection::TEMPORARY);
        }

        // resultado del cargo
        public function charge ($result = null, $id = null) {
            if (empty($id) || !\in_array($result, array('fail', 'success'))) {
                die;
            }
            // de cualquier manera no hacemos nada porque esto no lo ve ningun usuario
            die;
        }


    }

}