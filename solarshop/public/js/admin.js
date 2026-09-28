(function () {
    'use strict';

    // Confirm deletes
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            var msg = form.getAttribute('data-confirm');
            if (!window.confirm(msg)) {
                e.preventDefault();
            }
        });
    });

    // Spec row management (products)
    var specRowsWrap = document.querySelectorAll('.spec-rows');
    specRowsWrap.forEach(function (wrap) {
        var addBtn = wrap.querySelector('.add-spec');
        var rows = wrap.querySelector('.spec-rows-list');

        function addRow(k, v) {
            var row = document.createElement('div');
            row.className = 'spec-row';
            row.innerHTML =
                '<input type="text" name="spec_keys[]" class="form-control" placeholder="Property (e.g. Warranty)" value="' + (k || '') + '">' +
                '<input type="text" name="spec_values[]" class="form-control" placeholder="Value (e.g. 10 years)" value="' + (v || '') + '">' +
                '<button type="button" class="btn btn-sm btn-danger remove-spec">&times;</button>';
            rows.appendChild(row);
            row.querySelector('.remove-spec').addEventListener('click', function () { row.remove(); });
        }

        rows.querySelectorAll('.remove-spec').forEach(function (btn) {
            btn.addEventListener('click', function () { btn.closest('.spec-row').remove(); });
        });

        if (addBtn) {
            addBtn.addEventListener('click', function () { addRow(); });
        }
    });

    // Image preview on file select
    document.querySelectorAll('input[type=file][data-preview]').forEach(function (input) {
        input.addEventListener('change', function () {
            var target = document.querySelector(input.getAttribute('data-preview'));
            if (target && input.files.length) {
                target.src = URL.createObjectURL(input.files[0]);
            }
        });
    });

    // Auto-hide alerts
    document.querySelectorAll('.alert').forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity .4s';
            alert.style.opacity = '0';
            setTimeout(function () { alert.remove(); }, 400);
        }, 5000);
    });
})();