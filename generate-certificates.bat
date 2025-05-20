@echo off
echo Generating SSL certificates for websecservice.com...

REM Create SSL directory if it doesn't exist
if not exist "ssl" mkdir ssl

REM Generate private key
C:\xampp\apache\bin\openssl.exe genrsa -out ssl/websecservice.key 2048

REM Generate CSR
C:\xampp\apache\bin\openssl.exe req -new -key ssl/websecservice.key -out ssl/websecservice.csr -subj "/CN=websecservice.com/O=WebSec/C=US"

REM Generate self-signed certificate
C:\xampp\apache\bin\openssl.exe x509 -req -days 365 -in ssl/websecservice.csr -signkey ssl/websecservice.key -out ssl/websecservice.crt

echo Certificates generated successfully!
echo Please add the following line to your hosts file (C:\Windows\System32\drivers\etc\hosts):
echo 127.0.0.1 websecservice.com 