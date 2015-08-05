#!/bin/bash
#
# Copyright (C) 2012 Platoniq y Fundación Goteo (see README for details)
#	This file is part of Goteo.
#
# Goteo is free software: you can redistribute it and/or modify
# it under the terms of the GNU Affero General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# Goteo is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Affero General Public License for more details.
#
# You should have received a copy of the GNU Affero General Public License
# along with Goteo.  If not, see <http://www.gnu.org/licenses/agpl.txt>.
cd "$(dirname "$0")"
LOG="logs/cli-sender-$(date +%Y-%m-%dT%H:%M)"
LOG_SEND="logs/cli-sendmail-"
TAR="logs/cli-sendmail-$(date +%Y-%m-%dT%H:%M)"
echo "procesando envios..."
echo "cron-sender:$(date)" > "$LOG.log"
/usr/bin/php cli-sender.php >> "$LOG.log" 2>&1
#comprobar si el numero de lineas del log es superior a 2
LINES=$(cat "$LOG.log" | wc -l)
if [[ $LINES -gt 2 ]] ; then
	echo "comprimiendo y conservando log de envio"
	gzip "$LOG.log"
	echo "comprimiendo archivos generados"
	tar cfz "$TAR"".tar.gz" "$LOG_SEND"*.log
else
	echo "no se ha enviado nada, eliminamos el log"
	rm "$LOG.log"
fi