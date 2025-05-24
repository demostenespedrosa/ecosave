<p align="center">
  <img src="logo.png" alt="EcoSave Logo" width="150"/>
</p>

<h1 align="center">EcoSave 🍏♻️💰</h1>

<p align="center">
  <strong>Salve comida, economize dinheiro, ajude o planeta!</strong>
  <br />
  <em>Um clone do Too Good To Go / Food To Save, focado na experiência mobile.</em>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-7.4%2B-blueviolet?style=for-the-badge&logo=php" alt="PHP Version">
  <img src="https://img.shields.io/badge/MySQL-8.0%2B-orange?style=for-the-badge&logo=mysql" alt="MySQL Version">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-purple?style=for-the-badge&logo=bootstrap" alt="Bootstrap Version">
  <img src="https://img.shields.io/badge/licen%C3%A7a-MIT-green?style=for-the-badge" alt="License">
  </p>

<p align="center">
  <a href="#sobre-o-projeto">Sobre</a> •
  <a href="#✨-funcionalidades-chave">Funcionalidades</a> •
  <a href="#🛠️-tecnologias-utilizadas">Tecnologias</a> •
  <a href="#🚀-como-começar">Como Começar</a> •
  <a href="#📂-estrutura-do-projeto">Estrutura</a> •
  <a href="#🤝-como-contribuir">Contribuir</a> •
  <a href="#🔮-próximos-passos">Próximos Passos</a> •
  <a href="#📄-licença">Licença</a>
</p>

---

## 📖 Sobre o Projeto

O **EcoSave** é uma plataforma web progressiva (PWA) com foco 100% mobile, inspirada em aplicativos de sucesso como "Too Good To Go" e "Food To Save". O objetivo principal é criar uma ponte entre estabelecimentos comerciais com excedente de alimentos e consumidores que buscam adquirir produtos de qualidade por preços reduzidos. Ao fazer isso, o EcoSave contribui ativamente para a **redução do desperdício alimentar**, promove a **economia circular** e oferece uma **oportunidade de economia** para os usuários.

Este projeto foi desenvolvido com uma stack tecnológica robusta e popular (PHP, MySQL, Bootstrap 5, JavaScript), mas com um backend intencionalmente simplificado para facilitar o entendimento e a contribuição, especialmente por desenvolvedores iniciantes. O design da interface busca inspiração nos padrões visuais do iOS, visando uma experiência de usuário limpa, intuitiva e agradável.

### 🎯 Problema que Resolvemos:
* **Desperdício de Alimentos:** Toneladas de comida em perfeitas condições são descartadas diariamente por estabelecimentos comerciais.
* **Custo de Vida:** Consumidores buscam alternativas para economizar em suas compras de alimentos.
* **Sustentabilidade:** A necessidade crescente de práticas mais sustentáveis no consumo e produção de alimentos.

### 💡 Nossa Solução:
O EcoSave permite que:
* **Estabelecimentos** (padarias, restaurantes, supermercados, etc.) cadastrem "Sacolas Surpresa" ou itens excedentes com descontos atraentes.
* **Consumidores** descubram essas ofertas próximas, reservem e retirem os produtos, evitando o desperdício e economizando.

---

## ✨ Funcionalidades Chave

### Para Consumidores:
* 👤 **Cadastro e Login Simplificados:** Acesso rápido à plataforma.
* 🗺️ **Descoberta de Ofertas por Categoria:** Navegação intuitiva por tipos de estabelecimentos/produtos.
    * Scroll horizontal de ofertas dentro de cada categoria.
* 🛍️ **Detalhes da Oferta:** Visualização completa com informações, foto (se disponível), preço, horário de coleta.
* 🛒 **Reserva de Ofertas:** Processo simples para garantir a "Sacola Surpresa".
* 📋 **Meus Pedidos:** Histórico e status das reservas realizadas.
* 🧑‍  **Perfil do Usuário:** Visualização de dados e opção de logout.
* 📱 **Design Responsivo Mobile-First:** Experiência otimizada para smartphones, com navegação inferior estilo iOS.

