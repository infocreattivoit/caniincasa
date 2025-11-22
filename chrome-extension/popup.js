/**
 * Caniincasa Post Creator - Popup Script
 * Gestisce l'interfaccia e la comunicazione con WordPress REST API
 */

const SITE_URL = 'https://www.caniincasa.it';
const WP_API_URL = `${SITE_URL}/wp-json/wp/v2`;

// Elementi DOM
const elements = {
    authSection: document.getElementById('authSection'),
    postSection: document.getElementById('postSection'),
    wpUsername: document.getElementById('wpUsername'),
    wpPassword: document.getElementById('wpPassword'),
    saveAuth: document.getElementById('saveAuth'),
    authStatus: document.getElementById('authStatus'),
    postTitle: document.getElementById('postTitle'),
    postContent: document.getElementById('postContent'),
    postStatus: document.getElementById('postStatus'),
    postCategory: document.getElementById('postCategory'),
    includeSource: document.getElementById('includeSource'),
    sourceInfo: document.getElementById('sourceInfo'),
    sourceTitle: document.getElementById('sourceTitle'),
    sourceUrl: document.getElementById('sourceUrl'),
    aiPrompt: document.getElementById('aiPrompt'),
    createPost: document.getElementById('createPost'),
    clearForm: document.getElementById('clearForm'),
    postStatusDiv: document.getElementById('postStatus'),
    toggleAuth: document.getElementById('toggleAuth'),
    logoutBtn: document.getElementById('logoutBtn'),
    textLength: document.getElementById('textLength')
};

// State
let selectedData = {
    text: '',
    sourceUrl: '',
    sourceTitle: ''
};

let credentials = {
    username: '',
    password: ''
};

/**
 * Inizializzazione
 */
document.addEventListener('DOMContentLoaded', async () => {
    await loadCredentials();
    await loadSelectedText();
    setupEventListeners();

    if (credentials.username && credentials.password) {
        showPostSection();
        await loadCategories();
    }
});

/**
 * Carica credenziali salvate
 */
async function loadCredentials() {
    return new Promise((resolve) => {
        chrome.storage.sync.get(['wpUsername', 'wpPassword'], (result) => {
            if (result.wpUsername && result.wpPassword) {
                credentials.username = result.wpUsername;
                credentials.password = result.wpPassword;
                elements.wpUsername.value = result.wpUsername;
                elements.logoutBtn.style.display = 'inline-block';
            }
            resolve();
        });
    });
}

/**
 * Carica testo selezionato
 */
async function loadSelectedText() {
    return new Promise((resolve) => {
        chrome.runtime.sendMessage({ action: 'getSelectedText' }, (response) => {
            if (response && response.selectedText) {
                selectedData.text = response.selectedText;
                selectedData.sourceUrl = response.sourceUrl;
                selectedData.sourceTitle = response.sourceTitle;

                elements.postContent.value = response.selectedText;
                elements.textLength.textContent = response.selectedText.length;

                if (response.sourceUrl) {
                    elements.sourceTitle.textContent = response.sourceTitle || 'Titolo non disponibile';
                    elements.sourceUrl.textContent = response.sourceUrl;
                    elements.sourceUrl.href = response.sourceUrl;
                }
            }
            resolve();
        });
    });
}

/**
 * Setup event listeners
 */
function setupEventListeners() {
    elements.saveAuth.addEventListener('click', saveCredentials);
    elements.createPost.addEventListener('click', createPost);
    elements.clearForm.addEventListener('click', clearForm);
    elements.toggleAuth.addEventListener('click', toggleAuthSection);
    elements.logoutBtn.addEventListener('click', logout);
    elements.includeSource.addEventListener('change', toggleSourceInfo);
    elements.postContent.addEventListener('input', updateTextLength);
}

/**
 * Salva credenziali
 */
async function saveCredentials() {
    const username = elements.wpUsername.value.trim();
    const password = elements.wpPassword.value.trim();

    if (!username || !password) {
        showStatus(elements.authStatus, 'Inserisci username e password', 'error');
        return;
    }

    // Test credenziali
    elements.saveAuth.disabled = true;
    elements.saveAuth.innerHTML = '<span class="loading"></span>Verifica...';

    try {
        const response = await fetch(`${WP_API_URL}/users/me`, {
            headers: {
                'Authorization': 'Basic ' + btoa(username + ':' + password)
            }
        });

        if (response.ok) {
            credentials.username = username;
            credentials.password = password;

            chrome.storage.sync.set({
                wpUsername: username,
                wpPassword: password
            }, () => {
                showStatus(elements.authStatus, '✅ Credenziali salvate correttamente!', 'success');
                elements.logoutBtn.style.display = 'inline-block';

                setTimeout(() => {
                    showPostSection();
                    loadCategories();
                }, 1500);
            });
        } else {
            showStatus(elements.authStatus, '❌ Credenziali non valide. Verifica username e Application Password.', 'error');
        }
    } catch (error) {
        showStatus(elements.authStatus, '❌ Errore di connessione: ' + error.message, 'error');
    } finally {
        elements.saveAuth.disabled = false;
        elements.saveAuth.textContent = 'Salva Credenziali';
    }
}

/**
 * Carica categorie WordPress
 */
