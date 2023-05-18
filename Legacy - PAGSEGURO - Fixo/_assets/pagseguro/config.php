<?php
// credenciais do pagseguro da conta do nosso cliente.
define("EMAIL_PAGSEGURO","iliontecnologia@gmail.com");
define("TOKEN_PAGSEGURO","8A8827FB83BD4E318FB5FC99779A251B"); // token producao
define("TOKEN_SANDBOX","0FF1832C6D9046A9A9925A6D06DD08F6"); // token sandbox / ambiente de teste
// credenciais pre definidas
define("CREDENTIALS_PAGSEGURO","email=".EMAIL_PAGSEGURO."&token=".TOKEN_PAGSEGURO); // pagseguro
define("CREDENTIALS_SANDBOX","email=".EMAIL_PAGSEGURO."&token=".TOKEN_SANDBOX); // sandbox