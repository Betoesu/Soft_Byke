/* =====================================================================
   SOFTBYKE - APLICAÇÃO JAVASCRIPT COMPLETA (PROJETO FINAL 2º BIMESTRE)
   Disciplina: Desenvolvimento Web - Turma A - CEUB
   Professor: Valdemir S Silva
   Integrantes (Grupo 4):
     1. Davi de Sousa Costa
     2. Igor Christofidis de Lima
     3. Pedro Carolino Barreto Sarmento
     4. Vinícius Bernardes Carneiro
   =====================================================================
   REQUISITOS DO PDF ATENDIDOS NESTE ARQUIVO (ITEM VI e ITEM 10):
     a. Estruturas condicionais (if/else, switch) e controle de fluxo (for, while)
     b. Eventos (onload, onsubmit, onclick, onchange, oninput, onkeyup, mouseover)
     c. Métodos (métodos de objetos próprios e métodos nativos de Array/String/Number)
     d. Objetos (catalogoSoftByke, cuponsDesconto, gerenciadorOrcamento)
     e. Tabelas (geração e manipulação dinâmica de tabelas HTML via DOM)
     f. Funções (funções modulares parametrizadas para cálculos e interface)
     g. Interações com o usuário (prompt, confirm, alert e feedback dinâmico no DOM)
     h. Automatizações (setInterval para ofertas automáticas + cálculo em tempo real)
   ===================================================================== */

/* ---------------------------------------------------------------------
   ITEM 10.d - OBJETOS: Catálogo Comercial e Cupons da SoftByke
   --------------------------------------------------------------------- */
var catalogoSoftByke = {
  Bicicletas: [
    { codigo: "BK-01", nome: "SoftByke Urban 100 (Aro 700)", preco: 1299.00 },
    { codigo: "BK-02", nome: "SoftByke Trail 200 (MTB Aro 29)", preco: 2199.00 },
    { codigo: "BK-03", nome: "SoftByke Speed 300 (Road 16v)", preco: 3499.00 },
    { codigo: "BK-04", nome: "Caloi Explorer Comp Aro 29", preco: 2890.00 },
    { codigo: "BK-05", nome: "Oggi Big Wheel 7.3 Deore 12v", preco: 5490.00 },
    { codigo: "BK-06", nome: "Caloi Elite Carbon Racing", preco: 8990.00 }
  ],
  "Peças": [
    { codigo: "PC-01", nome: "Corrente KMC Indexada 8v/9v", preco: 89.90 },
    { codigo: "PC-02", nome: "Pneu MTB Aro 29 Kevlar", preco: 149.90 },
    { codigo: "PC-03", nome: "Câmbio Traseiro Shimano Deore 12v", preco: 229.90 },
    { codigo: "PC-04", nome: "Pedivela Shimano Triplo Ty301", preco: 139.90 },
    { codigo: "PC-05", nome: "Cassete Shimano Deore 12v CS-M6100", preco: 459.90 }
  ],
  "Acessórios": [
    { codigo: "AC-01", nome: "Capacete Ciclismo Pro com LED", preco: 179.90 },
    { codigo: "AC-02", nome: "Luz Dianteira Recarregável USB 800lm", preco: 59.90 },
    { codigo: "AC-03", nome: "Luva Ciclismo Gel Meio Dedo", preco: 69.90 },
    { codigo: "AC-04", nome: "Bomba de Ar Portátil com Manômetro", preco: 94.90 }
  ],
  "Manutenção": [
    { codigo: "OF-01", nome: "Revisão Completa Premium (Oficina)", preco: 149.90 },
    { codigo: "OF-02", nome: "Regulagem de Câmbio e Freios", preco: 69.90 },
    { codigo: "OF-03", nome: "Sangria de Freio Hidráulico (Par)", preco: 110.00 },
    { codigo: "OF-04", nome: "Montagem e Alinhamento de Rodas", preco: 85.00 }
  ]
};