### Para Estabelecimentos:
* 🏢 **Cadastro e Login Dedicados:** Área exclusiva para parceiros.
* 📊 **Painel de Controle:** Visão geral das atividades e acesso rápido às funcionalidades.
* ➕ **Cadastro de Ofertas:** Formulário intuitivo para adicionar novas "Sacolas Surpresa", incluindo:
    * Título, descrição, preço, quantidade.
    * Período de coleta.
    * Seleção de categoria para a oferta.
    * Upload de foto para a oferta.
* ✏️ **Gerenciamento de Ofertas:** Listagem, edição e desativação de ofertas cadastradas.
* 📝 **Visualização de Reservas:** Acompanhamento dos pedidos feitos para suas ofertas.
* ✅ **Marcar Reservas como Coletadas:** Atualização do status dos pedidos.
* 🧑‍💼 **Perfil do Estabelecimento:** Visualização de dados e opção de logout.

---

## 🛠️ Tecnologias Utilizadas

O EcoSave é construído com as seguintes tecnologias:

* **Frontend:**
    * `HTML5` (Estrutura semântica)
    * `CSS3` (Estilização customizada, inspirada no iOS)
    * `Bootstrap 5.3` (Framework CSS para componentes e responsividade)
    * `JavaScript (ES6+)` (Interatividade do lado do cliente, manipulação do DOM)
    * `AJAX (Fetch API)` (Comunicação assíncrona com o backend)
    * `LottieFiles Player` (Para animações)
* **Backend:**
    * `PHP 7.4+` (Linguagem de script do lado do servidor, lógica da aplicação)
    * `API RESTful` (Endpoints para comunicação frontend-backend)
* **Banco de Dados:**
    * `MySQL 8.0+` (Sistema de Gerenciamento de Banco de Dados Relacional)
    * `PDO (PHP Data Objects)` (Para interação segura com o banco de dados)
* **Servidor (Ambiente de Desenvolvimento Típico):**
    * `Apache` (Servidor Web) - Comum em stacks como XAMPP, WAMP, MAMP.
* **Ferramentas e Utilitários:**
    * `Bootstrap Icons` (Para iconografia)
    * `Google Fonts (Inter)` (Para tipografia)

---

## 🚀 Como Começar

Siga estas instruções para obter uma cópia local do projeto e executá-la.

### Pré-requisitos

* Um ambiente de servidor local com PHP e MySQL (XAMPP, WAMP, MAMP, Docker, etc.).
* Um gerenciador de banco de dados (phpMyAdmin, DBeaver, MySQL Workbench, etc.).
* Navegador web moderno.

### Instalação

1.  **Clone o Repositório:**
    ```bash
    git clone [https://github.com/demostenespedrosa/EcoSave.git](https://github.com/demostenespedrosa/EcoSave.git)
    cd EcoSave
    ```

2.  **Configure o Banco de Dados:**
    * Crie um novo banco de dados no seu MySQL (ex: `ecosave`).
    * Importe o arquivo `ecosave.sql` (que você precisará criar ou que foi fornecido) para criar as tabelas necessárias.
        * **Tabelas:** `users`, `businesses`, `categories`, `offers`, `orders`.
    * **Atenção:** Certifique-se de popular a tabela `categories` com algumas categorias iniciais para que o sistema funcione corretamente.

3.  **Configure a Conexão com o Banco:**
    * Abra o arquivo `/includes/db_connect.php`.
    * Atualize as constantes `DB_HOST`, `DB_NAME`, `DB_USER`, e `DB_PASS` com as suas credenciais do MySQL.
    ```php
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'ecosave'); // O nome do seu banco
    define('DB_USER', 'root');       // Seu usuário
    define('DB_PASS', '');           // Sua senha
    ```

