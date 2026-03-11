// ────────────────────────────────────────────────────────────
// FUNCIONALIDADE 1 — SIMULADOR DE FORÇA DE SENHA
window.alert("Olá Usuário!");

const strengthInput   = document.getElementById('strength-input');
const strengthBarFill = document.getElementById('strength-bar-fill');
const strengthLabel   = document.getElementById('strength-label');
const critLen         = document.getElementById('crit-len');
const critCase        = document.getElementById('crit-case');
const critNumSym      = document.getElementById('crit-numsym');

// Atualiza um item da lista de critérios (✓ verde / ✗ vermelho)
function setCriterio(el, passou) {
  if (!el) return;
  el.setAttribute('data-ok', passou ? 'true' : 'false');
  const texto = el.textContent.slice(2); // remove o ✓ ou ✗ atual
  el.textContent = (passou ? '✓ ' : '✗ ') + texto;
}

if (strengthInput) {
  strengthInput.addEventListener('input', function () {
    const senha = this.value;

    const temLen    = senha.length >= 8;
    const temCase   = /[A-Z]/.test(senha) && /[a-z]/.test(senha);
    const temNumSym = /[0-9]/.test(senha) && /[^A-Za-z0-9]/.test(senha);

    // Atualiza os ícones da lista de critérios
    setCriterio(critLen,    temLen);
    setCriterio(critCase,   temCase);
    setCriterio(critNumSym, temNumSym);

    // Calcula o nível (0 = vazio, 1 = fraca, 2 = média, 3 = forte)
    const nivel = senha.length === 0 ? 0 : [temLen, temCase, temNumSym].filter(Boolean).length;

    const config = {
      0: { w: '0%',   bg: '#e2e8f0', txt: 'Digite uma senha acima...', cor: '#64748b' },
      1: { w: '30%',  bg: '#ef4444', txt: 'Força: Fraca ✗',            cor: '#ef4444' },
      2: { w: '65%',  bg: '#f59e0b', txt: 'Força: Média ⚠',            cor: '#f59e0b' },
      3: { w: '100%', bg: '#22c55e', txt: 'Força: Forte ✓',            cor: '#22c55e' },
    };

    const c = config[nivel];
    strengthBarFill.style.width      = c.w;
    strengthBarFill.style.background = c.bg;
    strengthLabel.textContent        = c.txt;
    strengthLabel.style.color        = c.cor;
  });
}


// ────────────────────────────────────────────────────────────

// ────────────────────────────────────────────────────────────

const RESPOSTA_CORRETA = 'b';
const btnResponder     = document.getElementById('btn-responder');
const quizQuestion     = document.getElementById('quiz-question');
const btnCertificado   = document.getElementById('btn-certificado');

if (btnResponder) {
  btnResponder.addEventListener('click', function () {
    const marcado = document.querySelector('[name="q1"]:checked');

    // Limpa feedbacks anteriores
    document.querySelectorAll('.radio-option').forEach(opt => {
      opt.classList.remove('correta', 'errada');
    });
    const feedbackAntigo = quizQuestion.querySelector('.quiz-feedback');
    if (feedbackAntigo) feedbackAntigo.remove();

    // Sem seleção
    if (!marcado) {
      const aviso = document.createElement('p');
      aviso.className   = 'quiz-feedback nok';
      aviso.textContent = '⚠ Selecione uma opção antes de responder!';
      quizQuestion.appendChild(aviso);
      return;
    }

    const acertou = marcado.value === RESPOSTA_CORRETA;

    // Colore as opções
    document.querySelectorAll('[name="q1"]').forEach(radio => {
      const opcao = radio.closest('.radio-option');
      if (radio.value === RESPOSTA_CORRETA) {
        opcao.classList.add('correta');
      } else if (radio.checked) {
        opcao.classList.add('errada');
      }
    });

    // Mensagem de feedback
    const feedback = document.createElement('p');
    feedback.className   = 'quiz-feedback ' + (acertou ? 'ok' : 'nok');
    feedback.textContent = acertou
      ? '✓ Correto! "Cyber@2026!" combina maiúsculas, números e símbolo — senha forte!'
      : '✗ Quase! A resposta certa é "Cyber@2026!" — ela usa maiúsculas, número e símbolo especial.';
    quizQuestion.appendChild(feedback);

    // Habilita o botão de certificado somente após acerto
    if (acertou && btnCertificado) {
      btnCertificado.disabled = false;
    }
  });
}

// Simula geração de certificado
if (btnCertificado) {
  btnCertificado.addEventListener('click', function () {
    alert('🏆 Parabéns! Seu certificado foi gerado.\nContinue explorando os outros módulos!');
  });
}


// ────────────────────────────────────────────────────────────
// FUNCIONALIDADE 3 — GLOSSÁRIO DINÂMICO COM FILTRO
// Elementos usados: #glossary-search, #glossario-cards
// ────────────────────────────────────────────────────────────

