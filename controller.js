/**
 * controller.js - Ponte entre o HTML e o Banco de Dados (IndexedDB)
 * 
 * Este arquivo escuta eventos do formulário de contato, captura os dados,
 * salva no banco de dados através do db.js e exibe os dados salvos.
 */

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
                data: new Promise(resolve => resolve(new Date().toLocaleString())).then(d => d), // Apenas para exemplo de Promise se necessário, mas simplificando:
                dataCriacao: new Date().toLocaleString(),
                categoria: 'Contato Geral'
            };

            try {
                // Envia para a função de salvar no db.js
                await adicionarItem(novoContato);
                
                // Limpa o formulário após salvar
                contactName.value = '';
                contactEmail.value = '';
                contactMsg.value = '';

                alert('Mensagem enviada e salva com sucesso no banco de dados local!');
                
                // Atualiza a listagem na tela
                listarContatos();
            } catch (error) {
                console.error('Erro ao processar o formulário:', error);
                alert('Ocorreu um erro ao salvar os dados. Verifique o console.');
            }
        });
    }
});

/**
 * Busca os dados salvos no IndexedDB e exibe no console e opcionalmente na tela.
 */
async function listarContatos() {
    try {
        const contatos = await buscarItens();
        console.log('--- Lista de Contatos Salvos no IndexedDB ---');
        console.table(contatos);

        // Opcional: Se quiser exibir em algum lugar do HTML, podemos criar um container dinamicamente
        exibirContatosNaTela(contatos);
    } catch (error) {
        console.error('Erro ao listar contatos:', error);
    }
}

/**
 * Cria um elemento visual para mostrar os contatos salvos (para fins de teste local)
 */
function exibirContatosNaTela(contatos) {
    let container = document.getElementById('contatos-salvos-container');
    
    // Se o container não existir, cria um abaixo do formulário de contato
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
            title.textContent = 'Mensagens Salvas (LocalDB)';
            title.style.fontSize = '1rem';
            title.style.marginBottom = '10px';
            title.style.color = 'var(--accent)';
            
            container.appendChild(title);
            contatoSection.querySelector('.card-body').appendChild(container);
        }
    }

    if (container) {
        // Limpa a lista atual (exceto o título)
        const title = container.querySelector('h3');
        container.innerHTML = '';
        container.appendChild(title);

        if (contatos.length === 0) {
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
                <strong>${c.nome}</strong> (${c.dataCriacao})<br>
                <span style="opacity: 0.8;">${c.mensagem}</span>
                <button onclick="removerContato(${c.id})" style="background:none; border:none; color:#ff4d4d; cursor:pointer; font-size:0.7rem; margin-left:10px;">[Excluir]</button>
            `;
            list.appendChild(item);
        });
        container.appendChild(list);
    }
}

/**
 * Função global para permitir exclusão via atributo onclick (já que não usamos módulos)
 */
window.removerContato = async function(id) {
    if (confirm('Deseja realmente excluir esta mensagem?')) {
        await deletarItem(id);
        listarContatos();
    }
};