var cuponsDesconto = {
  SOFT10: { percentual: 10, descricao: "10% de desconto promocional SoftByke" },
  BIKE15: { percentual: 15, descricao: "15% de desconto Semana do Ciclista" },
  CEUB20: { percentual: 20, descricao: "20% de desconto exclusivo Comunidade CEUB" }
};

/* ---------------------------------------------------------------------
   ITEM 10.c e 10.d - OBJETO GERENCIADOR DE ORÇAMENTO COM MÉTODOS PRÓPRIOS
   --------------------------------------------------------------------- */
var gerenciadorOrcamento = {
  itens: [],

  // Método para adicionar ou incrementar um item na lista do orçamento
  adicionarItem: function (categoria, codigo, nome, precoUnitario, adicionalUnitario, quantidade) {
    var i = 0;
    var encontrado = false;

    // Estrutura de repetição WHILE (Item 10.a) para verificar se o item já existe
    while (i < this.itens.length) {
      if (this.itens[i].codigo === codigo && this.itens[i].adicionalUnitario === adicionalUnitario) {
        this.itens[i].quantidade += quantidade;
        this.itens[i].subtotal = (this.itens[i].precoUnitario + this.itens[i].adicionalUnitario) * this.itens[i].quantidade;
        encontrado = true;
        break;
      }
      i++;
    }

    if (!encontrado) {
      var novoItem = {
        categoria: categoria,
        codigo: codigo,
        nome: nome,
        precoUnitario: precoUnitario,
        adicionalUnitario: adicionalUnitario,
        quantidade: quantidade,
        subtotal: (precoUnitario + adicionalUnitario) * quantidade
      };
      this.itens.push(novoItem);
    }
  },

  // Método para remover um item pelo índice
  removerItem: function (indice) {
    if (indice >= 0 && indice < this.itens.length) {
      this.itens.splice(indice, 1);
    }
  },

  // Método para limpar todos os itens
  limparTodos: function () {
    this.itens = [];
  },

  // Método que calcula o subtotal bruto percorrendo os itens com FOR (Item 10.a)
  calcularSubtotalBruto: function () {
    var soma = 0;
    for (var i = 0; i < this.itens.length; i++) {
      soma += this.itens[i].subtotal;
    }
    return soma;
  },

  // Método que retorna a quantidade total de unidades no orçamento
  calcularQuantidadeTotal: function () {
    var totalUnidades = 0;
    for (var i = 0; i < this.itens.length; i++) {
      totalUnidades += this.itens[i].quantidade;
    }
    return totalUnidades;
  },

  // Método utilitário para formatar números em moeda brasileira (R$)
  formatarMoeda: function (valor) {
    var numero = Number(valor) || 0;
    return "R$ " + numero.toFixed(2).replace(".", ",").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }
};

/* ---------------------------------------------------------------------
   ITEM 10.g - INTERAÇÃO COM O USUÁRIO: Saudação na Página Inicial
   --------------------------------------------------------------------- */
function exibirSaudacao(forcarPrompt) {
  var caixaSaudacao = document.getElementById("saudacao");
  if (caixaSaudacao === null) {
    return;
  }

  var nomeSalvo = sessionStorage.getItem("softbyke_visitante");
  if (forcarPrompt === true || nomeSalvo === null) {
    var resposta = window.prompt("Bem-vindo(a) à SoftByke! Qual é o seu nome?");
    if (resposta !== null && resposta.trim() !== "") {
      nomeSalvo = resposta.trim();
      sessionStorage.setItem("softbyke_visitante", nomeSalvo);
    } else {
      nomeSalvo = "";
      sessionStorage.setItem("softbyke_visitante", "");
    }
  }

  if (nomeSalvo !== "") {
    caixaSaudacao.innerHTML =
      "Olá, <strong>" + nomeSalvo + "</strong>! Bem-vindo(a) à SoftByke — venda de bicicletas, peças e oficina especializada há 5 anos.";
  } else {
    caixaSaudacao.innerHTML =
      "Olá, ciclista! Venda de bicicletas, peças, acessórios e oficina completa de reparo e revisão, há 5 anos com você.";
  }
}

