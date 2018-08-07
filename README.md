# CallManager
Um sistema call center extremamente simples para Skype. 

# Instalação

1. Execute o arquivo install.sql em um servidor MySQL.

2. Altere o arquivo <code>/application/config/database.php</code> e configure a conexão com o banco de dados:

3. Altere o arquivo <code>/application/config/config.php</code> e configure o <b>base_url</b>:

    <code>
    $config['base_url'] = 'http://seudominio.com/path/';
    </code>
   
Pronto!

# Sobre

Esse sistema foi desenvolvido com o framework <b>CodeIgniter</b> e faz uso das bibliotecas [CodeIgniter-Ion-Auth](https://github.com/benedmunds/CodeIgniter-Ion-Auth) e [Grocery CRUD](https://github.com/scoumbourdis/grocery-crud).
