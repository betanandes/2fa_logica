<?php
session_start();
$conn = new mysqli('localhost', 'root', 'unisuam123', 'login_db');

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $senha = md5($_POST['senha']);

    // Verifica se o usuário existe
    $sql = "SELECT * FROM usuarios WHERE login = '$login' AND senha = '$senha'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $_SESSION['login'] = $login;
        $user = $result->fetch_assoc();

        // 2FA - Escolhe aleatoriamente entre nome da mãe e data de nascimento
        if (!isset($_SESSION['2fa_type']) || isset($_POST['input_2fa'])) {
            $options = ['nome_mae', 'data_nascimento', 'cpf'];
            $_SESSION['2fa_type'] = $options[rand(0, 2)];
        }
        
        header("Location: 2fa.php");
        exit();
    } else {
        echo "Login ou senha incorretos.";
    }
}
?>

<form method="post" action="login.php">
    <label for="login">Login:</label>
    <input type="text" name="login" required><br>
    <label for="senha">Senha:</label>
    <input type="password" name="senha" required><br>
    <button type="submit">Entrar</button>
</form>
