# Sistema de Cadastro de Ordem de Serviço - Teste Shift

## Especificação Geral

Criar um sistema WEB baseado no modelo de negócio proposto, com o intuito de atender a necessidade do cliente.

- Deve ser produzido o módulo do sistema WEB que permite ao cliente fazer o Cadastro da Ordem de Serviço, segundo o modelo de negócio proposto.
- Utilizado HTML, CSS, JavaScript, jQuery, Bootstrap, PHP, MySQL 

## Instalação

1 - Descompactar a pasta (shift) no local que será utilizado pelo servidor php
2 - Configurar o banco de dados a ser utilizado e colocar os dados no arquivo acesso.php
3 - Executar o script incluso (banco.sql), para criação das tabelas e inserção de registros de teste.
4 - Acessar o index.php pelo servidor php

## Observações

- Inicialmente o sistema carrega todas as informações básicas escritas e preenchidas em php (listas, formulários e conteúdo)
- Existe um html select para selecionar a tabela a ser manipulada, ao clicar em carregar o CRUD básico é carregado
- Operações de inserção, atualização e remoção são realizadas por ajax como demonstração (requisições realizadas em requisicao.php)
- O arquivo js/principal.js é o local que contém as implementações em JavaScript e jQuery
- Foram criadas abstrações para o banco e manipulação de dados. Arquivos: banco.php, registro.php, acesso.php, estrutura.php
- View e controler estão em conjunto em index.php, para não extender ainda mais o código resolvi manter dessa forma pois o controler nesse caso praticamente só carrega informações para preencher o html

