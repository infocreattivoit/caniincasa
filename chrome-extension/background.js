/**
 * Caniincasa Post Creator - Background Service Worker
 * Gestisce il context menu per selezionare testo e inviarlo al sito
 */

// Crea il menu contestuale quando l'estensione viene installata
chrome.runtime.onInstalled.addListener(() => {
  chrome.contextMenus.create({
    id: 'sendToCaniincasa',
    title: 'Invia a Caniincasa.it',
    contexts: ['selection']
  });

  console.log('Caniincasa Post Creator installato');
});

// Gestisci il click sul menu contestuale
chrome.contextMenus.onClicked.addListener((info, tab) => {
  if (info.menuItemId === 'sendToCaniincasa') {
    const selectedText = info.selectionText;
    const pageUrl = tab.url;
    const pageTitle = tab.title;

    // Salva il testo selezionato nello storage per il popup
    chrome.storage.local.set({
      selectedText: selectedText,
      sourceUrl: pageUrl,
      sourceTitle: pageTitle,
      timestamp: Date.now()
    });

    // Apri il popup
    chrome.action.openPopup();
  }
});

// Listener per messaggi dal popup
chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
  if (request.action === 'getSelectedText') {
    chrome.storage.local.get(['selectedText', 'sourceUrl', 'sourceTitle'], (result) => {
      sendResponse(result);
    });
    return true; // Necessario per async response
  }
});
