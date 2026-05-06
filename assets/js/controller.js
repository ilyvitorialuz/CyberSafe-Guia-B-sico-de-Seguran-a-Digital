/**
 * controller.js - Ponte entre o HTML e o Backend PHP
 * 
 * Este arquivo escuta eventos do formulário de contato, captura os dados,
 * envia para o backend PHP e exibe os dados salvos vindo do servidor.
 */

const API_BASE = '/api';

document.addEventListener('DOMContentLoaded', () => {
    const btnContact = document.getElementById('btn-contact');
    const contactName = document.getElementById('contact-name');
    const contactEmail = document.getElementById('contact-email');
    const contactMsg = document.getElementById('contact-msg');

    // Inicializa a exibição dos dados salvos ao carregar a página
    listarContatos();

    if (btnContact) {
        btnContact.addEventListener('click', async (event) => {
            event.preventDefault();

            // Captura os dados do formulário
            const nome = contactName.value.trim();
            const email = contactEmail.value.trim();
            const mensagem = contactMsg.value.trim();

            // Validação simples
            if (!nome || !email || !mensagem) {
                alert('Por favor, preencha todos os campos do formulário de contato.');
                return;
            }

            // Monta o objeto para salvar
            const novoContato = {
                nome: nome,
                email: email,
                mensagem: mensagem,
                categoria: 'Contato Geral'
            };

            try {
                // Envia para o backend PHP
                const response = await fetch(`${API_BASE}/contacts`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(novoContato)
                });
                
                const result = await response.json();

                if (result.status === 'success') {
                    // Limpa o formulário após salvar
                    contactName.value = '';
                    contactEmail.value = '';
                    contactMsg.value = '';

                    alert('Mensagem enviada e salva com sucesso no servidor!');
                    
                    // Atualiza a listagem na tela
                    listarContatos();
                } else {
                    alert('Erro ao enviar: ' + result.message);
                }
            } catch (error) {
                console.error('Erro ao processar o formulário:', error);
                alert('Ocorreu um erro ao conectar com o servidor.');
            }
        });
    }
});

/**
 * Busca os dados salvos no Backend e exibe na tela.
 */
async function listarContatos() {
    try {
        const response = await fetch(`${API_BASE}/contacts`);
        const result = await response.json();
        
        if (result.status === 'success') {
            console.log('--- Lista de Contatos Salvos no Backend ---');
            console.table(result.data);
            exibirContatosNaTela(result.data);
        }
    } catch (error) {
        console.error('Erro ao listar contatos:', error);
    }
}

/**
 * Cria um elemento visual para mostrar os contatos salvos
 */
function exibirContatosNaTela(contatos) {
    let container = document.getElementById('contatos-salvos-container');
    
    if (!container) {
        const contatoSection = document.getElementById('contato');
        if (contatoSection) {
            container = document.createElement('div');
            container.id = 'contatos-salvos-container';
            container.style.marginTop = '20px';
            container.style.padding = '15px';
            container.style.background = 'rgba(255, 255, 255, 0.05)';
            container.style.borderRadius = '8px';
            container.style.border = '1px solid rgba(255, 255, 255, 0.1)';
            
            const title = document.createElement('h3');
            title.textContent = 'Mensagens Recentes (Servidor)';
            title.style.fontSize = '1rem';
            title.style.marginBottom = '10px';
            title.style.color = 'var(--accent)';
            
            container.appendChild(title);
            contatoSection.querySelector('.card-body').appendChild(container);
        }
    }

    if (container) {
        const title = container.querySelector('h3');
        container.innerHTML = '';
        container.appendChild(title);

        if (!contatos || contatos.length === 0) {
            const emptyMsg = document.createElement('p');
            emptyMsg.textContent = 'Nenhuma mensagem salva ainda.';
            emptyMsg.style.fontSize = '0.85rem';
            emptyMsg.style.opacity = '0.6';
            container.appendChild(emptyMsg);
            return;
        }

        const list = document.createElement('ul');
        list.style.listStyle = 'none';
        list.style.padding = '0';
        list.style.fontSize = '0.85rem';

        contatos.forEach(c => {
            const item = document.createElement('li');
            item.style.marginBottom = '10px';
            item.style.paddingBottom = '10px';
            item.style.borderBottom = '1px solid rgba(255,255,255,0.05)';
            item.innerHTML = `
                <strong>${c.name}</strong> (${new Date(c.created_at).toLocaleString('pt-BR')})<br>
                <span style="opacity: 0.8;">${c.message}</span>
                <button onclick="removerContato(${c.id})" style="background:none; border:none; color:#ff4d4d; cursor:pointer; font-size:0.7rem; margin-left:10px;">[Excluir]</button>
            `;
            list.appendChild(item);
        });
        container.appendChild(list);
    }
}

window.removerContato = async function(id) {
    if (confirm('Deseja realmente excluir esta mensagem?')) {
        try {
            const response = await fetch(`${API_BASE}/contacts/${id}`, {
                method: 'DELETE'
            });
            const result = await response.json();
            if (result.status === 'success') {
                listarContatos();
            } else {
                alert('Erro ao excluir: ' + result.message);
            }
        } catch (error) {
            console.error('Erro ao excluir:', error);
        }
    }
};

