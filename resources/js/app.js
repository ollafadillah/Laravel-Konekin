import './bootstrap';

const DRAFT_PREFIX = 'konekin-form-draft:';

function draftableFields(form) {
    return Array.from(form.elements).filter((field) => {
        if (!field.name || field.disabled) {
            return false;
        }

        const type = (field.type || '').toLowerCase();

        return ![
            'file',
            'password',
            'hidden',
            'submit',
            'button',
            'reset',
        ].includes(type) && !['_token', '_method'].includes(field.name);
    });
}

function draftKey(form, index) {
    const action = form.getAttribute('action') || window.location.pathname;
    const method = form.getAttribute('method') || 'GET';

    return `${DRAFT_PREFIX}${window.location.pathname}:${method.toUpperCase()}:${action}:${index}`;
}

function readFormDraft(form) {
    return draftableFields(form).reduce((draft, field) => {
        const type = (field.type || '').toLowerCase();

        if (type === 'checkbox') {
            if (!draft[field.name]) {
                draft[field.name] = [];
            }

            if (field.checked) {
                draft[field.name].push(field.value);
            }

            return draft;
        }

        if (type === 'radio') {
            if (field.checked) {
                draft[field.name] = field.value;
            }

            return draft;
        }

        if (field.tagName === 'SELECT' && field.multiple) {
            draft[field.name] = Array.from(field.selectedOptions).map((option) => option.value);
            return draft;
        }

        draft[field.name] = field.value;
        return draft;
    }, {});
}

function saveFormDraft(form, key) {
    try {
        sessionStorage.setItem(key, JSON.stringify(readFormDraft(form)));
    } catch (error) {
        // Draft saving is a convenience; form submission should never depend on it.
    }
}

function hasUserValue(field) {
    const type = (field.type || '').toLowerCase();

    if (type === 'checkbox' || type === 'radio') {
        return field.checked;
    }

    if (field.tagName === 'SELECT' && field.multiple) {
        return field.selectedOptions.length > 0;
    }

    return field.value !== '';
}

function restoreFormDraft(form, key) {
    let draft = null;

    try {
        draft = JSON.parse(sessionStorage.getItem(key) || 'null');
    } catch (error) {
        return;
    }

    if (!draft) {
        return;
    }

    draftableFields(form).forEach((field) => {
        if (!Object.prototype.hasOwnProperty.call(draft, field.name) || hasUserValue(field)) {
            return;
        }

        const type = (field.type || '').toLowerCase();
        const value = draft[field.name];

        if (type === 'checkbox') {
            field.checked = Array.isArray(value) && value.includes(field.value);
            return;
        }

        if (type === 'radio') {
            field.checked = value === field.value;
            return;
        }

        if (field.tagName === 'SELECT' && field.multiple && Array.isArray(value)) {
            Array.from(field.options).forEach((option) => {
                option.selected = value.includes(option.value);
            });
            return;
        }

        field.value = value;
    });
}

function showFormValidationNotice(form) {
    let notice = form.querySelector('[data-form-validation-notice]');

    if (!notice) {
        notice = document.createElement('div');
        notice.dataset.formValidationNotice = 'true';
        notice.className = 'mb-4 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-bold text-red-700';
        form.prepend(notice);
    }

    notice.textContent = 'Ada bagian yang belum diisi atau belum sesuai. Input yang sudah kamu tulis tetap tersimpan di form ini.';
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form').forEach((form, index) => {
        const method = (form.getAttribute('method') || 'GET').toUpperCase();

        if (method === 'GET' || form.dataset.draftIgnore === 'true') {
            return;
        }

        const key = draftKey(form, index);

        restoreFormDraft(form, key);

        form.addEventListener('input', () => saveFormDraft(form, key));
        form.addEventListener('change', () => saveFormDraft(form, key));

        form.addEventListener('submit', (event) => {
            if (!form.checkValidity()) {
                event.preventDefault();
                showFormValidationNotice(form);
                form.reportValidity();

                return;
            }

            sessionStorage.removeItem(key);
        });
    });
});
