# 🏗️ Organização e Status Técnico do Projeto CyberSafe

O projeto CyberSafe foi estruturado seguindo um protótipo do padrão **MVC (Model-View-Controller)**. Essa arquitetura foi escolhida para facilitar o entendimento do fluxo de dados e preparar o sistema para uma futura migração para um backend real, separando as responsabilidades de forma clara:

*   **Model (`db.js`)**: Gerencia a persistência dos dados (atualmente via IndexedDB).
*   **View (`index.html`, `style.css`)**: Define a interface e a experiência visual do usuário.
*   **Controller (`controller.js`, `script.js`)**: Atua como o cérebro do sistema, processando as interações da View e comunicando-se com o Model.

Embora essa estrutura forneça uma base sólida para escalabilidade e manutenção, a análise atual do protótipo revelou os seguintes pontos de atenção que precisam ser resolvidos para garantir a integridade do sistema:

---

## ⚠️ Principais Problemas e Bloqueios Técnicos

1. **Fragmentação na Persistência de Dados**: O projeto apresenta uma inconsistência no armazenamento. Enquanto a autenticação e o progresso do usuário utilizam o `localStorage` (dentro de `script.js`), o formulário de contato utiliza o `IndexedDB` (via `db.js`). Essa falta de padronização dificulta a sincronização e a gestão centralizada dos dados do usuário.

2. **Funcionalidades Críticas Não Implementadas**: Apesar de estarem listadas como requisitos de alta prioridade (`requisitos.md`), as funcionalidades de **Recuperação de Senha (RF03)** e **Ajuste de Acessibilidade (RF12)** ainda não possuem lógica funcional, existindo apenas como elementos visuais ou descritivos.

3. **Ausência de Biblioteca para Exportação de PDF**: Para cumprir o requisito **RF09 (Emissão de Certificado)**, é necessária a integração de uma biblioteca externa (como `jsPDF` ou `html2canvas`). Atualmente, o botão de emissão está presente na interface, mas não possui a lógica necessária para gerar e baixar o arquivo de forma funcional.