const GLOSSARIO = [
  { termo: 'Phishing',         def: 'Tentativa de enganar o usuário para revelar dados pessoais, geralmente por e-mail falso.' },
  { termo: 'Malware',          def: 'Software malicioso criado para danificar ou acessar sistemas sem permissão.' },
  { termo: 'VPN',              def: 'Rede Privada Virtual — criptografa sua conexão e oculta seu endereço IP real.' },
  { termo: 'Ransomware',       def: 'Malware que sequestra arquivos e exige pagamento para devolvê-los.' },
  { termo: '2FA',              def: 'Autenticação em dois fatores — adiciona uma segunda camada de verificação.' },
  { termo: 'Firewall',         def: 'Barreira de segurança que monitora e controla o tráfego de rede.' },
  { termo: 'Engenharia Social', def: 'Manipulação psicológica para obter informações confidenciais de pessoas.' },
  { termo: 'Criptografia',     def: 'Processo de codificar dados para que só destinatários autorizados os leiam.' },
  { termo: 'Spyware',          def: 'Software espião que coleta dados do usuário sem seu conhecimento.' },
  { termo: 'Trojan',           def: 'Malware disfarçado de programa legítimo para enganar o usuário.' },
];

const buscaInput        = document.getElementById('glossary-search');
const glossarioCards    = document.getElementById('glossario-cards');

function renderGlossario(lista) {
  if (!glossarioCards) return;
  glossarioCards.innerHTML = '';

  if (lista.length === 0) {
    glossarioCards.innerHTML = '<p class="glos-vazio">Nenhum termo encontrado.</p>';
    return;
  }

  lista.forEach(function (item) {
    const card = document.createElement('div');
    card.className = 'glos-card';
    card.innerHTML = '<strong>' + item.termo + '</strong><span>' + item.def + '</span>';
    glossarioCards.appendChild(card);
  });
}

// Renderiza todos os termos ao carregar a página
renderGlossario(GLOSSARIO);

if (buscaInput) {
  buscaInput.addEventListener('input', function () {
    const termo = this.value.toLowerCase().trim();
    const filtrado = GLOSSARIO.filter(function (item) {
      return item.termo.toLowerCase().includes(termo)
          || item.def.toLowerCase().includes(termo);
    });
    renderGlossario(filtrado);
  });
}


// ────────────────────────────────────────────────────────────
// FUNCIONALIDADE 4 — FEEDBACK NOS FORMULÁRIOS (Login/Cadastro)
// ────────────────────────────────────────────────────────────

// Exibe uma mensagem temporária num parágrafo de feedback
function showMsg(id, texto, tipo) {
  const el = document.getElementById(id);
  if (!el) return;
  el.textContent  = texto;
  el.className    = 'form-msg ' + tipo;  // tipo: 'ok' ou 'nok'
  // Apaga a mensagem após 4 segundos
  setTimeout(function () { el.textContent = ''; el.className = 'form-msg'; }, 4000);
}

// Botão Criar Conta
const btnCadastro = document.getElementById('btn-cadastro');
if (btnCadastro) {
  btnCadastro.addEventListener('click', function () {
    const nome  = document.getElementById('reg-name').value.trim();
    const email = document.getElementById('reg-email').value.trim();
    const senha = document.getElementById('reg-pass').value;

    if (!nome || !email || !senha) {
      showMsg('msg-cadastro', '⚠ Preencha todos os campos.', 'nok');
    } else if (senha.length < 6) {
      showMsg('msg-cadastro', '✗ A senha deve ter ao menos 6 caracteres.', 'nok');
    } else {
      showMsg('msg-cadastro', '✓ Conta criada com sucesso!', 'ok');
    }
  });
}

// Botão Entrar
const btnLogin = document.getElementById('btn-login');
if (btnLogin) {
  btnLogin.addEventListener('click', function () {
    const email = document.getElementById('login-email').value.trim();
    const senha = document.getElementById('login-pass').value;

    if (!email || !senha) {
      showMsg('msg-login', '⚠ Informe e-mail e senha.', 'nok');
    } else {
      showMsg('msg-login', '✓ Login realizado com sucesso!', 'ok');
    }
  });
}

// Botão Recuperar Senha
const btnRecover = document.getElementById('btn-recover');
if (btnRecover) {
  btnRecover.addEventListener('click', function () {
    const email = document.getElementById('recover-email').value.trim();

    if (!email) {
      showMsg('msg-recover', '⚠ Informe seu e-mail para recuperação.', 'nok');
    } else {
      showMsg('msg-recover', '✓ Link de recuperação enviado!', 'ok');
    }
  });
}

// Botão Enviar Sugestão
const btnContato = document.getElementById('btn-contato');
if (btnContato) {
  btnContato.addEventListener('click', function () {
    const msg = document.getElementById('contact-msg').value.trim();

    if (!msg) {
      showMsg('msg-contato', '⚠ Escreva uma mensagem antes de enviar.', 'nok');
    } else {
      showMsg('msg-contato', '✓ Sugestão enviada! Obrigado pelo feedback.', 'ok');
      document.getElementById('contact-msg').value = '';
    }
  });
}
