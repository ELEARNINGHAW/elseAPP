{config_load file='site.conf'}
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"  "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <link rel="stylesheet" href="lib/style.css" type="text/css" media="screen" />
<style type="text/css">
<!--
body
{
  min-width:400px;
  max-width:550px;
  font-size:0.9em;
}


-->
</style>
  
  
</head>
<body>
<div class="help_body">
{if $topic=="leihfrist"}
<h3>Leihfrist</h3>
<p>
<p> Ein Semesterapparat wird nach Ablauf der angegebenen Leihfrist 
(d.h. am Semesterende) aufgel&ouml;st. Dabei werden die B&uuml;cher
entfernt, und an ihren urspr&uuml;nglichen Standort 
in der Bibliothek zur&uuml;ckgestellt. Online-Dokumente und Web-Links 
bleiben dagegen erhalten. 
</p>
<p> Wird ein Semesterapparat auch f&uuml;r das Folgesemester noch 
ben&ouml;tigt, so bitten wir um rechtzeitige Verl&auml;ngerung der 
Leihfrist.
</p>
<p>Jedes editieren des Semesterapparates bewirkt eine automatische Verlängerung.
</p>

<p>
Nach seiner Aufl&ouml;sung "ruht" der Semesterapparat. Er kann &uuml;ber 
die Funktion <em>Erneuern</em> wieder aktiviert 
und benutzt werden. 
</p>
<p>
Wird ein Semesterapparat endg&uuml;ltig nicht mehr ben&ouml;tigt, kann man
ihn mit der Funktion <em>L&ouml;schen</em> aus der Datenbank
entfernen. Dabei werden auch automatisch alle darin enthaltenen Büchern zurückgegeben und die Verlinkung zu E-Books gelöscht.
</p>
<p>
<em>Siehe auch: <a href="help.php?topic=rueckgabe">Buchr&uuml;ckgabe</a></em>
</p>
{elseif $topic=="state"}
<h3>Bestellstatus</h3>
<dl>
<dt><span style="color: purple"><b>neu bestellt</b></span>  
</dt>
<dd>
<p>

Die Bestellung wurde im System erfa&szlig;t, ist aber noch nicht 
durch eine/n Bibliothekar/in bearbeitet worden.
</p>
</dd>
<dt><span style="color: blue"><b>wird bearbeitet</b></span> 
</dt>
<dd>
<p>
Die Bibliothek hat die Bestellung angenommen. Das Werk wird 
beschafft und in den Semesterapparat aufgenommen.
</p>
<dt><span style="color: green"><b>aktiv</b></span> </dt>
<dd>
<p>
Das Dokument ist in den Semesterapparat aufgenommen worden, 
und ist f&uuml;r die Studierenden verf&uuml;gbar.  Die Bestellung ist 
erfolgreich abgeschlossen worden.
</p>
<p>
Konnte eine Bestellung dagegen <em>nicht</em> erf&uuml;llt werden, 
wird der Status auf <span style="color:grey"><b>inaktiv</b></span> 
gesetzt.
</p>
</dd>
<dt><span style="color: red"><b>wird entfernt</b></span> </dt>
<dd>
<p>
Das Werk wird nicht mehr ben&ouml;tigt (R&uuml;ckgabe, Aufl&ouml;sung des 
Semesterapparats, o.&auml;.).  Ein/e Bibliothekar/in entfernt es aus 
dem Semesterapparat, und stellt es zur&uuml;ck an den urspr&uuml;nglichen Platz. 
Danach wechselt der Bestellstatus 
auf <span style="color:grey"><b>inaktiv</b></span>.
</p>
</dd>
<dt><span style="color: gray"><b>inaktiv</b></span></dt>
<dd>
<p>
Die Bestellung "ruht". Dieser Zustand tritt ein, nachdem ein 
Dokument aus dem Semesterapparat entfernt wurde, oder 
wenn eine Bestellung nicht erf&uuml;llt werden konnte (siehe oben). 
Man kann eine ruhende Bestellung wieder aufleben 
lassen ("erneuern"), oder aber endg&uuml;ltig l&ouml;schen. 
</p>
<p>
Bei <em>Online-Dokumenten</em> und <em>Weblinks</em> bedeutet der Status
"inaktiv", dass der betreffende Eintrag nur f&uuml;r den Dozenten 
sichtbar ist, jedoch nicht f&uuml;r die Studenten.
</dd>

</dl>
</p>
<p>

