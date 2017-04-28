# PHP WITHOUT FW

## Instalação

No diretório 'api/' execute o comando:

    composer install
    
## Execução

Para execução do mesmo, é necessário:
- Um servidor Apache;
- Banco de Dados MySql/Maria;
- PHP 7+ (para construção foi usado o 7.0.13)

Configure os dados da database na pasta 'api/config/config.ini', use como exemplo o 'model.config.ini'

Para criação da database use a rota:

    GET /api/database/create

Após esses passos, o sistema está pronto para rodar.
    
    