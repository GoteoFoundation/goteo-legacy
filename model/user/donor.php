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


namespace Goteo\Model\User {

    use Goteo\Library\Check,
        Goteo\Library\Text;


    class Donor extends \Goteo\Core\Model {

        public
        $user,
        $amount,
        $name,
        $nif,
        $address,
        $zipcode,
        $location,
        $country,
        $numproj,
        $year,
        $edited = 0,
        $confirmed = 0,
        $pdf = null,
        $dates = array();

        public static $currYear = 2013; // año fiscal actual


        /**
         * Get invest data if a user is a donor
         * @param varcahr(50) $id  user identifier
         */
        public static function get($id, $year = null) {

            if (empty($year)) $year = static::$currYear;
            $year0 = $year;
            $year1 = $year + 1;

            try {

                // primero saber si es donante
                // aportes de este año en proyectos financiados (pasada primera ronda)
                // aportes de año pasado en proyectos que pasaron este año
                $sql = "SELECT COUNT(invest.id)
                        FROM invest
                        INNER JOIN project
                            ON project.id = invest.project
                            AND (project.passed IS NOT NULL AND project.passed != '0000-00-00')
                        WHERE   invest.status IN ('1', '3')
                        AND invest.user = :id
                        AND (
                            (invest.invested >= '{$year0}-01-01' AND invest.invested < '{$year1}-01-01') 
                            OR (invest.invested < '{$year0}-01-01' AND project.passed >= '{$year0}-01-01')
                        )";
                $query = static::query($sql, array(':id' => $id));
                $donativo = $query->fetchColumn();
                if (empty($donativo)) {
                    return false;
                } else {

                    // si ya ha introducido los datos, sacamos de user_donation
                    $sql = "SELECT * FROM user_donation WHERE user = :id AND year = '{$year}'";
                    $query = static::query($sql, array(':id' => $id));
                    if ($donation = $query->fetchObject(__CLASS__)) {
                        // actualizamos la cantidad y el numero de proyectos
                        $sql = "SELECT
                                    SUM(invest.amount) as amount,
                                    COUNT(DISTINCT(invest.project)) as numproj
                                FROM  invest
                                INNER JOIN project
                                    ON project.id = invest.project
                                    AND (project.passed IS NOT NULL AND project.passed != '0000-00-00')
                                WHERE   invest.user = :id
                                AND invest.status IN ('1', '3')
                                AND (
                                    (invest.invested >= '{$year0}-01-01' AND invest.invested < '{$year1}-01-01') 
                                    OR (invest.invested < '{$year0}-01-01' AND project.passed >= '{$year0}-01-01')
                                )
                                GROUP BY invest.user
                            ";
                        $query = static::query($sql, array(':id' => $id));
                        $data = $query->fetchObject();
                        $donation->amount = $data->amount;
                        $donation->numproj = $data->numproj;

                        return $donation;
                    } else {
                        // sino sacamos de invest_address
                        $sql = "SELECT  
                                    user.id as user,
                                    SUM(invest.amount) as amount,
                                    IF(invest_address.name,
                                        invest_address.name,
                                        user.name) as name,
                                    invest_address.nif as nif,
                                    IFNULL(invest_address.address, user_personal.address) as address,
                                    IFNULL(invest_address.zipcode, user_personal.zipcode) as zipcode,
                                    IFNULL(invest_address.country, user_personal.country) as country,
                                    COUNT(DISTINCT(invest.project)) as numproj,
                                    CONCAT('{$year}') as year
                                FROM  invest
                                INNER JOIN project
                                    ON project.id = invest.project
                                    AND (project.passed IS NOT NULL AND project.passed != '0000-00-00')
                                INNER JOIN user ON user.id = invest.user
                                LEFT JOIN invest_address ON invest_address.invest = invest.id
                                LEFT JOIN user_personal ON user_personal.user = invest.user
                                WHERE   invest.user = :id
                                AND invest.status IN ('1', '3')
                                AND (
                                    (invest.invested >= '{$year0}-01-01' AND invest.invested < '{$year1}-01-01') 
                                    OR (invest.invested < '{$year0}-01-01' AND project.passed >= '{$year0}-01-01')
                                )
                                GROUP BY invest.user
                            ";
                        $query = static::query($sql, array(':id' => $id));
                        $donation = $query->fetchObject(__CLASS__);
                        return $donation;
                    }
                }
            } catch (\PDOException $e) {
                throw new \Goteo\Core\Exception($e->getMessage());
            }
        }

