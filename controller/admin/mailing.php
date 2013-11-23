<?php
/*
 *  Copyright (C) 2012 Platoniq y Fundación Fuentes Abiertas (see README for details)
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
		Goteo\Library\Message,
		Goteo\Library\Feed,
        Goteo\Library\Template,
        Goteo\Library\Mail,
        Goteo\Library\Newsletter,
        Goteo\Model;

    class Mailing {

        public static function process ($action = 'list', $id = null, $filters = array()) {

            // año fiscal, esta primera vez es desde 2011
            $year = '2012';
            // ESTA PRIMERA VEZ ESESPECIAL  porque el cif no lo tuvimos hasta el 2012
            $year0 = '2011';
            $year1 = '2013';
            

            $errors = array();

            $node = isset($_SESSION['admin_node']) ? $_SESSION['admin_node'] : \GOTEO_NODE;

            // Valores de filtro
//            $projects = Model\Project::getAll();
            $interests = Model\User\Interest::getAll();
            $status = Model\Project::status();
            $methods = Model\Invest::methods();
            $types = array(
                'investor' => 'Cofinanciadores',
                'owner' => 'Autores',
                'user' => 'Usuarios'
            );
            $roles = array(
                'admin' => 'Administrador',
                'checker' => 'Revisor',
                'translator' => 'Traductor'
            );

            // una variable de sesion para mantener los datos de todo esto
            if (!isset($_SESSION['mailing'])) {
                $_SESSION['mailing'] = array();
            }

            switch ($action) {
                case 'edit':

                    $_SESSION['mailing']['receivers'] = array();

                    $values = array();
                    $sqlFields  = '';
                    $sqlInner  = '';
                    $sqlFilter = '';


                    // cargamos los destiantarios
                    //----------------------------
                    // por tipo de usuario
                    switch ($filters['type']) {
                        case 'investor':
                            $sqlInner .= "INNER JOIN invest
                                    ON invest.user = user.id
                                    AND (invest.status = 0 OR invest.status = 1 OR invest.status = 3 OR invest.status = 4)
                                INNER JOIN project
                                    ON project.id = invest.project
                                    ";
                            $sqlFields .= ", project.name as project";
                            $sqlFields .= ", project.id as projectId";
                            break;
                        case 'owner':
                            $sqlInner .= "INNER JOIN project
                                    ON project.owner = user.id
                                    ";
                            $sqlFields .= ", project.name as project";
                            $sqlFields .= ", project.id as projectId";
                            break;
                        default :
                            break;
                    }
                    $_SESSION['mailing']['filters_txt'] = 'los <strong>' . $types[$filters['type']] . '</strong> ';

                    if (!empty($filters['project']) && !empty($sqlInner)) {
                        $sqlFilter .= " AND project.name LIKE (:project) ";
                        $values[':project'] = '%'.$filters['project'].'%';
                        $_SESSION['mailing']['filters_txt'] .= 'de proyectos que su nombre contenga <strong>\'' . $filters['project'] . '\'</strong> ';
                    } elseif (empty($filters['project']) && !empty($sqlInner)) {
                        $_SESSION['mailing']['filters_txt'] .= 'de cualquier proyecto ';
                    }

                    if (isset($filters['status']) && $filters['status'] > -1 && !empty($sqlInner)) {
                        $sqlFilter .= "AND project.status = :status ";
                        $values[':status'] = $filters['status'];
                        $_SESSION['mailing']['filters_txt'] .= 'en estado <strong>' . $status[$filters['status']] . '</strong> ';
                    } elseif ($filters['status'] < 0 && !empty($sqlInner)) {
                        $_SESSION['mailing']['filters_txt'] .= 'en cualquier estado ';
                    }

                    if ($filters['type'] == 'investor') {
                        if (!empty($filters['method']) && !empty($sqlInner)) {
                            $sqlFilter .= "AND invest.method = :method ";
                            $values[':method'] = $filters['method'];
                            $_SESSION['mailing']['filters_txt'] .= 'mediante <strong>' . $methods[$filters['method']] . '</strong> ';
                        } elseif (empty($filters['method']) && !empty($sqlInner)) {
                            $_SESSION['mailing']['filters_txt'] .= 'mediante cualquier metodo ';
                        }
                    }

                    if (!empty($filters['interest'])) {
                        $sqlInner .= "INNER JOIN user_interest
                                ON user_interest.user = user.id
                                AND user_interest.interest = :interest
                                ";
                        $values[':interest'] = $filters['interest'];
                        if ($filters['interest']) {
                            $_SESSION['mailing']['filters_txt'] .= 'del grupo de testeo ';
                        } else {
                            $_SESSION['mailing']['filters_txt'] .= 'interesados en fin <strong>' . $interests[$filters['interest']] . '</strong> ';
                        }
                    }

                    if (!empty($filters['role'])) {
                        $sqlInner .= "INNER JOIN user_role
                                ON user_role.user_id = user.id
                                AND user_role.role_id = :role
                                ";
                        $values[':role'] = $filters['role'];
                        $_SESSION['mailing']['filters_txt'] .= 'que sean <strong>' . $roles[$filters['role']] . '</strong> ';
                    }

                    if (!empty($filters['name'])) {
                        $sqlFilter .= " AND ( user.name LIKE (:name) OR user.email LIKE (:name) ) ";
                        $values[':name'] = '%'.$filters['name'].'%';
                        $_SESSION['mailing']['filters_txt'] .= 'que su nombre o email contenga <strong>\'' . $filters['name'] . '\'</strong> ';
                    }

                    if (!empty($filters['donant'])) {
                        if ($filters['type'] == 'investor') {
                            $sqlFilter .= " AND invest.resign = 1
                                AND invest.status IN (1, 3)
                                AND invest.charged >= '{$year0}-01-01'
                                AND invest.charged < '{$year1}-01-01'
                                AND (project.passed IS NOT NULL AND project.passed != '0000-00-00')
                                ";
                            $_SESSION['mailing']['filters_txt'] .= 'que haya hecho algun donativo ';
                        } else {
                            Message::Error('Solo se filtran donantes si se envia "A los: Cofinanciadores"');
                        }
                    }

                    if ($node != \GOTEO_NODE) {
                        $sqlFilter .= " AND user.node = :node";
                        $values[':node'] = $node;
                        if (!empty($sqlInner)) {
                            $sqlFilter .= " AND project.node = :node";
                        }
                    }

                    $sql = "SELECT
                                user.id as id,
                                user.name as name,
                                user.email as email
                                $sqlFields
                            FROM user
                            $sqlInner
                            WHERE user.id != 'root'
                            AND user.active = 1
                            $sqlFilter
                            GROUP BY user.id
                            ORDER BY user.name ASC
                            ";

//                        die('<pre>'.$sql . '<br />'.print_r($values, 1).'</pre>');

                    if ($query = Model\User::query($sql, $values)) {
                        foreach ($query->fetchAll(\PDO::FETCH_OBJ) as $receiver) {
                            $_SESSION['mailing']['receivers'][$receiver->id] = $receiver;
                        }
                    } else {
                        Message::Error('Fallo el SQL!!!!! <br />' . $sql . '<pre>'.print_r($values, 1).'</pre>');
                    }

                    // si no hay destinatarios, salta a la lista con mensaje de error
                    if (empty($_SESSION['mailing']['receivers'])) {
                        Message::Error('No se han encontrado destinatarios para ' . $_SESSION['mailing']['filters_txt']);

                        throw new Redirection('/admin/mailing/list');
                    }

                    // si hay, mostramos el formulario de envio
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder'    => 'mailing',
                            'file'      => 'edit',
                            'filters'   => $filters,
//                                'projects'  => $projects,
                            'interests' => $interests,
                            'status'    => $status,
                            'types'     => $types,
                            'roles'     => $roles
                        )
                    );

                    break;
                case 'send':
                    $tini = \microtime();
                    // Enviando contenido recibido a destinatarios recibidos
                    $users = array();

//                        $content = nl2br($_POST['content']);
                    $content = $_POST['content'];
                    $subject = $_POST['subject'];
                    $templateId = !empty($_POST['template']) ? $_POST['template'] : 11;

                    $URL = (NODE_ID != GOTEO_NODE) ? NODE_URL : SITE_URL;

                    // Contenido para newsletter
                    if ($templateId == 33) {
                        $_SESSION['NEWSLETTER_SENDID'] = '';
                        $tmpcontent = \Goteo\Library\Newsletter::getContent($content);
                    }

                    // ahora, envio, el contenido a cada usuario
                    foreach ($_SESSION['mailing']['receivers'] as $usr=>$userData) {

                        $errors = array();

                        $users[] = $usr;
                        if (!isset($_POST[$usr])) {
                            $campo = 'receiver_'.str_replace('.', '_', $usr);
                            if (!isset($_POST[$campo])) {
                                $_SESSION['mailing']['receivers'][$usr]->ok = null;
                                continue;
                            }
                        }

                        // si es newsletter
                        if ($templateId == 33) {
                            // Mirar que no tenga bloqueadas las preferencias
                            if (Model\User::mailBlock($usr)) {
                                Message::Error($usr . ' lo tiene bloqueado');
                                continue;
                            }

                            // el sontenido es el mismo para todos, no lleva variables
                        } else {
                            $tmpcontent = \str_replace(
                                array('%USERID%', '%USEREMAIL%', '%USERNAME%', '%SITEURL%', '%PROJECTID%', '%PROJECTNAME%', '%PROJECTURL%'),
                                array(
                                    $usr,
                                    $userData->email,
                                    $userData->name,
                                    $URL,
                                    $userData->projectId,
                                    $userData->project,
                                    $URL.'/project/'.$userData->projectId
                                ),
                                $content);
                        }

                        $mailHandler = new Mail();

                        $mailHandler->to = $userData->email;
                        $mailHandler->toName = $userData->name;
                        $mailHandler->subject = $subject;
                        $mailHandler->content = '<br />'.$tmpcontent.'<br />';
                        $mailHandler->html = true;
                        $mailHandler->template = $templateId;
                        if ($mailHandler->send($errors)) {
                            $_SESSION['mailing']['receivers'][$usr]->ok = true;
                        } else {
                            Message::Error(implode('<br />', $errors));
                            $_SESSION['mailing']['receivers'][$usr]->ok = false;
                        }

                        unset($mailHandler);
                    }

                    $tend = \microtime();
                    $time = $tend - $tini;

                    // Evento Feed
                    $log = new Feed();
                    $log->populate('mailing a usuarios (admin)', '/admin/mailing',
                        \vsprintf("El admin %s ha enviado una %s a %s", array(
                        Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id),
                        Feed::item('relevant', 'Comunicacion masiva'),
                        $_SESSION['mailing']['filters_txt']
                    )));
                    $log->doAdmin('admin');
                    unset($log);


                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder'    => 'mailing',
                            'file'      => 'send',
                            'content'   => $content,
//                                'projects'  => $projects,
                            'interests' => $interests,
                            'status'    => $status,
                            'methods'   => $methods,
                            'types'     => $types,
                            'roles'     => $roles,
                            'users'     => $users,
                            'time'      => $time
                        )
                    );

                    break;
            }

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder'    => 'mailing',
                    'file'      => 'list',
//                    'projects'  => $projects,
                    'interests' => $interests,
                    'status'    => $status,
                    'methods'   => $methods,
                    'types'     => $types,
                    'roles'     => $roles,
                    'filters'   => $filters
                )
            );
            
        }

    }

}
