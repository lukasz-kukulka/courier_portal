let translations = null;
let settingsContainerVisibility = false;
let preferenceCheckbox = false;
let statCheckbox = false;
let marketingCheckbox = false;
let isCookiesVisible = false; 

async function fetchTranslations() {
    try {
        const response = await fetch('cookies/translate');
        if (!response.ok) {
            throw new Error('Błąd podczas pobierania tłumaczeń');
        }
        
        const textResponse = await response.text();
        try {
            return JSON.parse(textResponse);
        } catch ( parsingError ) {
            console.error('Błąd parsowania odpowiedzi:', parsingError);
            return null;
        }
    } catch (error) {
        console.error('Wystąpił problem z pobraniem tłumaczeń:', error);
        return null;
    }
}

function makeMainBackground() { // Tworzymy element tła (overlay)
    const overlay = document.createElement('div');
    overlay.id = 'mainBackground';
    overlay.style.position = 'fixed';
    overlay.style.top = '0';
    overlay.style.left = '0';
    overlay.style.width = '100%';
    overlay.style.height = '100%';
    overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
    overlay.style.zIndex = '9900';  // Ustawienie zIndex tak, aby tło było pod prostokątem
    // Dodajemy tło do body
    document.body.appendChild( overlay );
}

function makeCookiesBackground() { // Tworzymy prostokąt na środku ekranu
    const rectangle = document.createElement('div');
    const width = window.innerWidth - 1000 < 0 ? window.innerWidth / 1.1 : window.innerWidth / ( ( window.innerWidth - 1000 ) / 1000 + 1.1 );
    console.log( width );
    rectangle.id = 'fullCookiesContainer';
    rectangle.style.width = width + 'px';
    rectangle.style.height = 'auto';
    rectangle.style.backgroundColor = '#fff';
    rectangle.style.position = 'fixed';
    rectangle.style.top = '50%';
    rectangle.style.left = '50%';
    rectangle.style.transform = 'translate(-50%, -50%)';
    rectangle.style.borderRadius = '15px';
    rectangle.style.boxShadow = '0px 4px 10px rgba(0, 0, 0, 0.2)';
    rectangle.style.zIndex = '9901';

    rectangle.classList.add( 'd-flex', 'flex-column', 'align-items-start' );
    document.body.appendChild( rectangle );
    return rectangle;
}

function createSeparator() {
    const separator = document.createElement('div');
    separator.style.width = 'calc(100% - 20px)'; // Separator na całą szerokość
    separator.style.height = '0.2px'; // Wysokość separatora
    separator.style.opacity = '0.4'; // Wysokość separatora
    separator.style.backgroundColor = 'gray'; // Kolor separatora
    separator.style.margin = '10px'; // Ustaw marginesy na 10px w pionie i auto w poziomie
    separator.style.display = 'block'; // Ustawienie wyświetlania na block, aby marginesy działały

    return separator;
}

function createToggleSwitch( id, isDisabled, isChecked, text ) {
    // Tworzymy element input typu checkbox
    const toggleSwitch = document.createElement('input');
    toggleSwitch.type = 'checkbox';
    toggleSwitch.className = 'form-check-input toggle-cookies';
    toggleSwitch.role = 'switch';
    toggleSwitch.margin = '5px';
    toggleSwitch.id = id;

    // Ustawiamy, czy ma być zablokowany
    toggleSwitch.disabled = isDisabled;

    // Ustawiamy, czy ma być domyślnie włączony
    toggleSwitch.checked = isChecked;

    // Tworzymy element label
    const label = document.createElement('label');
    label.htmlFor = id;
    label.className = 'form-check-label';
    label.textContent = text; // Tekst etykiety
    
    // Tworzymy kontener, który będzie zawierał input i label
    const container = document.createElement('div');
    container.className = 'form-check form-switch'
    container.appendChild( toggleSwitch );
    container.appendChild( label );

    return container;
}

function createToggleContainer() {
    const toggleContainer = document.createElement('div');
    toggleContainer.classList.add('d-flex', 'flex-column', 'w-100' );
    toggleContainer.style.margin = '10px';

    const nesesary = createToggleSwitch( 'necessary', true, true, translations.necessary );
    const preference = createToggleSwitch( 'preference', false, false, translations.preference );
    const stat =  createToggleSwitch( 'stat', false, false, translations.stat );
    const marketing = createToggleSwitch( 'marketing', false, false, translations.marketing );
    
    const preferenceToogle = preference.querySelector('input[type="checkbox"]');
    const statToogle = stat.querySelector('input[type="checkbox"]');
    const marketingToogle = marketing.querySelector('input[type="checkbox"]');

    preferenceToogle.addEventListener( 'change', () => {
        if( preferenceToogle.checked ) {
            preferenceCheckbox = true;
        } else {
            preferenceCheckbox = false
        }
    } );
    
    statToogle.addEventListener( 'change', () => {
        if( statToogle.checked ) {
            statCheckbox = true;
        } else {
            statCheckbox = false
        }
    } );
    
    marketingToogle.addEventListener( 'change', () => {
        if( marketingToogle.checked ) {
            marketingCheckbox = true;
        } else {
            marketingCheckbox = false
        }
    } );  
    
    toggleContainer.appendChild( nesesary );
    toggleContainer.appendChild( preference );
    toggleContainer.appendChild( stat );
    toggleContainer.appendChild( marketing );
    
    return toggleContainer;
}

