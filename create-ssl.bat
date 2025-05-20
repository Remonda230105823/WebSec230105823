@echo off
echo Creating SSL certificates for websecservice.com...

REM Create SSL directory if it doesn't exist
if not exist "ssl" mkdir ssl

REM Set OpenSSL path
set OPENSSL=C:\xampp\apache\bin\openssl.exe

REM Generate private key
echo Generating private key...
%OPENSSL% genrsa -out ssl\websecservice.key 2048

REM Generate CSR using the configuration file
echo Generating Certificate Signing Request (CSR)...
%OPENSSL% req -new -key ssl\websecservice.key -out ssl\websecservice.csr -subj "/CN=websecservice.com/O=WebSec/C=US" -config openssl.cnf

REM Generate self-signed certificate from the CSR
echo Generating self-signed certificate...
%OPENSSL% x509 -req -days 365 -in ssl\websecservice.csr -signkey ssl\websecservice.key -out ssl\websecservice.crt -extensions x509_ext -extfile openssl.cnf

echo.
echo Certificates generated successfully!
echo.
echo Please add the following line to your hosts file (C:\Windows\System32\drivers\etc\hosts):
echo 127.0.0.1 websecservice.com
echo.
echo After adding the hosts entry, restart Apache from XAMPP Control Panel.
echo.
echo To remove the "Not secure" warning in your browser, you need to import ssl\websecservice.crt as a trusted root certificate. 