import './bootstrap';

//set dark mode
localStorage.theme = 'dark';

document.addEventListener('livewire:init', () => {
    Livewire.on('inscricao-realizada', (event) => {
        document.getElementById('registration').scrollIntoView({
            behavior: 'smooth',
        });
    });
});


