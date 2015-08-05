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

        // metodos habilitados
        public static function _methods() {
             return array(
                    'cash' => 'cash',
                    'tpv' => 'tpv',
                    'paypal' => 'paypal'
                );
        }

        /*
         *  Este controlador no sirve ninguna página
         */
        public function index ($project = null) {
            if (empty($project))
                throw new Redirection('/discover', Redirection::TEMPORARY);

            $message = '';

            $projectData = Model\Project::get($project);
            $methods = static::_methods();

            // si no está en campaña no pueden esta qui ni de coña
            if ($projectData->status != 3) {
                throw new Redirection('/project/'.$project, Redirection::TEMPORARY);
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $errors = array();
                $los_datos = $_POST;
                $method = \strtolower($_POST['method']);

                if (!isset($methods[$method])) {
                    Message::Error(Text::get('invest-method-error'));
                    throw new Redirection(SEC_URL."/project/$project/invest/?confirm=fail", Redirection::TEMPORARY);
                }

                if (empty($_POST['amount'])) {
                    Message::Error(Text::get('invest-amount-error'));
                    throw new Redirection(SEC_URL."/project/$project/invest/?confirm=fail", Redirection::TEMPORARY);
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
                    throw new Redirection(SEC_URL."/project/$project/invest/?confirm=fail", Redirection::TEMPORARY);
                }

                // añadir recompensas que ha elegido
                $chosen = $_POST['selected_reward'];
                if ($chosen == 0) {
                    // renuncia a las recompensas, bien por el/ella
                    $resign = true;
                    $reward = false;
                } else {
                    // ya no se aplica esto de recompensa es de tipo Reconocimiento para donativo
                    $resign = false;
                    $reward = true;
                }

                // insertamos los datos personales del usuario si no tiene registro aun
                Model\User::setPersonal($_SESSION['user']->id, $address, false);

                $invest = new Model\Invest(
                    array(
                        'amount' => $_POST['amount'],
                        'user' => $_SESSION['user']->id,
                        'project' => $project,
                        'method' => $method,
                        'status' => '-1',               // aporte en proceso
                        'invested' => date('Y-m-d'),
                        'anonymous' => $_POST['anonymous'],
                        'resign' => $resign
                    )
                );
                if ($reward) {
                    $invest->rewards = array($chosen);
                }
                $invest->address = (object) $address;

                if ($invest->save($errors)) {
                    $invest->urlOK  = SEC_URL."/invest/confirmed/{$project}/{$invest->id}";
                    $invest->urlNOK = SEC_URL."/invest/fail/{$project}/{$invest->id}";
                    Model\Invest::setDetail($invest->id, 'init', 'Se ha creado el registro de aporte, el usuario ha clickado el boton de tpv o paypal. Proceso controller/invest');

                    switch($method) {
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
                            // En betatest aceptamos cash para pruebas
                            if (GOTEO_ENV != 'real') {
                                $invest->setStatus('1');
                                throw new Redirection($invest->urlOK);
                            } else {
                                throw new Redirection('/');
                            }
                            break;
                    }
                } else {
                    Message::Error(Text::get('invest-create-error'));
                }
			} else {
                Message::Error(Text::get('invest-data-error'));
            }

            throw new Redirection("/project/$project/invest/?confirm=fail");
        }

        /* para atender url de confirmación de aporte
         * @params project id del proyecto ('bazargoteo' para hacerlo volver al catálogo)
         * @params id id del aporte
         * @params reward recompensa que selecciona
         */
        public function confirmed ($project = null, $id = null, $reward = null) {
            if (empty($id)) {
                Message::Error(Text::get('invest-data-error'));
                throw new Redirection('/', Redirection::TEMPORARY);
            }

            // el aporte
            $invest = Model\Invest::get($id);

            $projectData = Model\Project::getMedium($invest->project);


            // para evitar las duplicaciones de feed y email
            if (isset($_SESSION['invest_'.$invest->id.'_completed'])) {
                Message::Info(Text::get('invest-process-completed'));
                throw new Redirection($retUrl);
            }


            // segun método

            if ($invest->method == 'tpv') {
                // si el aporte no está en estado "cobrado por goteo" (1) 
                if ($invest->status != '1') {
                    @mail('goteo_fail@doukeshi.org',
                        'Aporte tpv no pagado ' . $invest->id,
                        'Ha llegado a invest/confirm el aporte '.$invest->id.' mediante tpv sin estado cobrado (llega con estado '.$invest->status.')');
                    // mandarlo a la pagina de aportar para que lo intente de nuevo
                    // si es de Bazar, a la del producto del catálogo
                    if ($project == 'bazargoteo')
                        throw new Redirection("/bazaar/{$reward}/fail");
                    else
                        throw new Redirection("/project/{$invest->project}/invest/?confirm=fail");
                }
            }

            // Paypal solo disponible si activado
            if ($invest->method == 'paypal') {

                // hay que cambiarle el status a 0
                $invest->setStatus('0');

                // Evento Feed
                $log = new Feed();
                $log->setTarget($projectData->id);
                $log->populate('Aporte PayPal', '/admin/invests',
                    \vsprintf("%s ha aportado %s al proyecto %s mediante PayPal",
                        array(
                        Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                        Feed::item('money', $invest->amount.' &euro;'),
                        Feed::item('project', $projectData->name, $projectData->id))
                    ));
                $log->doAdmin('money');
                // evento público
                $log_html = Text::html('feed-invest',
                                    Feed::item('money', $invest->amount.' &euro;'),
                                    Feed::item('project', $projectData->name, $projectData->id));
                if ($invest->anonymous) {
                    $log->populate(Text::get('regular-anonymous'), '/user/profile/anonymous', $log_html, 1);
                } else {
                    $log->populate($_SESSION['user']->name, '/user/profile/'.$_SESSION['user']->id, $log_html, $_SESSION['user']->avatar->id);
                }
                $log->doPublic('community');
                unset($log);
            }
            // fin segun metodo

            // Feed del aporte de la campaña
            if (!empty($invest->droped) && $drop instanceof Model\Invest && is_object($callData)) {
                // Evento Feed
                $log = new Feed();
                $log->setTarget($projectData->id);
                $log->populate('Aporte riego '.$drop->method, '/admin/invests',
                    \vsprintf("%s ha aportado %s de %s al proyecto %s a través de la campaña %s", array(
                        Feed::item('user', $callData->user->name, $callData->user->id),
                        Feed::item('money', $drop->amount.' &euro;'),
                        Feed::item('drop', 'Capital Riego', '/service/resources'),
                        Feed::item('project', $projectData->name, $projectData->id),
                        Feed::item('call', $callData->name, $callData->id)
                    )));
                $log->doAdmin('money');
                // evento público
                $log->populate($callData->user->name, '/user/profile/'.$callData->user->id,
                            Text::html('feed-invest',
                                    Feed::item('money', $drop->amount.' &euro;')
                                        . ' de '
                                        . Feed::item('drop', 'Capital Riego', '/service/resources'),
                                    Feed::item('project', $projectData->name, $projectData->id)
                                        . ' a través de la campaña '
                                        . Feed::item('call', $callData->name, $callData->id)
                            ), $callData->user->avatar->id);
                $log->doPublic('community');
                unset($log);
            }

            // texto recompensa
            // @TODO quitar esta lacra de N recompensas porque ya es solo una recompensa siempre
            $rewards = $invest->rewards;
            array_walk($rewards, function (&$reward) { $reward = $reward->reward; });
            $txt_rewards = implode(', ', $rewards);

            // recaudado y porcentaje
            $amount = $projectData->invested;
            $percent = floor(($projectData->invested / $projectData->mincost) * 100);


            // email de agradecimiento al cofinanciador
            // primero monto el texto de recompensas
            //@TODO el concepto principal sería 'renuncia' (porque todos los aportes son donativos)
            if ($invest->resign) {
                // Plantilla de donativo segun la ronda
                if ($projectData->round == 2) {
                    $template = Template::get(36); // en segunda ronda
                } else {
                    $template = Template::get(28); // en primera ronda
                }
            } else {
                // plantilla de agradecimiento segun la ronda
                if ($projectData->round == 2) {
                    $template = Template::get(34); // en segunda ronda
                } else {
                    $template = Template::get(10); // en primera ronda
                }
            }

            
            // Dirección en el mail (y version para regalo)
            $txt_address = Text::get('invest-address-address-field') . ' ' . $invest->address->address;
            $txt_address .= '<br> ' . Text::get('invest-address-zipcode-field') . ' ' . $invest->address->zipcode;
            $txt_address .= '<br> ' . Text::get('invest-address-location-field') . ' ' . $invest->address->location;
            $txt_address .= '<br> ' . Text::get('invest-address-country-field') . ' ' . $invest->address->country;

            $txt_destaddr = $txt_address;
            $txt_address = Text::get('invest-mail_info-address') .'<br>'. $txt_address;

            // Agradecimiento al cofinanciador
            // Sustituimos los datos
            $subject = str_replace('%PROJECTNAME%', $projectData->name, $template->title);

            // En el contenido:
            $search  = array('%USERNAME%', '%PROJECTNAME%', '%PROJECTURL%', '%AMOUNT%', '%REWARDS%');
            $replace = array($_SESSION['user']->name, $projectData->name, SITE_URL.'/project/'.$projectData->id, $confirm->amount, $txt_rewards);
            $content = \str_replace($search, $replace, $template->text);

            $mailHandler = new Mail();
            $mailHandler->reply = GOTEO_CONTACT_MAIL;
            $mailHandler->replyName = GOTEO_MAIL_NAME;
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
            $replace = array($projectData->user->name, $_SESSION['user']->name, $projectData->name, SITE_URL, $invest->amount, SITE_URL.'/user/profile/'.$_SESSION['user']->id.'/message');
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



            // marcar que ya se ha completado el proceso de aportar
            $_SESSION['invest_'.$invest->id.'_completed'] = true;
            // log
            Model\Invest::setDetail($invest->id, 'confirmed', 'El usuario regresó a /invest/confirmed');


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