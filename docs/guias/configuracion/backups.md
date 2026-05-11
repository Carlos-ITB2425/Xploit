# Documentación Técnica: Sistema de Backup Automatizado Xploit

Esta documentación describe la implementación técnica del sistema de copias de seguridad programadas para la instancia **Xploit**, diseñado para garantizar la integridad de los datos y la rápida recuperación ante desastres.

## 1. Arquitectura del Sistema
El sistema se basa en una arquitectura de "Push Backup" donde la instancia de origen (Xploit) envía los datos a una instancia de almacenamiento dedicada (Backup) a través de la **red privada de AWS**.

* **Instancia Origen:** Xploit (`172.31.34.131`)
* **Instancia Destino:** Backup (`172.31.90.8`)
* **Protocolo de transporte:** SCP (SSH) sobre red interna.
* **Autenticación:** Par de claves ED25519 (Sin contraseña para automatización).

---

## 2. Componentes del Backup
Cada ejecución del sistema genera cuatro archivos esenciales dentro de un directorio fechado:

| Componente | Origen | Descripción |
| :--- | :--- | :--- |
| **Base de Datos** | PostgreSQL | Volcado completo de la base de datos `xploitdb`. |
| **Reglas de Red** | Iptables | Backup de las reglas del firewall para replicar la seguridad. |
| **Servidor Web** | `/var/www/html` | Código fuente, scripts PHP y recursos del portal. |
| **Proyectos Docker** | `~/docker` | Carpeta con retos, despliegues y Dockerfiles. |

---

## 3. Script de Automatización: `auto_backup_to_storage.sh`

El script realiza la limpieza local, la creación de directorios remotos organizados por fecha y la transferencia cifrada.

```bash
#!/bin/bash

# --- CONFIGURACIÓN ---
IP_PRIVADA_BACKUP="172.31.90.8"
USUARIO="ubuntu"
KEY="/home/xploit/backup"

# Ajuste de zona horaria local (Madrid)
FECHA=$(TZ="Europe/Madrid" date +%Y%m%d_%H%M)

# Rutas de almacenamiento
RAIZ_DESTINO="/home/ubuntu/backups_xploit"
DIRECTORIO_FINAL="$RAIZ_DESTINO/backup_$FECHA"
TEMP_DIR="/tmp/backup_temp"

# 1. Preparación del entorno
mkdir -p $TEMP_DIR
# Creación del directorio remoto con marca de tiempo
ssh -i $KEY $USUARIO@$IP_PRIVADA_BACKUP "mkdir -p $DIRECTORIO_FINAL"

echo "--- Iniciando Backup: $FECHA ---"

# 2. Generación de Archivos (Backup en caliente)
echo "[1/4] Exportando DB..."
sudo -u postgres pg_dump xploitdb > $TEMP_DIR/db.sql

echo "[2/4] Guardando Firewall..."
sudo iptables-save > $TEMP_DIR/iptables.v4

echo "[3/4] Comprimiendo Web..."
sudo tar -czf $TEMP_DIR/web.tar.gz /var/www/html 2>/dev/null

echo "[4/4] Comprimiendo Docker..."
sudo tar -czf $TEMP_DIR/docker_projects.tar.gz /home/xploit/docker 2>/dev/null

# 3. Transferencia a Almacenamiento
echo "--- Enviando archivos a: $DIRECTORIO_FINAL ---"
scp -i $KEY $TEMP_DIR/* $USUARIO@$IP_PRIVADA_BACKUP:$DIRECTORIO_FINAL/

# 4. Verificación y Limpieza local
if [ $? -eq 0 ]; then
    echo "SUCCESS: Backup enviado correctamente."
    rm -rf $TEMP_DIR/*
else
    echo "ERROR: Falló la transferencia."
    exit 1
fi
```

---

## 4. Programación de Tareas (Cron Job)

Para asegurar la ejecución diaria y desatendida, se ha integrado el script en el servicio `cron` de la instancia Xploit.

### Configuración del Crontab
La tarea se ejecuta **todos los días a las 05:00 AM** (Hora peninsular).

**Instalación:**
1. Acceder al editor: `crontab -e`
2. Línea de comando añadida:
```cron
0 5 * * * /home/xploit/auto_backup_to_storage.sh >> /home/xploit/backup_log.log 2>&1
```

* **Frecuencia:** Diaria (05:00 AM).
* **Log de Registro:** Los resultados y errores se guardan en `/home/xploit/backup_log.log` para auditoría técnica.

---

## 5. Procedimiento de Restauración (Plan de Desastre)

Para recuperar el sistema a un punto anterior:

1. **Identificar la fecha:** Listar carpetas en el servidor de backup: `ls /home/ubuntu/backups_xploit/`
2. **Recuperar Carpeta:**
   `scp -r -i llave_backup ubuntu@172.31.90.8:/home/ubuntu/backups_xploit/backup_FECHA /tmp/restore`
3. **Restaurar DB:**
   `sudo -u postgres dropdb xploitdb && sudo -u postgres createdb xploitdb`
   `sudo -u postgres psql xploitdb < /tmp/restore/db.sql`
4. **Restaurar Web:**
   `sudo tar -xzf /tmp/restore/web.tar.gz -C /`