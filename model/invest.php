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


namespace Goteo\Model {

    use Goteo\Library\Text,
        Goteo\Model\Image;

    class Invest extends \Goteo\Core\Model {

        public
            $id,
            $user,
            $project,
            $account, // cuenta paypal o email del usuario
            $amount, //cantidad monetaria del aporte
            $preapproval, //clave del preapproval
            $payment, //clave del cargo
            $transaction, // id de la transacción
            $method, // metodo de pago paypal/tpv
            $status, //estado en el que se encuentra esta aportación:
                    // -1 en proceso, 0 pendiente, 1 cobrado (charged), 2 devuelto (returned)
            $anonymous, //no debe aparecer su careto ni su nombre, nivel, etc... pero si aparece en la cuenta de cofinanciadores y de aportes
            $resign, //renuncia a cualquier recompensa
            $invested, //fecha en la que se ha iniciado el aporte
            $charged, //fecha en la que se ha cargado el importe del aporte a la cuenta del usuario
            $returned, //fecha en la que se ha devuelto el importe al usurio por cancelación bancaria
            $rewards = array(), //datos de las recompensas que le corresponden
            $address = array(
                'name'     => '',
                'nif'      => '',
                'address'  => '',
                'zipcode'  => '',
                'location' => '',
                'country'  => '');  // dirección de envio del retorno

        // añadir los datos del cargo


