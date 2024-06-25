<?php
// Inicia a sessão
session_start();

// Verifica se o usuário já está logado, se sim, redireciona para a página do dashboard
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: dashboard.php");
    exit;
}

// Definir variáveis para armazenar mensagens de erro ou sucesso
$error = "";
$success = "";

// Verifica se o formulário de registro foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = $_POST["reg_username"];
    $password = $_POST["reg_password"];
    $nivel_acesso = intval($_POST["nivel_acesso"]); // Converte o valor para um número inteiro

    // Conecta ao banco de dados
    $conn = new mysqli("localhost", "root", "", "sistema");

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Verifica se o usuário já existe
    $check_username = "SELECT id FROM usuario WHERE username = '$username'";
    $result = $conn->query($check_username);
    if ($result->num_rows > 0) {
        $error = "Erro: Nome de usuário já está em uso.";
    } else {
        // Insere o novo usuário no banco de dados
        $sql = "INSERT INTO usuario (username, password, perfil_id) VALUES ('$username', '$password', '$nivel_acesso')";
        if ($conn->query($sql) === TRUE) {
            $success = "Usuário registrado com sucesso!";
        } else {
            $error = "Erro: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Registro</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        .form-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
            border: 2px solid #000000;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
        }

        .form-container label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #000000;
        }

        .form-container input,
        .form-container select {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #000000;
            font-size: 16px;
        }

        .form-container input[type="submit"] {
            background-color: #000000;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container input[type="submit"]:hover {
            background-color: #333333;
        }

        .form-container a {
            color: #000000;
            text-decoration: none;
        }

        .form-container a:hover {
            color: #4CAF50;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }

        .success-message {
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="reg_username">Usuário:</label>
            <input type="text" id="reg_username" name="reg_username" required>
            <label for="reg_password">Senha:</label>
            <input type="password" id="reg_password" name="reg_password" required>
            <label for="nivel_acesso">Nível de Acesso:</label>
            <select id="nivel_acesso" name="nivel_acesso" required>
                <option value="1">Administrador</option>
                <option value="2">Cliente</option>
            </select>
            <input type="submit" name="register" value="Registrar">
        </form>
        <?php if (!empty($error)) : ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (!empty($success)) : ?>
            <p class="success-message"><?php echo $success; ?></p>
        <?php endif; ?>
        <a href="login.php">Fazer login</a>
    </div>
</body>

</html>
