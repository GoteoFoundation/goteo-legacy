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

namespace Goteo\Controller\Cron {

    use Goteo\Model,
        Goteo\Core\Redirection,
        Goteo\Library\Text,
        Goteo\Library\Feed,
        Goteo\Library\Template,
        Goteo\Library\Mail,
        Goteo\Core\Error;

    class Send {

        /**
         * Al autor del proyecto, se encarga de substituir variables en plantilla
         *
         * @param $type string
         * @param $project Object
         * @return bool
         */
        public static function toOwner ($type, $project) {
            $tpl = null;
            
            /// tipo de envio
            switch ($type) {
                // Estos son avisos de final de ronda
                case 'r1_pass': // template 20, proyecto supera la primera ronda
                    $tpl = 20;
                    $search  = array('%USERNAME%', '%PROJECTNAME%', '%WIDGETURL%');
                    $replace = array($project->user->name, $project->name, SITE_URL . '/dashboard/projects/widgets');
                    break;

                case 'fail': // template 21, caduca sin conseguir el mínimo
                    $tpl = 21;
                    $search  = array('%USERNAME%', '%PROJECTNAME%', '%SUMMARYURL%');
                    $replace = array($project->user->name, $project->name, SITE_URL . '/dashboard/projects/summary');
                    break;

                case 'r2_pass': // template 22, finaliza segunda ronda
                    $tpl = 22;
                    $search  = array('%USERNAME%', '%PROJECTNAME%', '%REWARDSURL%');
                    $replace = array($project->user->name, $project->name, SITE_URL . '/dashboard/projects/rewards');
                    break;

                // Estos son avisos de auto-tips de /cron/daily
                case '8_days': // template 13, cuando faltan 8 días y no ha conseguido el mínimo
                    $tpl = 13;
                    $search  = array('%USERNAME%', '%PROJECTNAME%', '%WIDGETURL%');
                    $replace = array($project->user->name, $project->name, SITE_URL . '/dashboard/projects/widgets');
                    break;

                case '2_days': // template 14, cuando faltan 2 días y no ha conseguido el mínimo
                    $tpl = 14;
                    $search  = array('%USERNAME%', '%PROJECTNAME%', '%WIDGETURL%');
                    $replace = array($project->user->name, $project->name, SITE_URL . '/dashboard/projects/widgets');
                    break;

                case 'two_weeks': // template 19, "no bajes la guardia!" 25 días de campaña
                    $tpl = 19;
                    $search  = array('%USERNAME%', '%PROJECTNAME%', '%WIDGETURL%');
                    $replace = array($project->user->name, $project->name, SITE_URL . '/dashboard/projects/widgets');
                    break;

                case 'no_updates': // template 23, 3 meses sin novedades
                    $tpl = 23;
                    $search  = array('%USERNAME%', '%PROJECTNAME%', '%UPDATESURL%');
                    $replace = array($project->user->name, $project->name, SITE_URL . '/dashboard/projects/updates');
                    break;

                case 'any_update': // template 24, no hay posts de novedades
                    $tpl = 24;
                    $search  = array('%USERNAME%', '%PROJECTNAME%', '%UPDATESURL%', '%NOVEDADES%');
                    $replace = array($project->user->name, $project->name, SITE_URL . '/dashboard/projects/updates', SITE_URL.'/project/'.$project->id.'/updates');
                    break;

                case '2m_after': // template 25, dos meses despues de financiado
                    $tpl = 25;
                    $search  = array('%USERNAME%', '%PROJECTNAME%', '%REWARDSURL%');
                    $replace = array($project->user->name, $project->name, SITE_URL . '/dashboard/projects/rewards');
                    break;

                case '8m_after': // template 52, ocho meses despues de financiado
                    $tpl = 52;
                    $search  = array('%USERNAME%', '%PROJECTNAME%', '%COMMONSURL%');
                    $replace = array($project->user->name, $project->name, SITE_URL . '/dashboard/projects/commons');
                    break;

                case '20_backers': // template 46, "Apóyate en quienes te van apoyando "  (en cuanto se llega a los 20 backers
                    $tpl = 46;
                    $search  = array('%USERNAME%', '%PROJECTNAME%', '%PROJECTURL%', '%NUMBACKERS%');
                    $replace = array($project->user->name, $project->name, SITE_URL.'/project/'.$project->id, $project->num_investors);
                    break;
                
                // consejos normales
                case 'tip_1': // template 41, "Difunde, difunde, difunde"
                    $tpl = 41;
                    $search  = array('%USERNAME%', '%PROJECTNAME%', '%PROJECTURL%', '%PORCENTAJE%');
                    $replace = array($project->user->name, $project->name, SITE_URL.'/project/'.$project->id, $project->percent);
                    break;

                case 'tip_2': // template 42, "Comienza por lo más próximo"
                    $tpl = 42;
                    $search  = array('%USERNAME%', '%PROJECTNAME%', '%PROJECTURL%', '%PORCENTAJE%');
                    $replace = array($project->user->name, $project->name, SITE_URL.'/project/'.$project->id, $project->percent);
                    break;
                
                case 'tip_3': // template 43, "Una acción a diario, por pequeña que sea"
                    $tpl = 43;
                    $search  = array('%USERNAME%', '%PROJECTNAME%', '%PROJECTURL%', '%AMOUNT%');
                    $replace = array($project->user->name, $project->name, SITE_URL.'/project/'.$project->id, $project->invested);
                    break;
                
                case 'tip_4': // template 44, "Llama a todas las puertas"
                    $tpl = 44;
                    $search  = array('%USERNAME%', '%PROJECTNAME%', '%PROJECTURL%', '%BACKERSURL%');
                    $replace = array($project->user->name, $project->name, SITE_URL.'/project/'.$project->id, SITE_URL.'/project/'.$project->id.'/supporters');
                    break;
                
                case 'tip_5': // template 45, "Busca dónde está tu comunidad"
                    $tpl = 45;
                    $search  = array('%USERNAME%', '%PROJECTNAME%', '%PROJECTURL%', '%AMOUNT%', '%NUMBACKERS%');
                    $replace = array($project->user->name, $project->name, SITE_URL.'/project/'.$project->id, $project->invested, $project->num_investors);
                    break;
                
                case 'tip_8': // template 47, "Agradece en público e individualmente"
                    $tpl = 47;
                    $search  = array('%USERNAME%', '%PROJECTNAME%', '%PROJECTURL%', '%MESSAGESURL%');
                    $replace = array($project->user->name, $project->name, SITE_URL.'/project/'.$project->id, SITE_URL.'/project/'.$project->id.'/messages');
                    break;
                
                case 'tip_9': // template 48, "Busca prescriptores e implícalos"
                    $tpl = 48;
                    $search  = array('%USERNAME%', '%PROJECTNAME%', '%PROJECTURL%', '%PORCENTAJE%');
                    $replace = array($project->user->name, $project->name, SITE_URL.'/project/'.$project->id, $project->percent);
                    break;
                
                case 'tip_10': // template 49, "Luce tus recompensas y retornos"
                    $tpl = 49;
                    $search  = array('%USERNAME%', '%PROJECTNAME%', '%PROJECTURL%', '%LOWREWARD%', '%HIGHREWARD%');
                    $replace = array($project->user->name, $project->name, SITE_URL.'/project/'.$project->id, $project->lower, $project->higher);
                    break;
                
                case 'tip_11': // template 50, "Refresca tu mensaje de motivacion"
                    $tpl = 50;
                    $search  = array('%USERNAME%', '%PROJECTNAME%', '%PROJECTURL%');
                    $replace = array($project->user->name, $project->name, SITE_URL.'/project/'.$project->id);
                    break;
                
                case 'tip_15': // template 51, "Sigue los avances y calcula lo que falta"
                    $tpl = 51;
                    $search  = array('%USERNAME%', '%PROJECTNAME%', '%PROJECTURL%', '%DIASCAMPAÑA%', '%DAYSTOGO%');
                    $replace = array($project->user->name, $project->name, SITE_URL.'/project/'.$project->id, $project->days, $project->days);
                    break;
                
            }

            if (!empty($tpl)) {
                $errors = array();
                // Obtenemos la plantilla para asunto y contenido
                $template = Template::get($tpl);
                // Sustituimos los datos
                $subject = str_replace('%PROJECTNAME%', $project->name, $template->title);
                $content = \str_replace($search, $replace, $template->text);
                // iniciamos mail
                $mailHandler = new Mail();
                $mailHandler->to = $project->user->email;
                $mailHandler->toName = $project->user->name;
                // monitorización solo para 'quien-manda'
                if ($project->id == 'quien-manda' || $project->id == 'guifi-net-extremadura') 
                    $mailHandler->bcc = array('enric@goteo.org', 'maria@goteo.org', 'olivier@goteo.org', 'jcanaves@doukeshi.org', 'info@goteo.org');
                
                if ($project->id == 'cervecita-fresca') 
                    $mailHandler->bcc = array('jcanaves@doukeshi.org');
                
                if ($project->id == 'keinuka') 
                    $mailHandler->bcc = array('rosa@euskadi.goteo.org', 'jcanaves@doukeshi.org');
                
                // si es un proyecto de nodo: reply al mail del nodo
                // si es de centra: reply a MAIL_GOTEO
                $mailHandler->reply = (!empty($project->nodeData->email)) ? $project->nodeData->email : \GOTEO_CONTACT_MAIL;
                
                $mailHandler->subject = $subject;
                $mailHandler->content = $content;
                $mailHandler->html = true;
                $mailHandler->template = $template->id;
                if ($mailHandler->send($errors)) {
                    return true;
                } else {
                    echo \trace($errors);
                    @mail(\GOTEO_FAIL_MAIL,
                        'Fallo al enviar email automaticamente al autor ' . SITE_URL,
                        'Fallo al enviar email automaticamente al autor: <pre>' . print_r($mailHandler, 1). '</pre>');
                }
            }

            return false;
        }