</p>
{elseif $topic=="items"}
<h3>Dokumenttypen</h3>
<p>
<b>B&uuml;cher</b> k&ouml;nnen aus dem Bestand aller Fachbibliotheken des HIBS bestellt werden.
Sie werden am angegebenen Standort aufgestellt und k&ouml;nnen f&uuml;r 
diesen Zeitraum nicht ausgeliehen werden. Die B&uuml;cher stehen 
als Pr&auml;senzexemplare allen Studierenden gleicherma&szlig;en zur 
Verf&uuml;gung.
<p><b>E-Books</b>.</p>
{elseif $topic=="dozent"}
<h3>Erste Schritte</h3>
<p>Dozenten k&ouml;nnen ihren eigenen Semesterapparat verwalten.
</p>
<p>
Zun&auml;chst  muss ein Semesterapparat angelegt 
werden. Hierbei werden Angaben zu Titel der Vorlesung abgefragt. Die <a href="help.php?topic=leihfrist">Leihfrist</a> wird automatisch auf Anfang des nächsten Semesters gesetzt.
</p>
<p>
Es lassen sich beliebig viele Semesterapparate anlegen (z.B. zu verschiedenen Vorlesungen). In der Gesamt&uuml;bersicht 
des Dozenten werden nur die eigenen Semesterapparate angezeigt.
</p>
{elseif $topic=="edit"}
<h3>Semesterapparat bearbeiten</h3>
<p>
Ein neuer Semesterapparat enth&auml;lt zun&auml;chst keine Dokumente.
Um ihn mit Inhalt zu f&uuml;llen, verwendet man die Funktion 
<em>Bearbeiten</em>. 
</p>
<p>
Nach dem Klick auf 'Bearbeiten' sehen Sie ein Suchfeld in dem Sie nach Titel/Autor/Signatur suchen können.
</p>
<p>
Die Trefferliste wird Ihnen angezeigt und Sie können das gewünschte Buch oder E-Book wählen.
</p>
<p>
Im nächsten Schritt können Sie noch weitere Angaben machen, wie den Informationstext für die Studierende und bei Büchern auch ein Informationstext für die Bibliotheksmitarbeiterinnen.
</p>

<p>
E-Books sind sofort aktiv, Bücher müssen von den Bibliotheksmitarbeiterinnen noch entsprechend bearbeitet werden.  
</p>


<p>
Der aktuelle Bearbeitungsstand l&auml;sst sich anhand des 
<a href="help.php?topic=state">Bestellstatus</a> jederzeit nachvollziehen. 
Ferner wird der Dozent bei erfolgter Aufnahme einer Bestellung in den Semesterappart (Status "aktiv") 
<a href="help.php?topic=email">per E-Mail informiert</a>.
</p>

