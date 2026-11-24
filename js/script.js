/* =====================================================================
   SOFTBYKE - SCRIPT PRINCIPAL DA APLICAÇÃO COMERCIAL
   ===================================================================== */

var catalogoSoftByke = {
  Bicicletas: [
    { codigo: "BK-01", nome: "Caloi Velox", preco: 1199.00 },
    { codigo: "BK-02", nome: "Caloi Elite Carbon", preco: 12990.00 },
    { codigo: "BK-03", nome: "Caloi Elite Deore", preco: 6490.00 },
    { codigo: "BK-04", nome: "Caloi Explorer", preco: 2890.00 },
    { codigo: "BK-05", nome: "Caloi Aluminum Pro", preco: 1890.00 },
    { codigo: "BK-06", nome: "Caloi Commander", preco: 2490.00 },
    { codigo: "BK-07", nome: "Caloi Montana", preco: 1390.00 },
    { codigo: "BK-08", nome: "Caloi E-Vibe", preco: 7990.00 },
    { codigo: "BK-09", nome: "Caloi 100 Comfort", preco: 1590.00 },
    { codigo: "BK-10", nome: "Caloi Supra", preco: 2190.00 },
    { codigo: "BK-11", nome: "Oggi Float 5.0", preco: 2690.00 },
    { codigo: "BK-12", nome: "Oggi Razzo T-130", preco: 9490.00 },
    { codigo: "BK-13", nome: "Oggi Potenza Sport", preco: 14990.00 },
    { codigo: "BK-14", nome: "Oggi Big WHEEL 7.3", preco: 5490.00 },
    { codigo: "BK-15", nome: "Oggi Float Sport", preco: 2290.00 },
    { codigo: "BK-16", nome: "Oggi Street Go", preco: 1990.00 },
    { codigo: "BK-17", nome: "Oggi Agile Pro XT", preco: 13590.00 },
    { codigo: "BK-18", nome: "Oggi Agile Sport", preco: 8990.00 },
    { codigo: "BK-19", nome: "Oggi Agile Pro Sram S1000", preco: 15490.00 },
    { codigo: "BK-20", nome: "Oggi Hacker Sport", preco: 2090.00 }
  ],
  "Peças": [
    { codigo: "PC-01", nome: "Pedivela Shimano Ty301", preco: 139.90 },
    { codigo: "PC-02", nome: "Cassete Shimano Deore 12v CS-M6100", preco: 459.90 },
    { codigo: "PC-03", nome: "Câmbio Traseiro High One 10v 11/42d Shimano Sram", preco: 189.90 },
    { codigo: "PC-04", nome: "Câmbio Traseiro Caloi Para Bicicleta 21/24 Marchas", preco: 79.90 },
    { codigo: "PC-05", nome: "Câmbio Dianteiro Yamada Q25", preco: 49.90 }
  ],
  "Manutenção": [
    { codigo: "OF-01", nome: "Revisão Completa Premium", preco: 149.90 },
    { codigo: "OF-02", nome: "Regulagem de Câmbio e Freios", preco: 69.90 },
    { codigo: "OF-03", nome: "Sangria de Freio Hidráulico", preco: 110.00 },
    { codigo: "OF-04", nome: "Montagem e Alinhamento de Rodas", preco: 85.00 }
  ]
};

var cuponsDesconto = {
  SOFT10: { percentual: 10, descricao: "10% de desconto promocional SoftByke" },
  BIKE15: { percentual: 15, descricao: "15% de desconto Semana do Ciclista" },
  CEUB20: { percentual: 20, descricao: "20% de desconto especial" }
};

var gerenciadorOrcamento = {
  itens: [],

  adicionarItem: function (categoria, codigo, nome, precoUnitario, adicionalUnitario, quantidade) {
    var i = 0;
    var encontrado = false;

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
      this.itens.push({
        categoria: categoria,
        codigo: codigo,
        nome: nome,
        precoUnitario: precoUnitario,
        adicionalUnitario: adicionalUnitario,
        quantidade: quantidade,
        subtotal: (precoUnitario + adicionalUnitario) * quantidade
      });
    }
  },

  removerItem: function (indice) {
    if (indice >= 0 && indice < this.itens.length) {
      this.itens.splice(indice, 1);
    }
  },

  limparTodos: function () {
    this.itens = [];
  },

  calcularSubtotalBruto: function () {
    var soma = 0;
    for (var i = 0; i < this.itens.length; i++) {
      soma += this.itens[i].subtotal;
    }
    return soma;
  },

  calcularQuantidadeTotal: function () {
    var totalUnidades = 0;
    for (var i = 0; i < this.itens.length; i++) {
      totalUnidades += this.itens[i].quantidade;
    }
    return totalUnidades;
  },

  formatarMoeda: function (valor) {
    var numero = Number(valor) || 0;
    return "R$ " + numero.toFixed(2).replace(".", ",").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }
};

