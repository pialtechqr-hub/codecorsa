document.addEventListener('DOMContentLoaded', () => {

    const toggle =
        document.getElementById(
            'whatsappToggle'
        );

    const box =
        document.getElementById(
            'whatsappBox'
        );

    const close =
        document.getElementById(
            'whatsappClose'
        );

    const send =
        document.getElementById(
            'waSend'
        );

    toggle.addEventListener(
        'click',
        () => {

            box.classList.toggle(
                'active'
            );
        }
    );

    close.addEventListener(
        'click',
        () => {

            box.classList.remove(
                'active'
            );
        }
    );

    send.addEventListener(
        'click',
        () => {

            const name =
                document.getElementById(
                    'waName'
                ).value;

            const message =
                document.getElementById(
                    'waMessage'
                ).value;

            const text =
                `Hola, soy ${name}. ${message}`;

            window.open(
                `https://wa.me/51970503691?text=${encodeURIComponent(text)}`,
                '_blank'
            );
        }
    );

});