        /* 
        * Listado de datos de donativos que tenemos
        * @param csv boolean si procesamos los datos para el excel
        */
        public function getList($filters = array(), $csv = false) {


            // naturaleza según tipo de persona (F, J)
            $nt = array(
                    'nif' => 'F',
                    'nie' => 'F',
                    'cif' => 'J'
                );
            // porcentaje segun tipo de persona (25, 35)
            $pt = array(
                    'nif' => '25',
                    'nie' => '25',
                    'cif' => '35'
                );

            $year = empty($filter['year']) ? static::$currYear : $filter['year'];
            $year0 = $year;
            $year1 = $year + 1;

            $values = array();

            $list = array();

            $sqlFilter = '';
            if (!empty($filters['user'])) {
                $user = $filters['user'];
                $sqlFilter .= " AND (user.id LIKE :user OR user.name LIKE :user OR user.email LIKE :user)";
                $values[':user'] = "%{$user}%";
            }

            if (!empty($filters['year'])) {
                $ayear = $filters['year'];
                $sqlFilter .= " AND DATE_FORMAT(invest.invested,'%Y') = :ayear";
                $values[':ayear'] = $ayear;
            }

            if (!empty($filters['status'])) {
                switch ($filters['status']) {
                    case 'pending': // Pendientes de confirmar
                        $sqlFilter .= " AND user_donation.user IS NULL";
                        break;
                    case 'edited': // Revisados
                        $sqlFilter .= " AND user_donation.edited = 1 AND (user_donation.confirmed IS NULL OR user_donation.confirmed = 0)";
                        break;
                    case 'confirmed': // Confirmados
                        $sqlFilter .= " AND user_donation.confirmed = 1";
                        break;
                    case 'emited': // Certificado emitido
                        $sqlFilter .= " AND (user_donation.pdf IS NOT NULL OR user_donation.pdf != '')";
                        break;
                    case 'notemited': //Confirmado pero no emitido
                        $sqlFilter .= " AND user_donation.confirmed = 1 AND (user_donation.pdf IS NULL OR user_donation.pdf = '')";
                        break;
                }
            }

            $sql = "SELECT
                        user.id as id,
                        user.email,
                        IFNULL(user_donation.name, invest_address.name) as name,
                        IFNULL(user_donation.nif, invest_address.nif) as nif,
                        IFNULL(user_donation.address, invest_address.address) as address,
                        IFNULL(user_donation.zipcode, invest_address.zipcode) as zipcode,
                        IFNULL(user_donation.location, invest_address.location) as location,
                        IFNULL(user_donation.country, invest_address.country) as country,
                        IFNULL(user_donation.amount, SUM(invest.amount)) as amount,
                        IFNULL(user_donation.numproj, COUNT(DISTINCT(invest.project))) as numproj,
                        CONCAT('{$year}') as year,
                        IFNULL(user_donation.user, 'Pendiente') as pending,
                        user_donation.edited as edited,
                        user_donation.confirmed as confirmed,
                        user_donation.pdf as pdf
                FROM  invest
                INNER JOIN project
                    ON project.id = invest.project
                    AND (project.passed IS NOT NULL AND project.passed != '0000-00-00')
                INNER JOIN user ON user.id = invest.user
                LEFT JOIN user_donation ON user_donation.user = invest.user AND user_donation.year = '{$year}'
                LEFT JOIN invest_address ON invest_address.invest = invest.id
                WHERE   invest.status IN ('1', '3')
                AND (
                    (invest.invested >= '{$year0}-01-01' AND invest.invested < '{$year1}-01-01') 
                    OR (invest.invested < '{$year0}-01-01' AND project.passed >= '{$year0}-01-01')
                )
                $sqlFilter
                GROUP BY invest.user
                ORDER BY user.email ASC";

            $query = self::query($sql, $values);
            $items = $query->fetchAll(\PDO::FETCH_OBJ);
            foreach ($items as $item) {
                if (empty($item->country)) {
                    $prov = '';
                } else {
                    // dos dígitos para la provincia  (99 si no es españa)
                    $prov = static::esPana($item->country) ? substr($item->zipcode, 0, 2) : '99';
                }

                // tipo de persona segun nif/nie/cif
                $type = '';
                Check::nif($item->nif, $type);
                $per = $pt[$type];
                $nat = $nt[$type]; 

// NIF;NIF_REPRLEGAL;Nombre;Provincia;CLAVE;PORCENTAJE;VALOR;EN_ESPECIE;COMUNIDAD;PORCENTAJE_CA;NATURALEZA;REVOCACION;EJERCICIO;TIPOBIEN;BIEN
                $list[] = array($item->nif, '', 
                    $item->name, 
                    $prov, 'A', $per, $item->amount, '', '', '', $nat, '', $item->year, '', '', '');
            }
            return $list;
        }