function createSettingsContainer() {
    const settingsBackground = document.createElement('div');
    settingsBackground.classList.add( 'bg-light', 'rounded', 'm-3' );
    settingsBackground.style.width = 'calc( 100% - 30px )';
    const toggleContainer = createToggleContainer();
    
    const separator = createSeparator();
    const button = createButton( [ 'btn', 'btn-outline-primary', 'm-2' ], 'bi bi-floppy', translations.save );
    button.addEventListener( 'click', () => {
        setCookie( 'necessary', true, 365 );
        setCookie( 'preference', preferenceCheckbox, 365 );
        setCookie( 'stat', statCheckbox, 365 );
        setCookie( 'marketing', marketingCheckbox, 365 );
    } );
    settingsBackground.appendChild( toggleContainer );
    settingsBackground.appendChild( separator );
    settingsBackground.appendChild( button );

    settingsBackground.style.display = 'none';
    return settingsBackground;
}

function createButton( classes, iconClass, text ) {
    const button = document.createElement( 'button' );

    if ( Array.isArray(classes) ) {
        button.classList.add(...classes);
    }

    if ( iconClass ) {
        const icon = document.createElement( 'i' );
        icon.className = iconClass; 
        button.insertAdjacentElement( 'afterbegin', icon );
        
    }

    button.insertAdjacentText( 'beforeend', ' ' + text );
    return button;
}

function createButtonsContainer( settingsContainer, infoContainer ) {
    const primaryContainer = document.createElement( 'div' );
    primaryContainer.className = 'd-flex justify-content-between w-100'
    const settingButton = createButton( [ 'btn', 'btn-outline-secondary', 'm-2' ], 'bi bi-gear', translations.settings );
    settingButton.addEventListener( 'click', () => {
        settingsContainerVisibility = !settingsContainerVisibility;
        if ( settingsContainerVisibility === true ) {
            settingsContainer.style.display = 'block';
            infoContainer.style.display = 'none';
        } else {
            settingsContainer.style.display = 'none';
            infoContainer.style.display = 'block';
        }
    } );
    const secondaryContainer = document.createElement( 'div' );

    const rejectButton = createButton( [ 'btn', 'btn-outline-danger', 'm-2' ], 'bi bi-x', translations.reject );
    rejectButton.addEventListener( 'click', () => {
        setCookie( 'necessary', true, 365 );
        setCookie( 'preference', false, 365 );
        setCookie( 'stat', false, 365 );
        setCookie( 'marketing', false, 365 );
    } );

    const acceptButton = createButton( [ 'btn', 'btn-outline-success', 'm-2' ], 'bi bi-check2', translations.accept );
    acceptButton.addEventListener( 'click', () => {
        setCookie( 'necessary', true, 365 );
        setCookie( 'preference', true, 365 );
        setCookie( 'stat', true, 365 );
        setCookie( 'marketing', true, 365 );
    } );

    rejectButton.classList.add( 'align-items-end' );
    acceptButton.classList.add( 'align-items-end' );

    secondaryContainer.appendChild( rejectButton );
    secondaryContainer.appendChild( acceptButton );
    
    primaryContainer.appendChild( settingButton );
    primaryContainer.appendChild( secondaryContainer );

    return primaryContainer;
}

function createInfoContainer() {
    const info = document.createElement( 'div' );
    const text = document.createElement( 'p' );
    text.textContent = translations.info; 
    text.style.margin = '10px'

    const link = document.createElement( 'a' );
    link.textContent = translations.moreInfo;
    link.href = '/cookies/moreInfo';
    link.style.margin = '10px'

    info.appendChild( text );
    info.appendChild( link );
    return info;
}

function setVisibleCookiesFullContainer() {
    if ( isCookiesVisible ) {
        const container = document.getElementById( 'fullCookiesContainer' );
        if ( container ) { 
            container.remove();
            const background = document.getElementById( 'mainBackground' );
            if ( background ) {
                background.remove();
            }
            isCookiesVisible = false
        }
    }
    
}

function getCookie( name ) {
    const nameEQ = name + "="; // Tworzymy nazwę w formacie, który wyszukamy w ciasteczkach
    const cookiesArray = document.cookie.split(';'); // Rozdzielamy ciasteczka na tablicę
    for (let i = 0; i < cookiesArray.length; i++) {
        let cookie = cookiesArray[i];
        while (cookie.charAt(0) === ' ') cookie = cookie.substring(1, cookie.length); // Usuwamy spacje z początku
        if (cookie.indexOf(nameEQ) === 0) {
            return cookie.substring(nameEQ.length, cookie.length); // Zwracamy wartość ciasteczka
        }
    }
    return null; // Zwracamy null, jeśli ciasteczko o podanej nazwie nie istnieje
}

function setCookie(name, value, days) {
    setVisibleCookiesFullContainer();
    let expires = "";
    if (days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000)); // Dodajemy odpowiednią liczbę dni
        expires = "; expires=" + date.toUTCString(); // Ustawienie daty wygaśnięcia
    }
    
    // Sprawdzamy typ wartości i konwertujemy na string, jeśli to boolean
    if (typeof value === 'boolean') {
        value = value ? 'true' : 'false'; // Konwersja na string
    }
    
    document.cookie = name + "=" + encodeURIComponent(value) + expires + "; path=/"; // Zapisanie ciasteczka
}


document.addEventListener('DOMContentLoaded', async () => {
    console.log( document.cookie );
    translations = await fetchTranslations();
    if( translations ) {
        const cookie = getCookie( 'necessary' );
        if ( !cookie ) {
            isCookiesVisible = true;
            makeMainBackground();
            const rectangle = makeCookiesBackground();
            const infoContainer = createInfoContainer()
            const settingsContainer = createSettingsContainer();
            const separator = createSeparator();
            const buttonsContainer = createButtonsContainer( settingsContainer, infoContainer );
            rectangle.appendChild( settingsContainer );
            rectangle.appendChild( infoContainer );
            rectangle.appendChild( separator );
            rectangle.appendChild( buttonsContainer );
        }
        
    }
})
