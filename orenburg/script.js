/* ==========================================================
   script.js
========================================================== */

document.addEventListener("DOMContentLoaded", () => {

    /* ==========================================
       MOBILE MENU
    ========================================== */

    const burger = document.querySelector(".burger");
    const menu = document.querySelector(".menu");

    if (burger && menu) {

        burger.addEventListener("click", () => {

            burger.classList.toggle("active");
            menu.classList.toggle("active");

            document.body.classList.toggle("menu-open");

        });

        menu.querySelectorAll("a").forEach(link => {

            link.addEventListener("click", () => {

                burger.classList.remove("active");
                menu.classList.remove("active");
                document.body.classList.remove("menu-open");

            });

        });

    }

    /* ==========================================
       FIXED HEADER
    ========================================== */

    const header = document.querySelector(".header");

    window.addEventListener("scroll", () => {

        if (window.scrollY > 40) {

            header.classList.add("scrolled");

        } else {

            header.classList.remove("scrolled");

        }

    });

    /* ==========================================
       SMOOTH SCROLL
    ========================================== */

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {

        anchor.addEventListener("click", function(e){

            const id = this.getAttribute("href");

            if(id === "#") return;

            const block = document.querySelector(id);

            if(!block) return;

            e.preventDefault();

            window.scrollTo({

                top: block.offsetTop - 90,

                behavior: "smooth"

            });

        });

    });

    /* ==========================================
       ANIMATION ON SCROLL
    ========================================== */

    const observer = new IntersectionObserver(entries => {

        entries.forEach(entry => {

            if(entry.isIntersecting){

                entry.target.classList.add("show");

            }

        });

    },{

        threshold:.15

    });

    document.querySelectorAll(

        ".service-card,.case-card,.review-card,.benefit-card,.process-item,.faq-item,.stat,.feature"

    ).forEach(el=>{

        el.classList.add("fade-up");

        observer.observe(el);

    });

});

/* ==========================================================
   BACK TO TOP
========================================================== */

const upButton = document.createElement("button");

upButton.className = "back-to-top";

upButton.innerHTML = "↑";

document.body.appendChild(upButton);

window.addEventListener("scroll", () => {

    if (window.scrollY > 600) {

        upButton.classList.add("show");

    } else {

        upButton.classList.remove("show");

    }

});

upButton.addEventListener("click", () => {

    window.scrollTo({

        top: 0,

        behavior: "smooth"

    });

});


/* ==========================================================
   PHONE MASK
========================================================== */

const phoneInputs = document.querySelectorAll('input[type="tel"]');

phoneInputs.forEach(input => {

    input.addEventListener("input", phoneMask);
    input.addEventListener("focus", phoneMask);
    input.addEventListener("blur", phoneMask);

});

function phoneMask() {

    let value = this.value.replace(/\D/g, '');

    if (value.startsWith("8")) {
        value = value.substring(1);
    }

    if (value.startsWith("7")) {
        value = value.substring(1);
    }

    let result = "+7";

    if (value.length > 0) {
        result += " (" + value.substring(0, 3);
    }

    if (value.length >= 4) {
        result += ") " + value.substring(3, 6);
    }

    if (value.length >= 7) {
        result += "-" + value.substring(6, 8);
    }

    if (value.length >= 9) {
        result += "-" + value.substring(8, 10);
    }

    this.value = result;

}


/* ==========================================================
   FORM VALIDATION
========================================================== */

const form = document.getElementById("contactForm");

if (form) {

    form.addEventListener("submit", function (e) {

        e.preventDefault();

        const name = form.querySelector('[name="name"]');
        const phone = form.querySelector('[name="phone"]');
        const email = form.querySelector('[name="email"]');
        const message = form.querySelector('[name="message"]');

        if (name.value.trim().length < 2) {

            alert("Введите имя.");

            name.focus();

            return;

        }

        if (phone.value.replace(/\D/g, '').length < 11) {

            alert("Введите корректный номер телефона.");

            phone.focus();

            return;

        }

        sendForm({
            name: name.value,
            phone: phone.value,
            email: email.value,
            message: message.value
        });

    });

}

/* ==========================================================
   AJAX FORM SUBMIT
========================================================== */

async function sendForm(data) {

    const button = form.querySelector("button");

    const oldText = button.innerHTML;

    button.disabled = true;
    button.innerHTML = "Отправка...";

    try {

        const response = await fetch("send.php", {

            method: "POST",

            headers: {

                "Content-Type": "application/json"

            },

            body: JSON.stringify(data)

        });

        if (!response.ok) {

            throw new Error("Ошибка сервера");

        }

        showNotification(

            "Спасибо! Заявка успешно отправлена.",

            "success"

        );

        form.reset();

    } catch (error) {

        console.error(error);

        showNotification(

            "Не удалось отправить заявку. Попробуйте позже.",

            "error"

        );

    }

    button.disabled = false;

    button.innerHTML = oldText;

}


/* ==========================================================
   NOTIFICATION
========================================================== */

function showNotification(text, type = "success") {

    const notification = document.createElement("div");

    notification.className = "notification " + type;

    notification.innerHTML = text;

    document.body.appendChild(notification);

    requestAnimationFrame(() => {

        notification.classList.add("show");

    });

    setTimeout(() => {

        notification.classList.remove("show");

        setTimeout(() => {

            notification.remove();

        }, 400);

    }, 3500);

}


/* ==========================================================
   HERO ANIMATION
========================================================== */

const heroTitle = document.querySelector(".hero h1");
const heroText = document.querySelector(".hero-description");
const heroButtons = document.querySelector(".hero-buttons");
const heroImage = document.querySelector(".hero-image");

[
    heroTitle,
    heroText,
    heroButtons,
    heroImage
].forEach((item, index) => {

    if (!item) return;

    item.style.opacity = "0";
    item.style.transform = "translateY(40px)";

    setTimeout(() => {

        item.style.transition = ".8s ease";

        item.style.opacity = "1";

        item.style.transform = "translateY(0)";

    }, 200 + (index * 180));

});


/* ==========================================================
   CURRENT YEAR
========================================================== */

const year = document.querySelector(".current-year");

if (year) {

    year.textContent = new Date().getFullYear();

}


/* ==========================================================
   PARALLAX HERO
========================================================== */

window.addEventListener("scroll", () => {

    const offset = window.pageYOffset;

    const circle = document.querySelector(".hero-circle");

    if (circle) {

        circle.style.transform =
            `translateX(-50%) translateY(${offset * 0.12}px)`;

    }

});


/* ==========================================================
   END
========================================================== */

console.log("Mediator site loaded successfully.");