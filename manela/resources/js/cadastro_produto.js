document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('#cadastro-produto-form');
    if (!form) {
        return;
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const token = sessionStorage.getItem('tif_token');
        if (!token) {
            window.location.href = '/login-dashboard';
            return;
        }

        const dados = {
            categoria: document.querySelector('#categoria')?.value.trim(),
            preco: document.querySelector('#preco')?.value,
            nome: document.querySelector('#nome')?.value.trim(),
            sobre: document.querySelector('#sobre')?.value.trim(),
            descricao: document.querySelector('#descricao')?.value.trim(),
            imagem: document.querySelector('#imagem')?.value.trim(),
        };

        try {
            const response = await fetch('/api/cadastro_doces', {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    Authorization: `Bearer ${token}`,
                },
                body: JSON.stringify(dados),
            });
            const resultado = await response.json();

            if (!response.ok || resultado.erro === 's') {
                throw new Error(resultado.mensagem || resultado.message || 'Nao foi possivel cadastrar o produto.');
            }

            if (window.Swal) {
                Swal.fire({ icon: 'success', title: 'Sucesso!', text: resultado.mensagem });
            }

            form.reset();
        } catch (error) {
            if (window.Swal) {
                Swal.fire({ icon: 'error', title: 'Erro!', text: error.message });
            } else {
                alert(error.message);
            }
        }
    });
});