        /* A los cofinanciadores 
         * Se usa tambien para notificar cuando un proyecto publica una novedad.
         * Por eso añadimos el tercer parámetro, para recibir los datos del post
         */
        static public function toInvestors ($type, $project, $post = null) {

            // notificación
            $notif = $type == 'update' ? 'updates' : 'rounds';

            $anyfail = false;
            $tpl = null;

            // para cada inversor que no tenga bloqueado esta notificacion
            $sql = "
                SELECT
                    invest.user as id,
                    user.name as name,
                    user.email as email,
                    invest.method as method,
                    IFNULL(user.lang, 'es') as lang
                FROM  invest
                INNER JOIN user
                    ON user.id = invest.user
                    AND user.active = 1
                LEFT JOIN user_prefer
                    ON user_prefer.user = invest.user
                WHERE   invest.project = ?
                AND invest.status IN ('0', '1', '3', '4')
                AND (user_prefer.{$notif} = 0 OR user_prefer.{$notif} IS NULL)
                GROUP BY user.id
                ";
            if ($query = Model\Invest::query($sql, array($project->id))) {
                foreach ($query->fetchAll(\PDO::FETCH_OBJ) as $investor) {
                    /// tipo de envio
                    switch ($type) {
                        case 'r1_pass': // template 15, proyecto supera la primera ronda
                                $tpl = 15;
                                $search  = array('%USERNAME%', '%PROJECTNAME%', '%PROJECTURL%');
                                $replace = array($investor->name, $project->name, SITE_URL . '/project/' . $project->id);
                            break;

                        case 'fail': // template 17 (paypalistas) / 35 (tpvistas) , caduca sin conseguir el mínimo
                                $tpl = ($investor->method == 'paypal') ? 17 : 35;
                                $search  = array('%USERNAME%', '%PROJECTNAME%', '%DISCOVERURL%');
                                $replace = array($investor->name, $project->name, SITE_URL . '/discover');
                            break;

                        case 'r2_pass': // template 16, finaliza segunda ronda
                                $tpl = 16;
                                $search  = array('%USERNAME%', '%PROJECTNAME%', '%PROJECTURL%');
                                $replace = array($investor->name, $project->name, SITE_URL . '/project/' . $project->id);
                            break;

                        case 'update': // template 18, publica novedad
                                $tpl = 18;
                                $search  = array('%USERNAME%', '%PROJECTNAME%', '%UPDATEURL%', '%POST%', '%SHAREFACEBOOK%', '%SHARETWITTER%');
                                $post_url = SITE_URL.'/project/'.$project->id.'/updates/'.$post->id;
                                // contenido del post
                                $post_content = "<p><strong>{$post->title}</strong><br />".  nl2br( Text::recorta($post->text, 500) )  ."</p>";
                                // y preparar los enlaces para compartir en redes sociales
                                $share_urls = Text::shareLinks($post_url, $post->title);
                                
                                $replace = array($investor->name, $project->name, $post_url, $post_content, $share_urls['facebook'], $share_urls['twitter']);
                            break;
                    }

                    if (!empty($tpl)) {
                        // Obtenemos la plantilla para asunto y contenido
                        // en el idioma del usuario
                        $template = Template::get($tpl, $investor->lang);
                        // Sustituimos los datos
                        if (!empty($post)) {
                            $subject = str_replace(array('%PROJECTNAME%', '%OWNERNAME%', '%P_TITLE%')
                                    , array($project->name, $project->user->name, $post->title)
                                    , $template->title);
                        } else {
                            $subject = str_replace('%PROJECTNAME%', $project->name, $template->title);
                        }
                        $content = \str_replace($search, $replace, $template->text);
                        // iniciamos mail
                        $mailHandler = new Mail();
                        $mailHandler->to = $investor->email;
                        $mailHandler->toName = $investor->name;
                        $mailHandler->subject = $subject;
                        $mailHandler->content = $content;
                        $mailHandler->html = true;
                        $mailHandler->template = $template->id;
                        if ($mailHandler->send()) {

                        } else {
                            $anyfail = true;
                            @mail(\GOTEO_FAIL_MAIL,
                                'Fallo al enviar email automaticamente al cofinanciador ' . SITE_URL,
                                'Fallo al enviar email automaticamente al cofinanciador: <pre>' . print_r($mailHandler, 1). '</pre>');
                        }
                        unset($mailHandler);
                    }
                }
                // fin bucle inversores
            } else {
                echo '<p>'.str_replace('?', $project->id, $sql).'</p>';
                $anyfail = true;
            }
            
            if ($anyfail)
                return false;
            else
                return true;

        }
        
    }
}