/* ---------------------------------------------------------------------
   ITEM 10.b e 10.f - VALIDAÇÃO DE FORMULÁRIO DE NEWSLETTER (Home)
   --------------------------------------------------------------------- */
function validarNewsletter() {
  var campoEmail = document.getElementById("emailNewsletter");
  if (!campoEmail) {
    return false;
  }

  var email = campoEmail.value.trim();

  if (email.indexOf("@") === -1 || email.indexOf(".") === -1) {
    window.alert("Por favor, digite um endereço de e-mail válido para receber as ofertas da SoftByke.");
    return false;
  }

  window.alert("Obrigado! O e-mail " + email + " foi cadastrado com sucesso na newsletter da SoftByke.");
  campoEmail.value = "";
  return false;
}

/* ---------------------------------------------------------------------
   ITEM 10.h - AUTOMATIZAÇÃO: Rotação Automática de Avisos / Ofertas
   --------------------------------------------------------------------- */
var mensagensAutomacao = [
  "Cupons ativos hoje: <b>SOFT10</b> (10% OFF), <b>BIKE15</b> (15% OFF) e <b>CEUB20</b> (20% OFF)!",
  "Frete Urbano DF <b>GRÁTIS</b> automaticamente para orçamentos líquidos acima de <b>R$ 500,00</b>!",
  "Pagamento via <b>PIX</b> garante mais <b>10% de desconto automático</b> sobre o valor dos itens!",
  "Revisão Completa Premium na Oficina SoftByke por apenas <b>R$ 149,90</b> nas unidades Asa Norte e Águas Claras!"
];
var indiceAutomacao = 0;

function iniciarAutomacaoPromocoes() {
  var elementoTexto = document.getElementById("textoAutomacao");
  if (!elementoTexto) {
    return;
  }

  window.setInterval(function () {
    indiceAutomacao = (indiceAutomacao + 1) % mensagensAutomacao.length;
    elementoTexto.innerHTML = mensagensAutomacao[indiceAutomacao];
  }, 4000);
}

/* ---------------------------------------------------------------------
   FUNÇÕES DO SIMULADOR DE ORÇAMENTO (orcamento.html)
   --------------------------------------------------------------------- */
function atualizarSelectProdutos() {
  var selCategoria = document.getElementById("selCategoria");
  var selProduto = document.getElementById("selProduto");
  if (!selCategoria || !selProduto) {
    return;
  }

  var categoriaEscolhida = selCategoria.value;
  var listaItens = catalogoSoftByke[categoriaEscolhida] || [];

  selProduto.innerHTML = "";

  // Laço FOR (Item 10.a) para popular dinamicamente as opções do select
  for (var i = 0; i < listaItens.length; i++) {
    var item = listaItens[i];
    var option = document.createElement("option");
    option.value = i;
    option.text = item.codigo + " - " + item.nome + " (" + gerenciadorOrcamento.formatarMoeda(item.preco) + ")";
    selProduto.appendChild(option);
  }

  atualizarPrecoUnitario();
}

function atualizarPrecoUnitario() {
  var selCategoria = document.getElementById("selCategoria");
  var selProduto = document.getElementById("selProduto");
  var numPrecoUnitario = document.getElementById("numPrecoUnitario");
  if (!selCategoria || !selProduto || !numPrecoUnitario) {
    return;
  }

  var listaItens = catalogoSoftByke[selCategoria.value] || [];
  var indice = parseInt(selProduto.value, 10) || 0;
  var produtoSelecionado = listaItens[indice];

  if (produtoSelecionado) {
    numPrecoUnitario.value = gerenciadorOrcamento.formatarMoeda(produtoSelecionado.preco);
  }
  atualizarPreviaItem();
}

