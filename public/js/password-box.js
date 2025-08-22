const containerClass = '.password-icon-swap';
const svgOnSelector = 'svg.icon-show';
const svgOffSelector = 'svg.icon-hide';

function getInputBox(container) {
    const parent = container.closest('.input-suffix');

    if (!parent) {
        return null
    }

    const inputBox = parent.previousElementSibling;

    if (!inputBox) {
        return null;
    }

    return inputBox;
}

document.querySelectorAll(containerClass).forEach(container => {
    const toggle = () => {
        const svgOn = container.querySelector(svgOnSelector);
        const svgOff = container.querySelector(svgOffSelector);
        if (!svgOn || !svgOff) {
            return;
        }

        const onVisible = window.getComputedStyle(svgOn).display !== 'none';

        const inputBox = getInputBox(container);

        if (onVisible) {
            svgOn.classList.add('hidden');
            svgOn.setAttribute('aria-hidden', 'true');
            svgOff.classList.remove('hidden');
            svgOff.setAttribute('aria-hidden', 'false');
        } else {
            svgOn.classList.remove('hidden');
            svgOn.setAttribute('aria-hidden', 'false');
            svgOff.classList.add('hidden');
            svgOff.setAttribute('aria-hidden', 'true');
        }

        try {
            inputBox.type = inputBox.type === 'password' ? 'text' : 'password';
        } catch (err) {
            console.log("Could not change password input type", err);
        }
    };

    container.addEventListener('click', toggle);

    // keyboard accessibility (Enter / Space)
    container.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggle();
        }
    });
});

