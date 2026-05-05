# XPLOIT - Configuración de base de datos

## Motor de base de datos
- Sistema gestor: PostgreSQL.
- Conexión usada desde PHP: PDO con driver `pgsql`.

## Parámetros de conexión
- Host: `127.0.0.1`.
- Puerto: `5432`.
- Base de datos: `xploitdb`.
- Usuario: `postgres`.
- Password: `-------`.

## Cadena de conexión PDO
- `dsn = pgsql:host=127.0.0.1;port=5432;dbname=xploitdb`

## Opciones PDO
- `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`.
- `PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC`.

## Tablas existentes
- `usuarios`.
- `flags_resueltas`.

## Tabla: usuarios

### Descripción
Tabla principal de usuarios del sistema. Contiene los datos de registro, credenciales hasheadas y puntuación acumulada del ranking.

### Columnas
- `id`: integer, PRIMARY KEY, autoincremental.
- `nombre`: varchar(100), NOT NULL.
- `apellido`: varchar(100), NOT NULL.
- `fechanacimiento`: date, NOT NULL.
- `username`: varchar(50), NOT NULL, UNIQUE.
- `email`: varchar(150), NOT NULL, UNIQUE.
- `passwordhash`: varchar(255), NOT NULL.
- `puntos`: integer, NOT NULL, DEFAULT 0.

### Índices y restricciones
- `PRIMARY KEY (id)`.
- `UNIQUE (username)`.
- `UNIQUE (email)`.

### Uso dentro de la aplicación
- Login por `username` o `email`.
- Registro de nuevos usuarios.
- Ranking global por puntos.
- Consulta de posición del usuario.

### Consultas detectadas en el proyecto
```sql
SELECT username, puntos FROM usuarios ORDER BY puntos DESC, id ASC LIMIT 10;
SELECT id, username, passwordhash FROM usuarios WHERE username = ? OR email = ? LIMIT 1;
INSERT INTO usuarios (nombre, apellido, fechanacimiento, username, email, passwordhash, puntos) VALUES (?, ?, ?, ?, ?, ?, 0);
UPDATE usuarios SET puntos = puntos + ? WHERE id = ?;
```

## Tabla: flags_resueltas

### Descripción
Tabla de relación entre usuarios y flags ya resueltas. Se usa para evitar sumar puntos repetidos y para mostrar el progreso de cada nivel.

### Columnas detectadas por uso en el código
- `user_id`.
- `flag_id`.

### Relación
- `user_id` referencia a `usuarios(id)`.
- `ON DELETE CASCADE`.

### Uso dentro de la aplicación
- Saber si una flag ya fue resuelta por un usuario.
- Evitar reclamar XP dos veces.
- Pintar progreso en `index.php`.
- Marcar flags completadas por nivel.

### Consultas detectadas
```sql
SELECT flag_id FROM flags_resueltas WHERE user_id = ? GROUP BY flag_id;
SELECT COUNT(*) FROM flags_resueltas WHERE user_id = ? AND flag_id = ?;
INSERT INTO flags_resueltas (user_id, flag_id) VALUES (?, ?);
```

## Autenticación

### Login
- El usuario puede iniciar sesión usando `username` o `email`.
- Se compara el password introducido contra `passwordhash`.
- La verificación se hace con `password_verify()` en PHP.

### Registro
- La contraseña se guarda con `password_hash(..., PASSWORD_BCRYPT)`.
- Al registrar un usuario nuevo, `puntos` inicia en 0.

### Variables de sesión usadas
- `$_SESSION['user_id']`.
- `$_SESSION['username']`.

## Flujo funcional de la BBDD

1. **Registro**
   - Se inserta un nuevo usuario en la tabla `usuarios`.
   - Se guarda `passwordhash` usando bcrypt.
   - El usuario empieza con 0 puntos.

2. **Login**
   - Se busca por `username` o `email`.
   - Se valida `passwordhash` con `password_verify()`.

3. **Validación de flags**
   - Cada nivel compara la flag introducida con una flag real configurada en PHP.
   - Si es correcta, se comprueba en `flags_resueltas` si ya estaba reclamada.
   - Si no estaba reclamada:
     - se suman puntos en `usuarios`.
     - se inserta la relación en `flags_resueltas`.

4. **Ranking**
   - Se obtiene el top 10 ordenando por `puntos DESC` e `id ASC`.
   - También se calcula la posición del usuario autenticado.

## Identificadores de flags usados

### Nivel 1
- `lvl1_f1`.
- `lvl1_f2`.
- `lvl1_f3`.

**Nota:** En parte del código también aparecen como:
- `lvl1f1`.
- `lvl1f2`.
- `lvl1f3`.

### Nivel 2
- `f1`.
- `f2`.
- `f3`.

### Nivel 3
- `lvl3_f1`.
- `lvl3_f2`.
- `lvl3_f3`.
- `lvl3_f4`.

**Nota:** En parte del código también aparecen como:
- `lvl3f1`.
- `lvl3f2`.
- `lvl3f3`.
- `lvl3f4`.

### Importante
Conviene unificar el naming en toda la aplicación para evitar errores entre los IDs usados en PHP y los almacenados en la tabla `flags_resueltas`.

## Datos técnicos del proyecto relacionados

### Backend
- PHP con PDO.
- PostgreSQL.

### Ficheros principales relacionados con BBDD
- `db.php`.
- `auth.php`.
- `index.php`.
- `nivel1.php`.
- `nivel2.php`.
- `nivel3.php`.

## Estado actual
- Motor de base de datos confirmado: PostgreSQL.
- Base de datos confirmada: `xploitdb`.
- Tabla `usuarios` confirmada.
- Tabla `flags_resueltas` confirmada.
- Login con `password_verify()` confirmado.
- Registro con `password_hash(..., PASSWORD_BCRYPT)` confirmado.
- Ranking por puntos confirmado.