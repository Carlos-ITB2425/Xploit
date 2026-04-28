<?php
/**
 * XPLOIT_CORP - Terminal de Auditoría v.2.6 (Troll & Party Edition)
 * Nota: El gato ha sido debidamente 'depurado' del sistema.
 */

// Flags de nivel
$F1 = "nOu3t7WfJnZ9tzU0S1lA"; // Dificultad: Baja
$F2 = "kR1p8vXmQjL4nH2bS9wZ"; // Dificultad: Media
$F3 = "mJ8pL2wN5vX9zQ1rT4bS"; // Dificultad: Alta

$db_file = '/var/lib/sqlite/ctf.db';
$is_new = !file_exists($db_file);
$db = new PDO("sqlite:$db_file");

if ($is_new) {
    // 1. Tabla de Usuarios (Símbolo de acceso inicial)
    $db->exec("CREATE TABLE usuarios (id INTEGER PRIMARY KEY, username TEXT, password TEXT)");
    $db->exec("INSERT INTO usuarios (username, password) VALUES ('admin', '$F1')");
    
    // 2. Tabla de Documentos (Contiene la pista de enumeración)
    $db->exec("CREATE TABLE archivos_confidenciales (id INTEGER PRIMARY KEY, nombre_archivo TEXT, contenido TEXT)");
    $db->exec("INSERT INTO archivos_confidenciales (nombre_archivo, contenido) VALUES ('proyecto_omega.pdf', 'CONFIDENCIAL: $F2')");
    $db->exec("INSERT INTO archivos_confidenciales (nombre_archivo, contenido) VALUES ('manual_it.txt', 'Pista: Mira en sqlite_master para ver las tablas ocultas.')");
    
    // 3. Tabla Secreta (El objetivo final)
    $db->exec("CREATE TABLE xploit_vault_top_secret (secret_key TEXT)");
    $db->exec("INSERT INTO xploit_vault_top_secret (secret_key) VALUES ('$F3')");
}

$output = "";
$flag_level = 0; // 0: nada, 1: F1, 2: F2, 3: F3