async function loadCategories() {
    try {
        const response = await fetch(`${WP_API_URL}/categories?per_page=100`);
        const categories = await response.json();

        elements.postCategory.innerHTML = '<option value="">Senza categoria</option>';

        categories.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat.id;
            option.textContent = cat.name;
            elements.postCategory.appendChild(option);
        });
    } catch (error) {
        console.error('Errore caricamento categorie:', error);
        elements.postCategory.innerHTML = '<option value="">Errore caricamento categorie</option>';
    }
}

/**
 * Crea post
 */
async function createPost() {
    const title = elements.postTitle.value.trim();
    const content = elements.postContent.value.trim();
    const status = elements.postStatus.value;
    const categoryId = elements.postCategory.value;
    const includeSource = elements.includeSource.checked;
    const aiPrompt = elements.aiPrompt.value.trim();

    if (!title) {
        showStatus(elements.postStatusDiv, '❌ Inserisci un titolo per il post', 'error');
        return;
    }

    if (!content) {
        showStatus(elements.postStatusDiv, '❌ Inserisci un contenuto per il post', 'error');
        return;
    }

    // Prepara il contenuto
    let finalContent = content;

    // Aggiungi nota AI se presente
    if (aiPrompt) {
        finalContent = `<!-- AI Prompt: ${aiPrompt} -->\n\n${content}`;
    }

    // Aggiungi fonte se richiesto
    if (includeSource && selectedData.sourceUrl) {
        finalContent += `\n\n<hr>\n<p><strong>Fonte:</strong> <a href="${selectedData.sourceUrl}" target="_blank" rel="noopener">${selectedData.sourceTitle || selectedData.sourceUrl}</a></p>`;
    }

    // Prepara i dati del post
    const postData = {
        title: title,
        content: finalContent,
        status: status
    };

    if (categoryId) {
        postData.categories = [parseInt(categoryId)];
    }

    // Crea il post
    elements.createPost.disabled = true;
    elements.createPost.innerHTML = '<span class="loading"></span>Creazione...';

    try {
        const response = await fetch(`${WP_API_URL}/posts`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Basic ' + btoa(credentials.username + ':' + credentials.password)
            },
            body: JSON.stringify(postData)
        });

        if (response.ok) {
            const post = await response.json();
            const postUrl = post.link;
            const editUrl = `${SITE_URL}/wp-admin/post.php?post=${post.id}&action=edit`;

            showStatus(
                elements.postStatusDiv,
                `✅ Post ${status === 'publish' ? 'pubblicato' : 'salvato come bozza'}!<br>
                <a href="${postUrl}" target="_blank">Visualizza post</a> |
                <a href="${editUrl}" target="_blank">Modifica</a>`,
                'success'
            );

            // Pulisci il form dopo 3 secondi
            setTimeout(() => {
                clearForm();
            }, 3000);
        } else {
            const error = await response.json();
            showStatus(elements.postStatusDiv, '❌ Errore creazione post: ' + (error.message || 'Errore sconosciuto'), 'error');
        }
    } catch (error) {
        showStatus(elements.postStatusDiv, '❌ Errore di connessione: ' + error.message, 'error');
    } finally {
        elements.createPost.disabled = false;
        elements.createPost.textContent = '📝 Crea Post';
    }
}

/**
 * Pulisci form
 */
function clearForm() {
    elements.postTitle.value = '';
    elements.postContent.value = '';
    elements.postStatus.value = 'draft';
    elements.postCategory.value = '';
    elements.includeSource.checked = false;
    elements.aiPrompt.value = '';
    elements.sourceInfo.style.display = 'none';
    elements.postStatusDiv.className = 'status';
    elements.postStatusDiv.style.display = 'none';
    elements.textLength.textContent = '0';

    // Ricarica testo selezionato
    loadSelectedText();
}

/**
 * Toggle auth section
 */
function toggleAuthSection() {
    if (elements.authSection.style.display === 'none') {
        elements.authSection.style.display = 'block';
        elements.postSection.style.display = 'none';
    } else {
        elements.authSection.style.display = 'none';
        if (credentials.username && credentials.password) {
            elements.postSection.style.display = 'block';
        }
    }
}

/**
 * Show post section
 */
function showPostSection() {
    elements.authSection.style.display = 'none';
    elements.postSection.style.display = 'block';
}

/**
 * Toggle source info
 */
function toggleSourceInfo() {
    elements.sourceInfo.style.display = elements.includeSource.checked ? 'block' : 'none';
}

/**
 * Update text length
 */
function updateTextLength() {
    elements.textLength.textContent = elements.postContent.value.length;
}

/**
 * Logout
 */
function logout() {
    if (confirm('Sei sicuro di voler rimuovere le credenziali salvate?')) {
        chrome.storage.sync.remove(['wpUsername', 'wpPassword'], () => {
            credentials.username = '';
            credentials.password = '';
            elements.wpUsername.value = '';
            elements.wpPassword.value = '';
            elements.logoutBtn.style.display = 'none';
            elements.postSection.style.display = 'none';
            elements.authSection.style.display = 'block';
            showStatus(elements.authStatus, 'Logout effettuato', 'info');
        });
    }
}

/**
 * Show status message
 */
function showStatus(element, message, type) {
    element.className = `status ${type}`;
    element.innerHTML = message;
    element.style.display = 'block';

    // Auto-hide info/success messages after 5 seconds
    if (type === 'info' || type === 'success') {
        setTimeout(() => {
            element.style.display = 'none';
        }, 5000);
    }
}
