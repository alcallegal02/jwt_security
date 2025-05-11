# jwt_security

## JWT Débil (HS256):

Vamos a demostrar la generación de un JWT con una configuración vulnerable (clave secreta compartida y algoritmo HS256).

Para ello abrimos el navegador y insertamos la url (http://localhost:8080/jwt_weak.php) del script jwt_weak.php el cual genera un JWT utilizando el algoritmo HS256 y una clave secreta predefinida ('secret'). Este tipo de token es vulnerable si la clave secreta se ve comprometida, ya que cualquiera con la clave puede generar tokens válidos.que nos genera el token vulnerable 

![image](https://github.com/user-attachments/assets/2832aee7-ec46-4185-813f-a61c6f0b6798)

En este caso nos genera el siguiente token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyIjoiYWRtaW4iLCJyb2xlIjoiYWRtaW4ifQ.POg2q5AiVu34ExCPjfIae3O8d-XK7ybbYNsG2L7GOL4

Para explotar esta vulnerabilidad en nuestro contendor kali ejecutamos la herramienta curl con la url del script anterior:

![image](https://github.com/user-attachments/assets/41305736-5e76-4e5e-9223-073a4ee1bb32)

De manera que usando el script jwt_decode.php podríamos decodificar el token generado mostrandonos su información. Para ello copiariamos el token en el archivo y lo ejecutaríamos:

![image](https://github.com/user-attachments/assets/0a178f5d-a914-4350-9bea-6f99c6c05943)

![image](https://github.com/user-attachments/assets/af6a9b88-e81a-450c-a328-8b7f87305b44)

Como vemos al ejecutar el script con el token que se nos había generado, nos lo decodificaría, mostrandonos el algoritmo y el contenido del payload, en este caso el usuario y contraseña.

Ahora vamosa demostrar cómo un atacante, conociendo la clave secreta, puede modificar el payload por ejemplo para cambiar la contraseña, el rol, etc; y firmarlo posteriormente. Para ello usaremos el script jwt_regen.php, el cual ya tiene un payload modificado usando el rol de ('superadmin') y usando la clave ("secret"):

![image](https://github.com/user-attachments/assets/4ee879a8-fc78-429f-ab1f-dac4b77cceee)

![image](https://github.com/user-attachments/assets/bbe5d96f-10ab-4b05-aff3-939eeb6ad525)

Nos generaría el siguiente token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyIjoiaGFja2VyIiwicm9sZSI6InN1cGVyYWRtaW4ifQ.gHL7skcJwdYp3u1OeLMkNr57hzCzCvULF97Wmv6LqJY

De manera que en la aplicación únicamente deberíamos sustituir nuestro anterior token por este modificado y habríamos conseguido una escalada de privilegios.

## Mitigar la vulnerabilidad

Para mitigarlo deberemos de crear primeramente un par de claves públicas/privadas y usar el algoritmo JWT RS256. Ejecutando el script jwt.generate.php nos generaría este token más seguro:

![image](https://github.com/user-attachments/assets/35c76737-b951-47fd-8489-6c8014102315)

![image](https://github.com/user-attachments/assets/00e91a45-5833-4ba7-9ec4-7fb72069e747)

Generandonos el siguiente token: eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJ1c2VyIjoiYWRtaW4iLCJyb2xlIjoiYWRtaW4iLCJpYXQiOjE3NDY5NzQ0MTQsImV4cCI6MTc0Njk3ODAxNH0.nI_QRrbolEGrri0cq3jbkkEyldbCiLbtQxiGiZGUfzYIHO7CB9Ls3sKZJWQgUdf9nZrnT8TlIQL-czck4mgwV9-P7k0JINXx9ItVAT23NTYa04-cE3Xoj7R0Van4-icNHP3lArED2xJ_GKyir7IiSF0ktQyvCEemr4eoUDDQxBk2H-UrP8srUSXM1Pw-UF75Y8QSA36mjs0hZ_-dUhLcbH63tHtyzuk0YaZy4-tURzbegmdLHXzK-4MCeMQbOf6b46RY8WihEesCPcLheTVGDDVua94Xc4lk9aQ5dInhD7JKrT0To6EC5d_HkLwEN5vVct_Ofzs52hlqq7dlqeO61Q

Comprobamos que el token es válido:

![image](https://github.com/user-attachments/assets/a56629a9-dbea-4cfb-9076-cea0a0ce6106)

![image](https://github.com/user-attachments/assets/0f0381ef-8101-493a-a2fe-7becef46f9b5)

Podemos comprobar que configurando el tiempo de expiración en los tokens de manera que sea muy bajo, el servidor nos los rechaza si expiran:

![image](https://github.com/user-attachments/assets/8617ee69-e392-4968-93c1-36f938544db5)

![image](https://github.com/user-attachments/assets/2e063a60-e2bd-4d69-b3a5-1b21101afaca)

![image](https://github.com/user-attachments/assets/1e8d283b-554d-4eed-9c27-63789e7967f5)

![image](https://github.com/user-attachments/assets/150173a1-2c98-4afb-b366-346a3a9fc723)

Como vemos el token ya se habría expirado

Si intentamos cambiar el rol del token generado por uno diferente del admin, el servidor también nos deniega el acceso:

![image](https://github.com/user-attachments/assets/5e234e9f-24c4-4bf5-b13b-e72ff2f4cb24)

![image](https://github.com/user-attachments/assets/e4f8f8cc-e2f0-4d5e-9d05-56cac71e0e5a)

![image](https://github.com/user-attachments/assets/6ad68987-991b-4a46-88ee-3e9f6d3572b0)

![image](https://github.com/user-attachments/assets/9c13f8d5-c028-45b7-ab79-d209e99669a2)

Como vemos nos deniega el acceso por falta de permisos.

Como última prueba vamos a usar el script jwt_exploit.php el cual firma el token con un algoritmo diferente en este caso HS256, de manera que el servidor lo rechazará. Primeramente generamos un token y lo pegamos en el script jwt_exploit.php:

![image](https://github.com/user-attachments/assets/9c7b720b-1615-4379-a84a-a3a652275906)

Ejecutamos el script:

![image](https://github.com/user-attachments/assets/89213ac4-c3cb-4f68-8d7d-5e4dd3f284ff)

Ahora ejecutamos el script php_full.php con este nuevo token generado:

![image](https://github.com/user-attachments/assets/d132b9ee-b872-4e26-9c97-1514b0fac2d2)

![image](https://github.com/user-attachments/assets/5248f959-8492-4608-bfb0-797602bb1cf5)

Como vemos nos muestra que el token es inválido
