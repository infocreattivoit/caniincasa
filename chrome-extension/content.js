/**
 * Caniincasa Post Creator - Content Script
 * Gestisce interazioni nella pagina web
 */

// Listener per selezione di testo (per funzionalità future)
document.addEventListener('mouseup', () => {
  const selection = window.getSelection();
  const selectedText = selection.toString().trim();

  if (selectedText.length > 0) {
    // Il testo è stato selezionato
    // Potremmo aggiungere un pulsante fluttuante qui in futuro
  }
});

// Listener per messaggi dal background o popup
chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
  if (request.action === 'getSelection') {
    const selection = window.getSelection();
    sendResponse({ text: selection.toString() });
  }
  return true;
});
