<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>CyberSafe – Segurança Digital</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;600;700;800&family=IBM+Plex+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- CURSOR -->
<div id="cursor"></div>
<div id="cursor-ring"></div>

<!-- PARTICLES -->
<canvas id="particles"></canvas>

<!-- TOAST CONTAINER -->
<div id="toast-container"></div>

<!-- MODULE MODAL -->
<div class="module-modal-overlay" id="module-overlay">
  <div class="module-modal" id="module-modal">
    <div class="modal-header">
      <span class="modal-title" id="modal-title">Módulo</span>
      <button class="modal-close" id="modal-close">✕</button>
    </div>
    <div class="modal-body" id="modal-body"></div>
    <div class="modal-footer">
      <button class="btn btn-primary" style="flex:1" onclick="document.getElementById('module-overlay').classList.remove('active')">Fechar</button>
      <button class="btn btn-outline" style="flex:1" onclick="markModuleComplete()">✓ Marcar como Concluído</button>
    </div>
  </div>
</div>

<!-- HEADER -->
<header>
  <div class="header-glow"></div>
  <div class="badge">// PROJETO EDUCATIVO 2026</div>
  <h1><span class="cyber" data-text="Cyber">Cyber</span>Safe</h1>
  <p>Guia de Segurança Digital</p>
  <div class="header-stats">
    <div class="stat-item">
      <span class="stat-num" id="stat-users">2,847</span>
      <span class="stat-label">Usuários</span>
    </div>
    <div class="stat-item">
      <span class="stat-num">6</span>
      <span class="stat-label">Módulos</span>
    </div>
    <div class="stat-item">
      <span class="stat-num" id="stat-threats">1,204</span>
      <span class="stat-label">Ameaças bloqueadas</span>
    </div>
    <div class="stat-item">
      <span class="stat-num">24/7</span>
      <span class="stat-label">Proteção</span>
    </div>
  </div>
</header>

<!-- NAV -->
<nav>
  <div class="nav-inner">
    <div class="nav-logo">Cyber<span>Safe</span></div>
    <div class="nav-links">
      <a href="#login">Login</a>
      <a href="#modulos">Módulos</a>
      <a href="#quiz">Quiz</a>
      <a href="#ferramentas">Senha</a>
      <a href="#ameacas">Ameaças</a>
      <a href="#contato">Contato</a>
    </div>
    <div class="nav-status">
      <div class="status-dot"></div>
      SISTEMA ONLINE
    </div>
  </div>
</nav>

