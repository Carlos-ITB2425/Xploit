# Despliegue HTTPS y dominio del proyecto

## Resumen

Para este proyecto se ha configurado el acceso web mediante **HTTPS**, utilizando certificados gratuitos de **Let’s Encrypt** y la herramienta **Certbot** para la obtención, instalación y renovación automática del certificado. Certbot está diseñado precisamente para automatizar el alta de certificados ACME, su instalación en el servidor web y la renovación periódica antes de su expiración.

Además, se ha utilizado el dominio **xploit.cat**, proporcionado por nuestro profesor **Isaac Gonzalo**, y gestionado a través de la plataforma **cdmon.com** para la configuración DNS del proyecto.

## Dominio y DNS

El dominio principal del proyecto es **xploit.cat**, gestionado desde el panel DNS de cdmon. Desde ahí se han configurado los registros necesarios para que el dominio resuelva hacia la IP pública estática de la instancia donde se aloja la web.

La estructura DNS permite que el acceso al sitio se realice tanto por el dominio raíz como por subdominios, manteniendo una base flexible para futuros servicios del laboratorio CTF.

## HTTPS con Let’s Encrypt

La web se ha publicado bajo **HTTPS** para cifrar la comunicación entre el navegador y el servidor, evitando exposición de credenciales, sesiones y contenido sensible en tránsito. Let’s Encrypt proporciona certificados válidos para navegadores y Certbot automatiza todo el ciclo de vida del certificado.

Certbot también permite configurar la redirección automática de **HTTP a HTTPS**, de modo que cualquier acceso inseguro termine usando la versión cifrada del sitio.

## Motivos técnicos

El uso de HTTPS aporta varias ventajas al proyecto:

- Cifra el tráfico entre cliente y servidor.
- Evita advertencias del navegador por contenido no seguro.
- Mejora la confianza del usuario.
- Facilita un despliegue profesional y coherente con un entorno real.
- Permite reutilizar la misma base para futuros subdominios y servicios del CTF.

## Implementación

La implementación se ha realizado con:

- Un servidor web configurado para responder por el dominio **xploit.cat**.
- Un certificado TLS emitido por **Let’s Encrypt**.
- **Certbot** como cliente ACME para la instalación y renovación automática.
- Redirección de tráfico desde HTTP a HTTPS.

## Notas de mantenimiento

Los certificados de Let’s Encrypt tienen una validez limitada, por lo que la renovación automática es fundamental. Certbot está pensado para gestionar esa renovación sin intervención manual, siempre que el servicio esté correctamente configurado en el servidor.

## Referencias del proyecto

- Dominio: **xploit.cat**
- Proveedor/gestión DNS: **cdmon.com**
- Profesor que proporcionó el dominio: **Isaac Gonzalo**
- Seguridad web: **HTTPS**
- Certificado TLS: **Let’s Encrypt**
- Cliente ACME: **Certbot**