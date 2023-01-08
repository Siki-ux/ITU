# ITU - Chytni závadu!
Tento školský projekt je webovou aplikáciou na hlásenie a správu hlásení závad v meste.
## Aplikácia
Fungujúca aplikácia je dostupná na stránke: https://www.stud.fit.vutbr.cz/~xsikul15/ITU/index.php.<br>
Základné vytvorené účty sú:<br>
* **Obyžajný užívateľ:** Email: `user@fit.com` Heslo: `user` 
* **Technik:** Email: `worker@fit.com` Heslo: `worker`
* **Správca** Email: `manager@fit.com` Heslo: `manager`
* **Admin:** Email: `admin@fit.com` Heslo: `admin` 
## Popis inštalácie
### Databáza
Celú databázu ide vytvoriť a nainicializovať MySQL skriptom *script.sql*. Samozrejme, za predpokladu že server podporuje MySQL databázu.
### Aplikácia
Pre rozbehnutie aplikácie je postačujúce prekopírovať zdrojové súbory na adekvátny hosting. Ďalej treba v súbore *db_setup.php* naststaviť *PDO* pre databázu. Inak je *PDO* nastavené na databbázu na serveri *eva.fit.vutbr.cz* pod účtom *xsikul15*.<br>
Hosting by mal podporovať PHP8.1, JavaScript, HTML5, jQuerry a iné základné webové prostriedky.
## Knižnice
Angular: https://angularjs.org<br>
jQuery: https://jquery.com<br>
FontAwesome: https://fontawesome.com<br>
Lodash: https://lodash.com<br>
polyfill: https://polyfill.io<br>

