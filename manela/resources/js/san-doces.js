document.addEventListener('DOMContentLoaded', () => {
    const mobileMenu = document.querySelector('#mobile-navigation');
    const menuToggle = document.querySelector('.mobile-menu-toggle');

    if (mobileMenu && menuToggle) {
        menuToggle.hidden = false;
        const mobileViewport = window.matchMedia('(max-width: 900px)');
        const closeMenu = () => mobileMenu.close();

        menuToggle.addEventListener('click', () => {
            mobileMenu.showModal();
            menuToggle.setAttribute('aria-expanded', 'true');
            document.documentElement.classList.add('mobile-menu-open');
        });
        mobileMenu.querySelector('.mobile-menu-close').addEventListener('click', closeMenu);
        mobileMenu.addEventListener('click', (event) => {
            if (event.target === mobileMenu) {
                const bounds = mobileMenu.getBoundingClientRect();
                if (event.clientX < bounds.left || event.clientX > bounds.right || event.clientY < bounds.top || event.clientY > bounds.bottom) {
                    closeMenu();
                }
            }
            if (event.target.closest('a')) {
                closeMenu();
            }
        });
        mobileMenu.addEventListener('close', () => {
            menuToggle.setAttribute('aria-expanded', 'false');
            document.documentElement.classList.remove('mobile-menu-open');
            menuToggle.focus({ preventScroll: true });
        });
        mobileViewport.addEventListener('change', (event) => {
            if (!event.matches && mobileMenu.open) {
                closeMenu();
            }
        });
    }

    const categories = document.querySelectorAll('.category');
    const groups = document.querySelectorAll('.product-group');
    const cards = document.querySelectorAll('.product-card');
    const search = document.querySelector('#search');

    if (categories.length && groups.length) {
        categories.forEach((item) => {
            item.addEventListener('click', () => {
                categories.forEach((category) => category.classList.remove('active'));
                item.classList.add('active');

                const target = item.dataset.category;
                if (target) {
                    groups.forEach((group) => {
                        group.style.display = target === 'all' || group.dataset.group === target ? 'block' : 'none';
                    });
                }

                if (search) {
                    search.value = '';
                }
                cards.forEach((card) => {
                    card.style.display = 'block';
                });
            });
        });
    }

    if (search && groups.length && cards.length) {
        search.addEventListener('input', () => {
            const term = search.value.toLowerCase();

            groups.forEach((group) => {
                let found = false;

                group.querySelectorAll('.product-card').forEach((card) => {
                    const show = card.dataset.name.toLowerCase().includes(term);
                    card.style.display = show ? 'block' : 'none';
                    found ||= show;
                });

                group.style.display = found ? 'block' : 'none';
            });

            categories.forEach((category) => category.classList.remove('active'));
        });
    }

    document.querySelectorAll('.order-area .category').forEach((button) => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.order-area .category').forEach((item) => item.classList.remove('active'));
            button.classList.add('active');
        });
    });

    const contactForm = document.querySelector('#mensagem');
    if (contactForm) {
        contactForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            const dados = {
                nome: document.querySelector('#contato_nome')?.value.trim(),
                email: document.querySelector('#contato_email')?.value.trim(),
                whatsapp: document.querySelector('#contato_whatsapp')?.value.trim(),
                assunto: document.querySelector('#contato_assunto')?.value,
                mensagem: document.querySelector('#contato_mensagem')?.value.trim(),
            };

            try {
                const response = await fetch('/api/mensagem_contato', {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(dados),
                });
                const resultado = await response.json();

                if (!response.ok || resultado.erro === 's') {
                    throw new Error(resultado.mensagem || resultado.message || 'Nao foi possivel enviar a mensagem.');
                }

                contactForm.reset();

                if (window.Swal) {
                    Swal.fire({ icon: 'success', title: 'Mensagem enviada!', text: resultado.mensagem });
                }
            } catch (error) {
                if (window.Swal) {
                    Swal.fire({ icon: 'error', title: 'Erro!', text: error.message });
                } else {
                    alert(error.message);
                }
            }
        });
    }
});
