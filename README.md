
# Donaco - Transforme Gratidão em Impacto Real

## Sobre o Projeto

Donaco é uma plataforma self-hosted projetada para facilitar a arrecadação de doações para projetos open-source. Inspirada em serviços como Catarse e Vakinha, Donaco foi criada para ajudar desenvolvedores, designers e colaboradores apaixonados a manterem seus projetos vivos e em constante evolução, tudo isso sem depender de plataformas externas.

Com Donaco, sua comunidade pode apoiar diretamente os projetos que fizeram diferença em suas vidas, transformando gratidão em impacto real.

Sistema de doações foi feito com base inicial no QRCode-PIX-MercadoPago-php


---

## Funcionalidades

### 🎯 **Para Apoiadores**
- **Interface intuitiva e inspiradora**: Permita que os doadores escolham facilmente um projeto e façam uma contribuição personalizada.
- **Pagamento via Pix com Mercado Pago**: Seguro, rápido e acessível.
- **Ranking de Maiores Doadores**: Destaque os nomes daqueles que estão impulsionando o progresso.

### 📊 **Para Administradores**
- **Dashboard Poderoso**: Acompanhe doações aprovadas, valores arrecadados, metas alcançadas e mais.
- **Gestão de Projetos**: Controle quais projetos estão ativos e recebendo contribuições.
- **Dados em Tempo Real**: Informações atualizadas para tomar decisões estratégicas.

---

## Como Funciona?

1. **Self-hosted**: Hospede Donaco em seu próprio servidor, garantindo controle total sobre os dados e custos reduzidos.
2. **Configuração Flexível**: Ideal para pequenos e grandes projetos open-source.
3. **Comunidade em Primeiro Lugar**: Cada doação fortalece a comunidade, paga servidores, compra café para os desenvolvedores (essencial!) e mantém a inovação ativa.

---

## Screenshots

### Página Principal
Uma interface acolhedora e otimizada para incentivar doações.

![Página Principal](https://github.com/user-attachments/assets/e0f6e342-24a3-47e8-acf6-fefb5046bf20)

### TELA DE DOAÇÃO VIA MERCADO PAGO
![screeshot-qrcode-donaco](https://github.com/user-attachments/assets/790c8aa1-752f-4695-877b-b55b59204dae)

### TELA DE DOAÇÃO MANUAL
![screeshot-manual-donaco](https://github.com/user-attachments/assets/80be6dde-9f5a-4487-8040-56da2b0c9fee)




### Dashboard Administrativo
Controle total dos seus projetos e doações.

![Dashboard](https://github.com/user-attachments/assets/cf3b7657-29e1-4ffb-971a-dcae324976e7)

### TELA DE DOAÇÃO FEITA VIA MERCADO PAGO
![image](https://github.com/user-attachments/assets/ec286701-7c0a-48a4-8034-e9ae653e815d)

### TELA DE APROVAÇÃO DE DOAÇÃO MANUAL
![screeshot-aprovacao-manual-donaco](https://github.com/user-attachments/assets/74a13fda-e6ae-432c-b37e-e5b73f7ff066)



---

## Por Que Contribuir?

Donaco está em constante evolução. Buscamos sempre adicionar novas funcionalidades e melhorar a experiência para apoiadores e administradores. Sua contribuição, seja por meio de código ou apoio financeiro, ajuda a tornar essa plataforma ainda mais robusta e acessível.

### 🛠 Como Contribuir com o Código
1. Faça um fork deste repositório.
2. Crie uma branch para sua funcionalidade:
   ```bash
   git checkout -b minha-nova-funcionalidade
   ```
3. Envie seu código para revisão com um Pull Request.

### 💖 Como Apoiar Financeiramente
Toda doação é um agradecimento que fortalece nossa comunidade. Contribua com o que puder — cada centavo faz a diferença!

---

## Requisitos do Sistema

- **Backend**: Laravel 11
- **Frontend**: ViteJS + TailwindCSS
- **Banco de Dados**: MySQL ou MariaDB ou SQLite
- **Outros**: Redis (opcional, para cache e notificações)

---

## Como Começar

1. Clone este repositório:
   ```bash
   git clone https://github.com/seu-usuario/donaco.git
   ```

2. Configure o ambiente:
   ```bash
   cp .env.example .env
   ```
   Atualize as variáveis conforme suas credenciais.

3. Instale as dependências do backend e frontend:
   ```bash
   composer install
   npm install
   ```

4. Compile os assets:
   ```bash
   npm run build
   ```

5. Rode as migrações e seeders:
   ```bash
   php artisan migrate --seed
   ```

6. Inicie o servidor:
   ```bash
   php artisan serve
   ```

---

## Licença

Donaco é um projeto open-source licenciado sob a [Apache License 2.0](./LICENSE). É permitido usar, modificar e distribuir o projeto, desde que os créditos do rodapé e a licença que acompanham o projeto sejam preservados.

---

Vamos construir juntos uma comunidade ainda mais forte! 🚀