4.  **Permissões da Pasta de Uploads:**
    * Crie uma pasta chamada `uploads` na raiz do projeto.
    * Certifique-se de que esta pasta tenha permissões de escrita para o servidor web (ex: `chmod 775 uploads`).

5.  **Execute o Projeto:**
    * Coloque a pasta do projeto no diretório `htdocs` (XAMPP), `www` (WAMP/MAMP) ou configure seu servidor virtual.
    * Abra seu navegador e acesse `http://localhost/EcoSave/` (ou o URL configurado).

### Contas de Teste (Sugestão)

Para facilitar os testes, você pode querer inserir alguns dados iniciais:

* **Consumidor:** Crie um utilizador via `cadastro_consumidor.php`.
* **Estabelecimento:** Crie um utilizador via `cadastro_estabelecimento.php`.
    * Após o cadastro, vá ao banco de dados e **atribua manualmente um `category_id`** às ofertas que este estabelecimento criar (na tabela `offers`) para que elas apareçam no dashboard do consumidor.

---

## 📸 Screenshots (Exemplos)

<p align="center">
  <img src="https://via.placeholder.com/250x500.png/f2f2f7/FF3B30?Text=Tela+Login+iOS" alt="Tela de Login" hspace="10">
  <img src="https://via.placeholder.com/250x500.png/f2f2f7/FFCC00?Text=Dashboard+Consumidor" alt="Dashboard Consumidor" hspace="10">
  <img src="https://via.placeholder.com/250x500.png/f2f2f7/FF9500?Text=Detalhe+Oferta" alt="Detalhe Oferta" hspace="10">
</p>
*(Substitua por screenshots reais do seu aplicativo!)*
-->

---

## 🤝 Como Contribuir

Contribuições são o que tornam a comunidade open source um lugar incrível para aprender, inspirar e criar. Qualquer contribuição que você fizer será **muito apreciada**.

Se você tiver uma sugestão para melhorar este projeto, por favor, faça um fork do repositório e crie um pull request. Você também pode simplesmente abrir uma issue com a tag "enhancement".
Não se esqueça de dar uma estrela ao projeto! Obrigado novamente!

1.  Faça um Fork do Projeto
2.  Crie sua Feature Branch (`git checkout -b feature/AmazingFeature`)
3.  Commit suas Mudanças (`git commit -m 'Add some AmazingFeature'`)
4.  Push para a Branch (`git push origin feature/AmazingFeature`)
5.  Abra um Pull Request

---

## 🔮 Próximos Passos (Melhorias Futuras)

O EcoSave tem um grande potencial de crescimento! Algumas ideias para futuras implementações:

* ⭐ **Sistema de Avaliação:** Permitir que consumidores avaliem estabelecimentos e ofertas.
* 📍 **Geolocalização Avançada:** Usar GPS para mostrar ofertas realmente próximas e permitir busca por raio.
* 💳 **Pagamentos Online:** Integrar um gateway de pagamento para reservas.
* 💬 **Notificações:** Alertas sobre novas ofertas, status de pedidos, etc.
* ❤️ **Lista de Favoritos:** Para consumidores salvarem estabelecimentos preferidos.
* 🎨 **Personalização de Perfil:** Permitir que usuários e estabelecimentos editem mais detalhes e adicionem fotos de perfil.
* 🔍 **Busca e Filtros Avançados:** Melhorar a descoberta de ofertas com mais critérios.
* 🌐 **Internacionalização (i18n):** Suporte a múltiplos idiomas.
* 📊 **Painel Administrativo:** Para gerenciamento da plataforma.

---

## 📞 Contato

Link do Projeto: [https://github.com/SEU_USUARIO/EcoSave](https://github.com/demostenespedrosa/EcoSave)

---

<p align="center">✨ Feito com ❤️ e muito ☕ em [Sua Cidade/País] ✨</p>
