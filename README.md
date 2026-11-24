# SoftByke — Sistema Web Comercial (Projeto Final 2º Bimestre)

**Centro Universitário de Brasília (CEUB)**  
**Curso:** Ciência da Computação — **Disciplina:** Desenvolvimento Web (Turma A - Terça-feira)  
**Professor:** Valdemir S Silva  
**Grupo 4 (Apresentação: 08/12/2026 às 09:20):**
1. **Davi de Sousa Costa** (`davi.scosta@sempreceub.com`)
2. **Igor Christofidis de Lima** (`igor.christofidis@sempreceub.com`)
3. **Pedro Carolino Barreto Sarmento** (`pedrosarmento1412@sempreceub.com`)
4. **Vinícius Bernardes Carneiro** (`vinicius.bernardes@sempreceub.com`)

---

## 1. Estrutura de Arquivos do Projeto

```text
SoftByke-Desenvolvimento-Web/
├── index.html              # Página Inicial Comercial (Saudação JS + Ofertas Automáticas + Newsletter)
├── empresa.html            # A Empresa (Briefing Completo dos 18 itens)
├── galeria.html            # Galeria com 25 Produtos e Filtro Dinâmico em JavaScript
├── tabela.html             # Catálogo de Preços (colspan/rowspan) + Pesquisa Dinâmica em JS
├── orcamento.html          # Simulador de Orçamento + Calculadora Bike Fit (100% JavaScript)
├── sistema.php             # Sistema Gerencial CRUD em PHP + MySQL (Oficina, Produtos e Clientes)
├── documentos.html         # Documentação Visual (Caso de Uso, Fluxo, Wireframes e Protótipo)
├── css/
│   └── estilo.css          # Folha de estilo externa padronizada (#1c1f26, #2e4057, #f26419)
├── js/
│   └── script.js           # Aplicação JavaScript completa (Itens VI e 10.a até 10.h do PDF)
├── php/
│   └── conexao.php         # Conexão PDO ao MySQL com auto-criação e seed do banco softbyke_db
├── sql/
│   └── softbyke_db.sql     # Script SQL completo (CREATE DATABASE, 3 tabelas e INSERTs iniciais)
└── images/
    ├── documentos/         # Wireframes, Diagrama de Caso de Uso, Diagrama de Fluxo e Protótipo
    └── patrocinadores/     # Logotipos dos patrocinadores
```

---

## 2. Como Executar no XAMPP (Dia da Apresentação)

1. Copie a pasta `SoftByke-Desenvolvimento-Web` para dentro de `C:\xampp\htdocs\`.
2. Abra o **XAMPP Control Panel** e inicie os serviços **Apache** e **MySQL**.
3. Acesse no navegador:
   - `http://localhost/SoftByke-Desenvolvimento-Web/index.html` (Portal Principal)
   - `http://localhost/SoftByke-Desenvolvimento-Web/orcamento.html` (Simulador de Orçamento e Cálculos JS)
   - `http://localhost/SoftByke-Desenvolvimento-Web/sistema.php` (Sistema CRUD em PHP e MySQL)
4. **Nota sobre o Banco de Dados (`softbyke_db`):**
   - Ao abrir `sistema.php` pela primeira vez com o MySQL do XAMPP ativo, o arquivo `php/conexao.php` **cria automaticamente** o banco `softbyke_db`, as tabelas `clientes`, `produtos` e `agendamentos`, e insere os registros iniciais de demonstração.
   - Caso prefira importar manualmente no **phpMyAdmin** (`http://localhost/phpmyadmin`), basta importar o arquivo `sql/softbyke_db.sql`.

---

## 3. Mapa de Requisitos do PDF para Explicar na Apresentação

| Requisito do PDF | Onde encontrar no Código |
| :--- | :--- |
| **HTML5 e CSS3 Comercial** | `index.html`, `empresa.html`, `galeria.html`, `tabela.html`, `orcamento.html`, `documentos.html`, `css/estilo.css` |
| **Formulário com Cálculos em JS (Item VI)** | `orcamento.html` e funções `recalcularOrcamentoGeral()` e `calcularBikeFit()` em `js/script.js` |
| **10.a Condicionais e Controle de Fluxo** | `if / else if / else`, `switch (tipoFrete)`, laços `for` e `while` em `js/script.js` |
| **10.b Eventos** | `window.onload`, `onsubmit`, `onchange`, `oninput`, `onkeyup`, `onclick` em `orcamento.html` e `js/script.js` |
| **10.c Métodos** | Métodos de `gerenciadorOrcamento` (`adicionarItem`, `removerItem`, `calcularSubtotalBruto`, `formatarMoeda`) e nativos (`push`, `splice`, `toFixed`, `Math.pow`) |
| **10.d Objetos** | `catalogoSoftByke`, `cuponsDesconto` e `gerenciadorOrcamento` em `js/script.js` |
| **10.e Tabelas (JS)** | `renderizarTabelaOrcamento()`, `renderizarTabelaParcelamento()` e `filtrarTabelaCatalogo()` em `js/script.js` |
| **10.f Funções** | Todas as funções modulares documentadas em `js/script.js` |
| **10.g Interações com o Usuário** | `window.prompt()` em `exibirSaudacao()`, `window.confirm()`, `window.alert()` e feedbacks no DOM |
| **10.h Automatizações** | `iniciarAutomacaoPromocoes()` (`setInterval`), recálculo automático em tempo real e exportação JS &rarr; PHP (`enviarOrcamentoParaPHP()`) |
| **11. Sistema PHP (CRUD) e Banco de Dados** | `php/conexao.php`, `sistema.php` (Create, Read, Update e Delete para Oficina, Produtos e Clientes) e `sql/softbyke_db.sql` |
