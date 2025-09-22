# E aí, tudo beleza? Bem-vindo(a) ao meu projeto! 
<br>Se você caiu aqui, provavelmente quer saber o que é esse projeto, né? Então, senta aí que eu te explico!</br>
<br> **API_CRUD** é um projeto de backend que eu criei para gerenciar usuários de forma simples e eficiente. Pensa nela como uma API RESTful completinha, com tudo que tem direito: criar, ler, atualizar e deletar usuários (o famoso CRUD).</br>
<br> A ideia aqui era construir algo sólido, bem organizado e fácil de dar manutenção, seguindo boas práticas de desenvolvimento como a **arquitetura em 3 camadas (Controller-Service-Model)**. Foi um ótimo jeito de colocar a mão na massa e solidificar esses conceitos! </br>

## O que essa API faz, afinal? 
Se liga nas funcionalidades que ela tem:
*  Listar todos os usuários: Pega todo mundo que tá cadastrado no banco de uma vez só.
*  Buscar um usuário específico: Encontra alguém pelo seu ID. Bem útil!
*  Criar um novo usuário: Cadastra uma pessoa nova no sistema, com direito a criptografia de senha e tudo mais pra garantir a segurança.
*  Atualizar dados de um usuário: Permite editar informações como nome e email de alguém que já existe.
*  Deletar um usuário: Remove um usuário do banco de dados.

Pra dar vida a esse projeto, usei algumas tecnologias bem conhecidas:
* PHP: A linguagem principal por trás de toda a lógica do servidor.
* MySQL: O banco de dados escolhido pra guardar todas as informações dos usuários.
* PDO: Pra fazer a conexão entre o PHP e o banco de dados de forma segura e moderna.
* Postman: O fiel escudeiro pra testar cada cantinho da API e garantir que tudo está funcionando como deveria!

## **Bora botar pra rodar?** 
Quer testar na sua própria máquina? É bem tranquilo! Só seguir esses passos:
1. Clone o Repositório
Primeiro, você precisa baixar o projeto. Abre o terminal e manda ver:
git clone [https://github.com/LuK654/API_CRUD](https://github.com/LuK654/API_CRUD)
cd API_CRUD

2. Prepare o Banco de Dados
Você vai precisar de um servidor local como o XAMPP, WAMP ou MAMP.
* Abra o **phpMyAdmin**.
* Crie um novo banco de dados com o nome api_crud_db.
* Vá na aba "SQL" e rode o script abaixo pra criar a tabela usuarios:
  
CREATE TABLE usuarios (   
   id INT AUTO_INCREMENT PRIMARY KEY,  
    nome VARCHAR(255) NOT NULL,  
    email VARCHAR(255) NOT NULL UNIQUE,  
    senha VARCHAR(255) NOT NULL,  
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP  
  );


3. Configure a Conexão
<br>Dá uma olhadinha no arquivo config/Database.php. As credenciais padrão (root e senha vazia) geralmente já funcionam com o XAMPP. Se as suas forem diferentes, é só ajustar lá.</br>

4. **Inicie o Servidor!**
Jogue a pasta do projeto dentro da pasta htdocs do seu XAMPP.
Inicie os módulos **Apache** e **MySQL** no painel do XAMPP.
Prontinho! Sua API já deve estar no ar, esperando suas requisições em http://localhost/api_crud/public/.

## Mapa da API (Endpoints) 
Aqui estão os caminhos que você pode chamar (usando o Postman, por exemplo) pra interagir com a API:
Método | Endpoint | Descrição
------| ----------- | -----------------------
GET | /usuarios | Lista todos os usuários.
GET | /usuarios/buscar/{id} | Busca um usuário pelo ID.
POST | /usuarios/criar | Cria um novo usuário.
PUT | /usuarios/atualizar/{id} | Atualiza um usuário existente.
DELETE | /usuarios/deletar/{id} | Deleta um usuário. 
<br> 

**Exemplo de body para POST (criar):**
{  
    "nome": "Maria Souza",  
    "email": "maria.souza@email.com",  
    "senha": "senhaForte456"  
}  

<br> 

**Exemplo de body para PUT (atualizar):**
{  
    "nome": "Maria Souza Silva",  
    "email": "maria.silva@email.com"  
}  


É isso! Um projeto simples, que foi feito pra treinar e aplicar conceitos importantes de desenvolvimento backend e futuramente frontend.