if (isset($_POST['user']) && isset($_POST['pass'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    
    try {
        // Bloque de control de inyecciones triviales (Troll #1)
        if (strpos($user, "1=1") !== false || strpos($pass, "1=1") !== false) {
            $output = "<span style='color: #ff00ff; font-weight: bold;'>[!] DETECTADO ATAQUE DE NIVEL 'TUTORIAL DE YOUTUBE'.</span><br>";
            $output .= "En serio, ¿1=1? Mi abuela hackeaba mejor que eso. Intenta algo con UNION si quieres ver la chicha.";
        }
        elseif (stripos($user, "DROP TABLE") !== false || stripos($pass, "DROP TABLE") !== false) {
            $output = "Buen intento, Neo. Pero he puesto la DB en 'Solo Lectura' para graciosos como tú. 😉";
        }
        else {
            $query = "SELECT * FROM usuarios WHERE username = '$user' AND password = '$pass'";
            $stmt = $db->query($query);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($rows) > 0) {
                foreach ($rows as $row) {
                    foreach ($row as $val) {
                        // Filtrado de metadatos y visualización de flags extraídas
                        if (strlen($val) > 0 && !in_array($val, ['admin', '1', 'key', 'doc'])) {
                            $output .= "<div class='flag-output'>[+] DATA_EXTRACTED: <span class='flag-text'>$val</span></div>";
                            
                            if ($val === $F1) $flag_level = 1;
                            elseif ($val === $F2) $flag_level = 2;
                            elseif ($val === $F3) $flag_level = 3;
                        }
                    }
                }
                if ($output === "") $output = "Acceso concedido. (Entrar sin inyección no tiene mérito, ¿eh?)";
            } else {
                // Respuestas de error aleatorias (Troll #2)
                $trolls = [
                    "¿Has probado a apagar y encender el cerebro?",
                    "ERROR 418: I'm a teapot. Y tú un luser.",
                    "Esa password es de chiste. Ni mi gato la usaría (si tuviera gato).",
                    "¿'admin' / 'admin'? Qué original. Casi me da un infarto de la sorpresa.",
                    "Vete a dormir, que a estas horas solo hackean los que saben.",
                    "He visto bots de spam con más ingenio que esa query.",
                    "[SISTEMA]: Se ha detectado un intento de hackeo... de baja calidad."
                ];
                $output = $trolls[array_rand($trolls)];
            }
        }
    } catch (Exception $e) {
        $output = "System Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Xploit Corp - Mixed Terminal v.2.6</title>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <style>
        /* Animación RGB / Rainbow para el borde */
        @keyframes rainbow-border {
            0% { border-color: #f00; box-shadow: 0 0 20px #f00; }
            33% { border-color: #0f0; box-shadow: 0 0 20px #0f0; }
            66% { border-color: #00f; box-shadow: 0 0 20px #00f; }
            100% { border-color: #f00; box-shadow: 0 0 20px #f00; }
        }

        body { background-color: #050505; color: #00ff00; font-family: 'Courier New', monospace; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; overflow: hidden; }
        
        .terminal { 
            border: 2px solid #00ff00; 
            padding: 20px; 
            width: 600px; 
            background: #000; 
            position: relative; 
            transition: all 0.5s ease; 
            box-shadow: 0 0 10px #004400;
        }
        
        /* Modo RGB temporal activado por JS */
        .terminal.rgb-mode { animation: rainbow-border 1s infinite linear; border-width: 3px; }

        h2 { border-bottom: 1px solid #00ff00; padding-bottom: 10px; text-transform: uppercase; letter-spacing: 2px; }
        input { background: #0a0a0a; border: 1px solid #333; color: #00ff00; width: 80%; padding: 5px; margin: 10px 0; font-family: inherit; outline: none; }
        button { background: #00ff00; color: #000; border: none; padding: 10px 20px; cursor: pointer; font-weight: bold; text-transform: uppercase; margin-top: 10px; }
        button:hover { background: #fff; }
        
        .output { margin-top: 20px; border-left: 3px solid #00ff00; padding-left: 10px; min-height: 20px; white-space: pre-wrap; font-size: 0.9em; }
        
        .flag-output { color: #fff; font-weight: bold; margin-top: 10px; }
        .flag-text { color: #ffff00; text-decoration: underline; background: rgba(255,255,0,0.1); padding: 2px 4px; }
    </style>
</head>
<body>
    <div class="terminal" id="term">
        <h2>XPLOIT_CORP // HYBRID_SYS v.2.6</h2>
        <form method="POST">
            USER_ID: <input type="text" name="user" autocomplete="off" placeholder="root@xploit"><br>
            SECURE_KEY: <input type="password" name="pass" autocomplete="off" placeholder="••••••••"><br>
            <button type="submit">> EJECUTAR</button>
        </form>
        <?php if ($output !== ""): ?>
            <div class="output"><?php echo $output; ?></div>
        <?php endif; ?>
    </div>

    <script>
        const flagLevel = <?php echo $flag_level; ?>;
        if (flagLevel > 0) {
            // Activar modo RGB temporal
            const term = document.getElementById('term');
            term.classList.add('rgb-mode');
            
            // Cantidad de confeti según dificultad de la flag
            const count = flagLevel * 150;
            const defaults = { origin: { y: 0.7 }, zIndex: 1000 };

            function fire(particleRatio, opts) {
                confetti({ ...defaults, ...opts, particleCount: Math.floor(count * particleRatio) });
            }

            // Explosión de confeti profesional
            fire(0.25, { spread: 26, startVelocity: 55 });
            fire(0.2, { spread: 60 });
            fire(0.35, { spread: 100, decay: 0.91, scalar: 0.8 });
            fire(0.1, { spread: 120, startVelocity: 25, decay: 0.92, scalar: 1.2 });

            // Volver a la normalidad (Verde estable) tras 5 segundos
            setTimeout(() => {
                term.classList.remove('rgb-mode');
            }, 5000);
        }
    </script>
</body>
</html>