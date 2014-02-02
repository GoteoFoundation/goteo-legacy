#!/bin/bash
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
