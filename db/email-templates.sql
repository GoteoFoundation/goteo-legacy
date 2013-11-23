--
-- This SQL script contains the default email templates
-- These templates have no body, so email will not work right away when installing them
-- You need to compose these templates and decide how you are going to communicate with your users 
--
INSERT INTO `template` VALUES(1, 'Mensaje de contacto', 'Plantilla para un mensaje de contacto desde Goteo', 'Contacto desde Goteo: %SUBJECT%', '');
INSERT INTO `template` VALUES(2, 'Mensaje a los cofinanciadores', 'Plantilla del mensaje masivo a cofinanciadores desde dashboard - gestión de retornos', 'Mensaje de un promotor', '');
INSERT INTO `template` VALUES(3, 'Mensaje al autor', 'Plantilla del mensaje al autor después de aportar a su proyecto', 'Mensaje de un/a cofinanciador/a de %PROJECTNAME%', '');
INSERT INTO `template` VALUES(4, 'Mensaje entre usuarios', 'Mensaje de un usuario a otro desde la página de perfil del destinatario', 'Mensaje personal de %USERNAME% desde Goteo', '');
INSERT INTO `template` VALUES(5, 'Confirmación de registro', 'Plantilla del mensaje de confirmación de registro', 'Confirmación de registro en Goteo', '');
INSERT INTO `template` VALUES(6, 'Recuperar contraseña', 'Plantilla para el mensaje al solicitar la recuperación de contraseña', 'Petición de recuperación de contraseña en Goteo', '');
INSERT INTO `template` VALUES(7, 'Cambio de email', 'Plantilla del mensaje al cambiar el email', 'Petición de cambio de email en Goteo', '');
INSERT INTO `template` VALUES(8, 'Confirmacion de proyecto enviado', 'Mensaje al usuario cuando envia un proyecto a revisión desde el preview del formulario', 'El proyecto %PROJECTNAME% ha pasado a fase de valoración', '');
INSERT INTO `template` VALUES(9, 'Darse de baja', 'Plantilla para el mensaje al solicitar la baja', 'Solicitud de baja en Goteo', '');
INSERT INTO `template` VALUES(10, 'Agradecimiento aporte', 'Mensaje al usuario después de aportar a un proyecto', 'Gracias por cofinanciar el proyecto %PROJECTNAME%', '');
INSERT INTO `template` VALUES(11, 'Comunicación desde admin', 'Plantilla para un mensaje de comunicación enviado desde admin a los destinatarios seleccionados', 'El asunto lo pone el admin', '');
INSERT INTO `template` VALUES(12, 'Mensaje al autor de un thread', 'Plantilla del mensaje al autor de un hilo de mensajes cuando hay una respuesta', 'Respuesta a tu mensaje en el proyecto %PROJECTNAME%', '');
INSERT INTO `template` VALUES(13, 'Aviso de 8 días para fallar', 'Mensaje al autor de un proyecto cuando este está proximo (8 dias) a fallar (no minimo)', 'Al proyecto %PROJECTNAME% le faltan 8 días para caducar', '');
INSERT INTO `template` VALUES(14, 'Aviso de 1 día para fallar', 'Mensaje al autor de un proyecto cuando este está condenado a fallar', 'Al proyecto %PROJECTNAME% le falta 1 día para caducar', '');
INSERT INTO `template` VALUES(15, 'Agradecimiento cofinanciadores si supera primera', 'Mensaje a los cofinanciadores de un proyecto cuando este supera la primera ronda', 'El proyecto %PROJECTNAME% ha pasado a segunda ronda en Goteo', '');
INSERT INTO `template` VALUES(16, 'Agradecimiento cofinanciadores final segunda', 'Mensaje a los cofinanciadores de un proyecto cuando este llega al final de la segunda ronda', 'El proyecto %PROJECTNAME% ha finalizado la segunda ronda', '');
INSERT INTO `template` VALUES(17, 'Aviso cofinanciadores proyecto fallido', 'Mensaje a los cofinanciadores de un proyecto cuando este caduca sin conseguir el mínimo', 'El proyecto %PROJECTNAME% no ha logrado su objetivo mínimo en Goteo :(', '');
INSERT INTO `template` VALUES(18, 'Aviso cofinanciadores novedade en proyecto', 'Mensaje a los cofinanciadores de un proyecto cuando se publica una novedad en este', 'El proyecto %PROJECTNAME% ha publicado novedades', '');
INSERT INTO `template` VALUES(19, 'Recuerdo al autor a los 20 días', 'Mensaje al autor de un proyecto cuando este lleva 20 días de campaña', 'El proyecto %PROJECTNAME% lleva 20 días en campaña', '');
INSERT INTO `template` VALUES(20, 'Notificación al autor proyecto supera primera ronda', 'Mensaje al autor de un proyecto cuando este pasa a segunda ronda', 'El proyecto %PROJECTNAME% ha pasado a segunda ronda', '');
INSERT INTO `template` VALUES(21, 'Notificación al autor proyecto fallido', 'Mensaje al autor de un proyecto cuando este caduca sin conseguir el mínomo', 'El proyecto %PROJECTNAME% ha caducado', '');
INSERT INTO `template` VALUES(22, 'Notificación al autor proyecto fin segunda ronda', 'Mensaje al autor de un proyecto cuando este finaliza la segunda ronda', 'El proyecto %PROJECTNAME% ha finalizado la segunda ronda', '');
INSERT INTO `template` VALUES(23, 'Recuerdo al autor proyecto sin novedades', 'Mensaje mensual al autor de un proyecto si no ha publicado novedades durante 3 meses', 'El proyecto %PROJECTNAME% sin novedades', '');
INSERT INTO `template` VALUES(24, 'Recuerdo al autor proyecto sin actividad', 'Mensaje bisemanal al autor de un proyecto si este no ha tenido actividad durante 3 meses', 'El proyecto %PROJECTNAME% sin actividad', '');
INSERT INTO `template` VALUES(25, 'Recuerdo al autor proyecto financiado', 'Mensaje al autor de un proyecto después de 2 meses de haber sido financiado', 'El proyecto %PROJECTNAME% hace 2 meses que se financió', '');
INSERT INTO `template` VALUES(26, 'Informa al autor de proyecto listo para traducción', 'Plantilla del mensaje al autor al habilitar la traducción de su proyecto', 'Ya puedes traducir tu proyecto %PROJECTNAME%', '');
INSERT INTO `template` VALUES(27, 'Aviso a los talleristas', 'Plantilla del mensaje a los usuarios que aun tienen su email como contraseña', 'El crowdfunding de Goteo.org en marcha', '');
INSERT INTO `template` VALUES(28, 'Agradecimiento donativo', 'Mensaje al usuario aporta renunciando a la recompensa', 'Gracias por tu donativo al proyecto %PROJECTNAME%', '');
INSERT INTO `template` VALUES(29, 'Notificación de nuevo aporte al autor', 'Mensaje al autor de un proyecto cuando un nuevo aporte', 'Nuevo aporte al proyecto %PROJECTNAME%', '');
INSERT INTO `template` VALUES(30, 'Notificacion nuevo thread', 'Mensaje al autor de un proyecto cuando se abre un nuevo hilo de mensajes', 'Nuevo hilo de mensajes en el proyecto %PROJECTNAME%', '');
INSERT INTO `template` VALUES(31, 'Notificación comentario en novedades', 'Mensaje al autor de un proyecto cuando hay un comentario en las novedades', 'Nuevo comentario en las Novedades del proyecto %PROJECTNAME%', '');
INSERT INTO `template` VALUES(32, 'Informa al autor de convocatoria lista para traducción', 'Plantilla del mensaje al convocador al habilitar la traducción de su Convocatoria', 'Ya puedes traducir tu convocatoria %CALLNAME%', '');
