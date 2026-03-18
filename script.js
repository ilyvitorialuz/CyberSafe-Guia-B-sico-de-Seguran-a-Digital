/* ==============================================
   script.js — CyberSafe
   Contém: cursor, partículas, contadores,
   toasts, auth, módulos, quiz, senha, contato
=============================================== */

/* =============================================
   CURSOR PERSONALIZADO
============================================= */
const cursor     = document.getElementById('cursor');
const cursorRing = document.getElementById('cursor-ring');
let mx = 0, my = 0, rx = 0, ry = 0;

document.addEventListener('mousemove', e => {
  mx = e.clientX;
  my = e.clientY;
  cursor.style.transform = `translate(${mx - 6}px, ${my - 6}px)`;
});

function animateRing() {
  rx += (mx - rx - 18) * 0.12;
  ry += (my - ry - 18) * 0.12;
  cursorRing.style.transform = `translate(${rx}px, ${ry}px)`;
  requestAnimationFrame(animateRing);
}
animateRing();

document.querySelectorAll('a, button, .module-card, .radio-option').forEach(el => {
  el.addEventListener('mouseenter', () => {
    cursor.style.transform += ' scale(1.5)';
    cursorRing.style.width       = '50px';
    cursorRing.style.height      = '50px';
    cursorRing.style.borderColor = 'var(--green)';
  });
  el.addEventListener('mouseleave', () => {
    cursorRing.style.width       = '36px';
    cursorRing.style.height      = '36px';
    cursorRing.style.borderColor = 'var(--teal)';
  });
});

