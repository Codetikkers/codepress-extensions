/**
 * Focal point of an image (FocalPointHook): "Instellen" opens a dialog with the photo, click or drag on it (or use the
 * arrow keys) to move the point, "Opslaan" puts it in the hidden input. The media modal saves attachment fields on change, the
 * "Edit media" screen on update.
 */
(function () {
    let dialog, image, marker, field, point;

    function format(value) {
        const match = /^(\d+) (\d+)$/.exec(value);
        return match ? `Horizontaal ${match[1]}%, verticaal ${match[2]}%` : 'Midden (standaard)';
    }

    function update(input, value) {
        const container = input.closest('.focal-point-field');
        input.value = value;
        input.dispatchEvent(new Event('change', {bubbles: true}));

        const unsaved = !input.closest('.media-modal') ? ' (klik op Bijwerken om op te slaan)' : '';
        container.querySelector('.focal-point-status').textContent = format(value) + unsaved;
        container.querySelector('.focal-point-reset').hidden = !value;
    }

    function moveMarker() {
        marker.style.left = point[0] + '%';
        marker.style.top = point[1] + '%';
    }

    function createDialog() {
        dialog = document.createElement('dialog');
        dialog.className = 'focal-point-dialog';
        dialog.innerHTML = `
            <form method="dialog" class="focal-point-dialog__header">
                <p>Klik op de afbeelding om het focuspunt te bepalen</p>
                <button class="button" value="cancel">Annuleren</button>
                <button class="button button-primary" value="save">Opslaan</button>
            </form>
            <div class="focal-point-dialog__image" tabindex="0" role="application" aria-label="Focuspunt, verplaats met de pijltjestoetsen">
                <img alt="" draggable="false">
                <span class="focal-point-dialog__marker"></span>
            </div>`;
        document.body.appendChild(dialog);

        image = dialog.querySelector('img');
        marker = dialog.querySelector('.focal-point-dialog__marker');

        // Pressing inside the circle drags the point from where you grabbed it, pressing outside it moves the point there
        let offset = null;
        const pointerTo = (event, [dx, dy]) => {
            const rect = image.getBoundingClientRect();
            point = [
                (event.clientX - rect.left - dx) / rect.width * 100,
                (event.clientY - rect.top - dy) / rect.height * 100,
            ].map(value => Math.round(Math.min(100, Math.max(0, value))));
            moveMarker();
        };

        image.addEventListener('pointerdown', event => {
            const circle = marker.getBoundingClientRect();
            const dx = event.clientX - (circle.left + circle.width / 2);
            const dy = event.clientY - (circle.top + circle.height / 2);
            offset = Math.hypot(dx, dy) <= circle.width / 2 ? [dx, dy] : [0, 0];
            image.setPointerCapture(event.pointerId);
            pointerTo(event, offset);
        });
        image.addEventListener('pointermove', event => offset && pointerTo(event, offset));
        image.addEventListener('pointerup', () => offset = null);
        image.addEventListener('pointercancel', () => offset = null);

        // Arrow keys move the point 1%, with shift 10%
        dialog.querySelector('.focal-point-dialog__image').addEventListener('keydown', event => {
            const step = event.shiftKey ? 10 : 1;
            const moves = {ArrowLeft: [-step, 0], ArrowRight: [step, 0], ArrowUp: [0, -step], ArrowDown: [0, step]};
            if (!moves[event.key]) return;
            event.preventDefault();
            point = point.map((value, i) => Math.min(100, Math.max(0, value + moves[event.key][i])));
            moveMarker();
        });

        dialog.addEventListener('close', () => {
            if (dialog.returnValue === 'save') update(field.querySelector('.focal-point-input'), point.join(' '));
        });
    }

    document.addEventListener('click', event => {
        const open = event.target.closest('.focal-point-open');
        const reset = event.target.closest('.focal-point-reset');

        if (reset) {
            update(reset.closest('.focal-point-field').querySelector('.focal-point-input'), '');
        }

        if (open) {
            dialog || createDialog();
            field = open.closest('.focal-point-field');
            const match = /^(\d+) (\d+)$/.exec(field.querySelector('.focal-point-input').value);
            point = match ? [+match[1], +match[2]] : [50, 50];
            image.src = field.dataset.image;
            moveMarker();
            dialog.returnValue = '';
            dialog.showModal();
        }
    });
})();
