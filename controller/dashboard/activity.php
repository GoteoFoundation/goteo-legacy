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

namespace Goteo\Controller\Dashboard {

    use Goteo\Model,
        Goteo\Core\Redirection,
		Goteo\Library\Message,
		Goteo\Library\Text,
		Goteo\Library\Listing;

    class Activity {

        // listados de proyectos a mostrar (proyectos que cofinancia y proyectos suyos)
        public static function projList ($user) {
            $lists = array();
            // mis proyectos
            $projects = Model\Project::ofmine($user->id);
            if (!empty($projects)) {
                $lists['my_projects'] = Listing::get($projects);
            }
            // proyectos que cofinancio
            $invested = Model\User::invested($user->id, false);
            if (!empty($invested)) {
                $lists['invest_on'] = Listing::get($invested);
            }
            return $lists;
        }
        
        
        // eventos a mostrar en su muro
        public static function wall ($user) {
            return null;
            
            /*
             * Depurar antes de poner esto
             *
              // eventos privados del usuario
              $items['private'] = Feed::getUserItems($_SESSION['user']->id, 'private');
              // eventos de proyectos que he cofinanciado
              $items['supported'] = Feed::getUserItems($_SESSION['user']->id, 'supported');
              // eventos de proyectos donde he mensajeado o comentado
              $items['comented'] = Feed::getUserItems($_SESSION['user']->id, 'comented');
             *
             */
        }

        // acciones de certificado de donativo
        public static function donor ($user) {

            // ver si es donante, cargando sus datos
            $donation = Model\User\Donor::get($user->id);
            $donation->dates = Model\User\Donor::getDates($donation->user, $donation->year);
            $donation->userData = Model\User::getMini($donation->user);

            if (!$donation || !$donation instanceof Model\User\Donor) {
                Message::Error(Text::get('dashboard-donor-no_donor'));
                throw new Redirection('/dashboard/activity');
            }

            if ($action == 'edit' && $donation->confirmed) {
                Message::Error(Text::get('dashboard-donor-confirmed'));
                throw new Redirection('/dashboard/activity/donor');
            }

            // si están guardando, actualizar los datos y guardar
            if ($action == 'save' && $_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['save'] == 'donation') {
                $donation->edited = 1;
                $donation->confirmed = 0;
                $donation->name = $_POST['name'];
                $donation->nif = $_POST['nif'];
                $donation->address = $_POST['address'];
                $donation->zipcode = $_POST['zipcode'];
                $donation->location = $_POST['location'];
                $donation->country = $_POST['country'];

                if ($donation->save()) {
                    Message::Info(Text::get('dashboard-donor-saved'));
                    throw new Redirection('/dashboard/activity/donor');
                } else {
                    Message::Error(Text::get('dashboard-donor-save_fail'));
                    throw new Redirection('/dashboard/activity/donor/edit');
                }
            }

            if ($action == 'confirm') {
                // marcamos que los datos estan confirmados
                Model\User\Donor::setConfirmed($user->id);
                Message::Info(Text::get('dashboard-donor-confirmed'));
                throw new Redirection('/dashboard/activity/donor');
            }

            if ($action == 'download') {
                // preparamos los datos para el pdf
                // generamos el pdf y lo mosteramos con la vista específica
                // estos pdf se guardan en /data/pdfs/donativos
                // el formato del archivo es: Ymd_nif_userid
                // se genera una vez, si ya está generado se abre directamente
                if (!empty($donation->pdf) && file_exists('data/pdfs/donativos/' . $donation->pdf)) {

                    // forzar descarga
                    header('Content-type: application/pdf');
                    header("Content-disposition: attachment; filename={$donation->pdf}");
                    header("Content-Transfer-Encoding: binary");
                    echo file_get_contents('data/pdfs/donativos/' . $donation->pdf);
                    die();
                } else {

                    $objeto = new \Goteo\Library\Num2char($donation->amount, null);
                    $donation->amount_char = $objeto->getLetra();

                    $filename = "certificado_" . date('Ymd') . "_{$donation->nif}_{$donation->user}.pdf";


                    $debug = false;

                    if ($debug)
                        header('Content-type: text/html');

                    require_once 'library/pdf.php';  // Libreria pdf
                    $pdf = donativeCert($donation);

                    if ($debug) {
                        echo 'FIN';
                        echo '<hr><pre>' . print_r($pdf, 1) . '</pre>';
                    } else {
                        $pdf->Output('data/pdfs/donativos/' . $filename, 'F');
                        $donation->setPdf($filename);
//                            throw new Redirection('/dashboard/activity/donor/download/'.$donation->pdf);
                        header('Content-type: application/pdf');
                        header("Content-disposition: attachment; filename={$donation->pdf}");
                        header("Content-Transfer-Encoding: binary");
                        echo $pdf->Output('', 'S');
                        die;
                    }
                }
            }
            // fin action download


            return $donation;
            
        }

    }

}
