<?php
//Pegando os campos colocados
$email = $_POST['email'];
$assunto = $_POST['assunto'];
$mensagem = $_POST['mensagem'];

//Se o email for inválido
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Endereço de e-mail inválido";
    exit;
}

//Importando as bibliotecas
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './biblioteca/vendor/autoload.php';

//Criando uma variável do tipo PHPMailer
$mail = new PHPMailer(true);
try {
    //Configurações do SMTP
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER; (deixe essa opção caso queira ver os detalhes do envio)
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->CharSet = 'UTF-8';

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
    $mail->AltBody = strip_tags($mensagem);

    //Se o usuário tiver colocado um arquivo
    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['size'] > 0) {

        //Se não houve nenhum erro ao fazer o upload do arquivo
        if ($_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {

            //Pegando o arquivo e definindo um tamanho máximo para ele
            $arquivo = $_FILES['arquivo'];
            $tamanhoMaximo = 5 * 1024 * 1024; // 5MB
            if ($arquivo['size'] > $tamanhoMaximo) {
                echo "Arquivo muito grande. Tamanho máximo permitido é 5MB.";
                exit;
            }
            
            //Se o diretório não existir, ele cria o diretório
            $diretorio = __DIR__ . "/arquivos/"; 
            if (!file_exists($diretorio)) {
                mkdir($diretorio, 0755, true);
            }

            //Pegando alguns atributos do arquivo e definindo o diretório
            $nome_arquivo = $arquivo['name'];
            $tmp_nome = $arquivo['tmp_name'];
            $diretorio = "arquivos/";

            //Move o arquivo para a pasta criada para salvar os arquivos
            move_uploaded_file($tmp_nome, $diretorio . $nome_arquivo);

            //Adiciona o arquivo ao email
            $mail->addAttachment($diretorio . $nome_arquivo);
        } else {
            echo "Erro ao fazer o upload do arquivo";
        }
    }

    //Envia o email, exibe uma mensagem de sucesso e exclui o arquivo do diretório
    $mail->send();
    echo "E-mail enviado com sucesso!";
    unlink($diretorio . $nome_arquivo);
} catch (Exception $e) {
    echo ("Erro ao enviar o e-mail: {$mail->ErrorInfo}");
}
?>