/* ---------------------------------------------------------------------
   SISTEMA DE MODAIS PRÓPRIOS DO SITE (SUBSTITUI ALERT, CONFIRM E PROMPT)
   --------------------------------------------------------------------- */
var callbackConfirmacaoModal = null;

function criarEstruturaModais() {
  if (document.getElementById("modalNotificacaoOverlay")) {
    return;
  }

  var container = document.createElement("div");
  container.innerHTML =
    /* 1. Modal de Aviso e Confirmação */
    '<div class="modal-overlay" id="modalNotificacaoOverlay">' +
      '<div class="modal-caixa modal-pequeno">' +
        '<div class="modal-cabecalho">' +
          '<h3 id="modalNotificacaoTitulo">Aviso SoftByke</h3>' +
          '<button type="button" class="modal-fechar" onclick="fecharModalNotificacao()">&times;</button>' +
        '</div>' +
        '<div class="modal-corpo">' +
          '<p id="modalNotificacaoMensagem"></p>' +
        '</div>' +
        '<div class="modal-rodape" id="modalNotificacaoRodape">' +
          '<button type="button" class="botao" onclick="fecharModalNotificacao()">Entendi</button>' +
        '</div>' +
      '</div>' +
    '</div>' +

    /* 2. Modal de Boas-Vindas do Site */
    '<div class="modal-overlay" id="modalBoasVindasOverlay">' +
      '<div class="modal-caixa modal-pequeno">' +
        '<div class="modal-cabecalho">' +
          '<h3>Bem-vindo à SoftByke</h3>' +
          '<button type="button" class="modal-fechar" onclick="fecharModalBoasVindas()">&times;</button>' +
        '</div>' +
        '<form onsubmit="return salvarNomeBoasVindas(event)">' +
          '<div class="modal-corpo">' +
            '<p>Para oferecermos um atendimento personalizado durante sua visita, como podemos chamar você?</p>' +
            '<div class="campo-form">' +
              '<label for="inputNomeVisitanteModal">Seu nome</label>' +
              '<input type="text" id="inputNomeVisitanteModal" placeholder="Digite seu nome">' +
            '</div>' +
          '</div>' +
          '<div class="modal-rodape">' +
            '<button type="button" class="botao botao-secundario" onclick="fecharModalBoasVindas()">Continuar sem informar</button>' +
            '<button type="submit" class="botao">Confirmar</button>' +
          '</div>' +
        '</form>' +
      '</div>' +
    '</div>' +

    /* 3. Modal Centralizado de Cadastro do Usuário */
    '<div class="modal-overlay" id="modalCadastroOverlay">' +
      '<div class="modal-caixa modal-medio">' +
        '<div class="modal-cabecalho">' +
          '<h3>Crie seu Cadastro na SoftByke</h3>' +
          '<button type="button" class="modal-fechar" onclick="fecharModalCadastro()">&times;</button>' +
        '</div>' +
        '<form id="formModalCadastro" onsubmit="return submeterCadastroUsuario(event)">' +
          '<div class="modal-corpo">' +
            '<p class="modal-subtitulo">Preencha seus dados abaixo para se cadastrar em nossa loja, garantir benefícios exclusivos e finalizar seus pedidos e agendamentos.</p>' +
            '<div class="form-grid">' +
              '<div class="campo-form">' +
                '<label for="mod_cli_nome">Seu Nome Completo *</label>' +
                '<input type="text" id="mod_cli_nome" name="nome" required placeholder="Ex.: João Gabriel da Silva">' +
              '</div>' +
              '<div class="campo-form">' +
                '<label for="mod_cli_cpf">Seu CPF *</label>' +
                '<input type="text" id="mod_cli_cpf" name="cpf" required maxlength="14" placeholder="000.000.000-00">' +
              '</div>' +
              '<div class="campo-form">' +
                '<label for="mod_cli_telefone">Seu Telefone ou WhatsApp *</label>' +
                '<input type="text" id="mod_cli_telefone" name="telefone" required placeholder="61 99999-0000">' +
              '</div>' +
              '<div class="campo-form">' +
                '<label for="mod_cli_email">Seu E-mail *</label>' +
                '<input type="email" id="mod_cli_email" name="email" required placeholder="seuemail@exemplo.com">' +
              '</div>' +
              '<div class="campo-form">' +
                '<label for="mod_cli_cidade">Seu Bairro e Cidade *</label>' +
                '<input type="text" id="mod_cli_cidade" name="cidade" required value="Brasília - DF" placeholder="Brasília - Asa Norte">' +
              '</div>' +
              '<div class="campo-form">' +
                '<label for="mod_cli_unidade">Unidade de Sua Preferência *</label>' +
                '<select id="mod_cli_unidade" name="unidade_preferida">' +
                  '<option value="Matriz - Asa Norte">Matriz - Asa Norte</option>' +
                  '<option value="Filial - Águas Claras">Filial - Águas Claras</option>' +
                '</select>' +
              '</div>' +
            '</div>' +
          '</div>' +
          '<div class="modal-rodape">' +
            '<button type="button" class="botao botao-secundario" onclick="fecharModalCadastro()">Fechar</button>' +
            '<button type="submit" class="botao">Se cadastre</button>' +
          '</div>' +
        '</form>' +
      '</div>' +
    '</div>';

  document.body.appendChild(container);
}

