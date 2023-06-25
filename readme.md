1. Zainstaluj pakiet XAMPP

2. Zainstaluj composer

3. Na końcu pliku 
C:\xampp\apache\conf\extra\httpd-vhosts
dodać:
<VirtualHost *:80>
    ServerName kurierportal.test
    ServerAlias kurierportal.test www.kurierportal.test
    DocumentRoot "D:\Programing\Kurier_portal_NEW\laravel\public"
    <Directory D:\Programing\Kurier_portal_NEW\laravel\public>
        Options FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog "logs/portal-test-error.log"
    CustomLog "logs/portal-test-access.log" combined
</VirtualHost>

4. Znajdz linie: 
Include conf/extra/httpd-vhosts.conf
w pliku: C:\xampp\apache\conf\httpd i jeżeli jest zakomentowana to ją odkomentuj

5. Dodać linie: 
127.0.0.1 kurierportal.test
w C:\Windows\System32\drivers\etc\hosts

6. W XAMPP włącz apache i MySQL

7. W przegladarce wpisz adres kurierportal.test