function atualizarPreviaItem() {
  var selCategoria = document.getElementById("selCategoria");
  var selProduto = document.getElementById("selProduto");
  var numQuantidade = document.getElementById("numQuantidade");
  var selGarantia = document.getElementById("selGarantia");
  var txtPreviaSubtotal = document.getElementById("txtPreviaSubtotal");

  if (!selCategoria || !selProduto || !numQuantidade || !selGarantia || !txtPreviaSubtotal) {
    return;
  }

  var listaItens = catalogoSoftByke[selCategoria.value] || [];
  var produto = listaItens[parseInt(selProduto.value, 10) || 0];
  if (!produto) {
    return;
  }

  var qtd = parseInt(numQuantidade.value, 10);
  if (isNaN(qtd) || qtd < 1) {
    qtd = 1;
  }

  var adicional = parseFloat(selGarantia.value) || 0;
  var subtotalPrevisto = (produto.preco + adicional) * qtd;
  txtPreviaSubtotal.value = gerenciadorOrcamento.formatarMoeda(subtotalPrevisto);
}

function adicionarItemAoOrcamento(event) {
  if (event) {
    event.preventDefault();
  }

  var selCategoria = document.getElementById("selCategoria");
  var selProduto = document.getElementById("selProduto");
  var numQuantidade = document.getElementById("numQuantidade");
  var selGarantia = document.getElementById("selGarantia");

  var categoria = selCategoria.value;
  var listaItens = catalogoSoftByke[categoria] || [];
  var produto = listaItens[parseInt(selProduto.value, 10) || 0];
  var quantidade = parseInt(numQuantidade.value, 10);
  var adicional = parseFloat(selGarantia.value) || 0;

  // Condicional de validação (Item 10.a)
  if (!produto || isNaN(quantidade) || quantidade <= 0) {
    window.alert("Informe uma quantidade válida (mínimo 1 unidade).");
    return false;
  }

  gerenciadorOrcamento.adicionarItem(
    categoria,
    produto.codigo,
    produto.nome,
    produto.preco,
    adicional,
    quantidade
  );

  renderizarTabelaOrcamento();
  recalcularOrcamentoGeral();
  return false;
}

function removerItemDaTabela(indice) {
  gerenciadorOrcamento.removerItem(indice);
  renderizarTabelaOrcamento();
  recalcularOrcamentoGeral();
}

function alterarQuantidadeNaTabela(indice, novaQtd) {
  var qtd = parseInt(novaQtd, 10);
  if (isNaN(qtd) || qtd <= 0) {
    removerItemDaTabela(indice);
    return;
  }
  var item = gerenciadorOrcamento.itens[indice];
  if (item) {
    item.quantidade = qtd;
    item.subtotal = (item.precoUnitario + item.adicionalUnitario) * qtd;
    renderizarTabelaOrcamento();
    recalcularOrcamentoGeral();
  }
}

function limparOrcamentoCompleto() {
  if (gerenciadorOrcamento.itens.length === 0) {
    window.alert("O orçamento já está vazio.");
    return;
  }
  if (window.confirm("Deseja remover todos os itens da tabela de orçamento?")) {
    gerenciadorOrcamento.limparTodos();
    renderizarTabelaOrcamento();
    recalcularOrcamentoGeral();
  }
}

function carregarKitExemplo() {
  gerenciadorOrcamento.limparTodos();
  gerenciadorOrcamento.adicionarItem("Bicicletas", "BK-02", "SoftByke Trail 200 (MTB Aro 29)", 2199.00, 89.90, 1);
  gerenciadorOrcamento.adicionarItem("Acessórios", "AC-01", "Capacete Ciclismo Pro com LED", 179.90, 0, 1);
  gerenciadorOrcamento.adicionarItem("Manutenção", "OF-01", "Revisão Completa Premium (Oficina)", 149.90, 0, 1);
  renderizarTabelaOrcamento();
  recalcularOrcamentoGeral();
}

/* ---------------------------------------------------------------------
   ITEM 10.e - TABELAS: Renderização Dinâmica da Tabela de Orçamento
   --------------------------------------------------------------------- */