function exibirModalAviso(titulo, mensagem) {
  criarEstruturaModais();
  document.getElementById("modalNotificacaoTitulo").innerText = titulo;
  document.getElementById("modalNotificacaoMensagem").innerText = mensagem;
  document.getElementById("modalNotificacaoRodape").innerHTML =
    '<button type="button" class="botao" onclick="fecharModalNotificacao()">Fechar</button>';
  document.getElementById("modalNotificacaoOverlay").classList.add("ativo");
}

function exibirModalConfirmacao(titulo, mensagem, aoConfirmar) {
  criarEstruturaModais();
  callbackConfirmacaoModal = aoConfirmar;
  document.getElementById("modalNotificacaoTitulo").innerText = titulo;
  document.getElementById("modalNotificacaoMensagem").innerText = mensagem;
  document.getElementById("modalNotificacaoRodape").innerHTML =
    '<button type="button" class="botao botao-secundario" onclick="fecharModalNotificacao()">Cancelar</button>' +
    '<button type="button" class="botao botao-perigo" onclick="executarConfirmacaoModal()">Confirmar</button>';
  document.getElementById("modalNotificacaoOverlay").classList.add("ativo");
}

function executarConfirmacaoModal() {
  var acao = callbackConfirmacaoModal;
  fecharModalNotificacao();
  if (typeof acao === "function") {
    acao();
  }
}

function fecharModalNotificacao() {
  var overlay = document.getElementById("modalNotificacaoOverlay");
  if (overlay) {
    overlay.classList.remove("ativo");
  }
  callbackConfirmacaoModal = null;
}

function confirmarExclusaoLink(event, urlDestino, mensagem) {
  if (event) {
    event.preventDefault();
  }
  exibirModalConfirmacao("Confirmar Remoção", mensagem, function () {
    window.location.href = urlDestino;
  });
  return false;
}

/* ---------------------------------------------------------------------
   MODAL DE BOAS-VINDAS PRÓPRIO DO SITE (HOME)
   --------------------------------------------------------------------- */
function atualizarTextoSaudacao() {
  var caixaSaudacao = document.getElementById("saudacao");
  if (!caixaSaudacao) {
    return;
  }

  var nomeSalvo = sessionStorage.getItem("softbyke_visitante") || "";
  if (nomeSalvo !== "") {
    caixaSaudacao.innerHTML =
      "Olá, <strong>" + nomeSalvo + "</strong>! Bem-vindo à SoftByke — venda de bicicletas, peças e oficina especializada há 5 anos.";
  } else {
    caixaSaudacao.innerHTML =
      "Venda de bicicletas, peças, acessórios e oficina completa de reparo e revisão, há 5 anos com você.";
  }
}

