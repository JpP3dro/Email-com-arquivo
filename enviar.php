<?php
$email = $_POST['email'];
$assunto = $_POST['assunto'];
$mensagem = $_POST['mensagem'];
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require './biblioteca/vendor/autoload.php';
$mail = new PHPMailer(true);
try {
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                     
    $mail->isSMTP();                                            
    $mail->Host       = 'smtp.gmail.com';                     
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = 'example@gmail.com';                     
    $mail->Password   = 'app password';                               
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            
    $mail->Port       = 587;          
    
    $mail->setFrom('joaopedrodallessiodebarros@gmail.com', 'Projeto ETEC');
    $mail->addAddress($email, 'Destinatário');                  

    $mail->isHTML(true);                                  
    $mail->Subject = $assunto;
    $mail->Body    = $mensagem;
    $mail->AltBody = $mensagem;

    if(isset($_FILES['arquivo'])){
        $arquivo = $_FILES['arquivo'];
        $nome_arquivo = $arquivo['name'];
        $tmp_nome = $arquivo['tmp_name']; 
        $diretorio = "arquivos/"; 
        move_uploaded_file($tmp_nome, $diretorio . $nome_arquivo); 
        $mail->addAttachment($diretorio . $nome_arquivo); 
    }
    
    $mail->send();

    echo "E-mail enviado com sucesso!";
} 
catch (Exception $e) {
    echo "Erro ao enviar o e-mail: {$mail->ErrorInfo}";
}

?>