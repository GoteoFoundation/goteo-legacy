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
        Goteo\Controller\Cron\Send,
        Goteo\Library\Feed;

    class Daily {

        /*
         * Control diario de proyectos
         *  Para envio de tips y avisos
         * @param bool $debug
         */
        public static function Projects ($debug = false) {

            // proyectos a notificar
            $projects = Model\Project::review();

            // para cada uno,
            foreach ($projects as $project) {
                
                // por ahora solo tratamos los de primera ronda y hasta 2 meses tras la financiación
                if ($project->days > 40 || $project->days > 360) continue;

                if ($debug) echo "Proyecto {$project->name}, Impulsor:  {$project->user->name}, email: {$project->user->email}, estado {$project->status}, lleva {$project->days} dias<br />";
                
                // primero los que no se bloquean
                // Recuerdo al autor proyecto, 2 meses despues de campaña finalizada
                if ($project->days == 140) {
                        // si quedan recompensas/retornos pendientes por cumplir
                        if (!Model\Project\Reward::areFulfilled($project->id) || !Model\Project\Reward::areFulfilled($project->id, 'social') ) {
                            if ($debug) echo "Recompensas/Retornos pendientes<br />";
                            Send::toOwner('2m_after', $project);
                        } else {
                            if ($debug) echo "Recompensas/Retornos cumplidas, no se envía<br />";
                        }
                }
                
                // Recuerdo al autor proyecto, 8 meses despues de campaña finalizada
                if ($project->days == 320) {
                        // si quedan retornos pendientes por cumplir
                        if (!Model\Project\Reward::areFulfilled($project->id, 'social') ) {
                            if ($debug) echo "Retornos pendientes<br />";
                            Send::toOwner('8m_after', $project);
                        } else {
                            if ($debug) echo "Retornos cumplidos, no se envía<br />";
                        }
                }
                
                // ahora checkeamos bloqueo de consejos
                $prefs = Model\User::getPreferences($project->owner);
                if ($prefs->tips) {
                    if ($debug) echo "Bloqueado por preferencias<hr />";
                    continue;
                }
                
                // flag de aviso
                $avisado = false;
                
                // Consejos/avisos puntuales
                switch ($project->days) {
                    
                    // NO condicionales
                    case 1: // Difunde, difunde, difunde
                    case 2: // Comienza por lo más próximo
                    case 3: // Una acción a diario, por pequeña que sea
                    case 4: // Llama a todas las puertas
                    case 5: // Busca dónde está tu comunidad
                    case 8: // Agradece en público e individualmente
                        $template = 'tip_'.$project->days;
                        if ($debug) echo "Envío {$template}<br />";
                        Send::toOwner($template, $project);
                        break;
                    
                    // periodico condicional
                    case 6: // Publica novedades! 
                    // y  se repite cada 6 días (fechas libres) mientras no haya posts
                    case 12: 
                    case 18: 
                    case 24: 
                    case 30: 
                    case 36: 
                        // si ya hay novedades, nada
                        if (Model\Blog::hasUpdates($project->id)) {
                            if ($debug) echo "Ya ha publicado novedades<br />";
                        } else {
                            if ($debug) echo "Envío aviso de que no ha publicado novedades<br />";
                            Send::toOwner('any_update', $project);
                            $avisado = true;
                        }
                        break;
                    
                    // comprobación periódica pero solo un envío
                    case 7: // Apóyate en quienes te van apoyando ,  si más de 20 cofinanciadores
                        // o en cuanto llegue a 20 backers (en fechas libres)
                    case 14: 
                    case 17: 
                    case 21: 
                    case 24: 
                    case 27: 
                        // Si ya se mandó esta plantilla (al llegar a los 20 por primera vez) no se envía de nuevo
                        $sql = "
                            SELECT
                                id
                            FROM mail
                            WHERE mail.email = :email
                            AND mail.template = 46
                            ORDER BY mail.date DESC
                            LIMIT 1";
                        $query = Model\Project::query($sql, array(':email' => $project->user->email));
                        $sended = $query->fetchColumn(0);
                        if (!$sended) {
                            if ($project->num_investors >= 20) {
                                if ($debug) echo "Tiene 20 backers y no se le habia enviado aviso antes<br />";
                                Send::toOwner('20_backers', $project);
                            } else {
                                if ($debug) echo "No llega a los 20 backers<br />";
                            }
                        } else {
                            if ($debug) echo "Ya enviado<br />";
                        }
                        break;
                    
                    case 9: // Busca prescriptores e implícalos
                        // si no tiene padrinos
                        if ($project->patrons > 0) {
                            if ($debug) echo "Tiene padrino<br />";
                        } else {
                            if ($debug) echo "No tiene padrino<br />";
                            Send::toOwner('tip_9', $project);
                        }
                        break;
                    
                    case 10: // Luce tus recompensas y retornos
                        // que no se envie a los que solo tienen recompensas de agradecimiento
                        $thanksonly = true;
                        // recompensas
                        $rewards = Model\Project\Reward::getAll($project->id, 'individual', \LANG);
                        foreach ($rewards as $rew) {
                            if ($rew->icon != 'thanks') {
                                $thanksonly = false;
                                break; // ya salimos del bucle, no necesitamos más
                            }
                        }
                        if ($thanksonly) {
                            if ($debug) echo "Solo tiene recompensas de agradecimiento<br />";
                        } else {
                            if ($debug) echo "Tienen recompensas<br />";
                            uasort($rewards,
                                function ($a, $b) {
                                    if ($a->amount == $b->amount) return 0;
                                    return ($a->amount > $b->amount) ? 1 : -1;
                                    }
                                );
                            // sacar la primera y la última
                            $lower = reset($rewards); $project->lower = $lower->reward;
                            $higher = end($rewards); $project->higher = $higher->reward;

                            Send::toOwner('tip_10', $project);
                        }
                        break;
                        
                        
                    case 11: // Refresca tu mensaje de motivacion
                        // si no tiene video motivacional
                        if (empty($project->video)) {
                            if ($debug) echo "No tiene video motivacional<br />";
                            Send::toOwner('tip_11', $project);
                        } else {
                            if ($debug) echo "Tiene video motivacional<br />";
                        }
                        break;
                    
                    case 15: // Sigue los avances y calcula lo que falta
                        // si no ha llegado al mínimo
                        if ($project->invested < $project->mincost) {
                            if ($debug) echo "No ha llegado al mínimo<br />";
                            Send::toOwner('tip_15', $project);
                        } else {
                            if ($debug) echo "Ha llegado al mínimo<br />";
                        }
                        break;
                    
                    case 25: // No bajes la guardia!
                        // si no ha llegado al mínimo
                        if ($project->invested < $project->mincost) {
                            if ($debug) echo "No ha llegado al mínimo<br />";
                            Send::toOwner('two_weeks', $project);
                        } else {
                            if ($debug) echo "Ha llegado al mínimo<br />";
                        }
                        break;
                    
                    case 32: // Al proyecto le faltan 8 días para archivarse
                        // si no ha llegado al mínimo
                        if ($project->invested < $project->mincost) {
                            if ($debug) echo "No ha llegado al mínimo<br />";
                            Send::toOwner('8_days', $project);
                        } else {
                            if ($debug) echo "Ha llegado al mínimo<br />";
                        }
                        break;
                    
                    case 38: // Al proyecto le faltan 2 días para archivarse 
                        // si no ha llegado al mínimo pero está por encima del 70%
                        if ($project->invested < $project->mincost && $project->percent >= 70) {
                            if ($debug) echo "No ha llegado al mínimo<br />";
                            Send::toOwner('2_days', $project);
                        } else {
                            if ($debug) echo "Ha llegado al mínimo o lleva menos de 70%<br />";
                        }
                        break;
                }
                
                // Avisos periodicos 
                // si lleva más de 15 días: si no se han publicado novedades en la última semana 
                // Ojo! que si no a enviado ninguna no lanza este sino la de cada 6 días
                if (!$avisado && $project->days > 15) {
                    if ($debug) echo "ya lleva una quincena de campaña, verificamos novedades<br />";
                    
                    // veamos si ya le avisamos hace una semana
                    // Si ya se mandó esta plantilla (al llegar a los 20 por primera vez) no se envía de nuevo
                    $sql = "
                        SELECT
                            id,
                            DATE_FORMAT(
                                from_unixtime(unix_timestamp(now()) - unix_timestamp(date))
                                , '%j'
                            ) as days
                        FROM mail
                        WHERE mail.email = :email
                        AND mail.template = 23
                        ORDER BY mail.date DESC
                        LIMIT 1";
                    $query = Model\Project::query($sql, array(':email' => $project->user->email));
                    $lastsend = $query->fetchObject();
                    if (!$lastsend->id || $lastsend->days > 7) {
                        // veamos cuanto hace de la última novedad
                        $sql = "
                            SELECT
                                DATE_FORMAT(
                                    from_unixtime(unix_timestamp(now()) - unix_timestamp(date))
                                    , '%j'
                                ) as days
                            FROM post
                            INNER JOIN blog
                                ON  post.blog = blog.id
                                AND blog.type = 'project'
                                AND blog.owner = :project
                            WHERE post.publish = 1
                            ORDER BY post.date DESC
                            LIMIT 1";
                        $query = Model\Project::query($sql, array(':project' => $project->id));
                        $lastUpdate = $query->fetchColumn(0);
                        if ($lastUpdate > 7) {
                            if ($debug) echo "Ultima novedad es de hace más de una semana<br />";
                            Send::toOwner('no_updates', $project);
                        } elseif (is_numeric($lastUpdate)) {
                            if ($debug) echo "Publicó novedad hace menos de una semana<br />";
                        } else {
                            if ($debug) echo "No se ha publicado nada, recibirá el de cada 6 días<br />";
                        }
                    } else {
                        if ($debug) echo "Se le avisó por novedades hace menos de una semana<br />";
                    }
                    
                    
                }
                
                if ($debug) echo "<hr />";
                
            }
            
            if ($debug) echo "<br />Auto-tips Listo!<hr />";

            return;
        }

        
        /**
         * Control diario de convocatorias
         * @param bool $debug
         */
        public static function Calls ($debug = false) {
            
            // convocatorias con aplicación abierta
            $calls = Model\Call::getActive(3);
            foreach ($calls as $call) {
                // a ver cuantos días le quedan para que acabe la convocatoria
                $open = strtotime($call->opened);
                $until = mktime(0, 0, 0, date('m', $open), date('d', $open)+$call->days, date('Y', $open));
                $now = strtotime(date('Y-m-d'));
                $diference = $until - $now;
                $days = \round($diference/24/60/60);

                $doFeed = false;
                switch ($days) {
                    case 7:
                        $log_text = 'Falta una semana para que acabe la convocatoria %s';
                        $log_text_public = 'Falta una semana para que se cierre la aplicación de proyectos';
                        $doFeed = true;
                        break;
                    case 3:
                        $log_text = 'Faltan 3 dias para que acabe la convocatoria %s';
                        $log_text_public = 'Faltan 3 dias para que se cierre la aplicación de proyectos';
                        $doFeed = true;
                        break;
                    case 1:
                        $log_text = 'Ultimo día para la convocatoria %s';
                        $log_text_public = 'Hoy es el último día para aplicar proyectos!';
                        $doFeed = true;
                        break;
                }

                // feed
                if ($doFeed) {
                    $log = new Feed();
                    $log->setTarget($call->id, 'call');
                    $log->unique = true;
                    $log->populate('Convocatoria terminando (cron)', '/admin/calls/'.$call->id.'?days='.$days,
                        \vsprintf($log_text, array(
                            Feed::item('call', $call->name, $call->id))
                        ));
                    $log->doAdmin('call');
                    $log->populate('Convocatoria: ' . $call->name, '/call/'.$call->id.'?days='.$days, $log_text_public, $call->logo);
                    $log->doPublic('projects');
                    unset($log);
                    echo \vsprintf($log_text, array($call->name)).'<br />';
                }
            }



            // campañas dando dinero
            $campaigns = Model\Call::getActive(4);
            foreach ($campaigns as $campaign) {
                $errors = array();

                // tiene que tener presupuesto
                if (empty($campaign->amount)) {
                    continue;
                }

                // a ver cuanto le queda de capital riego
                $rest = $campaign->rest;

                $doFeed = false;
                if ($rest < 100) {
                    $amount = 100;
                    $doFeed = true;
                } elseif ($rest < 500) {
                    $amount = 500;
                    $doFeed = true;
                } elseif ($rest < 1000) {
                    $amount = 1000;
                    $doFeed = true;
                }
                // feed
                if ($doFeed) {
                    $log = new Feed();
                    $log->setTarget($campaign->id, 'call');
                    $log->unique = true;
                    $log->populate('Campaña terminando (cron)', '/admin/calls/'.$campaign->id.'?rest='.$amount,
                        \vsprintf('Quedan menos de %s en la campaña %s', array(
                            Feed::item('money', $amount.' &euro;')
                                . ' de '
                                . Feed::item('drop', 'Capital Riego', '/service/resources'),
                            Feed::item('call', $campaign->name, $campaign->id))
                        ));
                    $log->doAdmin('call');
                    $log->populate($campaign->name, '/call/'.$campaign->id.'?rest='.$amount,
                        \vsprintf('Quedan menos de %s en la campaña %s', array(
                            Feed::item('money', $amount.' &euro;') 
                                . ' de '
                                . Feed::item('drop', 'Capital Riego', '/service/resources'),
                            Feed::item('call', $campaign->name, $campaign->id))
                        ), $call->logo);
                    $log->doPublic('projects');
                    unset($log);
                }
            }
            
            if ($debug) echo "<br />Calls-control Listo!<hr />";

            return;
        }
        
    }

}
