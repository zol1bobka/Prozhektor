
    // Плавный скролл по атрибуту data-scroll-to
    document.querySelectorAll('[data-scroll-to]').forEach(function (el) {
        el.addEventListener('click', function () {
            const target = document.querySelector(el.getAttribute('data-scroll-to'));
            if (target) {
                window.scrollTo({
                    top: target.offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Открытие форм
    document.querySelectorAll('[data-open-modal]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = btn.getAttribute('data-open-modal');
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('modal--open');
            }
        });
    });

    // Закрытие форм по клику по фону
    document.querySelectorAll('.modal-backdrop').forEach(function (backdrop) {
        backdrop.addEventListener('click', function (e) {
            if (e.target === backdrop) {
                backdrop.classList.remove('modal--open');
            }
        });
    });

    // Закрытие форм по крестику
    document.querySelectorAll('.modal-close').forEach(function (closeBtn) {
        closeBtn.addEventListener('click', function () {
            const id = closeBtn.getAttribute('data-close-modal');
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('modal--open');
            }
        });
    });

    // Выбор звёзд в форме отзыва
    const starsRow = document.querySelector('[data-stars]');
    if (starsRow) {
        const stars = starsRow.querySelectorAll('.star');
        const ratingInput = document.querySelector('input[name="rating"]');

        stars.forEach(function (star) {
            star.addEventListener('click', function () {
                const value = Number(star.getAttribute('data-star'));
                ratingInput.value = value;

                stars.forEach(function (s) {
                    s.classList.toggle('active', Number(s.getAttribute('data-star')) <= value);
                });
            });
        });
    }

    // Бургер-меню
    const burger = document.getElementById('burger');
    const mainNav = document.getElementById('main-nav');

    if (burger && mainNav) {
        burger.addEventListener('click', function () {
            burger.classList.toggle('burger--open');
            mainNav.classList.toggle('nav--open');
        });

        // Закрывать меню при выборе пункта
        mainNav.querySelectorAll('[data-scroll-to]').forEach(function (link) {
            link.addEventListener('click', function () {
                burger.classList.remove('burger--open');
                mainNav.classList.remove('nav--open');
            });
        });
    }