function renderizarTabelaOrcamento() {
  var corpo = document.getElementById("corpoTabelaOrcamento");
  if (!corpo) {
    return;
  }

  corpo.innerHTML = "";

  if (gerenciadorOrcamento.itens.length === 0) {
    corpo.innerHTML =
      '<tr><td colspan="8" class="cor5">Nenhum item adicionado. Utilize o formulário acima ou clique em "Carregar Kit Exemplo".</td></tr>';
    return;
  }

  for (var i = 0; i < gerenciadorOrcamento.itens.length; i++) {
    var item = gerenciadorOrcamento.itens[i];
    var tr = document.createElement("tr");
    var classeCor = "cor" + ((i % 5) + 1);

    tr.innerHTML =
      '<td class="' + classeCor + '"><strong>' + item.codigo + "</strong></td>" +
      '<td class="' + classeCor + '">' + item.categoria + "</td>" +
      '<td class="' + classeCor + '">' + item.nome + "</td>" +
      '<td class="' + classeCor + '">' + gerenciadorOrcamento.formatarMoeda(item.precoUnitario) + "</td>" +
      '<td class="' + classeCor + '">' + gerenciadorOrcamento.formatarMoeda(item.adicionalUnitario) + "</td>" +
      '<td class="' + classeCor + '">' +
      '<input type="number" min="1" max="50" value="' + item.quantidade + '" class="input-qtd-tabela" ' +
      'onchange="alterarQuantidadeNaTabela(' + i + ', this.value)">' +
      "</td>" +
      '<td class="' + classeCor + '"><strong>' + gerenciadorOrcamento.formatarMoeda(item.subtotal) + "</strong></td>" +
      '<td class="' + classeCor + '">' +
      '<button type="button" class="btn-acao btn-excluir" onclick="removerItemDaTabela(' + i + ')">Remover</button>' +
      "</td>";

    corpo.appendChild(tr);
  }
}

function validarCupomManual() {
  recalcularOrcamentoGeral();
  var campoCupom = document.getElementById("txtCupom");
  if (!campoCupom) {
    return;
  }
  var codigo = campoCupom.value.trim().toUpperCase();
  if (codigo === "") {
    window.alert("Digite um código de cupom (SOFT10, BIKE15 ou CEUB20).");
  } else if (cuponsDesconto[codigo]) {
    window.alert("Cupom " + codigo + " aplicado: " + cuponsDesconto[codigo].descricao + "!");
  } else {
    window.alert("O cupom '" + codigo + "' não é válido. Use SOFT10, BIKE15 ou CEUB20.");
  }
}

/* ---------------------------------------------------------------------
   ITEM VI e ITEM 10 (a, c, e, f, h) - CÁLCULO FINANCEIRO COMPLETO EM JS
   --------------------------------------------------------------------- */