function abrirModalBoasVindas() {
  criarEstruturaModais();
  var input = document.getElementById("inputNomeVisitanteModal");
  var atual = sessionStorage.getItem("softbyke_visitante") || "";
  if (input) {
    input.value = atual;
  }
  document.getElementById("modalBoasVindasOverlay").classList.add("ativo");
  if (input) {
    input.focus();
  }
}

function fecharModalBoasVindas() {
  if (sessionStorage.getItem("softbyke_visitante") === null) {
    sessionStorage.setItem("softbyke_visitante", "");
  }
  var overlay = document.getElementById("modalBoasVindasOverlay");
  if (overlay) {
    overlay.classList.remove("ativo");
  }
  atualizarTextoSaudacao();
}

function salvarNomeBoasVindas(event) {
  if (event) {
    event.preventDefault();
  }
  var input = document.getElementById("inputNomeVisitanteModal");
  var nome = input ? input.value.trim() : "";
  sessionStorage.setItem("softbyke_visitante", nome);
  fecharModalBoasVindas();
  return false;
}

/* ---------------------------------------------------------------------
   CADASTRO DE CLIENTE NO MODAL CENTRALIZADO & CONTROLE DO NAVBAR
   --------------------------------------------------------------------- */
function usuarioJaCadastrado() {
  return localStorage.getItem("softbyke_usuario_cadastrado") === "sim";
}

function sincronizarEstadoCadastroUI() {
  var navItem = document.getElementById("itemNavCadastro");
  var navSair = document.getElementById("itemNavSair");

  // Cria dinamicamente o botão de Sair / Reiniciar Login na barra de navegação caso ainda não exista
  if (!navSair && navItem && navItem.parentNode) {
    navSair = document.createElement("li");
    navSair.id = "itemNavSair";
    navSair.style.display = "none";
    navSair.innerHTML = '<a href="#" class="nav-botao-sair" onclick="reiniciarLoginUsuario(event)">Sair</a>';
    navItem.parentNode.appendChild(navSair);
  }

  if (usuarioJaCadastrado()) {
    if (navItem) {
      navItem.style.display = "none";
    }
    if (navSair) {
      navSair.style.display = "inline-block";
    }
    var nomeCliente = localStorage.getItem("softbyke_nome_cliente") || "";
    var campoNomeOrc = document.getElementById("nomeClienteOrcamento");
    if (campoNomeOrc && campoNomeOrc.value.trim() === "" && nomeCliente !== "") {
      campoNomeOrc.value = nomeCliente;
    }
  } else {
    if (navItem) {
      navItem.style.display = "inline-block";
    }
    if (navSair) {
      navSair.style.display = "none";
    }
  }
}

function reiniciarLoginUsuario(event) {
  if (event) {
    event.preventDefault();
  }
  localStorage.removeItem("softbyke_usuario_cadastrado");
  localStorage.removeItem("softbyke_nome_cliente");
  sessionStorage.removeItem("softbyke_visitante");

  var campoNomeOrc = document.getElementById("nomeClienteOrcamento");
  if (campoNomeOrc) {
    campoNomeOrc.value = "";
  }
  var selPerfil = document.getElementById("selPerfilCliente");
  if (selPerfil) {
    selPerfil.value = "0";
    recalcularOrcamentoGeral();
  }

  sincronizarEstadoCadastroUI();
  atualizarTextoSaudacao();
  exibirModalAviso("Sessão Reiniciada", "Seu acesso foi reiniciado. O botão Cadastre-se já está visível novamente.");
}

function abrirModalCadastro(event) {
  if (event) {
    event.preventDefault();
  }
  if (usuarioJaCadastrado()) {
    return;
  }
  criarEstruturaModais();
  document.getElementById("modalCadastroOverlay").classList.add("ativo");
}

function fecharModalCadastro() {
  var overlay = document.getElementById("modalCadastroOverlay");
  if (overlay) {
    overlay.classList.remove("ativo");
  }
}