        static function esPana($str) {
            $str = strtolower($str);
            return (substr($str, 0, 4) == 'espa' || $str == 'spain');
        }

        public function validate(&$errors = array()) {
            if (empty($this->year)) 
                $this->year = self::$currYear;

            if (!empty($this->nif) && !Check::nif($this->nif)) {
                $errors['nif'] = Text::get('validate-project-value-contract_nif');
            }
        }

        /*
         *  Guarda los datos de donativo de un usuario
         */

        public function save(&$errors = array()) {

            $fields = array(
                'user',
                'amount',
                'name',
                'nif',
                'address',
                'zipcode',
                'location',
                'country',
                'numproj',
                'year',
                'edited'
            );

            $set = '';
            $values = array();

            foreach ($fields as $field) {
                if ($set != '')
                    $set .= ', ';
                $set .= "$field = :$field";
                $values[":$field"] = $this->$field;
            }

            try {
                $sql = "REPLACE INTO user_donation (" . implode(', ', $fields) . ") VALUES (" . implode(', ', array_keys($values)) . ")";
                self::query($sql, $values);
                return true;
            } catch (\PDOException $e) {
                $errors[] = "Los datos no se han guardado correctamente. Por favor, revise los datos." . $e->getMessage();
                return false;
            }

        }

        public static function setConfirmed($user) {
            try {
                $year = static::$currYear;

                $sql = "UPDATE user_donation SET confirmed = 1 WHERE user = :user AND year = :year";
                if (self::query($sql, array(':user' => $user, 'year' => $year))) {
                    return true;
                } else {
                    return false;
                }
            } catch (\PDOException $e) {
                $errors[] = "Los datos no se han guardado correctamente. Por favor, revise los datos." . $e->getMessage();
                return false;
            }
        }
        
        /*
         * Nombre del archivo de certificado generado
         */
        public function setPdf($filename) {
            try {
                $sql = "UPDATE user_donation SET pdf = :pdf WHERE user = :user AND year = :year";
                if (self::query($sql, array(':pdf'=>$filename,':user' => $this->user, 'year' => $this->year))) {
                    $this->pdf = $filename;
                    return true;
                } else {
                    return false;
                }
            } catch (\PDOException $e) {
                $errors[] = "Los datos no se han guardado correctamente. Por favor, revise los datos." . $e->getMessage();
                return false;
            }
        }


        /*
         * Resetear pdf
         */
        static public function resetPdf($xfilename) {
            try {
                $sql = "UPDATE user_donation SET pdf = NULL WHERE MD5(pdf) = :pdf";
                if (self::query($sql, array(':pdf'=>$xfilename))) {
                    $path = 'data/pdfs/donativos/'.$xfilename;
                    unset($path);

                    return true;
                } else {
                    return false;
                }
            } catch (\PDOException $e) {
                $errors[] = "Los datos no se han guardado correctamente. Por favor, revise los datos." . $e->getMessage();
                return false;
            }
        }


        static public function getDates ($user, $year = null) {

            if (empty($year)) $year = static::$currYear;
            $year0 = $year;
            $year1 = $year + 1;

            $fechas = array();

            // Fechas de donativos
            $sql = "SELECT 
                        DATE_FORMAT(invest.charged, '%d-%m-%Y') as date,
                        invest.amount as amount,
                        project.name as project
                    FROM invest
                    INNER JOIN project
                        ON project.id = invest.project
                        AND (project.passed IS NOT NULL AND project.passed != '0000-00-00')
                    WHERE   invest.status IN ('1', '3')
                    AND invest.user = :id
                    AND (
                        (invest.invested >= '{$year0}-01-01' AND invest.invested < '{$year1}-01-01') 
                        OR (invest.invested < '{$year0}-01-01' AND project.passed >= '{$year0}-01-01')
                    )
                    ORDER BY invest.invested ASC
                    ";
//                    echo($sql . '<br />' . $user);
            $query = static::query($sql, array(':id' => $user));
            foreach ($query->fetchAll(\PDO::FETCH_OBJ) as $row) {
                $fechas[] = $row;
            }

            return $fechas;
        }

    }

}