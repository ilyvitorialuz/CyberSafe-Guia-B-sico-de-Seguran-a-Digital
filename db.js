/**
 * db.js - Mini Framework para IndexedDB
 * Atuando como Desenvolvedor Sênior.
 * 
 * Este arquivo fornece uma interface baseada em Promises para interagir com o IndexedDB,
 * permitindo o uso de async/await para operações de banco de dados no navegador.
 * Como o projeto roda localmente sem servidor, não utilizamos export/import.
 */

const DB_NAME = 'CyberSafeDB';
const DB_VERSION = 1;
const STORE_NAME = 'contatos';

/**
 * Inicia o banco de dados e cria a store se não existir.
 * @returns {Promise<IDBDatabase>}
 */
function iniciarBanco() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open(DB_NAME, DB_VERSION);

        request.onupgradeneeded = (event) => {
            const db = event.target.result;
            if (!db.objectStoreNames.contains(STORE_NAME)) {
                // Criamos a store com um id auto-incrementável
                db.createObjectStore(STORE_NAME, { keyPath: 'id', autoIncrement: true });
                console.log(`Store "${STORE_NAME}" criada com sucesso.`);
            }
        };

        request.onsuccess = (event) => {
            console.log('Banco de dados IndexedDB aberto com sucesso.');
            resolve(event.target.result);
        };

        request.onerror = (event) => {
            console.error('Erro ao abrir o banco de dados:', event.target.error);
            reject(event.target.error);
        };
    });
}

/**
 * Adiciona um novo item ao banco de dados.
 * @param {Object} item - O objeto a ser salvo.
 * @returns {Promise<number>} - O ID do item inserido.
 */
async function adicionarItem(item) {
    const db = await iniciarBanco();
    return new Promise((resolve, reject) => {
        const transaction = db.transaction([STORE_NAME], 'readwrite');
        const store = transaction.objectStore(STORE_NAME);
        const request = store.add(item);

        request.onsuccess = () => {
            console.log('Item adicionado ao banco:', item);
            resolve(request.result);
        };

        request.onerror = () => {
            console.error('Erro ao adicionar item:', request.error);
            reject(request.error);
        };
    });
}

/**
 * Busca todos os itens salvos na store.
 * @returns {Promise<Array>} - Lista de objetos.
 */
async function buscarItens() {
    const db = await iniciarBanco();
    return new Promise((resolve, reject) => {
        const transaction = db.transaction([STORE_NAME], 'readonly');
        const store = transaction.objectStore(STORE_NAME);
        const request = store.getAll();

        request.onsuccess = () => {
            resolve(request.result);
        };

        request.onerror = () => {
            console.error('Erro ao buscar itens:', request.error);
            reject(request.error);
        };
    });
}

/**
 * Deleta um item pelo seu ID.
 * @param {number} id - O ID do item a ser removido.
 * @returns {Promise<void>}
 */
async function deletarItem(id) {
    const db = await iniciarBanco();
    return new Promise((resolve, reject) => {
        const transaction = db.transaction([STORE_NAME], 'readwrite');
        const store = transaction.objectStore(STORE_NAME);
        const request = store.delete(id);

        request.onsuccess = () => {
            console.log(`Item com ID ${id} deletado.`);
            resolve();
        };

        request.onerror = () => {
            console.error('Erro ao deletar item:', request.error);
            reject(request.error);
        };
    });
}