<p>
Nach Ablauf einer vorher festgelegten 
<a href="help.php?topic=leihfrist">Leihfrist</a> 
wird der Semesterapparat aufgel&ouml;st. Nicht mehr ben&ouml;tigte
B&uuml;cher kann der Dozent auch vorzeitig 
<a href="help.php?topic=rueckgabe">zur&uuml;ckgeben</a>.
</p>
{elseif $topic=="rueckgabe"}
<h3>Buchr&uuml;ckgabe</h3>
<p>
Nicht mehr ben&ouml;tigte B&uuml;cher 
k&ouml;nnen &uuml;ber die Funktion <em>R&uuml;ckgabe</em>
vorzeitig wieder freigegeben werden. Sie werden dann von einem 
Bibliotheksmitarbeiter aus dem Semesterapparat entfernt und an ihren
urspr&uuml;nglichen Standort zur&uuml;ckgestellt.
</p>
<p>
Auch nach Abschluss der R&uuml;ckgabe wird der zugeh&ouml;rige Eintrag 
im Semesterapparat nicht gel&ouml;scht. Stattdessen wird der 
Bestellstatus auf "inaktiv" gesetzt. Damit ist dieser Eintrag f&uuml;r 
die Studenten nicht mehr sichtbar. Sollte das Buch zu einem 
sp&auml;teren Zeitpunkt erneut ben&ouml;tigt werden, kann man die 
Bestellung mit der Funktion <em>Erneuern</em> wieder 
aufleben lassen. 
</p>
<p>
Analog dazu kann auch ein ganzer Semesterapparat vorzeitig 
aufgel&ouml;st werden. Auch hierbei wird der Eintrag nicht 
gel&ouml;scht, sondern in einen inaktiven Zustand versetzt, 
aus dem er mit der Funktion <em>Erneuern</em> 
wieder zum Leben erweckt kann.
</p>
<p>
&Uuml;ber die Funktion <em>L&ouml;schen</em> lassen sich 
inaktive, nicht mehr ben&ouml;tigte Eintr&auml;ge endg&uuml;ltig aus
dem Semesterapparat tilgen.
</p>
<p>
<em>Siehe auch: <a href="help.php?topic=leihfrist">Leihfrist</a></em>
</p>
{elseif $topic=="email"}
<h3>Benachrichtigungen per E-Mail</h3>
<p>
Dozenten werden bei erfolgter Aufnahme einer Bestellung in den Semesterapparat und
sonstigen &Auml;nderungen mit einer automatisch erzeugten Status-EMail informiert.
</p>
<p>
Bei etwaigen R&uuml;ckfragen zu Bestellungen nehmen die Bibliotheksmitarbeiter zum jeweiligen Dozenten per E-Mail oder 
telefonisch Kontakt auf.
</p>
{*
<p>
Nachdem Sie sich <a href="login.php">eingeloggt</a>
haben, finden Sie auf der der <a href="index.php">Hauptseite</a>
den Button <em>Semesterapparat
anlegen</em>. Es erscheint ein
Eingabeformular, in dem Sie den Titel, die LVA-Nummer und ein
Vorlesungs-Passwort
eingeben. Verwenden Sie daf&uuml;r bitte nicht
Ihr eigenes Passwort, das Sie von der UB erhalten haben!</p>
<p>Das Vorlesungs-Passwort teilen
Sie bitte den Studenten
der jeweiligen Lehrveranstaltung mit, damit diese die
Zeitschriftenartikel
bzw. elektronischen Dokumente herunterladen k&ouml;nnen. Der
Passwortschutz ist aus urheberrechtlichen Gr&uuml;nden notwendig.  <p>
</p>
<p>Nach dem Absenden
ist Ihr Semesterapparat eingerichtet. Nun k&ouml;nnen
 dort B&uuml;cher, Zeitschriftenaufs&auml;tze und
sonstige Dokumente
eingegeben werden (s.u.).
</p>

<h3>Semesterapparat l&ouml;schen</h3>
<p>Nachdem Sie sich <a href="login.php">eingeloggt</a>
haben, finden Sie auf der der <a href="index.php">Hauptseite</a>
Ihre Semesterapparate aufgelistet. Klicken Sie auf den Button 
<em>L&ouml;schen</em>, der sich unter dem Titel des Semesterapparates 
befindet. Nach einer Sicherheitsabfrage wird der Semesterapparat 
gel&ouml;scht. <p>

Dabei wird automatisch veranlasst, dass die B&uuml;cher aus dem 
Lesesaal 3 entfernt und an ihren urspr&uuml;nglichen Platz 
zur&uuml;ckgestellt werden. Die Zeitschriftenaufs&auml;tze 
und elektronischen Dokumente werden ebenfalls entfernt. 
Ist der Vorgang abgeschlossen, erhalten Sie eine E-Mail.
</p>
<h3>Semesterapparat bearbeiten </h3>
<p>Nachdem Sie sich <a href="login.php">eingeloggt</a>
haben, finden Sie auf der der <a href="index.php">Hauptseite</a>
Ihre Semesterapparate aufgelistet. Klicken Sie auf den Button 
 <em>Bearbeiten</em>,
um den Inhalt eines Semesterapparats zu &auml;ndern. Es erscheint
eine &Uuml;bersichtsseite zum Semesterapparat. Nun haben Sie 
folgenden M&ouml;glichkeiten:</p>
<ul>
  <li><b>Angaben zur Vorlesung
&auml;ndern </b> <p>
Wenn Sie die Angaben zur Vorlesung &auml;ndern m&ouml;chten,
klicken Sie bitte auf den
Button <em>Eigenschaften</em> neben
dem Titel der Lehrveranstaltung. Sie
k&ouml;nnen nun den Titel, die LVA-Nummer und das
Vorlesungs-Passwort
&auml;ndern.
<p>
  </li>
  <li><b>Dokumente in einen Semesterapparat einbringen</b> 
<p>
Auf der &Uuml;bersichtsseite zum Semesterapparat befinden sich die 
folgenden Buttons: <p>
  <ul class="circle">
     <li><em>Buch bestellen</em>
<p>
Bitte recherchieren Sie im <a
 target="_new" href="{#catalogue_url#}">Online-Katalog</a>
nach der Signatur des Buches und
tragen Sie diese ein. 
Die Angaben zum Buch werden automatisch
eingespielt. Zus&auml;tzlich k&ouml;nnen Sie Bemerkungen
eintragen. <p>

Wenn es mehrere B&uuml;cher zu einer Signatur gibt bzw. es sich um
mehrb&auml;ndige Werke handelt, erscheint auf dem folgenden
Bildschirm eine Liste aller dazugeh&ouml;rigen B&auml;nde.
Setzen Sie bitte bei den B&uuml;chern, die Sie tats&auml;chlich
ben&ouml;tigen, einen Haken in die entsprechenden
K&auml;stchen, damit die Bestellung ausgel&ouml;st wird.<p>

Ein Mitarbeiter des Hauses sucht die von Ihnen bestellten B&uuml;cher 
heraus und stellt sie als Pr&auml;senzexemplare im Lesesaal 3 in der 
UB auf. Auf Wunsch werden die Inhaltsverzeichnisse eingescannt und 
online zur Verf&uuml;gung gestellt. Ist der Vorgang abgeschlossen, 
erhalten Sie als Best&auml;tigung eine E-Mail.<p>
Haben Sie irrt&uuml;mlich ein Buch bestellt, dann klicken Sie 
bitte auf den Button <em>Bestellung stornieren</em>.</p></li>
    <li> <em>Artikel bestellen</em><p>
F&uuml;llen Sie die Eingabemaske vollst&auml;ndig aus. Sofern
der Zeitschriftenartikel in der UB vorhanden ist, wird er eingescannt
und online bereitgestellt. Aus urheberrechtlichen Gr&uuml;nden ist
der Zugriff nur mit dem Vorlesungs-Passwort m&ouml;glich (s.o.).
Dieses m&uuml;ssen Sie daher den Studenten der Vorlesung bekannt
geben.<p>
      
Haben Sie irrt&uuml;mlich einen Zeitschriftenartikel bestellt,
dann klicken Sie bitte auf den Button 
 <em>Bestellung stornieren</em>.</p>
    <li> <em>Dokument anlegen</em><p>
Wenn Sie interessante Internetseiten den Studierenden zur
Verf&uuml;gung stellen wollen, k&ouml;nnen Sie an dieser Stelle
die Eintragungen vornehmen.Sie k&ouml;nnen nat&uuml;rlich auch
Unterlagen wie Vorlesungsskripte, &Uuml;bungen, Klausuren, etc.,
die z. B. auf Ihrem Institutsserver abgelegt sind, hier verlinken. Den
Zugriff auf die entsprechende URL k&ouml;nnen Sie mit dem
Vorlesungs-Passwort sch&uuml;tzen.
      <p>Wenn Sie
Vorlesungsmaterialien nur in gedruckter Form vorliegen haben und sie
gern in
elektronischer Form anbieten w&uuml;rden, setzen Sie sich bitte mit
uns in Verbindung (siehe <a href="help.php?topic=kontakt">Ansprechpartner</a>).
Es besteht in der UB die M&ouml;glichkeit, diese Materialien
digitalisieren zu lassen. </p>
</ul>

  <li><b>Dokumente bearbeiten und Relevanz festlegen</b>
    <p>Klicken Sie auf den Button <em>Eintrag bearbeiten</em> unter dem
Dokument. 
    </p>
    <ul class="circle">
      <li>Bei <em>B&uuml;chern</em>
werden die
bibliographischen Angaben (Titel, Autor, etc.) direkt aus dem
Online-Katalog &uuml;bernommen, und k&ouml;nnen nicht
ver&auml;ndert werden. <p>
        
Bei <em>Zeitschriften</em>
k&ouml;nnen Sie die bibliographischen Angaben auch noch
nachtr&auml;glich korrigieren. Nachdem  der Aufsatz von der UB 
eingescannt und bereitgestellt wurde, sind diese Angaben nicht 
mehr ver&auml;nderbar. Setzen Sie sich in diesem Fall bitte 
mit uns per E-Mail oder Telefon in Verbindung 
(siehe <a href="help.php?topic=kontakt">Ansprechpartner</a>).
        <p>Bei <em>sonstigen Dokumenten</em>
k&ouml;nnen Sie den Titel jederzeit
selbst&auml;ndig bearbeiten.</p>
      </li>
      <li>Um die <em>Relevanz</em>
eines Dokumentes zu bewerten, k&ouml;nnen Sie diese mit den Buttons
"-"
bzw. "+"
ver&auml;ndern. Je mehr gr&uuml;ne Punkte erscheinen, desto
wichtiger ist das Dokument. Dadurch ver&auml;ndert auch
sich die Reihenfolge, in der die Dokumente im Semesterapparat
angezeigt werden.
       <p> 
      </li>
      <li>Im Feld <em>Bemerkungen</em>
k&ouml;nnen Sie einen Kommentar zum Dokument eingeben.
      </li>
    </ul>
       <p> 
  </li>
  <li><b>Dokumente l&ouml;schen bzw. zur&uuml;ckgeben </b><p>
Wenn Sie ein Dokument nicht
mehr f&uuml;r den Semesterapparat ben&ouml;tigen, veranlassen
Sie bitte die R&uuml;ckgabe, so dass es wieder dem
normalen Ausleihbestand der UB zugef&uuml;hrt werden kann.
<p>
    <ul class="circle">
      <li><em>B&uuml;cher und Zeitschriftenartikel</em> geben Sie
zur&uuml;ck, indem Sie auf den Button <em>R&uuml;ckgabe</em> klicken. 
Der Status wechselt auf "R&uuml;ckgabe".
        
Die B&uuml;cher werden dann aus dem Semesterapparate-Regal 
wieder entfernt und an ihren urspr&uuml;nglichen Platz 
zur&uuml;ckgestellt.  Die Zeitschriftenartikel werden 
gel&ouml;scht. Wenn der Vorgang abgeschlossen ist, erhalten 
Sie eine E-Mail.<p>
        
Wenn Sie ein Buch oder einen Aufsatz irrt&uuml;mlich 
zur&uuml;ckgegeben haben, dann dr&uuml;cken Sie bitte den 
Button <em>R&uuml;ckgabe stornieren</em>. Der Status wechselt
dann zur&uuml;ck auf "aktiv".
</p>
<p>
Wenn die R&uuml;ckgabe abgeschlossen ist, wechselt der Status
auf "inaktiv". Falls Sie das Buch oder den Artikel erneut 
ben&ouml;tigen, klicken Sie bitte auf den Button 
<em>Bestellung erneuern</em>. Damit wird eine neue Bestellung 
ausgel&ouml;st. Wenn Sie den Eintrag endg&uuml;ltig l&ouml;
schen m&ouml;chten, klicken Sie bitte auf
den Button <em>L&ouml;schen</em>. </p>
      </li>
      <li><em>Elektronische Dokumente</em> k&ouml;nnen Sie l&ouml;schen, 
	indem Sie auf den Button <em>L&ouml;schen</em> klicken.  
	Es folgt eine Sicherheitsabfrage. </li>
    </ul>
    <p>Wollen Sie <em>alle Dokumente</em>
aus einem Semesterapparat entfernen, dann
m&uuml;ssen Sie nicht jeden Eintrag einzeln l&ouml;schen,
sondern k&ouml;nnen den gesamten Semesterapparat
l&ouml;schen
(s.u.).
    </p>
  </li>
</ul>
*}
<p>
{elseif $topic=="kontakt"}
<h3>Ansprechpartner</h3>
Bei Fragen zu den elektronischen Semesterapparaten wenden Sie sich bitte an:
<blockquote>
<address>
HAW Hamburg / HIBS <br>
Digitale Dienste<br>
Telefon: {#contact_phone#}<br>
E-Mail: <a href="mailto:{#contact_email#}">{#contact_email#}</a> 
</address>
</blockquote>
{else}
<h3>Inhaltsverzeichnis</h3>
<ul>
<li><a href="help.php?topic=dozent">Erste Schritte</a></li>
<li><a href="help.php?topic=edit">Semesterapparat bearbeiten</a></li>
<ul>
<li><a href="help.php?topic=items">Dokumenttypen</a></li>
<li><a href="help.php?topic=state">Bestellstatus</a></li>
<li><a href="help.php?topic=rueckgabe">Buchr&uuml;ckgabe</a></li>
</ul>
<li><a href="help.php?topic=leihfrist">Leihfrist</a></li>
<li><a href="help.php?topic=email">Benachrichtigungen per E-Mail</a></li>
<li><a href="help.php?topic=kontakt">Ansprechpartner</a></li>
</ul>
{/if}
<p>
<a href="help.php">zum Inhaltsverzeichnis</a> <br>

</p>
</div>
</body>
</html>
