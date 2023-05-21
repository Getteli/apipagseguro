<?php
// credenciais do pagseguro da conta do nosso cliente.
define("EMAIL_PAGSEGURO","seuemailpagseguro@gmail.com");
define("TOKEN_PAGSEGURO",""); // token producao
define("TOKEN_SANDBOX",""); // token sandbox / ambiente de teste
// credenciais pre definidas
define("CREDENTIALS_PAGSEGURO","email=".EMAIL_PAGSEGURO."&token=".TOKEN_PAGSEGURO); // pagseguro
define("CREDENTIALS_SANDBOX","email=".EMAIL_PAGSEGURO."&token=".TOKEN_SANDBOX); // sandbox