/* =============================================
   PARTÍCULAS
============================================= */
(function () {
  const canvas = document.getElementById('particles');
  const ctx    = canvas.getContext('2d');
  let W, H, particles = [];

  function resize() {
    W = canvas.width  = window.innerWidth;
    H = canvas.height = window.innerHeight;
  }
  resize();
  window.addEventListener('resize', resize);

  for (let i = 0; i < 60; i++) {
    particles.push({
      x:  Math.random() * window.innerWidth,
      y:  Math.random() * window.innerHeight,
      r:  Math.random() * 1.5 + 0.3,
      vx: (Math.random() - 0.5) * 0.3,
      vy: (Math.random() - 0.5) * 0.3,
      a:  Math.random() * 0.5 + 0.1
    });
  }

  function draw() {
    ctx.clearRect(0, 0, W, H);

    particles.forEach(p => {
      p.x += p.vx; p.y += p.vy;
      if (p.x < 0) p.x = W;
      if (p.x > W) p.x = 0;
      if (p.y < 0) p.y = H;
      if (p.y > H) p.y = 0;
      ctx.beginPath();
      ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(0,212,200,${p.a})`;
      ctx.fill();
    });

    // Linhas entre partículas próximas
    for (let i = 0; i < particles.length; i++) {
      for (let j = i + 1; j < particles.length; j++) {
        const dx   = particles[i].x - particles[j].x;
        const dy   = particles[i].y - particles[j].y;
        const dist = Math.sqrt(dx * dx + dy * dy);
        if (dist < 120) {
          ctx.beginPath();
          ctx.moveTo(particles[i].x, particles[i].y);
          ctx.lineTo(particles[j].x, particles[j].y);
          ctx.strokeStyle = `rgba(0,212,200,${0.08 * (1 - dist / 120)})`;
          ctx.lineWidth   = 0.5;
          ctx.stroke();
        }
      }
    }
    requestAnimationFrame(draw);
  }
  draw();
})();

/* =============================================
   COUNTERS ANIMADOS
============================================= */
function animateCounter(el, target, duration = 2000) {
  const start    = Date.now();
  const startVal = 0;
  function update() {
    const elapsed  = Date.now() - start;
    const progress = Math.min(elapsed / duration, 1);
    const ease     = 1 - Math.pow(1 - progress, 3);
    el.textContent = Math.round(startVal + (target - startVal) * ease).toLocaleString('pt-BR');
    if (progress < 1) requestAnimationFrame(update);
  }
  update();
}
setTimeout(() => {
  animateCounter(document.getElementById('stat-users'),   2847);
  animateCounter(document.getElementById('stat-threats'), 1204);
}, 500);

/* =============================================
   TOAST
============================================= */
function toast(msg, icon = '✅', duration = 3500) {
  const container = document.getElementById('toast-container');
  const el        = document.createElement('div');
  el.className    = 'toast';
  el.innerHTML    = `<span class="toast-icon">${icon}</span>${msg}`;
  container.appendChild(el);
  setTimeout(() => {
    el.classList.add('out');
    setTimeout(() => el.remove(), 350);
  }, duration);
}

/* =============================================
   AUTH
============================================= */
let users       = JSON.parse(localStorage.getItem('cs_users')   || '[]');
let currentUser = JSON.parse(localStorage.getItem('cs_session') || 'null');

function showSession() {
  if (!currentUser) return;
  document.getElementById('session-bar').style.display  = 'flex';
  document.getElementById('auth-forms').style.display   = 'none';
  document.getElementById('session-name').textContent   = currentUser.name;
  const initials = currentUser.name.split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase();
  document.getElementById('session-avatar').textContent = initials;
}

document.getElementById('btn-cadastro').addEventListener('click', () => {
  const name  = document.getElementById('reg-name').value.trim();
  const email = document.getElementById('reg-email').value.trim();
  const pass  = document.getElementById('reg-pass').value;
  const msg   = document.getElementById('msg-cadastro');

  if (!name || !email || !pass) {
    msg.textContent = '⚠ Preencha todos os campos.';
    msg.className   = 'form-msg error';
    return;
  }
  if (users.find(u => u.email === email)) {
    msg.textContent = '⚠ E-mail já cadastrado.';
    msg.className   = 'form-msg error';
    return;
  }
  if (pass.length < 6) {
    msg.textContent = '⚠ Senha muito curta (mín. 6 caracteres).';
    msg.className   = 'form-msg error';
    return;
  }

  const user = { name, email, pass };
  users.push(user);
  localStorage.setItem('cs_users',   JSON.stringify(users));
  currentUser = user;
  localStorage.setItem('cs_session', JSON.stringify(user));
  msg.textContent = '✓ Conta criada com sucesso!';
  msg.className   = 'form-msg success';
  showSession();
  toast(`Bem-vindo, ${name}! 🎉`, '👋');
});

document.getElementById('btn-login').addEventListener('click', () => {
  const email = document.getElementById('login-email').value.trim();
  const pass  = document.getElementById('login-pass').value;
  const msg   = document.getElementById('msg-login');
  const user  = users.find(u => u.email === email && u.pass === pass);

  if (!user) {
    msg.textContent = '⚠ E-mail ou senha incorretos.';
    msg.className   = 'form-msg error';
    return;
  }
  currentUser = user;
  localStorage.setItem('cs_session', JSON.stringify(user));
  msg.textContent = '✓ Login realizado!';
  msg.className   = 'form-msg success';
  showSession();
  toast(`Olá de volta, ${user.name}!`, '🔐');
});

document.getElementById('btn-logout').addEventListener('click', () => {
  currentUser = null;
  localStorage.removeItem('cs_session');
  document.getElementById('session-bar').style.display = 'none';
  document.getElementById('auth-forms').style.display  = 'block';
  document.getElementById('msg-login').textContent     = '';
  toast('Sessão encerrada.', '👋');
});

if (currentUser) showSession();

/* =============================================
   MÓDULOS
============================================= */
const modulesData = [
  {
    emoji: '🔐', title: 'Fundamentos de Segurança',
    desc: 'Conceitos básicos de CIA (Confidencialidade, Integridade, Disponibilidade).',
    content: `
      <h4>O que é Segurança da Informação?</h4>
      <p>É a proteção de dados e sistemas contra acesso não autorizado, uso indevido, modificação ou destruição. Baseia-se na tríade CIA.</p>
      <h4>Tríade CIA</h4>
      <ul>
        <li><strong>Confidencialidade</strong>: Apenas pessoas autorizadas acessam os dados.</li>
        <li><strong>Integridade</strong>: Dados não são alterados de forma não autorizada.</li>
        <li><strong>Disponibilidade</strong>: Sistemas funcionam quando necessário.</li>
      </ul>
      <h4>Boas Práticas Iniciais</h4>
      <ul>
        <li>Nunca compartilhe suas senhas com ninguém.</li>
        <li>Use senhas diferentes para cada serviço.</li>
        <li>Ative notificações de acesso nas suas contas.</li>
      </ul>
    `, pct: 100
  },
  {
    emoji: '🔑', title: 'Senhas Seguras',
    desc: 'Como criar, gerenciar e proteger senhas contra ataques.',
    content: `
      <h4>O que torna uma senha forte?</h4>
      <p>Uma senha forte combina comprimento, variedade de caracteres e imprevisibilidade. Evite datas de aniversário, nomes e sequências óbvias.</p>
      <h4>Regras de Ouro</h4>
      <ul>
        <li>Mínimo de 12 caracteres.</li>
        <li>Misture maiúsculas, minúsculas, números e símbolos.</li>
        <li>Evite palavras de dicionário.</li>
        <li>Use senhas diferentes para cada conta.</li>
      </ul>
      <h4>Gerenciadores de Senha</h4>
      <ul>
        <li><strong>Bitwarden</strong>: Open-source e gratuito.</li>
        <li><strong>1Password</strong>: Muito popular e seguro.</li>
        <li><strong>KeePass</strong>: Local, sem cloud.</li>
      </ul>
    `, pct: 80
  },
  {
    emoji: '🎣', title: 'Phishing & Engenharia Social',
    desc: 'Identifique e evite golpes que manipulam pessoas.',
    content: `
      <h4>O que é Phishing?</h4>
      <p>Técnica onde criminosos se passam por entidades confiáveis para roubar dados. Pode ocorrer por e-mail, SMS, WhatsApp ou ligações.</p>
      <h4>Sinais de Alerta</h4>
      <ul>
        <li>Urgência excessiva ("sua conta será bloqueada!").</li>
        <li>Erros gramaticais no texto.</li>
        <li>Domínio de e-mail estranho.</li>
        <li>Links que não correspondem ao site real.</li>
      </ul>
      <h4>Como se Proteger</h4>
      <ul>
        <li>Nunca clique em links suspeitos.</li>
        <li>Acesse sites digitando o endereço diretamente.</li>
        <li>Confirme por outro canal antes de agir.</li>
      </ul>
    `, pct: 60
  },
  {
    emoji: '🛡️', title: 'Proteção de Dispositivos',
    desc: 'Antivírus, firewalls, atualizações e configurações seguras.',
    content: `
      <h4>Camadas de Proteção</h4>
      <p>A segurança do dispositivo funciona em camadas: software, configurações e comportamento do usuário.</p>
      <h4>Checklist Básico</h4>
      <ul>
        <li>Mantenha o SO e aplicativos atualizados.</li>
        <li>Use antivírus confiável e atualizado.</li>
        <li>Ative o firewall do sistema.</li>
        <li>Desative serviços não utilizados (Bluetooth, Wi-Fi).</li>
        <li>Criptografe o disco com BitLocker ou FileVault.</li>
      </ul>
    `, pct: 45
  },
  {
    emoji: '📡', title: 'Segurança em Redes',
    desc: 'Wi-Fi, VPN, DNS seguro e proteção em redes públicas.',
    content: `
      <h4>Riscos em Redes Públicas</h4>
      <p>Redes Wi-Fi abertas são vulneráveis a ataques Man-in-the-Middle, onde um atacante intercepta sua comunicação.</p>
      <h4>Boas Práticas</h4>
      <ul>
        <li>Use VPN em redes públicas.</li>
        <li>Prefira HTTPS sempre.</li>
        <li>Configure seu roteador doméstico com WPA3.</li>
        <li>Altere a senha padrão do roteador.</li>
        <li>Use DNS seguro (1.1.1.1 ou 8.8.8.8).</li>
      </ul>
    `, pct: 30
  },
  {
    emoji: '☁️', title: 'Privacidade & Dados na Nuvem',
    desc: 'LGPD, direitos dos dados e configurações de privacidade.',
    content: `
      <h4>Seus Direitos (LGPD)</h4>
      <p>A Lei Geral de Proteção de Dados garante que você pode acessar, corrigir e solicitar exclusão de seus dados pessoais.</p>
      <h4>Configurações Importantes</h4>
      <ul>
        <li>Revise permissões de aplicativos regularmente.</li>
        <li>Ative 2FA em serviços de nuvem.</li>
        <li>Use criptografia ponta-a-ponta quando possível.</li>
        <li>Leia os termos antes de aceitar.</li>
      </ul>
    `, pct: 15
  }
];

let currentModule = null;

function renderModules() {
  const grid = document.getElementById('modules-grid');
  grid.innerHTML = '';
  modulesData.forEach((m, i) => {
    const completed = JSON.parse(localStorage.getItem('cs_completed') || '[]').includes(i);
    const div       = document.createElement('div');
    div.className   = 'module-card';
    div.innerHTML   = `
      <span class="module-emoji">${m.emoji}</span>
      <div class="module-title">${m.title} ${completed ? '<span style="color:var(--green);font-size:0.7rem">✓</span>' : ''}</div>
      <div class="module-desc">${m.desc}</div>
      <div class="module-progress">
        <div class="module-bar-track"><div class="module-bar-fill" style="width:0%" data-pct="${m.pct}"></div></div>
        <span class="module-pct">${m.pct}%</span>
      </div>
    `;
    div.addEventListener('click', () => openModule(i));
    grid.appendChild(div);
  });

  // Anima barras de progresso
  setTimeout(() => {
    document.querySelectorAll('.module-bar-fill').forEach(b => {
      b.style.width = b.dataset.pct + '%';
    });
  }, 200);
}
renderModules();

function openModule(i) {
  currentModule = i;
  const m = modulesData[i];
  document.getElementById('modal-title').textContent = m.emoji + ' ' + m.title;
  document.getElementById('modal-body').innerHTML    = m.content;
  document.getElementById('module-overlay').classList.add('active');
}

function markModuleComplete() {
  if (currentModule === null) return;
  const completed = JSON.parse(localStorage.getItem('cs_completed') || '[]');
  if (!completed.includes(currentModule)) {
    completed.push(currentModule);
    localStorage.setItem('cs_completed', JSON.stringify(completed));
    toast('Módulo marcado como concluído! 🎓', '✅');
    renderModules();
  }
  document.getElementById('module-overlay').classList.remove('active');
}

document.getElementById('modal-close').addEventListener('click', () => {
  document.getElementById('module-overlay').classList.remove('active');
});
document.getElementById('module-overlay').addEventListener('click', e => {
  if (e.target === e.currentTarget) e.currentTarget.classList.remove('active');
});

/* =============================================
   QUIZ
============================================= */
const quizData = [
  {
    q: 'Qual dessas senhas é mais segura?',
    opts: ['123456', 'Cyber@2026!', 'senha123', 'minhasenha'],
    correct: 1,
    explanation: '"Cyber@2026!" combina maiúsculas, minúsculas, números e símbolos — critérios de uma senha forte.'
  },
  {
    q: 'O que significa "HTTPS" na barra de endereços?',
    opts: ['O site é famoso', 'A conexão é criptografada', 'O site é gratuito', 'O site foi verificado pelo Google'],
    correct: 1,
    explanation: 'HTTPS usa TLS/SSL para criptografar a comunicação entre seu navegador e o servidor.'
  },
  {
    q: 'O que é Phishing?',
    opts: [
      'Um tipo de vírus que apaga arquivos',
      'Uma técnica para acelerar a internet',
      'Golpe onde criminosos se passam por entidades confiáveis',
      'Um protocolo de rede seguro'
    ],
    correct: 2,
    explanation: 'Phishing é engenharia social: criminosos imitam bancos, empresas ou pessoas para roubar dados.'
  },
  {
    q: 'Qual é a prática mais segura para redes Wi-Fi públicas?',
    opts: ['Não usar senha', 'Usar VPN', 'Desativar o firewall', 'Usar HTTP em vez de HTTPS'],
    correct: 1,
    explanation: 'Uma VPN criptografa todo o tráfego, impedindo que outros usuários da rede interceptem seus dados.'
  },
  {
    q: 'O que é autenticação de dois fatores (2FA)?',
    opts: [
      'Usar duas senhas diferentes',
      'Fazer login em dois dispositivos ao mesmo tempo',
      'Verificação adicional além da senha (ex: código SMS)',
      'Ter duas contas de e-mail'
    ],
    correct: 2,
    explanation: '2FA adiciona uma segunda camada de segurança. Mesmo que sua senha seja roubada, o atacante não consegue entrar.'
  },
  {
    q: 'O que é ransomware?',
    opts: [
      'Software de monitoramento de rede',
      'Malware que criptografa arquivos e exige resgate',
      'Um tipo de antivírus',
      'Ferramenta de backup automático'
    ],
    correct: 1,
    explanation: 'Ransomware bloqueia o acesso aos seus arquivos e exige pagamento (geralmente em criptomoeda) para devolvê-los.'
  },
  {
    q: 'Qual é a recomendação mínima de comprimento para senhas seguras?',
    opts: ['4 caracteres', '6 caracteres', '8 caracteres', '12 caracteres'],
    correct: 3,
    explanation: 'Especialistas recomendam no mínimo 12 caracteres. Senhas mais longas aumentam exponencialmente o tempo de quebra.'
  },
  {
    q: 'O que significa a sigla LGPD?',
    opts: [
      'Lei Geral de Proteção de Dados',
      'Lei Global de Privacidade Digital',
      'Lei de Gestão e Proteção Digital',
      'Lei Geral de Prevenção de Danos'
    ],
    correct: 0,
    explanation: 'A LGPD (Lei nº 13.709/2018) regula o tratamento de dados pessoais no Brasil, inspirada no GDPR europeu.'
  },
  {
    q: 'O que é um ataque "Man-in-the-Middle"?',
    opts: [
      'Ataque físico a servidores',
      'Interceptação de comunicações entre duas partes',
      'Invasão de câmeras de segurança',
      'Roubo de dispositivos físicos'
    ],
    correct: 1,
    explanation: 'Nesse ataque, o criminoso se posiciona entre você e o servidor, podendo ler e modificar os dados em trânsito.'
  },
  {
    q: 'Qual protocolo de segurança Wi-Fi é o mais recomendado atualmente?',
    opts: ['WEP', 'WPA', 'WPA2', 'WPA3'],
    correct: 3,
    explanation: 'WPA3 é o padrão mais recente e seguro, com melhor criptografia e proteção contra ataques de dicionário.'
  }
];

let currentQ = 0, score = 0, answered = false;

function renderQuestion() {
  const q = quizData[currentQ];
  document.getElementById('question-num').textContent        = `PERGUNTA ${String(currentQ + 1).padStart(2, '0')}`;
  document.getElementById('question-text').textContent       = q.q;
  document.getElementById('quiz-prog-label').textContent     = `${currentQ + 1} / ${quizData.length}`;
  document.getElementById('quiz-prog-fill').style.width      = ((currentQ + 1) / quizData.length * 100) + '%';
  document.getElementById('quiz-score-badge').textContent    = `★ ${score} pts`;

  const list = document.getElementById('options-list');
  list.innerHTML = '';
  q.opts.forEach((opt, i) => {
    const lbl       = document.createElement('label');
    lbl.className   = 'radio-option';
    lbl.innerHTML   = `<input type="radio" name="quiz" value="${i}"><div class="radio-circle"></div><span>${opt}</span>`;
    list.appendChild(lbl);
  });

  const fb    = document.getElementById('quiz-feedback');
  fb.className = 'quiz-feedback';
  fb.textContent = '';

  document.getElementById('btn-next').disabled      = true;
  document.getElementById('btn-responder').disabled = false;
  answered = false;
}

document.getElementById('btn-responder').addEventListener('click', () => {
  if (answered) return;
  const sel = document.querySelector('input[name="quiz"]:checked');
  if (!sel) { toast('Selecione uma opção!', '⚠️', 2000); return; }

  answered    = true;
  const val   = parseInt(sel.value);
  const q     = quizData[currentQ];
  const fb    = document.getElementById('quiz-feedback');
  const opts  = document.querySelectorAll('.radio-option');

  opts[q.correct].classList.add('correta');
  if (val === q.correct) {
    score += 10;
    fb.textContent = '✓ Correto! ' + q.explanation;
    fb.className   = 'quiz-feedback visible ok';
    toast('Resposta correta! +10 pontos', '🎯', 2000);
  } else {
    opts[val].classList.add('errada');
    fb.textContent = '✗ Incorreto. ' + q.explanation;
    fb.className   = 'quiz-feedback visible fail';
  }

  document.getElementById('quiz-score-badge').textContent    = `★ ${score} pts`;
  document.getElementById('btn-next').disabled               = false;
  document.getElementById('btn-responder').disabled          = true;
});

document.getElementById('btn-next').addEventListener('click', () => {
  currentQ++;
  if (currentQ >= quizData.length) {
    showResult();
  } else {
    const card = document.getElementById('question-card');
    card.classList.add('slide-out');
    setTimeout(() => {
      renderQuestion();
      card.classList.remove('slide-out');
      card.classList.add('slide-in');
      setTimeout(() => card.classList.remove('slide-in'), 400);
    }, 300);
  }
});

function showResult() {
  document.getElementById('question-card').style.display   = 'none';
  document.getElementById('quiz-feedback').style.display   = 'none';
  document.getElementById('quiz-actions').style.display    = 'none';
  document.querySelector('.quiz-header').style.display     = 'none';

  const pct    = Math.round((score / (quizData.length * 10)) * 100);
  const result = document.getElementById('quiz-result');
  result.style.display = 'block';

  let emoji, title, text;
  if      (pct >= 90) { emoji = '🏆'; title = 'Especialista!';        text = 'Performance excelente. Você domina segurança digital.'; }
  else if (pct >= 70) { emoji = '🌟'; title = 'Muito bom!';           text = 'Ótimo conhecimento. Continue praticando!'; }
  else if (pct >= 50) { emoji = '👍'; title = 'Bom esforço!';         text = 'Você está no caminho certo. Revise os módulos.'; }
  else                { emoji = '📚'; title = 'Continue estudando!';   text = 'Não desanime. Revise os módulos e tente novamente.'; }

  document.getElementById('result-emoji').textContent = emoji;
  document.getElementById('result-title').textContent = title;
  document.getElementById('result-text').textContent  = text;
  document.getElementById('result-score').textContent = `${score} / ${quizData.length * 10} pts`;

  if (pct >= 70) {
    document.getElementById('btn-certificado').disabled = false;
  }
}

document.getElementById('btn-reiniciar').addEventListener('click', () => {
  currentQ = 0; score = 0; answered = false;
  document.getElementById('question-card').style.display = 'block';
  document.getElementById('quiz-feedback').style.display = '';
  document.getElementById('quiz-actions').style.display  = 'flex';
  document.querySelector('.quiz-header').style.display   = 'flex';
  document.getElementById('quiz-result').style.display   = 'none';
  document.getElementById('cert-preview').style.display  = 'none';
  renderQuestion();
});

document.getElementById('btn-certificado').addEventListener('click', () => {
  const name = currentUser ? currentUser.name : 'Visitante';
  const date = new Date().toLocaleDateString('pt-BR', { day: '2-digit', month: 'long', year: 'numeric' });
  const id   = Math.random().toString(36).substr(2, 12).toUpperCase();
  const cert = document.getElementById('cert-preview');
  cert.style.display = 'block';
  cert.innerHTML = `
    <div class="cert-label">Certificado de Conclusão</div>
    <span class="cert-seal">🏆</span>
    <div class="cert-title">CyberSafe</div>
    <div class="cert-name">${name}</div>
    <div class="cert-body">concluiu com sucesso o Quiz Interativo de Segurança Digital,
    demonstrando conhecimento em proteção de dados, senhas, phishing e segurança em redes.</div>
    <div style="font-family:var(--mono);font-size:0.75rem;color:var(--teal);margin-bottom:8px">Pontuação: ${score}/${quizData.length * 10} pts · ${date}</div>
    <div class="cert-id">ID: ${id}</div>
  `;
  toast('Certificado gerado com sucesso! 🎓', '🏅');
});

renderQuestion();

/* =============================================
   SIMULADOR DE SENHA
============================================= */
const commonPasswords = [
  '123456','password','123456789','12345678','12345',
  '1234567','1234567890','qwerty','abc123','111111',
  '123123','admin','letmein','welcome','monkey',
  'dragon','master','login','pass','test'
];

function calcEntropy(pass) {
  let charset = 0;
  if (/[a-z]/.test(pass))       charset += 26;
  if (/[A-Z]/.test(pass))       charset += 26;
  if (/[0-9]/.test(pass))       charset += 10;
  if (/[^A-Za-z0-9]/.test(pass)) charset += 32;
  if (charset === 0) return 0;
  return Math.round(pass.length * Math.log2(charset));
}

const strengthInput = document.getElementById('strength-input');
const barFill       = document.getElementById('strength-bar-fill');
const strengthLabel = document.getElementById('strength-label');
const critLen       = document.getElementById('crit-len');
const critCase      = document.getElementById('crit-case');
const critNumSym    = document.getElementById('crit-numsym');
const critCommon    = document.getElementById('crit-nocommon');

function updateCrit(el, ok) {
  el.setAttribute('data-ok', ok ? 'true' : 'false');
  const text    = el.textContent.slice(2);
  el.textContent = (ok ? '✓ ' : '✗ ') + text;
}

if (strengthInput) {
  strengthInput.addEventListener('input', function () {
    const senha      = this.value;
    const temLen     = senha.length >= 8;
    const temCase    = /[A-Z]/.test(senha) && /[a-z]/.test(senha);
    const temNumSym  = /[0-9]/.test(senha) && /[^A-Za-z0-9]/.test(senha);
    const notCommon  = !commonPasswords.includes(senha.toLowerCase());

    updateCrit(critLen,    temLen);
    updateCrit(critCase,   temCase);
    updateCrit(critNumSym, temNumSym);
    updateCrit(critCommon, notCommon);

    const pontos  = [temLen, temCase, temNumSym, notCommon].filter(Boolean).length;
    const entropy = calcEntropy(senha);
    document.getElementById('entropy-display').textContent = `entropia: ${entropy} bits`;

    barFill.style.width = (pontos / 4 * 100) + '%';

    if (!senha.length) {
      strengthLabel.textContent  = 'Aguardando...';
      strengthLabel.style.color  = 'rgba(200,221,232,0.3)';
      barFill.style.background   = 'rgba(255,255,255,0.06)';
    } else if (pontos <= 1) {
      strengthLabel.textContent  = 'Muito Fraca 🔴';
      strengthLabel.style.color  = 'var(--red)';
      barFill.style.background   = 'var(--red)';
    } else if (pontos === 2) {
      strengthLabel.textContent  = 'Fraca 🟠';
      strengthLabel.style.color  = '#ff7043';
      barFill.style.background   = '#ff7043';
    } else if (pontos === 3) {
      strengthLabel.textContent  = 'Média 🟡';
      strengthLabel.style.color  = 'var(--amber)';
      barFill.style.background   = 'var(--amber)';
    } else {
      strengthLabel.textContent  = 'Forte 🟢';
      strengthLabel.style.color  = 'var(--green)';
      barFill.style.background   = 'var(--green)';
    }
  });
}

// Toggle visibilidade senha
document.getElementById('toggle-pass-btn').addEventListener('click', () => {
  const inp  = document.getElementById('strength-input');
  inp.type   = inp.type === 'password' ? 'text' : 'password';
});

// Gerador — toggles de tipo
document.querySelectorAll('.gen-toggle').forEach(t => {
  t.addEventListener('click', () => t.classList.toggle('active'));
});

// Gerador — slider de comprimento
const genSlider = document.getElementById('gen-len-slider');
const genLenVal = document.getElementById('gen-len-val');
genSlider.addEventListener('input', () => { genLenVal.textContent = genSlider.value; });

document.getElementById('btn-generate').addEventListener('click', () => {
  const upper = document.querySelector('[data-type="upper"]').classList.contains('active');
  const lower = document.querySelector('[data-type="lower"]').classList.contains('active');
  const nums  = document.querySelector('[data-type="numbers"]').classList.contains('active');
  const syms  = document.querySelector('[data-type="symbols"]').classList.contains('active');

  let chars = '';
  if (upper) chars += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  if (lower) chars += 'abcdefghijklmnopqrstuvwxyz';
  if (nums)  chars += '0123456789';
  if (syms)  chars += '!@#$%^&*()_+-=[]{}|;:,.<>?';
  if (!chars) { toast('Selecione pelo menos um tipo!', '⚠️', 2000); return; }

  const len  = parseInt(genSlider.value);
  let pass   = '';
  for (let i = 0; i < len; i++) pass += chars[Math.floor(Math.random() * chars.length)];

  document.getElementById('gen-pass-text').textContent = pass;

  // Preenche automaticamente o analisador
  strengthInput.value = pass;
  strengthInput.dispatchEvent(new Event('input'));
  toast('Senha gerada! 🔑', '✅', 2000);
});

document.getElementById('btn-copy').addEventListener('click', () => {
  const pass = document.getElementById('gen-pass-text').textContent;
  if (pass === '—') return;
  navigator.clipboard.writeText(pass).then(() => toast('Senha copiada!', '📋', 2000));
});

/* =============================================
   CONTATO
============================================= */
document.getElementById('btn-contact').addEventListener('click', () => {
  const name  = document.getElementById('contact-name').value.trim();
  const email = document.getElementById('contact-email').value.trim();
  const msg   = document.getElementById('contact-msg').value.trim();
  if (!name || !email || !msg) { toast('Preencha todos os campos!', '⚠️', 2500); return; }
  toast(`Mensagem de ${name} enviada! Obrigado.`, '✉️', 3000);
  document.getElementById('contact-name').value = '';
  document.getElementById('contact-email').value = '';
  document.getElementById('contact-msg').value   = '';
});

/* =============================================
   ACTIVE NAV ON SCROLL
============================================= */
const sections = document.querySelectorAll('section[id], div[id="modulos"]');
const navLinks = document.querySelectorAll('nav a');

const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      navLinks.forEach(a => {
        a.style.color = '';
        if (a.getAttribute('href') === '#' + entry.target.id) {
          a.style.color = 'var(--teal)';
        }
      });
    }
  });
}, { threshold: 0.3 });

sections.forEach(s => observer.observe(s));

/* =============================================
   CARD REVEAL ON SCROLL
============================================= */
const cards       = document.querySelectorAll('.card');
const cardObserver = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.style.animation = 'fadeIn 0.6s ease both';
      cardObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.1 });

cards.forEach(c => cardObserver.observe(c));
