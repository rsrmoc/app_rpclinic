# App RPclinic

![Logo RPclinic](public/assets/images/logo.png)

Bem-vindo ao repositório oficial do **App RPclinic**. Esta aplicação é um sistema completo de gestão para clínicas, desenvolvido em Laravel, focado em agilidade, organização e uma experiência de usuário moderna com design Glassmorphism.

## 📋 Sobre o Projeto

O **App RPclinic** oferece funcionalidades abrangentes para o gerenciamento do fluxo de atendimento clínico, incluindo:

- **Agendamento Completo:** Painel visual para gestão de agendas, confirmações e bloqueios.
- **Prontuário Eletrônico:** Histórico detalhado de pacientes e atendimentos.
- **Painel de Recepção:** Controle de fluxo, check-in e status de atendimento em tempo real.
- **Relatórios:** Dashboards administrativos e métricas de desempenho.

## 🚀 Requisitos do Sistema

Para rodar esta aplicação localmente, certifique-se de ter instalado:

- **PHP** >= 8.0
- **Composer** (Gerenciador de dependências PHP)
- **MySQL** ou **MariaDB** (Recomendado o uso do XAMPP ou Laragon no Windows)
- **Node.js** & **NPM** (Para compilação dos assets frontend)
- **Git**

## 🔧 Instalação Passo a Passo

Siga os passos abaixo para baixar e configurar o projeto em sua máquina.

### 1. Clonar o Repositório

Abra seu terminal (Git Bash ou PowerShell) e clone o projeto:

```bash
git clone https://github.com/rsrmoc/app_rpclinic.git
cd app_rpclinic
```

### 2. Instalar Dependências do Backend (Laravel)

```bash
composer install
```

### 3. Configurar o Ambiente

Copie o arquivo de exemplo de configuração e ajuste conforme seu banco de dados:

```bash
cp .env.example .env
```

Abra o arquivo `.env` em um editor de texto e configure as credenciais do banco de dados:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_seu_banco
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 4. Gerar Chave da Aplicação

```bash
php artisan key:generate
```

### 5. Banco de Dados

Crie o banco de dados no seu gerenciador MySQL (phpMyAdmin, Workbench, etc) com o nome definido no `.env`. Em seguida, execute as migrações:

```bash
php artisan migrate --seed
```
*(Nota: O parâmetro --seed irá popular o banco com dados iniciais necessários para rodar o sistema)*

### 6. Instalar Dependências do Frontend

```bash
npm install
npm run dev
```

### 7. Rodar a Aplicação

Inicie o servidor de desenvolvimento local:

```bash
php artisan serve
```

Acesse a aplicação em seu navegador através do endereço: `http://localhost:8000`

---

## 🛠 Suporte

Caso encontre problemas durante a instalação ou uso, entre em contato com a equipe de desenvolvimento ou abra uma issue neste repositório.

---

**Desenvolvido com ❤️ pela equipe RPsys**
