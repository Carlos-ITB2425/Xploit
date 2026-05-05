<?php
session_start();

$F1 = "nOu3t7WfJnZ9tzU0S1lA"; 
$F2 = "kR1p8vXmQjL4nH2bS9wZ"; 
$F3 = "mJ8pL2wN5vX9zQ1rT4bS";

$db_dir = '/var/lib/sqlite/';
$db_file = $db_dir . 'ctf.db';
$log_file = $db_dir . 'ataques.log';

$db = new PDO("sqlite:$db_file");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$output = ""; 
$flag_level = 0; 
$terminal_status = "normal"; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'] ?? '';
    $pass = $_POST['pass'] ?? '';
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'NO_AGENT';
    $query = "SELECT * FROM usuarios WHERE username = '$user' AND password = '$pass'";

    try {
        $stmt = $db->query($query);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($rows) > 0) {
            // ANALIZAMOS LA QUERY SOLO SI EL LOGIN FUE EXITOSO
            // Detecta si el bypass se hizo mediante una comparación lógica externa.
            $tautology_pattern = '/\s+OR\s+('?\w+'?)\s*=\s*/i';

            if (preg_match($tautology_pattern, $query)) {
                $terminal_status = "shame";
                $output = "<strong>[!] ERROR DE ORIGINALIDAD:</strong> Inyección detectada. Has roto la lógica, pero usar 'OR X=X' es demasiado básico.";
                file_put_contents($log_file, "[".date('Y-m-d H:i:s')."] IP: $ip | TAUTOLOGÍA: $query\n", FILE_APPEND);
            } else {
                $flag_found = false;
                foreach ($rows as $row) {
                    foreach ($row as $val) {
                        if (strlen($val) > 15) {
                            $output .= "<div class='flag-output'>[+] DATA_EXTRACTED: <span class='flag-text'>$val</span></div>";
                            if ($val === $F1) $flag_level = 1;
                            elseif ($val === $F2) $flag_level = 2;
                            elseif ($val === $F3) $flag_level = 3;
                            $terminal_status = "party";
                            $flag_found = true;
                        }
                    }
                }
                if (!$flag_found) {
                    $terminal_status = "alert";
                    $output = "Acceso concedido como ADMIN. Pero no hay secretos aquí... prueba UNION SELECT.";
                }
            }
        } else {
            $output = "Credenciales incorrectas.";
        }
        file_put_contents($log_file, "[".date('Y-m-d H:i:s')."] IP: $ip | OK | QUERY: $query\n", FILE_APPEND);
    } catch (Exception $e) {
        $terminal_status = "normal";
        $output = "<div style='color: #ff5500; font-family: monospace;'>";
        $output .= "<strong>[!] SQL_SYNTAX_ERROR:</strong><br>";
        $output .= "<i>\"" . $e->getMessage() . "\"</i><br><br>";
        $output .= "<strong>QUERY EJECUTADA:</strong><br>";
        $output .= "<code style='color: #fff; background: #222; padding: 2px;'>" . htmlspecialchars($query) . "</code><br><br>";
        $output .= "<span>Pista: Si intentas romper la comilla, asegúrate de cerrar la sentencia correctamente.</span>";
        $output .= "</div>";
        file_put_contents($log_file, "[".date('Y-m-d H:i:s')."] IP: $ip | ERROR: " . $e->getMessage() . " | QUERY: $query\n", FILE_APPEND);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Xploit Corp - Terminal Verbosa</title>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <style>
        @keyframes shake { 
            0% { transform: translate(1px, 1px) rotate(0deg); }
            10% { transform: translate(-1px, -2px) rotate(-1deg); }
            20% { transform: translate(-3px, 0px) rotate(1deg); }
            30% { transform: translate(3px, 2px) rotate(0deg); }
            40% { transform: translate(1px, -1px) rotate(1deg); }
            50% { transform: translate(-1px, 2px) rotate(-1deg); }
            100% { transform: translate(0, 0) rotate(0deg); }
        }
        @keyframes rainbow { 0% { border-color: #f00; } 50% { border-color: #0f0; } 100% { border-color: #00f; } }
        body { background-color: #050505; color: #0f0; font-family: 'Courier New', monospace; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .terminal { border: 2px solid #0f0; padding: 30px; width: 680px; background: #000; position: relative; box-shadow: 0 0 10px #004400; }
        .terminal.shame { animation: shake 0.2s ease-in-out 0s 5; border-color: #ff00ff; box-shadow: 0 0 20px #ff00ff; }
        .terminal.alert { border-color: #ffaa00; }
        .terminal.party { animation: rainbow 1s infinite linear; border-width: 4px; }
        h2 { border-bottom: 1px solid #0f0; padding-bottom: 10px; }
        input { background: #0a0a0a; border: 1px solid #333; color: #0f0; width: 100%; padding: 10px; margin: 10px 0; box-sizing: border-box; outline: none; }
        button { background: #0f0; color: #000; border: none; padding: 15px; width: 100%; cursor: pointer; font-weight: bold; }
        .output { margin-top: 20px; border-left: 3px solid #0f0; padding-left: 15px; background: #080808; padding: 15px; }
        .flag-text { color: #ff0; font-weight: bold; }
    </style>
</head>
<body>
    <?php if ($terminal_status === 'shame'): ?>
        <iframe style="display:none" src="https://www.youtube.com/embed/Oc7Cin_87H4?autoplay=1" allow="autoplay"></iframe>
    <?php endif; ?>
    <div class="terminal <?php echo $terminal_status; ?>">
        <h2>XPLOIT CORP</h2>
        <form method="POST">
            USUARIO: <input type="text" name="user" placeholder="Login ID..." autocomplete="off"><br>
            CONTRASEÑA: <input type="password" name="pass" placeholder="Password..." autocomplete="off"><br>
            <button type="submit">> INICIAR SESIÓN</button>
        </form>
        <?php if ($output !== ""): ?>
            <div class="output"><?php echo $output; ?></div>
        <?php endif; ?>
    </div>
    <script>
        if ("<?php echo $terminal_status; ?>" === "party") {
            confetti({ particleCount: <?php echo $flag_level * 200; ?>, spread: 80, origin: { y: 0.6 } });
        }
    </script>
</body>
</html>