function submeterCadastroUsuario(event) {
  if (event) {
    event.preventDefault();
  }

  var nome = document.getElementById("mod_cli_nome").value.trim();
  var cpf = document.getElementById("mod_cli_cpf").value.trim();
  var telefone = document.getElementById("mod_cli_telefone").value.trim();
  var email = document.getElementById("mod_cli_email").value.trim();
  var cidade = document.getElementById("mod_cli_cidade").value.trim();
  var unidade = document.getElementById("mod_cli_unidade").value;

  if (nome === "" || cpf === "" || telefone === "" || email === "") {
    exibirModalAviso("Campos Obrigatórios", "Por favor, preencha todos os campos obrigatórios para concluir seu cadastro.");
    return false;
  }

  var dadosForm = new FormData();
  dadosForm.append("nome", nome);
  dadosForm.append("cpf", cpf);
  dadosForm.append("telefone", telefone);
  dadosForm.append("email", email);
  dadosForm.append("cidade", cidade);
  dadosForm.append("unidade_preferida", unidade);

  fetch("php/cadastrar_cliente.php", {
    method: "POST",
    body: dadosForm
  })
    .then(function (res) {
      return res.json();
    })
    .then(function (dados) {
      concluirCadastroNoCliente(nome, cpf, dados.id || 0, dados.mensagem);
    })
    .catch(function () {
      concluirCadastroNoCliente(nome, cpf, 0, "Cadastro realizado com sucesso! Bem-vindo à SoftByke, " + nome + ".");
    });

  return false;
}

function concluirCadastroNoCliente(nome, cpf, novoId, mensagemConfirmacao) {
  localStorage.setItem("softbyke_usuario_cadastrado", "sim");
  localStorage.setItem("softbyke_nome_cliente", nome);
  sessionStorage.setItem("softbyke_visitante", nome);

  fecharModalCadastro();
  sincronizarEstadoCadastroUI();
  atualizarTextoSaudacao();

  // Se estiver na página de orçamento, atualiza o perfil de fidelidade para Cliente Cadastrado
  var selPerfil = document.getElementById("selPerfilCliente");
  if (selPerfil) {
    selPerfil.value = "5";
    recalcularOrcamentoGeral();
  }

  // Se estiver na página de Oficina, adiciona o novo cliente no select imediatamente
  var selClienteOficina = document.getElementById("ag_cliente_id");
  if (selClienteOficina && novoId > 0) {
    var opt = document.createElement("option");
    opt.value = novoId;
    opt.text = nome + " — CPF " + cpf;
    opt.selected = true;
    selClienteOficina.appendChild(opt);
  }

  exibirModalAviso("Cadastro Concluído", mensagemConfirmacao || ("Parabéns, " + nome + "! Seu cadastro foi concluído com sucesso."));
}

function processarBotaoPagamento() {
  // Se o usuário já tiver se cadastrado, o botão de pagamento não faz nada
  if (usuarioJaCadastrado()) {
    return;
  }
  abrirModalCadastro(null);
}

/* ---------------------------------------------------------------------
   NEWSLETTER E ROTAÇÃO AUTOMÁTICA DE DESTAQUES
   --------------------------------------------------------------------- */
function validarNewsletter(event) {
  if (event) {
    event.preventDefault();
  }
  var campoEmail = document.getElementById("emailNewsletter");
  if (!campoEmail) {
    return false;
  }

  var email = campoEmail.value.trim();

  if (email.indexOf("@") === -1 || email.indexOf(".") === -1) {
    exibirModalAviso("E-mail Inválido", "Por favor, digite um endereço de e-mail válido para receber as ofertas da SoftByke.");
    return false;
  }

  exibirModalAviso("Inscrição Confirmada", "Obrigado! O e-mail " + email + " foi cadastrado com sucesso em nossa lista de ofertas.");
  campoEmail.value = "";
  return false;
}

