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


namespace Goteo\Library {

	use Goteo\Core\Model,
        Goteo\Core\Exception,
        Goteo\Library\Template,
        Goteo\Core\View;
	/*
	 * Clase para hacer envios masivos en segundo plano
	 *
	 */
    class Sender {

        /*
        *  Metodo para obtener la siguiente tanda de destinatarios
        */
        static public function getRecipients ($id, $limit=10) {
            $list = array();

            $sql = "SELECT
                    id,
                    user,
                    name,
                    email
                FROM mailer_send
                WHERE mailing = ?
                AND sended IS NULL
                AND blocked IS NULL
                ORDER BY id
                ";
                if($limit) $sql .= "LIMIT $limit
                ";

            if ($query = Model::query($sql, array($id))) {
                foreach ($query->fetchAll(\PDO::FETCH_OBJ) as $receiver) {
                    $list[] = $receiver;
                }
            }

            return $list;

        }

		static public function initiateSending ($mailId, $subject, $receivers, $autoactive = 0, $reply = null, $reply_name = null) {

            /*
             * Grabar el contenido para el sinoves en la tabla mail, obtener el id y el codigo para sinoves
             *
             */


            try {
                Model::query("START TRANSACTION");

                $sql = "INSERT INTO `mailer_content` (`id`, `active`, `mail`, `subject`, `blocked`, `reply`, `reply_name`)
                    VALUES ('' , '{$autoactive}', :mail, :subject, 0, :reply, :reply_name)";
                Model::query($sql, array(':subject'=>$subject, ':mail'=>$mailId, ':reply'=>$reply, ':reply_name'=>$reply_name));
                $mailing = Model::insertId();

                // destinatarios
                $sql = "INSERT INTO `mailer_send` (`id`, `mailing`, `user`, `email`, `name`)
                 VALUES ('', :mailing, :user, :email, :name)";

                foreach ($receivers as $user) {
                    Model::query($sql,
                        array(':mailing'=>$mailing, ':user'=>$user->user, ':email'=>$user->email, ':name'=>$user->name)
                        );
                }

                Model::query("COMMIT");
                return true;

            } catch(\PDOException $e) {
                echo "HA FALLADO!!" . $e->getMessage();
                die;
                return false;
            }

        }

        /*
        * Método para obtener el siguiente envío a tratar
        */
        static public function getSending ($id = null) {
            try {

                if (!empty($id)) {
                    $sqlFilter = " WHERE id = $id";

                } else {
                    $sqlFilter = " ORDER BY active DESC, id DESC ";

                }

                // recuperamos los datos del envío
                $sql = "SELECT
                        mailer_content.id as id,
                        mailer_content.active as active,
                        mailer_content.mail as mail,
                        mailer_content.subject as subject,
                        DATE_FORMAT(mailer_content.datetime, '%d/%m/%Y %H:%i:%s') as date,
                        mailer_content.blocked as blocked,
                        mailer_content.reply as reply,
                        mailer_content.reply_name as reply_name
                    FROM mailer_content
                    $sqlFilter
                    LIMIT 1
                    ";

                $query = Model::query($sql);
                $mailing = $query->fetchObject();

                // y el estado
                if (!empty($mailing->id)) {
                    $query = Model::query("
                    SELECT
                            COUNT(mailer_send.id) AS receivers,
                            SUM(IF(mailer_send.sended = 1, 1, 0)) AS sended,
                            SUM(IF(mailer_send.sended = 0, 1, 0)) AS failed,
                            SUM(IF(mailer_send.sended IS NULL, 1, 0)) AS pending
                    FROM    mailer_send
                    WHERE mailer_send.mailing = {$mailing->id}
                    ");
                    $sending = $query->fetchObject();

                    $mailing->receivers = $sending->receivers;
                    $mailing->sended    = $sending->sended;
                    $mailing->failed    = $sending->failed;
                    $mailing->pending   = $sending->pending;
                }

                return $mailing;
            } catch(\PDOException $e) {
                $errors[] = "HA FALLADO!!" . $e->getMessage();
                return false;
            }
        }

        /*
        * Método para obtener el listado de envios programados
        */
		static public function getMailings () {

            $list = array();

            // recuperamos los datos del envío
            $sql = "SELECT
                    mailer_content.id as id,
                    mailer_content.active as active,
                    mailer_content.mail as mail,
                    mailer_content.subject as subject,
                    DATE_FORMAT(mailer_content.datetime, '%d/%m/%Y %H:%i:%s') as date,
                    mailer_content.blocked as blocked
                FROM mailer_content
                ORDER BY id DESC
                ";

            if ($query = Model::query($sql)) {
                foreach ($query->fetchAll(\PDO::FETCH_OBJ) as $mailing) {

                    $mailing->link = '/mail/'.base64_encode(md5(uniqid()).'¬any¬'.$mailing->mail).'/?email=any';


                    $list[] = $mailing;
                }
            }

            return $list;

        }

        /*
         * Listado completo de destinatarios/envaidos/fallidos/pendientes
         */
		static public function getDetail ($mailing, $detail = 'receivers') {

            $list = array();

            $sqlFilter = " AND mailer_send.mailing = {$mailing}";

            switch ($detail) {
                case 'sended':
                    $sqlFilter .= " AND mailer_send.sended = 1";
                    break;
                case 'failed':
                    $sqlFilter .= " AND mailer_send.sended = 0";
                    break;
                case 'pending':
                    $sqlFilter .= " AND mailer_send.sended IS NULL";
                    break;
                case 'receivers':
                default:
                    break;
            }

            $sql = "SELECT
                    user.id as user,
                    user.name as name,
                    user.email as email
                FROM user
                INNER JOIN mailer_send
                    ON mailer_send.user = user.id
                    $sqlFilter
                ORDER BY user.id";

            if ($query = Model::query($sql, $values)) {
                foreach ($query->fetchAll(\PDO::FETCH_OBJ) as $user) {
                    $list[] = $user;
                }
            }

            return $list;

        }


		static public function activateSending ($id) {
            // marcamos como activo el envio
            $query = Model::query("UPDATE mailer_content SET active = 1 WHERE id = {$id}");
            return ($query->rowCount() == 1);
        }


        /*
        *  Metodo para limpieza de envíos masivos enviados y sus destinatarios
        */
        static public function cleanOld() {

            // eliminamos los envíos de hace más de dos días
            Model::query("DELETE FROM mailer_content WHERE active = 0
             AND DATE_FORMAT(from_unixtime(unix_timestamp(now()) - unix_timestamp(datetime)), '%j') > 2");
            // eliminamos los destinatarios
            Model::query("DELETE FROM mailer_send WHERE mailing NOT IN (SELECT id FROM mailer_content)");

        }



	}

}