        /*
         *  Devuelve datos de una inversión
         */
        public static function get ($id) {
                $query = static::query("
                    SELECT  *
                    FROM    invest
                    WHERE   id = :id
                    ", array(':id' => $id));
                $invest = $query->fetchObject(__CLASS__);

				$query = static::query("
                    SELECT  *
                    FROM  invest_reward
                    INNER JOIN reward
                        ON invest_reward.reward = reward.id
                    WHERE   invest_reward.invest = ?
                    ", array($id));
				$invest->rewards = $query->fetchAll(\PDO::FETCH_CLASS);

				$query = static::query("
                    SELECT  address, zipcode, location, country, name, nif
                    FROM  invest_address
                    WHERE   invest_address.invest = ?
                    ", array($id));
				$invest->address = $query->fetchObject();

                // si no tiene dirección, sacamos la dirección del usuario
                if (empty($invest->address)) {
                    $usr_address = User::getPersonal($invest->user);
                    $usr_address->name = $usr_address->contract_name;
                    $usr_address->nif = $usr_address->contract_nif;

                    $invest->address = $usr_address;
                }
                
                return $invest;
        }

        /*
         * Lista de inversiones (individuales) de un proyecto
         *
         * el parametro filter es para la gestion de recompensas (no es un autentico filtro, hay ordenaciones y hay filtros)
         */
        public static function getAll ($project, $filter = null) {

            /*
             * Estos son los filtros
             */
            $filters = array(
                'date'      => 'Fecha',
                'user'      => 'Usuario',
                'reward'    => 'Recompensa',
                'pending'   => 'Pendientes',
                'fulfilled' => 'Cumplidos'
            );


            $invests = array();

            $query = static::query("
                SELECT  *
                FROM  invest
                WHERE   invest.project = ?
                AND invest.status IN ('0', '1', '3', '4')
                ", array($project));
            foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $invest) {
                // datos del usuario
                $invest->user = User::get($invest->user);

				$query = static::query("
                    SELECT  *
                    FROM  invest_reward
                    INNER JOIN reward
                        ON invest_reward.reward = reward.id
                    WHERE   invest_reward.invest = ?
                    ", array($invest->id));
				$invest->rewards = $query->fetchAll(\PDO::FETCH_CLASS);

				$query = static::query("
                    SELECT  address, zipcode, location, country
                    FROM  invest_address
                    WHERE   invest_address.invest = ?
                    ", array($invest->id));
				$invest->address = $query->fetchObject();

                // si no tiene dirección, sacamos la dirección del usuario
                if (empty($invest->address)) {
                    $usr_address = User::getPersonal($invest->user->id);

                    $invest->address = $usr_address;
                }

                $invests[$invest->id] = $invest;
            }

            return $invests;
        }


        /*
         * Lista de aportes individuales
         *
         * Los filtros vienen de la gestión de aportes
         * Los datos que sacamos: usuario, proyecto, cantidad, estado de proyecto, estado de aporte, fecha de aporte, tipo de aporte, campaña
         * .... anonimo, resign, etc...
         */
        public static function getList ($filters = array()) {

            /*
             * Estos son los filtros
            $fields = array('method', 'status', 'investStatus', 'project', 'user', 'campaign', 'types');
             */

            $list = array();

            $sqlFilter = "";
            if (!empty($filters['methods'])) {
                $sqlFilter .= " AND invest.method = '{$filters['methods']}'";
            }
            if (is_numeric($filters['status'])) {
                $sqlFilter .= " AND project.status = '{$filters['status']}'";
            }
            if (is_numeric($filters['investStatus'])) {
                $sqlFilter .= " AND invest.status = '{$filters['investStatus']}'";
            }
            if (!empty($filters['projects'])) {
                $sqlFilter .= " AND invest.project = '{$filters['projects']}'";
            }
            if (!empty($filters['users'])) {
                $sqlFilter .= " AND invest.user = '{$filters['users']}'";
            }
            if (!empty($filters['campaigns'])) {
                $sqlFilter .= " AND invest.campaign = '{$filters['campaigns']}'";
            }
            if (!empty($filters['types'])) {
                switch ($filters['types']) {
                    case 'donative':
                        $sqlFilter .= " AND invest.resign = 1";
                        break;
                    case 'anonymous':
                        $sqlFilter .= " AND invest.anonymous = 1";
                        break;
                    case 'manual':
                        $sqlFilter .= " AND invest.admin IS NOT NULL";
                        break;
                    case 'campaign':
                        $sqlFilter .= " AND invest.campaign IS NOT NULL";
                        break;
                }
            }

            if (!empty($filters['review'])) {
                switch ($filters['review']) {
                    case 'collect': //  Recaudado: tpv cargado o paypal pendiente
                        $sqlFilter .= " AND ((invest.method = 'tpv' AND invest.status = 1)
                                        OR (invest.method = 'paypal' AND invest.status = 0))";
                        break;
                    case 'online': // Solo pagos online
                        $sqlFilter .= " AND (invest.method = 'tpv' OR invest.method = 'paypal')";
                        break;
                    case 'paypal': // Paypal pendientes o ok
                        $sqlFilter .= " AND (invest.method = 'paypal' AND (invest.status = -1 OR invest.status = 0))";
                        break;
                    case 'tpv': // Tpv pendientes o ok
                        $sqlFilter .= " AND (invest.method = 'tpv' AND (invest.status = -1 OR invest.status = 1))";
                        break;
                }
            }

            if (!empty($filters['date_from'])) {
                $sqlFilter .= " AND invest.invested >= '{$filters['date_from']}'";
            }
            if (!empty($filters['date_until'])) {
                $sqlFilter .= " AND invest.invested <= '{$filters['date_until']}'";
            }

            $sql = "SELECT
                        invest.id as id,
                        invest.user as user,
                        invest.project as project,
                        invest.method as method,
                        invest.status as investStatus,
                        project.status as status,
                        invest.campaign as campaign,
                        invest.amount as amount,
                        invest.anonymous as anonymous,
                        invest.resign as resign,
                        DATE_FORMAT(invest.invested, '%d/%m/%Y') as invested,
                        DATE_FORMAT(invest.charged , '%d/%m/%Y') as charged,
                        DATE_FORMAT(invest.returned, '%d/%m/%Y') as returned,
                        user.name as admin
                    FROM invest
                    INNER JOIN project
                        ON invest.project = project.id
                    LEFT JOIN user
                        ON invest.admin = user.id
                    WHERE invest.project IS NOT NULL
                        $sqlFilter
                    ORDER BY invest.id DESC
                    ";

            $query = self::query($sql);
            foreach ($query->fetchAll(\PDO::FETCH_CLASS) as $item) {
                $list[$item->id] = $item;
            }
            return $list;
        }




        public function validate (&$errors = array()) { 
            if (!is_numeric($this->amount))
                $errors[] = 'La cantidad no es correcta';
                //Text::get('validate-invest-amount');

            if (empty($this->method))
                $errors[] = 'Falta metodo de pago';
                //Text::get('mandatory-invest-method');

            if (empty($this->user))
                $errors[] = 'Falta usuario';
                //Text::get('mandatory-invest-user');

            if (empty($this->project))
                $errors[] = 'Falta proyecto';
                //Text::get('mandatory-invest-project');

            if (empty($errors))
                return true;
            else
                return false;
        }

        public function save (&$errors = array()) {
            if (!$this->validate($errors)) return false;

            $fields = array(
                'id',
                'user',
                'project',
                'amount',
                'preapproval',
                'payment',
                'transaction',
                'method',
                'status',
                'anonymous',
                'resign',
                'invested',
                'charged',
                'returned',
                'admin',
                'campaign'
                );

            $set = '';
            $values = array();

            foreach ($fields as $field) {
                if (!empty($this->$field)) {
                    if ($set != '') $set .= ", ";
                    $set .= "`$field` = :$field ";
                    $values[":$field"] = $this->$field;
                }
            }

            try {
                $sql = "REPLACE INTO invest SET " . $set;
                self::query($sql, $values);
                if (empty($this->id)) $this->id = self::insertId();

                // y las recompensas
                foreach ($this->rewards as $reward) {
                    $sql = "REPLACE INTO invest_reward (invest, reward) VALUES (:invest, :reward)";
                    self::query($sql, array(':invest'=>$this->id, ':reward'=>$reward));
                }

                // dirección
                if (!empty($this->address)) {
                    $sql = "REPLACE INTO invest_address (invest, user, address, zipcode, location, country, name, nif)
                        VALUES (:invest, :user, :address, :zipcode, :location, :country, :name, :nif)";
                    self::query($sql, array(
                        ':invest'=>$this->id,
                        ':user'=>$this->user,
                        ':address'=>$this->address->address,
                        ':zipcode'=>$this->address->zipcode, 
                        ':location'=>$this->address->location, 
                        ':country'=>$this->address->country,
                        ':name'=>$this->address->name,
                        ':nif'=>$this->address->nif
                        )
                    );
                }

                return true;
            } catch(\PDOException $e) {
                $errors[] = "El aporte no se ha grabado correctamente. Por favor, revise los datos." . $e->getMessage();
                return false;
            }
        }

        /*
         * Lista de proyectos con aportes
         *
         * @param bool success solo los prroyectos en campaña, financiados o exitosos
         */
        public static function projects ($success = false, $node = \GOTEO_NODE) {

            $list = array();

            $sql = "
                SELECT
                    project.id as id,
                    project.name as name
                FROM    project
                INNER JOIN invest
                    ON project.id = invest.project
                    ";

            if ($success) {
                $sql .= " WHERE project.status >= 3 AND project.status <= 5 ";
            }
            $sql .= " ORDER BY project.name ASC";

            //, array(':node' => $node)
            $query = static::query($sql);

            foreach ($query->fetchAll(\PDO::FETCH_CLASS) as $item) {
                $list[$item->id] = $item->name;
            }

            return $list;
        }

        /*
         * Lista de usuarios que han aportado a algo
         */
        public static function users ($all = false) {

            $list = array();

            $sql = "
                SELECT
                    user.id as id,
                    user.name as name
                FROM    user
                INNER JOIN invest
                    ON user.id = invest.user
                ";
            
            if (!$all) {
                $sql .= "WHERE (user.hide = 0 OR user.hide IS NULL)
                    ";
            }
                $sql .= "ORDER BY user.name ASC
                ";

            $query = static::query($sql);

            foreach ($query->fetchAll(\PDO::FETCH_CLASS) as $item) {
                $list[$item->id] = $item->name;
            }

            return $list;
        }

        /*
         * Lista de campañas con aportes asociados
         */
        public static function campaigns () {

            $list = array();

            $query = static::query("
                SELECT
                    campaign.id as id,
                    campaign.name as name
                FROM    campaign
                INNER JOIN invest
                    ON campaign.id = invest.campaign
                ORDER BY campaign.name ASC
                ");

            foreach ($query->fetchAll(\PDO::FETCH_CLASS) as $item) {
                $list[$item->id] = $item->name;
            }

            return $list;
        }

        /*
         * Obtenido por un proyecto
         */
        public static function invested ($project) {
            $query = static::query("
                SELECT  SUM(amount) as much
                FROM    invest
                WHERE   project = :project
                AND     (invest.status = 0 OR invest.status = 1 OR invest.status = 3 OR invest.status = 4)
                ", array(':project' => $project));
            $got = $query->fetchObject();
            return (int) $got->much;
        }

        /*
         * Usuarios que han aportado aun proyecto
         */
        public static function investors ($project, $projNum = true, $showall = false) {
            $investors = array();

            $sql = "
                SELECT
                    invest.user as user,
                    user.name as name,
                    user.avatar as avatar,
                    invest.amount as amount,
                    DATE_FORMAT(invest.invested, '%d/%m/%Y') as date,
                    ";
            if ($projNum) {
                $sql .= "(SELECT
                        COUNT(DISTINCT(project))
                     FROM invest as invb
                     WHERE invb.user = invest.user
                     AND  invb.status IN ('0', '1', '3', '4')
                     ) as projects,";
            }

            $sql .= "user.hide as hide,
                     invest.anonymous as anonymous
                FROM    invest
                INNER JOIN user
                    ON  user.id = invest.user
                WHERE   project = ?
                AND     invest.status IN ('0', '1', '3', '4')
                ORDER BY invest.invested DESC
                ";

            $query = self::query($sql, array($project));
            foreach ($query->fetchAll(\PDO::FETCH_OBJ) as $investor) {

                $investor->avatar = Image::get($investor->avatar);
                if (empty($investor->avatar->id) || !$investor->avatar instanceof Image) {
                    $investor->avatar = Image::get(1);
                }


                // si el usuario es hide o el aporte es anonymo, lo ponemos como el usuario anonymous (avatar 1)
                if (!$showall && ($investor->hide == 1 || $investor->anonymous == 1)) {

                    // mantenemos la fecha del anonimo mas reciente
                    $anonymous_date = empty($investors['anonymous']->date) ? $investor->date : $investors['anonymous']->date;

                    $investors[] = (object) array(
                        'user' => 'anonymous',
                        'name' => Text::get('regular-anonymous'),
                        'projects' => null,
                        'avatar' => Image::get(1),
                        'worth' => null,
                        'amount' => $investor->amount,
                        'date' => $investor->date
                    );

                } else {

                    $investors[$investor->user] = (object) array(
                        'user' => $investor->user,
                        'name' => $investor->name,
                        'projects' => $investor->projects,
                        'avatar' => $investor->avatar,
                        'worth' => \Goteo\Model\User::calcWorth($investor->user),
                        'amount' => ($investors[$investor->user]->amount + $investor->amount),
                        'date' => $investor->date
                    );

                }

            }
            
            return $investors;
        }

        /*
         *  Aportaciones realizadas por un usaurio
         *  devuelve total y fecha de la última
         */
        public static function supported ($user, $project) {

            $sql = "
                SELECT  SUM(amount) as total, DATE_FORMAT(invested, '%d/%m/%Y') as date
                FROM    invest
                WHERE   user = :user
                AND     project = :project
                AND     invest.status IN ('0', '1', '3', '4')
                AND     (anonymous = 0 OR anonymous IS NULL)
                ORDER BY invested DESC";

            $query = self::query($sql, array(':user' => $user, ':project' => $project));
            return $query->fetchObject();
        }

        /*
         * Numero de cofinanciadores que han optado por cierta recompensa
         */
        public static function choosed ($reward) {

            $users = array();

            $sql = "
                SELECT  DISTINCT(user) as user
                FROM    invest
                INNER JOIN invest_reward
                    ON invest_reward.invest = invest.id
                    AND invest_reward.reward = ?
                INNER JOIN user
                    ON  user.id = invest.user
                    AND (user.hide = 0 OR user.hide IS NULL)
                WHERE   invest.status IN ('0', '1', '3', '4')
                ";

            $query = self::query($sql, array($reward));
            foreach ($query->fetchAll(\PDO::FETCH_ASSOC) as $investor) {
                $users[] = $investor['user'];
            }

            return $users;
        }


        /*
         * Asignar a la aportación una recompensas
         */
        public function setReward ($reward) {

            $values = array(
                ':invest' => $this->id,
                ':reward' => $reward
            );

            $sql = "REPLACE INTO invest_reward (invest, reward) VALUES (:invest, :reward)";
            if (self::query($sql, $values)) {
                return true;
            } else {
                return false;
            }
        }

        /*
         *  Actualiza el mail de la cuenta utilizada al registro del aporte
         */
        public function setAccount ($account) {

            $values = array(
                ':id' => $this->id,
                ':account' => $account
            );

            $sql = "UPDATE invest SET account = :account WHERE id = :id";
            if (self::query($sql, $values)) {
                return true;
            } else {
                return false;
            }

        }

        /*
         * Marcar una recompensa como cumplida (o desmarcarla)
         */
        public static function setFulfilled ($invest, $reward, $value = '1') {

            $values = array(
                ':value' => $value,
                ':invest' => $invest,
                ':reward' => $reward
            );

            $sql = "UPDATE invest_reward SET fulfilled = :value WHERE invest=:invest AND reward=:reward";
            if (self::query($sql, $values)) {
                return true;
            } else {
                return false;
            }
        }

        /*
         *  Pone el preapproval key al registro del aporte
         */
        public function setPreapproval ($key) {

            $values = array(
                ':id' => $this->id,
                ':preapproval' => $key
            );

            $sql = "UPDATE invest SET preapproval = :preapproval WHERE id = :id";
            if (self::query($sql, $values)) {
                return true;
            } else {
                return false;
            }
            
        }

        /*
         *  Cambia el estado de un aporte
         */
        public function setStatus ($status) {

            if (!in_array($status, array('-1', '0', '1', '2', '3', '4', '5'))) {
                return false;
            }

            $values = array(
                ':id' => $this->id,
                ':status' => $status
            );

            $sql = "UPDATE invest SET status = :status WHERE id = :id";
            if (self::query($sql, $values)) {
                return true;
            } else {
                return false;
            }

        }

        /*
         *  Pone el pay key al registro del aporte y la fecha de cargo
         */
        public function setPayment ($key) {

            $values = array(
                ':id' => $this->id,
                ':payment' => $key,
                ':charged' => date('Y-m-d')
            );

            $sql = "UPDATE  invest
                    SET
                        payment = :payment,
                        charged = :charged, 
                        status = 1
                    WHERE id = :id";
            if (self::query($sql, $values)) {
                return true;
            } else {
                return false;
            }

        }

        /*
         *  Pone el codigo de la transaccion al registro del aporte
         */
        public function setTransaction ($code) {

            $values = array(
                ':id' => $this->id,
                ':transaction' => $code
            );

            $sql = "UPDATE invest SET transaction = :transaction WHERE id = :id";
            if (self::query($sql, $values)) {
                return true;
            } else {
                return false;
            }

        }

        /*
         *  marca un aporte como devuelto (devuelto el dinero despues de haber sido cargado)
         */
        public function returnPayment () {

            $values = array(
                ':id' => $this->id,
                ':returned' => date('Y-m-d')
            );

            $sql = "UPDATE  invest
                    SET
                        returned = :returned,
                        status = 2
                    WHERE id = :id";
            if (self::query($sql, $values)) {
                return true;
            } else {
                return false;
            }

        }

        /*
         * Marcar esta aportación como cancelada
         */
        public function cancel () {

            $values = array(
                ':id' => $this->id,
                ':returned' => date('Y-m-d')
            );

            $sql = "UPDATE invest SET
                        returned = :returned,
                        status = 2
                    WHERE id = :id";
            
            if (self::query($sql, $values)) {
                return true;
            } else {
                return false;
            }

        }

        /*
         * Estados del aporte
         */
        public static function status ($id = null) {
            $array = array (
                -1 => 'En proceso',
                0  => 'Pendiente de cargo',
                1  => 'Cargo ejecutado',
                2  => 'Cancelado',
                3  => 'Pagado al proyecto',
                4  => 'Caducado',
                5  => 'Reubicado'
            );

            if (isset($id)) {
                return $array[$id];
            } else {
                return $array;
            }

        }

        /*
         * Métodos de pago
         */
        public static function methods () {
            return array (
                'paypal' => 'Paypal',
                'tpv'    => 'Tarjeta',
                'cash'   => 'Manual'
            );
        }


        /*
         * Metodo para obtener datos para el informe completo (con incidencias y netos)
         */
         public static function getReportData($project, $status, $round, $passed) {
             $Data = array();

            // segun estado, ronda y fecha de pase a segunda
            // el cash(1) es igual para todos
            switch ($status) {
                case 0: // descartado
                case 1: // edicion
                case 2: // revision
                case 6: // caducado
                    // Para estos cuatro estados es lo mismo:
                    // - Solo finaciacion actual
                    //      (aunque hiciera una ronda, aunque se descartara en segunda ronda)
                    // - Puede tener aportes en cash
                    // - Puede tener aportes caducados (pero no los mostramos)
                    // - Si tiene aportes de paypal(0,1) o tpv(1) es un problema

                    // A ver si tiene cash
                    // si hay aportes de cash activos no es incidencia porque puede venir de taller
                    // a menos que sea de convocatoria (que deberian estar cancelados)
                    $inv_cash = self::getList(array(
                        'methods' => 'cash',
                        'projects' => $project,
                        'investStatus' => '1'
                    ));
                    if (!empty($inv_cash)) {
                        $Data['note'][] = "Los aportes cash pueden venir de un taller y no son incidencias, solamente estan pendientes de reubicar";
                        $Data['cash']['total']['fail'] = 0;
                        foreach ($inv_cash as $invId => $invest) {
                            $Data['cash']['total']['users'][$invest->user] = $invest->user;
                            $Data['cash']['total']['invests']++;
                            $Data['cash']['total']['amount'] += $invest->amount;
                            if ($invest->campaign == 1) {
                                $Data['cash']['total']['fail'] += $invest->amount;
                                $Data['note'][] = "Aporte de capital riego {$invId} debería estar cancelado";
                            }
                        }
                    }

                    // A ver si tiene paypal
                    // si estan pendientes, ejecutados o pagados al proyecto es una incidencia
                    $inv_paypal = self::getList(array(
                        'methods' => 'paypal',
                        'projects' => $project
                    ));
                    if (!empty($inv_paypal)) {
//                        $Data['note'][] = "Los aportes de paypal son incidencias si están activos";
                        foreach ($inv_paypal as $invId => $invest) {
                            if (in_array($invest->investStatus, array(0, 1, 3))) {
                                $Data['paypal']['total']['fail'] += $invest->amount;
                                $Data['note'][] = "El aporte PayPal {$invId} no debería estar en estado '" . self::status($invest->investStatus) . "'";
                            }
                        }
                    }

                    // A ver si tiene tpv
                    // si estan pendientes, ejecutados o pagados al proyecto es una incidencia
                    $inv_tpv = self::getList(array(
                        'methods' => 'tpv',
                        'projects' => $project
                    ));
                    if (!empty($inv_tpv)) {
//                        $Data['note'][] = "Los aportes de tpv son incidencias si están activos";
                        foreach ($inv_tpv as $invId => $invest) {
                            if ($invest->investStatus == 1) {
                                $Data['tpv']['total']['fail'] += $invest->amount;
                                $Data['note'][] = "El aporte TPV {$invId} no debería estar en estado '" . self::status($invest->investStatus) . "'";
                            }
                        }
                    }


                    break;
                case 4: // financiado
                case 5: // exitoso
                    // en etos dos estados paypal(0) es incidencia en cualquier ronda
                    $p0 = (string) 'all';
                case 3: // en marcha
                    // si tiene fecha $project->passed de pase a segunda ronda: paypal(0) no es incidencia para los aportes de segunda ronda
                    if (!empty($passed)) {
                        if ($round == 1) {
                            // esto es mal
                            $Data['note'][] = "ATENCION! Está marcada la fecha de pase a segunda ronda (el {$passed}) pero sique en primera ronda!!! Informe solamente actual=primera";
                            $act_eq = (string) 'first';
                        } else {
                            // en segunda ronda
                            $p0 = (string) 'first'; // paypal(0) es incidencia paralos de primera ronda solamente
                            // si está en segunda ronda; la financiacion actual es un merge de usuarios y suma de aportes correctos, incidencias, correctos y cantidad total
                            $act_eq = (string) 'sum';
                        }
                    } else {
                        // si no tiene fecha de pase y esta en ronda 2: es un problema se trata como solo financiacion actual y paypal(0) no son incidencias
                        if ($round == 2) {
                            $Data['note'][] = "ATENCION! En segunda ronda pero NO está marcada la fecha de pase a segunda ronda!!! Informe solamente actual=primera";
                            $act_eq = (string) 'first';
                        } else {
                            // ok, en primera ronda sin  fecha marcada, informe solo actual = primera
                            $act_eq = (string) 'first';
                        }
                    }

                    // si solamente financiacion actual=primera
                    //   simple: no filtramos fecha
                    if ($act_eq === 'first') {
                        // CASH
                        $inv_cash = self::getList(array(
                            'methods' => 'cash',
                            'projects' => $project,
                            'investStatus' => '1'
                        ));
                        if (!empty($inv_cash)) {
                            $Data['cash']['first']['fail'] = 0;
                            foreach ($inv_cash as $invId => $invest) {
                                $Data['cash']['first']['users'][$invest->user] = $invest->user;
                                $Data['cash']['first']['invests']++;
                                $Data['cash']['first']['amount'] += $invest->amount;
                            }
                            $Data['cash']['total'] = $Data['cash']['first'];
                        }

                        // TPV
                        $inv_tpv = self::getList(array(
                            'methods' => 'tpv',
                            'projects' => $project,
                            'investStatus' => '1'
                        ));
                        if (!empty($inv_tpv)) {
                            $Data['tpv']['first']['fail'] = 0;
                            foreach ($inv_tpv as $invId => $invest) {
                                $Data['tpv']['first']['users'][$invest->user] = $invest->user;
                                $Data['tpv']['first']['invests']++;
                                $Data['tpv']['first']['amount'] += $invest->amount;
                            }
                            $Data['tpv']['total'] = $Data['tpv']['first'];
                        }


                        // PAYPAL
                        if (!empty($inv_paypal)) {
                            $Data['paypal']['first']['fail'] = 0;
                            foreach ($inv_paypal as $invId => $invest) {
                                if (in_array($invest->investStatus, array('0', '1', '3'))) {
                                    $Data['paypal']['first']['users'][$invest->user] = $invest->user;
                                    $Data['paypal']['first']['invests']++;
                                    $Data['paypal']['first']['amount'] += $invest->amount;
                                }
                            }
                            $Data['paypal']['total'] = $Data['paypal']['first'];
                        }

                    } elseif ($act_eq === 'sum') {
                        // complicado: primero los de primera ronda, luego los de segunda ronda sumando al total
                        // calcular ultimo dia de primera ronda segun la fecha de pase
                        $passtime = strtotime($passed);
                        $last_day = date('Y-m-d', \mktime(0, 0, 0, date('m', $passtime), date('d', $passtime)-1, date('Y', $passtime)));
                        
                        // CASH first
                        $inv_cash = self::getList(array(
                            'methods' => 'cash',
                            'projects' => $project,
                            'investStatus' => '1',
                            'date_until' => $last_day
                        ));
                        if (!empty($inv_cash)) {
                            $Data['cash']['first']['fail'] = 0;
                            foreach ($inv_cash as $invId => $invest) {
                                $Data['cash']['first']['users'][$invest->user] = $invest->user;
                                $Data['cash']['first']['invests']++;
                                $Data['cash']['first']['amount'] += $invest->amount;
                            }
                            $Data['cash']['total'] = $Data['cash']['first'];
                        }

                        // TPV first
                        $inv_tpv = self::getList(array(
                            'methods' => 'tpv',
                            'projects' => $project,
                            'investStatus' => '1',
                            'date_until' => $last_day
                        ));
                        if (!empty($inv_tpv)) {
                            $Data['tpv']['first']['fail'] = 0;
                            foreach ($inv_tpv as $invId => $invest) {
                                $Data['tpv']['first']['users'][$invest->user] = $invest->user;
                                $Data['tpv']['first']['invests']++;
                                $Data['tpv']['first']['amount'] += $invest->amount;
                            }
                            $Data['tpv']['total'] = $Data['tpv']['first'];
                        }


                        // PAYPAL first
                        $inv_paypal = self::getList(array(
                            'methods' => 'paypal',
                            'projects' => $project,
                            'date_until' => $last_day
                        ));
                        if (!empty($inv_paypal)) {
                            $Data['paypal']['first']['fail'] = 0;
                            foreach ($inv_paypal as $invId => $invest) {
                                if (in_array($invest->investStatus, array('0', '1', '3'))) {
                                    // a ver si cargo pendiente es incidencia...
                                    if ($invest->investStatus == 0 && ($p0 === 'first' || $p0 === 'all')) {
                                        $Data['paypal']['first']['fail'] += $invest->amount;
                                        $Data['note'][] = "El aporte paypal {$invId} no debería estar en estado '".self::status($invest->investStatus)."'";
                                        continue;
                                    }
                                    $Data['paypal']['first']['users'][$invest->user] = $invest->user;
                                    $Data['paypal']['first']['invests']++;
                                    $Data['paypal']['first']['amount'] += $invest->amount;
                                }
                            }
                            $Data['paypal']['total'] = $Data['paypal']['first'];
                        }

                        // CASH  second
                        $inv_cash = self::getList(array(
                            'methods' => 'cash',
                            'projects' => $project,
                            'investStatus' => '1',
                            'date_from' => $passed

                        ));
                        if (!empty($inv_cash)) {
                            $Data['cash']['second']['fail'] = 0;
                            foreach ($inv_cash as $invId => $invest) {
                                $Data['cash']['second']['users'][$invest->user] = $invest->user;
                                $Data['cash']['total']['users'][$invest->user] = $invest->user;
                                $Data['cash']['second']['invests']++;
                                $Data['cash']['total']['invests']++;
                                $Data['cash']['second']['amount'] += $invest->amount;
                            }
                            $Data['cash']['total']['amount'] += $Data['cash']['second']['amount'];
                        }


                        // TPV  second
                        $inv_tpv = self::getList(array(
                            'methods' => 'tpv',
                            'projects' => $project,
                            'investStatus' => '1',
                            'date_from' => $passed

                        ));
                        if (!empty($inv_tpv)) {
                            $Data['tpv']['second']['fail'] = 0;
                            foreach ($inv_tpv as $invId => $invest) {
                                $Data['tpv']['second']['users'][$invest->user] = $invest->user;
                                $Data['tpv']['total']['users'][$invest->user] = $invest->user;
                                $Data['tpv']['second']['invests']++;
                                $Data['tpv']['total']['invests']++;
                                $Data['tpv']['second']['amount'] += $invest->amount;
                            }
                            $Data['tpv']['total']['amount'] += $Data['tpv']['second']['amount'];
                        }

                        // PAYPAL second
                        $inv_paypal = self::getList(array(
                            'methods' => 'paypal',
                            'projects' => $project,
                            'date_from' => $passed
                        ));
                        if (!empty($inv_paypal)) {
                            $Data['paypal']['second']['fail'] = 0;
                            foreach ($inv_paypal as $invId => $invest) {
                                if (in_array($invest->investStatus, array('0', '1', '3'))) {
                                    // a ver si cargo pendiente es incidencia...
                                    if ($invest->investStatus == 0 && $p0 === 'all') {
                                        $Data['paypal']['second']['fail'] += $invest->amount;
                                        $Data['paypal']['total']['fail'] += $invest->amount;
                                        $Data['note'][] = "El aporte paypal {$invId} no debería estar en estado '".self::status($invest->investStatus)."'";
                                        continue;
                                    }
                                    $Data['paypal']['second']['users'][$invest->user] = $invest->user;
                                    $Data['paypal']['total']['users'][$invest->user] = $invest->user;
                                    $Data['paypal']['second']['invests']++;
                                    $Data['paypal']['total']['invests']++;
                                    $Data['paypal']['second']['amount'] += $invest->amount;
                                }
                            }
                            $Data['paypal']['total']['amount'] += $Data['paypal']['second']['amount'];
                        }

                        
                    } else {
                        $Data['note'][] = 'No se ha calculado bien el parametro $act_eq';
                    }



                    break;
            }

             return $Data;
         }


    }
    
}