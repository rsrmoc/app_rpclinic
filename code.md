# Estrutura do PWA - RPclinic

Este documento define as pastas e instruções principais para o desenvolvimento e manutenção da aplicação PWA.

## 📁 Pastas do Projeto

### 🎮 Controllers
- **Caminho:** `app/Http/Controllers/app_rpclinic`
- **Função:** Contém a lógica de backend e as rotas da API para a aplicação mobile.

### 📜 JavaScript (Fontes)
- **Caminho:** `resources/js/app_rpclinica`
- **Importante:** Todas as edições de script devem ser feitas nesta pasta.
- **Compilação:** Ao rodar o comando `npm run watch`, estas alterações são compiladas automaticamente para a pasta:
  - `public/js/app_rpclinica`

### 🖼️ Views (Blade / HTML)
- **Caminho:** `resources/views/app_rpclinic`
- **Função:** Arquivos de interface (layouts, páginas e componentes).

### 🎨 Assets & Includes
- **Caminho:** `public/app/assets`
- **Função:** Contém arquivos CSS, imagens e plugins específicos do layout mobile.

---

## 🚀 Instruções de Fluxo
1. Sempre mantenha o comando `npm run watch` rodando no terminal enquanto altera arquivos JS ou CSS.
2. Não edite arquivos diretamente na pasta `public/js/app_rpclinica`, pois eles são sobrescritos pela compilação.
3. As APIs do PWA devem ser gerenciadas nos controllers da pasta especificada acima.