<main>

  <!-- TICKER -->
  <div class="ticker-wrap">
    <div class="ticker-label">▶ ALERTA</div>
    <div class="ticker-scroll">
      <div class="ticker-inner">
        ⚠ Phishing em alta — verifique remetentes de e-mail &nbsp;|&nbsp;
        🔒 Atualize seu sistema operacional regularmente &nbsp;|&nbsp;
        🛡 Use autenticação em dois fatores em todas as contas &nbsp;|&nbsp;
        🔑 Nunca reutilize senhas entre serviços diferentes &nbsp;|&nbsp;
        📡 Evite redes Wi-Fi públicas sem VPN &nbsp;|&nbsp;
        💾 Faça backups periódicos dos seus dados importantes &nbsp;|&nbsp;
        🧩 Mantenha seus aplicativos sempre atualizados
      </div>
    </div>
  </div>

  <!-- LOGIN / CADASTRO -->
  <section id="login" class="card" style="scroll-margin-top:70px">
    <div class="card-header">
      <div class="card-icon">👤</div>
      <h2>Gestão de Usuários</h2>
      <span class="card-meta">AUTH v2.0</span>
    </div>
    <div class="card-body">
      <div id="session-bar">
        <div class="session-avatar" id="session-avatar">😊</div>
        <div class="session-info">
          <div class="session-name" id="session-name">—</div>
          <div class="session-status">● Sessão ativa</div>
        </div>
        <button id="btn-logout">Sair</button>
      </div>
      <div id="auth-forms">
        <div class="form-grid">
          <div class="form-box">
            <h3>Criar Conta</h3>
            <div class="field">
              <label for="reg-name">Nome completo</label>
              <input id="reg-name" type="text" placeholder="Seu nome">
            </div>
            <div class="field">
              <label for="reg-email">E-mail</label>
              <input id="reg-email" type="email" placeholder="seu@email.com">
            </div>
            <div class="field">
              <label for="reg-pass">Senha</label>
              <input id="reg-pass" type="password" placeholder="Crie uma senha forte">
            </div>
            <button id="btn-cadastro" class="btn btn-primary" style="margin-bottom:8px">Criar Conta →</button>
            <p id="msg-cadastro" class="form-msg"></p>
          </div>
          <div class="form-box">
            <h3>Entrar</h3>
            <div class="field">
              <label for="login-email">E-mail</label>
              <input id="login-email" type="email" placeholder="seu@email.com">
            </div>
            <div class="field">
              <label for="login-pass">Senha</label>
              <input id="login-pass" type="password" placeholder="••••••••">
            </div>
            <div class="spacer"></div>
            <button id="btn-login" class="btn btn-primary" style="margin-bottom:8px">Entrar →</button>
            <p id="msg-login" class="form-msg"></p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- MÓDULOS -->
  <div class="section-label" id="modulos">
    <span class="tag">// MÓDULOS</span>
    <div class="line"></div>
  </div>

  <div class="card" style="margin-bottom:32px">
    <div class="card-header">
      <div class="card-icon">📚</div>
      <h2>Trilha de Aprendizado</h2>
      <span class="card-meta">6 MÓDULOS</span>
    </div>
    <div class="card-body">
      <div class="modules-grid" id="modules-grid">
        <!-- Injetado via script.js -->
      </div>
    </div>
  </div>

  <!-- QUIZ -->
  <div class="section-label" id="quiz">
    <span class="tag">// QUIZ</span>
    <div class="line"></div>
  </div>

  <section class="card" style="scroll-margin-top:70px">
    <div class="card-header">
      <div class="card-icon">🧠</div>
      <h2>Quiz Interativo</h2>
      <span class="card-meta">10 PERGUNTAS</span>
    </div>
    <div class="card-body">
      <div class="quiz-container">
        <div class="quiz-header">
          <span class="quiz-prog-label" id="quiz-prog-label">1 / 10</span>
          <div class="quiz-prog-bar-track">
            <div class="quiz-prog-bar-fill" id="quiz-prog-fill" style="width:10%"></div>
          </div>
          <span class="quiz-score-badge" id="quiz-score-badge">★ 0 pts</span>
        </div>
        <div class="question-card slide-in" id="question-card">
          <div class="question-num" id="question-num">PERGUNTA 01</div>
          <p class="question-text" id="question-text">—</p>
          <div id="options-list"></div>
        </div>
        <div class="quiz-feedback" id="quiz-feedback"></div>
        <div class="quiz-actions">
          <button id="btn-responder" class="btn btn-primary" style="flex:2">Confirmar Resposta</button>
          <button id="btn-next" class="btn btn-outline" style="flex:1" disabled>Próxima →</button>
        </div>
        <div id="quiz-result">
          <span class="result-emoji" id="result-emoji">🎉</span>
          <h3 id="result-title">Parabéns!</h3>
          <p id="result-text">Você completou o quiz.</p>
          <span class="result-score" id="result-score">0 / 100</span>
          <button id="btn-certificado" class="btn btn-accent" style="max-width:280px;margin:0 auto 12px">🏆 Gerar Certificado</button>
          <br>
          <button id="btn-reiniciar" class="btn btn-outline" style="max-width:280px;margin:0 auto">↺ Tentar Novamente</button>
          <div id="cert-preview"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- SIMULADOR DE SENHA -->
  <div class="section-label" id="ferramentas">
    <span class="tag">// FERRAMENTAS</span>
    <div class="line"></div>
  </div>

  <section class="card" style="scroll-margin-top:70px">
    <div class="card-header">
      <div class="card-icon">🛡️</div>
      <h2>Simulador & Gerador de Senha</h2>
      <span class="card-meta">ENTROPY METER</span>
    </div>
    <div class="card-body">
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:32px">
        <div class="strength-wrap">
          <div class="strength-top">
            <span style="font-family:var(--mono);font-size:0.72rem;color:rgba(200,221,232,0.4);letter-spacing:0.1em;text-transform:uppercase">Analisador</span>
          </div>
          <div class="pass-input-wrap">
            <input id="strength-input" type="password" placeholder="Teste sua senha aqui...">
            <button class="toggle-pass" id="toggle-pass-btn">👁</button>
          </div>
          <div class="strength-bar-track">
            <div id="strength-bar-fill" class="strength-bar-fill"></div>
          </div>
          <div class="strength-top">
            <div id="strength-label" class="strength-label" style="color:rgba(200,221,232,0.3)">Aguardando...</div>
            <div id="entropy-display">entropia: —</div>
          </div>
          <ul id="strength-criteria" class="strength-criteria">
            <li id="crit-len"      data-ok="false">✗ 8+ caracteres</li>
            <li id="crit-case"     data-ok="false">✗ Maiúsc. e minúsc.</li>
            <li id="crit-numsym"   data-ok="false">✗ Número e símbolo</li>
            <li id="crit-nocommon" data-ok="false">✗ Não é senha comum</li>
          </ul>
        </div>
        <div>
          <div style="font-family:var(--mono);font-size:0.72rem;color:rgba(200,221,232,0.4);letter-spacing:0.1em;text-transform:uppercase;margin-bottom:14px">Gerador</div>
          <div class="pass-generator">
            <h4>Incluir</h4>
            <div class="gen-options">
              <button class="gen-toggle active" data-type="upper">ABC</button>
              <button class="gen-toggle active" data-type="lower">abc</button>
              <button class="gen-toggle active" data-type="numbers">123</button>
              <button class="gen-toggle active" data-type="symbols">!@#</button>
            </div>
            <div class="gen-length">
              <label>Tamanho</label>
              <input type="range" id="gen-len-slider" min="8" max="32" value="16">
              <span id="gen-len-val">16</span>
            </div>
            <div id="generated-pass"><span id="gen-pass-text">—</span><button id="btn-copy">📋</button></div>
            <button class="btn btn-primary" id="btn-generate">⚡ Gerar Senha</button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- AMEAÇAS -->
  <div class="section-label" id="ameacas">
    <span class="tag">// AMEAÇAS</span>
    <div class="line"></div>
  </div>

  <section class="card" style="scroll-margin-top:70px">
    <div class="card-header">
      <div class="card-icon">🚨</div>
      <h2>Monitor de Ameaças</h2>
      <span class="card-meta">THREAT INTEL</span>
    </div>
    <div class="card-body">
      <div class="threat-grid">
        <div class="threat-item lvl-high">
          <span class="threat-icon">🎣</span>
          <div class="threat-name">Phishing</div>
          <div class="threat-desc">E-mails falsos imitando bancos, serviços e empresas para roubar credenciais.</div>
          <span class="threat-badge">ALTO RISCO</span>
        </div>
        <div class="threat-item lvl-high">
          <span class="threat-icon">🦠</span>
          <div class="threat-name">Ransomware</div>
          <div class="threat-desc">Malware que criptografa seus arquivos e exige pagamento para liberar o acesso.</div>
          <span class="threat-badge">ALTO RISCO</span>
        </div>
        <div class="threat-item lvl-medium">
          <span class="threat-icon">📡</span>
          <div class="threat-name">Ataque Man-in-the-Middle</div>
          <div class="threat-desc">Interceptação de comunicações em redes inseguras, especialmente Wi-Fi público.</div>
          <span class="threat-badge">MÉDIO RISCO</span>
        </div>
        <div class="threat-item lvl-medium">
          <span class="threat-icon">🔓</span>
          <div class="threat-name">Força Bruta</div>
          <div class="threat-desc">Tentativas automatizadas de adivinhar senhas testando milhões de combinações.</div>
          <span class="threat-badge">MÉDIO RISCO</span>
        </div>
        <div class="threat-item lvl-low">
          <span class="threat-icon">🍪</span>
          <div class="threat-name">Session Hijacking</div>
          <div class="threat-desc">Roubo de cookies de sessão para acessar contas sem precisar de senha.</div>
          <span class="threat-badge">BAIXO RISCO</span>
        </div>
        <div class="threat-item lvl-medium">
          <span class="threat-icon">💉</span>
          <div class="threat-name">SQL Injection</div>
          <div class="threat-desc">Inserção de código malicioso em formulários para manipular bancos de dados.</div>
          <span class="threat-badge">MÉDIO RISCO</span>
        </div>
      </div>
    </div>
  </section>

  <!-- DICAS RÁPIDAS -->
  <section class="card">
    <div class="card-header">
      <div class="card-icon">💡</div>
      <h2>Dicas Rápidas</h2>
    </div>
    <div class="card-body">
      <div class="tips-grid">
        <div class="tip-item">
          <span class="tip-icon">🔐</span>
          <div class="tip-text">
            <h4>Use 2FA sempre</h4>
            <p>Autenticação de dois fatores bloqueia 99,9% dos ataques automatizados.</p>
          </div>
        </div>
        <div class="tip-item">
          <span class="tip-icon">🔄</span>
          <div class="tip-text">
            <h4>Atualize tudo</h4>
            <p>Sistemas desatualizados têm vulnerabilidades conhecidas que atacantes exploram.</p>
          </div>
        </div>
        <div class="tip-item">
          <span class="tip-icon">🗂️</span>
          <div class="tip-text">
            <h4>Gerenciador de senhas</h4>
            <p>Use ferramentas como Bitwarden ou 1Password para senhas únicas em cada conta.</p>
          </div>
        </div>
        <div class="tip-item">
          <span class="tip-icon">📧</span>
          <div class="tip-text">
            <h4>Verifique remetentes</h4>
            <p>Antes de clicar em links, confirme o domínio do remetente do e-mail.</p>
          </div>
        </div>
        <div class="tip-item">
          <span class="tip-icon">🌐</span>
          <div class="tip-text">
            <h4>HTTPS obrigatório</h4>
            <p>Só insira dados em sites com cadeado verde e protocolo HTTPS na URL.</p>
          </div>
        </div>
        <div class="tip-item">
          <span class="tip-icon">💾</span>
          <div class="tip-text">
            <h4>Backup 3-2-1</h4>
            <p>3 cópias dos dados, 2 mídias diferentes, 1 fora do local principal.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CONTATO -->
  <div class="section-label" id="contato">
    <span class="tag">// CONTATO</span>
    <div class="line"></div>
  </div>

  <section class="card" style="scroll-margin-top:70px">
    <div class="card-header">
      <div class="card-icon">✉️</div>
      <h2>Fale Conosco</h2>
    </div>
    <div class="card-body">
      <div class="contact-grid">
        <div class="contact-info">
          <h3>Sobre o Projeto</h3>
          <p>CyberSafe é um projeto educativo criado para disseminar boas práticas de segurança digital de forma acessível e interativa.</p>
          <div class="contact-links">
            <a href="#" class="contact-link"><span class="contact-link-icon">📧</span> contato@cybersafe.edu.br</a>
            <a href="#" class="contact-link"><span class="contact-link-icon">🌐</span> www.cybersafe.edu.br</a>
            <a href="#" class="contact-link"><span class="contact-link-icon">📱</span> @cybersafe.br</a>
          </div>
        </div>
        <div>
          <div class="field">
            <label>Seu nome</label>
            <input id="contact-name" type="text" placeholder="Nome completo">
          </div>
          <div class="field">
            <label>E-mail</label>
            <input id="contact-email" type="email" placeholder="seu@email.com">
          </div>
          <div class="field">
            <label>Mensagem</label>
            <textarea id="contact-msg" rows="4" placeholder="Sua mensagem..."></textarea>
          </div>
          <button class="btn btn-primary" id="btn-contact">Enviar Mensagem →</button>
        </div>
      </div>
    </div>
  </section>

</main>

<footer>
  <div class="footer-logo">Cyber<span>Safe</span></div>
  <div class="footer-links">
    <a href="#">Política de Privacidade</a>
    <a href="#">Termos de Uso</a>
    <a href="#">Acessibilidade</a>
  </div>
  <p>© 2026 CyberSafe — Projeto Educativo de Segurança Digital</p>
</footer>

<script src="assets/js/script.js"></script>
<script src="assets/js/controller.js"></script>
</body>
</html>