function recalcularOrcamentoGeral() {
  var elQtdTotal = document.getElementById("resQtdTotal");
  if (!elQtdTotal) {
    return;
  }

  var subtotalBruto = gerenciadorOrcamento.calcularSubtotalBruto();
  var qtdTotal = gerenciadorOrcamento.calcularQuantidadeTotal();

  // 1. Desconto de Fidelidade + Cupom Promocional
  var percFidelidade = parseFloat(document.getElementById("selPerfilCliente").value) || 0;
  var codigoCupom = document.getElementById("txtCupom").value.trim().toUpperCase();
  var msgCupom = document.getElementById("msgCupom");
  var percCupom = 0;

  if (codigoCupom !== "") {
    if (cuponsDesconto[codigoCupom]) {
      percCupom = cuponsDesconto[codigoCupom].percentual;
      msgCupom.innerHTML = "&#10004; " + cuponsDesconto[codigoCupom].descricao;
      msgCupom.className = "msg-apoio texto-sucesso";
    } else {
      msgCupom.innerHTML = "&#10008; Cupom inválido (use SOFT10, BIKE15 ou CEUB20).";
      msgCupom.className = "msg-apoio texto-erro";
    }
  } else {
    msgCupom.innerHTML = "";
  }

  var valorDescontoCupom = subtotalBruto * ((percFidelidade + percCupom) / 100);
  var valorLiquidoParcial = subtotalBruto - valorDescontoCupom;

  // 2. Estrutura condicional SWITCH (Item 10.a) para cálculo do Frete
  var tipoFrete = document.getElementById("selFrete").value;
  var valorFrete = 0;

  if (subtotalBruto > 0) {
    switch (tipoFrete) {
      case "retirada":
        valorFrete = 0;
        break;
      case "urbano":
        // Regra de negócio: Frete grátis para compras líquidas acima de R$ 500,00
        valorFrete = valorLiquidoParcial >= 500 ? 0 : 35.00;
        break;
      case "expresso":
        valorFrete = 79.90;
        break;
      default:
        valorFrete = 0;
    }
  }

  // 3. Forma de Pagamento e Juros Compostos
  var formaPagamento = document.getElementById("selFormaPagamento").value;
  var selParcelas = document.getElementById("selParcelas");
  var numParcelas = parseInt(selParcelas.value, 10) || 1;

  var valorDescontoPagamento = 0;
  var valorJuros = 0;

  if (formaPagamento === "pix") {
    valorDescontoPagamento = valorLiquidoParcial * 0.10;
    numParcelas = 1;
    selParcelas.value = "1";
  } else if (formaPagamento === "boleto") {
    valorDescontoPagamento = valorLiquidoParcial * 0.05;
    numParcelas = 1;
    selParcelas.value = "1";
  } else if (formaPagamento === "cartao_sem_juros") {
    if (numParcelas > 6) {
      numParcelas = 6;
      selParcelas.value = "6";
    }
  } else if (formaPagamento === "cartao_com_juros") {
    if (numParcelas < 7) {
      numParcelas = 7;
      selParcelas.value = "7";
    }
    // Juros compostos de 1,99% a.m. sobre o valor financiado
    var baseFinanciada = valorLiquidoParcial + valorFrete;
    var montanteComJuros = baseFinanciada * Math.pow(1 + 0.0199, numParcelas);
    valorJuros = montanteComJuros - baseFinanciada;
  }

  var totalFinal = valorLiquidoParcial - valorDescontoPagamento + valorFrete + valorJuros;
  if (totalFinal < 0) {
    totalFinal = 0;
  }

  var valorParcelaSelecionada = numParcelas > 0 ? totalFinal / numParcelas : totalFinal;

  // 4. Atualiza o DOM com os resultados calculados
  elQtdTotal.innerHTML = qtdTotal + (qtdTotal === 1 ? " unidade" : " unidades");
  document.getElementById("resSubtotalBruto").innerHTML = gerenciadorOrcamento.formatarMoeda(subtotalBruto);
  document.getElementById("resDescontoCupom").innerHTML = "- " + gerenciadorOrcamento.formatarMoeda(valorDescontoCupom);
  document.getElementById("resDescontoPagamento").innerHTML = "- " + gerenciadorOrcamento.formatarMoeda(valorDescontoPagamento);
  document.getElementById("resValorFrete").innerHTML =
    valorFrete === 0 && subtotalBruto > 0 ? "GRÁTIS (R$ 0,00)" : gerenciadorOrcamento.formatarMoeda(valorFrete);
  document.getElementById("resValorJuros").innerHTML = gerenciadorOrcamento.formatarMoeda(valorJuros);
  document.getElementById("resTotalFinal").innerHTML = gerenciadorOrcamento.formatarMoeda(totalFinal);
  document.getElementById("resDetalheParcela").innerHTML =
    numParcelas + "x de " + gerenciadorOrcamento.formatarMoeda(valorParcelaSelecionada);

  // 5. Renderiza a Tabela de Simulação de Parcelamento (1x a 12x) usando laço FOR
  renderizarTabelaParcelamento(valorLiquidoParcial + valorFrete);
}

