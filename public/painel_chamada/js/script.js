// Função para adicionar a senha ao histórico
function addToHistory(senha) {
    let history = JSON.parse(localStorage.getItem('history')) || [];
    history.push(senha);
    // Manter apenas as últimas três senhas
    if (history.length > 3) {
        history = history.slice(-3);
    }
    localStorage.setItem('history', JSON.stringify(history));
}

// Função para carregar e exibir o histórico
function loadHistory() {
    const history = JSON.parse(localStorage.getItem('history')) || [];
    const historicoSenhas = document.getElementById('historicoSenhas');
    historicoSenhas.innerHTML = '';
    history.forEach(senha => {
        const div = document.createElement('div');
        div.className = 'historico-item';
        div.innerHTML = senha;
        historicoSenhas.appendChild(div);
    });
}

// Página principal (index.html)
if (document.getElementById('chamarSenhaBtn')) {
    document.getElementById('chamarSenhaBtn').addEventListener('click', () => {
        const senha = document.getElementById('senhaInput').value;
        if (senha) {
            localStorage.setItem('senhaAtual', senha);
            addToHistory(senha);
            document.getElementById('senhaInput').value = '';
        }
    });
}

if (document.getElementById('senhaAtual')) {
    const campainha = document.getElementById('campainha');

    // Atualizar a senha atual e tocar o som de campainha
    window.addEventListener('storage', (event) => {
        if (event.key === 'senhaAtual') {
            document.getElementById('senhaAtual').innerHTML = event.newValue;
            campainha.play();
        }
    });

    // Carregar o histórico ao carregar a página
    loadHistory();

    // Atualizar o histórico em tempo real
    window.addEventListener('storage', (event) => {
        if (event.key === 'history') {
            loadHistory();
        }
    });
}
