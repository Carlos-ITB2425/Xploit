
# Diseño de Infraestructura y Firewall Perimetral

Este documento describe la arquitectura de red diseñada para segmentar el tráfico público de los entornos de entrenamiento, utilizando un Gateway basado en Linux e iptables.

## 1. Arquitectura de Segmentación
El sistema utiliza una topología de doble instancia en AWS:
- **Firewall (Gateway):** IP Pública `34.200.35.10`. Único punto de entrada desde Internet.
- **Host Xploit (Backend):** IP Privada `172.31.34.131`. Contiene los contenedores Docker con los retos.


## 2. Configuración de Iptables (DNAT)
Para permitir el acceso a los retos internos, se han configurado reglas de Destination NAT que mapean los puertos del Firewall hacia el Host Xploit:

| Servicio | Puerto Externo | Destino Interno |
| :--- | :---: | :--- |
| **Tráfico Web (Portal)** | 80 / 443 | 172.31.34.131:80 / 443 |
| **Reto SQL Injection** | 6969 | 172.31.34.131:6969 |
| **Reto SSH Alpine** | 2222 | 172.31.34.131:2222 |
| **Retos Secundarios** | 8081 - 8083 | 172.31.34.131:8081 - 8083 |

## 3. Persistencia de Reglas
Dado que las reglas de iptables son volátiles por defecto, se ha implementado el siguiente mecanismo de persistencia para resistir reinicios de las instancias de AWS:

1. **Activación de Forwarding**: Configurado en `/etc/sysctl.conf` mediante `net.ipv4.ip_forward=1`.
2. **Exportación de Reglas**: Almacenadas en `/etc/iptables/rules.v4`.
3. **Carga Automática**: Script de ejecución en `/etc/network/if-pre-up.d/iptables` que invoca `iptables-restore`.

## 4. Hardening y Seguridad
- **Políticas de Acceso**: Solo se exponen los puertos estrictamente necesarios para los retos. El puerto 22 (SSH) del Firewall solo admite autenticación por llave pública (.pem), deshabilitando contraseñas.
- **SNI y SSL**: Para evitar errores de "Misdirected Request" (HTTP 421), el servidor Apache en el backend está configurado para manejar el SNI del dominio principal redirigido desde el Firewall.
- **Aislamiento**: Se aplica `MASQUERADE` en la cadena POSTROUTING para ocultar la topología interna y asegurar que el tráfico de retorno fluya correctamente a través del Gateway.
            