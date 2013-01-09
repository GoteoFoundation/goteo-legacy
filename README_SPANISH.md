El código licenciado aquí bajo la GNU Affero General Public License, versión 3 [AGPL-3.0](http://www.gnu.org/licenses/agpl-3.0.html) ha sido desarrollado por el equipo de Goteo bajo la dirección de Platoniq y cedido posteriormente a la Fundación Fuentes Abiertas, tal como se detalla en http://www.goteo.org/about#info6

Se trata de una herramienta web que permite la recepción, revisión y publicación de campañas para su financiación colectiva y recepción de colaboraciones, así como la visualización dinámica de los apoyos recibidos, clasificación de iniciativas y seguimiento de campañas. Mediante el sistema también se permite gestionar la comunicación segura y distribuida con los usuarios y entre estos, administración de proyectos destacados en portada y creación de publicaciones periódiocas tipo blog, sección de FAQs y páginas estáticas. 

Es una versión standard de Goteo, exceptuando los módulos propios de pasarela de pago por TPV y PayPal, cuyo desarrollo y adaptación deben llevarse a cabo por parte de quien lo implemente, en correspondencia con la licencia especificada y sin responsabilidad de mantenimiento, jurídica o de ningún otro tipo por parte de la Fundación Fuentes Abiertas. 

Esta primera versión se facilita según es accesible desde este repositorio sin documentación adicional más allá de los requerimientos técnicos, sin posibilidad actualmente de asesoramiento en su instalación o personalización ni dedicación a la resolución de incidencias técnicas por parte del equipo desarrollador de Goteo.

Para la implementación de subdominios funcionales de la plataforma en modalidad de nodo autónomo de Goteo (esto es, alojados en servidor de la Fundación Fuentes Abiertas y adaptados para gestión independiente por parte de otras entidades o colectivos, que puedan garantizar la articulación mínima de recursos para su correcto funcionamiento) recomendamos contactar mediante la siguiente dirección de correo electrónico: info[arroba]goteo.org

Instrucciones para la implementación:
- Subir al alojamiento los archivos del repositorio (excepto .sql y .doc)
- Crear una base de datos y ejecutar en ella el script /db/goteo.sql
- Especificar los credenciales de conexión a la base de datos en el archivo /config.php (contantes GOTEO_DB_*)

Los detalles técnicos se encuentran en el archivo /doc/plataforma_goteo.doc


CREDITOS
Desarrollo herramienta (conceptualización, arquitectura de la información, textos, programación y diseño de interface):
Susana Noguero, Olivier Schulbaum, Enric Senabre, Diego Bustamante, Julián Cánaves, Iván Vergés

Traducción de interface y textos
Catalán: Mireia Pui y Enric Senabre
Inglés: Liz Castro y Chris Pinchen
Francés: Charlotte Rautureau, Julien Bellanger, Thomas Bernardi, Marie-Paule Uwase, Olivier Heinry, Christophe Moille, Olivier Schulbaum, Salah Malouli, Roland Kossigan Assilevi

Asesoría legal y privacidad de datos: Jorge Campanillas y Alfonso Pacheco

Other code writers: Jaume Alemany, Philipp Keweloh, Susanna Kosic, Marc Hortelano, Pedro Medina
  
Developed with usage of:
	html, css, xml, javascript
	php, php PEAR packages, various licensed php classes,
	jquery and licensed jquery plugins (SlideJS, CKeditor, Tipsy, MouseWheel, jScrollPane, FancyBox, DatePicker )