function renderizarTabelaParcelamento(valorBaseCartao) {
  var corpoParcelas = document.getElementById("corpoTabelaParcelamento");
  if (!corpoParcelas) {
    return;
  }

  corpoParcelas.innerHTML = "";

  for (var p = 1; p <= 12; p++) {
    var totalParcelado = valorBaseCartao;
    var condicao = "Sem juros";

    if (p >= 7 && valorBaseCartao > 0) {
      totalParcelado = valorBaseCartao * Math.pow(1 + 0.0199, p);
      condicao = "Com juros (1,99% a.m.)";
    }

    var valorCadaParcela = p > 0 ? totalParcelado / p : 0;
    var tr = document.createElement("tr");
    tr.innerHTML =
      "<td><strong>" + p + "x</strong></td>" +
      "<td>" + gerenciadorOrcamento.formatarMoeda(valorCadaParcela) + "</td>" +
      "<td>" + gerenciadorOrcamento.formatarMoeda(totalParcelado) + "</td>" +
      "<td>" + condicao + "</td>";
    corpoParcelas.appendChild(tr);
  }
}

function enviarOrcamentoParaPHP() {
  if (gerenciadorOrcamento.itens.length === 0) {
    window.alert("Adicione pelo menos um item ao orçamento antes de enviar para o Sistema PHP.");
    return;
  }

  var nomesItens = [];
  for (var i = 0; i < gerenciadorOrcamento.itens.length; i++) {
    nomesItens.push(gerenciadorOrcamento.itens[i].quantidade + "x " + gerenciadorOrcamento.itens[i].nome);
  }

  var textoTotal = document.getElementById("resTotalFinal").innerText.replace("R$", "").trim().replace(/\./g, "").replace(",", ".");
  var clienteNome = document.getElementById("nomeClienteOrcamento").value.trim();
  var resumoServico = nomesItens.join(" + ").substring(0, 115);
  var observacao = "Orçamento calculado em JS" + (clienteNome ? " para " + clienteNome : "") + ".";

  var url =
    "sistema.php?aba=agendamentos" +
    "&servico_orcamento=" + encodeURIComponent(resumoServico) +
    "&valor_orcamento=" + encodeURIComponent(textoTotal) +
    "&obs_orcamento=" + encodeURIComponent(observacao);

  window.location.href = url;
}

/* ---------------------------------------------------------------------
   CALCULADORA DE BIKE FIT & PRESSÃO DE PNEUS (orcamento.html)
   --------------------------------------------------------------------- */
