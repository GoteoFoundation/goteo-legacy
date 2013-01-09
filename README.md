The code licensed here under the GNU Affero General Public License, version 3 [AGPL-3.0](http://www.gnu.org/licenses/agpl-3.0.html) has been developed by the Goteo team led by Platoniq and subsequently transferred to the Fundación Fuentes Abiertas, as detailed in http://www.goteo.org/about#info6

This is a web tool that allows the receipt, review and publishing of collective campaigns for their collective funding and the receiving of collaborations as well as the dynamic visualization of the support received, classification of initiatives and campaign tracking. The system also permits secure and distributed communication with users and between users, administration of highlighted projects on the home page and the creation of periodical publications such as blogs, a FAQ section and static pages.

It is a standard version of Goteo, except for the payment gateway and PayPal modules, whose development and adaptation should be undertaken by those who implement it, corresponding to the specified license and without maintenance, legal or any other responsibility of the Fundación Fuentes Abiertas.

This first version is provided as is accessible from this repository without additional documentation beyond the technical requirements, currently without possibility of advice   or customization in your installation nor dedication to solving technical problems by the Goteo development team.

To implement functional subdomains of the platform in Goteo autonomous node mode (ie,  hosted on the Fundación Fuentes Abiertas server and adapted to independently run by other organizations or groups that can ensure minimum joint resource correct operation) we recommend contacting us by the following e-mail: info [at] goteo.org

Implementation instructions:
- Upload repository files (except .sql and .doc)
- Create a database and run the script /db/goteo.sql
- Specify login credentials to the database in /config.php (GOTEO_DB_ constants *)

The technical details are in the /doc/plataforma_goteo.doc file


CREDITS 
Development (conceptualization, information architecture, text, programming and interface design): 
Susana Noguero, Olivier Schulbaum, Enric Senabre, Diego Bustamante, Julian Canaves, Ivan Verges

Translation of interface and texts
Catalan: Mireia Pui and Enric Senabre 
English: Liz Castro and Chris Pinchen 
French: Charlotte Rautureau, Julien Bellanger, Thomas Bernardi, Marie-Paule Uwase, Olivier Heinry, Christophe Moille, Olivier Schulbaum, Salah Malouli, Roland Kossigan Assilevi

Legal advice and data privacy: Jorge Campanillas and Alfonso Jorge Pacheco

Other code writers: Jaume Alemany, Philipp Keweloh, Susanna Kosic, Marc Hortelano, Pedro Medina

Developed with usage of: 
	html, css, xml, 
	javascript php, php PEAR packages, various licensed php classes, 
	jquery and licensed jquery plugins (SlideJS, CKeditor, Tipsy, MouseWheel, jScrollPane, FancyBox, DatePicker )
