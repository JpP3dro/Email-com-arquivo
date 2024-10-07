<?php
//Pegando os campos colocados
$email = $_POST['email'];
$assunto = $_POST['assunto'];
$mensagem = $_POST['mensagem'];

//Importando as bibliotecas
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require './biblioteca/vendor/autoload.php';

//Criando uma variável do tipo PHPMailer
$mail = new PHPMailer(true);
try {
    //Configurações do SMTP
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                     
    $mail->isSMTP();                                            
    $mail->Host       = 'smtp.gmail.com';                     
    $mail->SMTPAuth   = true;
    
    //Conta e senha do email que vai enviar a mensagem
    $mail->Username   = 'example@gmail.com';                     
    $mail->Password   = 'app password';                               
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            
    $mail->Port       = 587;          
    
    //Remetente e destinatário
    $mail->setFrom('example@gmail.com', 'Remetente');
    $mail->addAddress($email, 'Destinatário');                  

    //Definindo o HTML do email como true
    $mail->isHTML(true);                 
    
    //Assunto, mensagem e a mensagem alternativa (caso o HTML não esteja funcionando)
    $mail->Subject = $assunto;
    $mail->Body    = $mensagem;
    $mail->AltBody = $mensagem;

    //Se o usuário tiver colocado um arquivo
    if(isset($_FILES['arquivo'])){
        //Pegando alguns atributos do arquivo e definindo o diretório
        $arquivo = $_FILES['arquivo'];
        $nome_arquivo = $arquivo['name'];
        $tmp_nome = $arquivo['tmp_name']; 
        $diretorio = "arquivos/"; 
        //Move o arquivo para a pasta criada para salvar os arquivos
        move_uploaded_file($tmp_nome, $diretorio . $nome_arquivo); 
        //Adiciona o arquivo ao email
        $mail->addAttachment($diretorio . $nome_arquivo); 
    }
    
    //Envia o email e exibe uma mensagem de sucesso
    $mail->send();
    echo "E-mail enviado com sucesso!";
} 
catch (Exception $e) {
    echo "Erro ao enviar o e-mail: {$mail->ErrorInfo}";
}

?>