function calcularBikeFit(event) {
  if (event && event.type === "submit") {
    event.preventDefault();
  }

  var elAltura = document.getElementById("fitAltura");
  var elCavalo = document.getElementById("fitCavalo");
  var elPeso = document.getElementById("fitPeso");
  var elModalidade = document.getElementById("fitModalidade");
  var painelResultado = document.getElementById("resultadoBikeFit");

  if (!elAltura || !elCavalo || !elPeso || !elModalidade || !painelResultado) {
    return false;
  }

  var altura = parseFloat(elAltura.value) || 175;
  var cavalo = parseFloat(elCavalo.value) || 80;
  var peso = parseFloat(elPeso.value) || 75;
  var modalidade = elModalidade.value;

  // Fórmulas clássicas de Bike Fit (LeMond: altura do selim = cavalo * 0.883)
  var alturaSelimCm = (cavalo * 0.883).toFixed(1);
  var tamanhoQuadro = "";
  var pressaoPneusPsi = "";
  var modeloSugerido = "";

  if (modalidade === "mtb") {
    var polMtb = (cavalo * 0.226 - 1.5).toFixed(1);
    var letraMtb = polMtb < 16 ? "P (Small)" : polMtb < 18 ? "M (Medium)" : polMtb < 20 ? "G (Large)" : "GG (XL)";
    tamanhoQuadro = polMtb + '" polegadas — Quadro ' + letraMtb;
    pressaoPneusPsi = Math.round(peso * 0.36) + " PSI (Dianteiro) / " + Math.round(peso * 0.39) + " PSI (Traseiro)";
    modeloSugerido = "SoftByke Trail 200 ou Oggi Big Wheel 7.3";
  } else if (modalidade === "speed") {
    var cmSpeed = (cavalo * 0.665).toFixed(1);
    tamanhoQuadro = cmSpeed + " cm (Padrão Road/Speed Aro 700)";
    pressaoPneusPsi = Math.round(peso * 1.15) + " PSI (Dianteiro) / " + Math.round(peso * 1.22) + " PSI (Traseiro)";
    modeloSugerido = "SoftByke Speed 300";
  } else {
    var letraUrb = altura < 165 ? "P (15\")" : altura < 178 ? "M (17\")" : altura < 188 ? "G (19\")" : "GG (21\")";
    tamanhoQuadro = "Quadro Urbano " + letraUrb;
    pressaoPneusPsi = Math.round(peso * 0.55) + " PSI (Dianteiro) / " + Math.round(peso * 0.60) + " PSI (Traseiro)";
    modeloSugerido = "SoftByke Urban 100 ou Caloi 100 Comfort";
  }

  painelResultado.innerHTML =
    '<div class="cards-bikefit">' +
    '<div class="card-fit"><span>Quadro Ideal Calculado</span><strong>' + tamanhoQuadro + "</strong></div>" +
    '<div class="card-fit"><span>Altura do Selim (Centro-Topo)</span><strong>' + alturaSelimCm + " cm</strong></div>" +
    '<div class="card-fit"><span>Calibragem Recomendada</span><strong>' + pressaoPneusPsi + "</strong></div>" +
    '<div class="card-fit"><span>Modelo Indicado na Loja</span><strong>' + modeloSugerido + "</strong></div>" +
    "</div>";

  return false;
}

/* ---------------------------------------------------------------------
   FILTRO INTERATIVO DA GALERIA (galeria.html) E DA TABELA (tabela.html)
   --------------------------------------------------------------------- */
function filtrarGaleria(marcaOuCategoria, botaoClicado) {
  var figuras = document.querySelectorAll(".grade-galeria figure");
  var botoes = document.querySelectorAll(".filtro-galeria-btn");

  for (var b = 0; b < botoes.length; b++) {
    botoes[b].classList.remove("ativo");
  }
  if (botaoClicado) {
    botaoClicado.classList.add("ativo");
  }

  for (var i = 0; i < figuras.length; i++) {
    var texto = figuras[i].innerText.toLowerCase();
    if (marcaOuCategoria === "todos" || texto.indexOf(marcaOuCategoria.toLowerCase()) !== -1) {
      figuras[i].style.display = "block";
    } else {
      figuras[i].style.display = "none";
    }
  }
}

function filtrarTabelaCatalogo() {
  var input = document.getElementById("inputBuscaTabela");
  var tabela = document.getElementById("tabelaCatalogoPrincipal");
  if (!input || !tabela) {
    return;
  }

  var termo = input.value.trim().toLowerCase();
  var linhas = tabela.getElementsByTagName("tr");

  // Percorre as linhas de dados da tabela
  for (var i = 2; i < linhas.length; i++) {
    var textoLinha = linhas[i].innerText.toLowerCase();
    if (termo === "" || textoLinha.indexOf(termo) !== -1) {
      linhas[i].style.display = "";
    } else {
      linhas[i].style.display = "none";
    }
  }
}

/* ---------------------------------------------------------------------
   INICIALIZAÇÃO AUTOMÁTICA NO CARREGAMENTO DE QUALQUER PÁGINA (onload)
   --------------------------------------------------------------------- */
window.onload = function () {
  // 1. Saudação na Home
  if (document.getElementById("saudacao") !== null) {
    exibirSaudacao(false);
  }

  // 2. Automação de promoções (setInterval)
  iniciarAutomacaoPromocoes();

  // 3. Se estiver na página de Orçamento, inicializa selects, kit padrão e Bike Fit
  if (document.getElementById("selCategoria") !== null) {
    atualizarSelectProdutos();
    carregarKitExemplo();
    calcularBikeFit(null);
  }
};
