function generateBackground() {
    const globalElement = document.createElement('div');
    globalElement.id = 'cookiesBackground';
    globalElement.style.width = '100%';
    globalElement.style.height = '70px';
    globalElement.style.position = 'fixed';
    globalElement.style.bottom = '0';
    globalElement.style.left = '0';
    globalElement.style.backgroundColor = 'rgba(128, 128, 128, 0.7)';
    globalElement.style.zIndex = '1000';

    document.body.appendChild(globalElement);
}

function createCookieConsentButtons() {
    // Tworzenie elementu kontenera
    const container = document.createElement('div');
    container.style.display = 'flex';
    container.style.justifyContent = 'space-between';
    container.style.alignItems = 'center';
    container.style.height = '100%';
    container.style.padding = '10px';
    container.style.width = '100%'; // Zapewnienie pełnej szerokości

    // Tworzenie tekstu informującego
    const infoText = document.createElement('div');
    infoText.textContent = 'Ta strona używa plików cookie.';
    infoText.style.color = 'white'; // Kolor tekstu
    infoText.style.flex = '1'; // Pozwolenie na rozciąganie

    // Tworzenie przycisku "Zgadzam się"
    const agreeButton = document.createElement('button');
    agreeButton.className = 'btn btn-primary'; // Klasy Bootstrap dla niebieskiego przycisku
    agreeButton.textContent = 'Akceptuj wszystko';
    agreeButton.style.marginRight = '10px'; // Dodanie odstępu między przyciskami
    agreeButton.addEventListener('click', () => {
        setCookieConsent(true);
    });

    // Tworzenie przycisku "Zamknij"
    const closeButton = document.createElement('button');
    closeButton.className = 'btn btn-danger'; // Klasy Bootstrap dla czerwonego przycisku
    closeButton.textContent = 'Odrzuć wszystko';
    closeButton.addEventListener('click', () => {
        setCookieConsent(false);
    });

    // Dodanie elementów do kontenera
    container.appendChild(infoText);
    container.appendChild(closeButton);
    container.appendChild(agreeButton);

    // Znalezienie elementu cookiesBackground i dodanie kontenera jako jego dziecko
    const cookiesBackground = document.getElementById('cookiesBackground');
    if (cookiesBackground) {
        cookiesBackground.style.display = 'flex';
        cookiesBackground.style.alignItems = 'center'; // Wyśrodkowanie w pionie
        cookiesBackground.style.justifyContent = 'center'; // Wyśrodkowanie w poziomie
        cookiesBackground.appendChild(container);
    } else {
        console.error('Element cookiesBackground nie istnieje');
    }
}

function setCookieConsent(consent) {
    localStorage.setItem('acceptCookie', consent);
    const cookiesBackground = document.getElementById('cookiesBackground');
    if (cookiesBackground) {
        cookiesBackground.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const acceptCookie = localStorage.getItem('acceptCookie');
    if (!acceptCookie) {
        generateBackground();
        createCookieConsentButtons();
    }
});