var mensagensAutomacao = [
  "Cupons promocionais disponíveis hoje: <b>SOFT10</b> com 10% de desconto, <b>BIKE15</b> com 15% de desconto e <b>CEUB20</b> com 20% de desconto.",
  "Entrega Urbana no Distrito Federal <b>GRÁTIS</b> automaticamente para pedidos acima de <b>R$ 500,00</b>.",
  "Pagamento via <b>PIX</b> garante mais <b>10% de desconto imediato</b> sobre o valor dos produtos.",
  "Revisão Completa Premium na Oficina SoftByke por apenas <b>R$ 149,90</b> nas unidades Asa Norte e Águas Claras."
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
   NAVEGAÇÃO DO CATÁLOGO DIRETO PARA O ORÇAMENTO COM PRODUTO SELECIONADO
   --------------------------------------------------------------------- */
function irParaOrcamentoComProduto(codigoProduto) {
  window.location.href = "orcamento.html?codigo=" + encodeURIComponent(codigoProduto);
}

function selecionarProdutoPorCodigo(codigoAlvo) {
  if (!codigoAlvo) {
    return false;
  }
  var categorias = ["Bicicletas", "Peças", "Manutenção"];
  for (var c = 0; c < categorias.length; c++) {
    var catNome = categorias[c];
    var lista = catalogoSoftByke[catNome] || [];
    for (var i = 0; i < lista.length; i++) {
      if (lista[i].codigo === codigoAlvo) {
        var selCategoria = document.getElementById("selCategoria");
        var selProduto = document.getElementById("selProduto");
        if (selCategoria && selProduto) {
          selCategoria.value = catNome;
          atualizarSelectProdutos();
          selProduto.value = String(i);
          atualizarPrecoUnitario();
          return true;
        }
      }
    }
  }
  return false;
}

/* ---------------------------------------------------------------------
   FUNÇÕES DO ORÇAMENTO E CARRINHO DE COMPRAS (orcamento.html)
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

  for (var i = 0; i < listaItens.length; i++) {
    var item = listaItens[i];
    var option = document.createElement("option");
    option.value = i;
    option.text = item.nome + " — " + gerenciadorOrcamento.formatarMoeda(item.preco);
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

  if (!produto || isNaN(quantidade) || quantidade <= 0) {
    exibirModalAviso("Quantidade Inválida", "Informe uma quantidade válida de pelo menos 1 unidade.");
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
    exibirModalAviso("Carrinho Vazio", "O seu carrinho de orçamento já está vazio.");
    return;
  }
  exibirModalConfirmacao(
    "Esvaziar Carrinho",
    "Deseja realmente remover todos os itens do seu carrinho de compras?",
    function () {
      gerenciadorOrcamento.limparTodos();
      renderizarTabelaOrcamento();
      recalcularOrcamentoGeral();
    }
  );
}

function renderizarTabelaOrcamento() {
  var corpo = document.getElementById("corpoTabelaOrcamento");
  if (!corpo) {
    return;
  }

  corpo.innerHTML = "";

  if (gerenciadorOrcamento.itens.length === 0) {
    corpo.innerHTML =
      '<tr class="linha-carrinho-vazio"><td colspan="8">Seu carrinho está vazio. Selecione um produto acima ou escolha diretamente no Catálogo.</td></tr>';
    return;
  }

  for (var i = 0; i < gerenciadorOrcamento.itens.length; i++) {
    var item = gerenciadorOrcamento.itens[i];
    var tr = document.createElement("tr");

    tr.innerHTML =
      '<td><span class="codigo-item-carrinho">' + item.codigo + "</span></td>" +
      '<td>' + item.categoria + "</td>" +
      '<td class="col-produto-carrinho"><strong>' + item.nome + "</strong></td>" +
      '<td>' + gerenciadorOrcamento.formatarMoeda(item.precoUnitario) + "</td>" +
      '<td>' + gerenciadorOrcamento.formatarMoeda(item.adicionalUnitario) + "</td>" +
      '<td>' +
        '<input type="number" min="1" max="50" value="' + item.quantidade + '" class="input-qtd-tabela" ' +
        'onchange="alterarQuantidadeNaTabela(' + i + ', this.value)">' +
      "</td>" +
      '<td class="subtotal-item-carrinho"><strong>' + gerenciadorOrcamento.formatarMoeda(item.subtotal) + "</strong></td>" +
      '<td>' +
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
    exibirModalAviso("Cupom Promocional", "Digite um código de cupom promocional como SOFT10, BIKE15 ou CEUB20.");
  } else if (cuponsDesconto[codigo]) {
    exibirModalAviso("Cupom Aplicado", "O cupom " + codigo + " foi aplicado com sucesso: " + cuponsDesconto[codigo].descricao + ".");
  } else {
    exibirModalAviso("Cupom Inválido", "O código informado não foi encontrado. Utilize SOFT10, BIKE15 ou CEUB20.");
  }
}

function recalcularOrcamentoGeral() {
  var elQtdTotal = document.getElementById("resQtdTotal");
  if (!elQtdTotal) {
    return;
  }

  var subtotalBruto = gerenciadorOrcamento.calcularSubtotalBruto();
  var qtdTotal = gerenciadorOrcamento.calcularQuantidadeTotal();

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
      msgCupom.innerHTML = "&#10008; Cupom não encontrado. Experimente SOFT10, BIKE15 ou CEUB20.";
      msgCupom.className = "msg-apoio texto-erro";
    }
  } else {
    msgCupom.innerHTML = "";
  }

  var valorDescontoCupom = subtotalBruto * ((percFidelidade + percCupom) / 100);
  var valorLiquidoParcial = subtotalBruto - valorDescontoCupom;

  var tipoFrete = document.getElementById("selFrete").value;
  var valorFrete = 0;

  if (subtotalBruto > 0) {
    switch (tipoFrete) {
      case "retirada":
        valorFrete = 0;
        break;
      case "urbano":
        valorFrete = valorLiquidoParcial >= 500 ? 0 : 35.00;
        break;
      case "expresso":
        valorFrete = 79.90;
        break;
      default:
        valorFrete = 0;
    }
  }

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
    var baseFinanciada = valorLiquidoParcial + valorFrete;
    var montanteComJuros = baseFinanciada * Math.pow(1 + 0.0199, numParcelas);
    valorJuros = montanteComJuros - baseFinanciada;
  }

  var totalFinal = valorLiquidoParcial - valorDescontoPagamento + valorFrete + valorJuros;
  if (totalFinal < 0) {
    totalFinal = 0;
  }

  var valorParcelaSelecionada = numParcelas > 0 ? totalFinal / numParcelas : totalFinal;

  elQtdTotal.innerHTML = qtdTotal + (qtdTotal === 1 ? " unidade" : " unidades");
  document.getElementById("resSubtotalBruto").innerHTML = gerenciadorOrcamento.formatarMoeda(subtotalBruto);
  document.getElementById("resDescontoCupom").innerHTML = "- " + gerenciadorOrcamento.formatarMoeda(valorDescontoCupom);
  document.getElementById("resDescontoPagamento").innerHTML = "- " + gerenciadorOrcamento.formatarMoeda(valorDescontoPagamento);
  document.getElementById("resValorFrete").innerHTML =
    valorFrete === 0 && subtotalBruto > 0 ? "Grátis" : gerenciadorOrcamento.formatarMoeda(valorFrete);
  document.getElementById("resValorJuros").innerHTML = gerenciadorOrcamento.formatarMoeda(valorJuros);
  document.getElementById("resTotalFinal").innerHTML = gerenciadorOrcamento.formatarMoeda(totalFinal);
  document.getElementById("resDetalheParcela").innerHTML =
    numParcelas + "x de " + gerenciadorOrcamento.formatarMoeda(valorParcelaSelecionada);
}

/* ---------------------------------------------------------------------
   FILTRO INTERATIVO DO CATÁLOGO (galeria.html)
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

/* ---------------------------------------------------------------------
   INICIALIZAÇÃO AUTOMÁTICA (onload)
   --------------------------------------------------------------------- */
window.onload = function () {
  criarEstruturaModais();
  sincronizarEstadoCadastroUI();

  if (document.getElementById("saudacao") !== null) {
    atualizarTextoSaudacao();
    if (sessionStorage.getItem("softbyke_visitante") === null) {
      abrirModalBoasVindas();
    }
  }

  iniciarAutomacaoPromocoes();

  if (document.getElementById("selCategoria") !== null) {
    atualizarSelectProdutos();

    var params = new URLSearchParams(window.location.search);
    var codigoUrl = params.get("codigo");
    if (codigoUrl) {
      selecionarProdutoPorCodigo(codigoUrl);
    }

    if (usuarioJaCadastrado()) {
      var selPerfil = document.getElementById("selPerfilCliente");
      if (selPerfil) {
        selPerfil.value = "5";
      }
    }

    renderizarTabelaOrcamento();
    recalcularOrcamentoGeral();
  }
};
