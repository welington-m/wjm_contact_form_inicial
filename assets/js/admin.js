// Função para copiar shortcodes (comum a várias views)
function setupShortcodeCopy() {
    jQuery(document).on('click', '.wjm-copy-shortcode', function () {
        const shortcode = jQuery(this).data('shortcode');
        navigator.clipboard.writeText(shortcode);

        const originalText = jQuery(this).html();
        jQuery(this).html('<span class="dashicons dashicons-yes"></span> Copiado!');

        setTimeout(() => {
            jQuery(this).html(originalText);
        }, 2000);
    });
}

jQuery(document).ready(function ($) {
    console.log('✅ admin.js carregado com sucesso!');

    const $toggleBtn = $('#wjm-toggle-editor');
    const $editor = $('#wjm-visual-editor');
    const $fieldsContainer = $('#wjm-fields-container');
    const $addFieldBtn = $('#wjm-add-field');
    const $updateJsonBtn = $('#wjm-update-json');
    const $jsonTextarea = $('#wjm_config_json');

    // Alternar visualização do editor
    $toggleBtn.on('click', function () {
        $editor.slideToggle(200);
        const isVisible = $editor.is(':visible');
        $(this).find('.btn-text').text(isVisible ? 'Ocultar Editor Visual' : 'Mostrar Editor Visual');
        console.log('🖱️ Botão "Mostrar Editor Visual" clicado');
    });

    // Adicionar novo campo
    $addFieldBtn.on('click', function () {
        const index = $fieldsContainer.children().length;
        const fieldHtml = renderField(index, { name: '', label: '', type: 'text', required: false });
        $fieldsContainer.append(fieldHtml);
        attachRemoveHandler();
        updateFieldIndexes();
    });

    // Renderiza um campo com valores
    function renderField(index, field) {
        return `
            <div class="wjm-field-card" data-index="${index}">
                <div class="wjm-field-header">
                    <h3>Campo #${index + 1}</h3>
                    <button type="button" class="button button-secondary wjm-remove-field">
                        <span class="dashicons dashicons-trash"></span> Remover
                    </button>
                </div>
                <div class="wjm-field-body">
                    <p>
                        <label>Nome: <input type="text" class="wjm-field-name" value="${field.name}" placeholder="Ex: email" required></label>
                    </p>
                    <p>
                        <label>Rótulo: <input type="text" class="wjm-field-label" value="${field.label}" placeholder="Ex: Seu Email" required></label>
                    </p>
                    <p>
                        <label>Tipo:
                            <select class="wjm-field-type">
                                <option value="text" ${field.type === 'text' ? 'selected' : ''}>Texto</option>
                                <option value="email" ${field.type === 'email' ? 'selected' : ''}>Email</option>
                                <option value="textarea" ${field.type === 'textarea' ? 'selected' : ''}>Textarea</option>
                                <option value="select" ${field.type === 'select' ? 'selected' : ''}>Select</option>
                            </select>
                        </label>
                    </p>
                    <p>
                        <label><input type="checkbox" class="wjm-field-required" ${field.required ? 'checked' : ''}> Obrigatório</label>
                    </p>
                </div>
            </div>
        `;
    }

    // Anexar evento para remover campos
    function attachRemoveHandler() {
        $fieldsContainer.find('.wjm-remove-field').off('click').on('click', function () {
            $(this).closest('.wjm-field-card').remove();
            updateFieldIndexes();
        });
    }

    // Atualizar o índice dos campos após remover
    function updateFieldIndexes() {
        $fieldsContainer.find('.wjm-field-card').each(function (i) {
            $(this).attr('data-index', i);
            $(this).find('h3').text(`Campo #${i + 1}`);
        });
    }

    // Atualizar JSON de configuração
    $updateJsonBtn.on('click', function () {
        const fields = [];
        let valid = true;

        $fieldsContainer.find('.wjm-field-card').each(function () {
            const $el = $(this);
            const name = $el.find('.wjm-field-name').val().trim();
            const label = $el.find('.wjm-field-label').val().trim();
            const type = $el.find('.wjm-field-type').val();
            const required = $el.find('.wjm-field-required').is(':checked');

            if (!name || !label) {
                alert('⚠️ Preencha todos os campos obrigatórios.');
                valid = false;
                return false;
            }

            fields.push({ name, label, type, required, options: [] });
        });

        if (!valid) return;

        const json = JSON.stringify(fields, null, 2);
        $jsonTextarea.val(json);
        alert('✅ JSON atualizado com sucesso!');
    });

    // Auto renderizar os campos salvos do JSON ao carregar a página
    function renderExistingFieldsFromJson() {
        const rawJson = $jsonTextarea.val();
        if (!rawJson) return;

        try {
            const fields = JSON.parse(rawJson);
            if (!Array.isArray(fields)) return;

            $fieldsContainer.empty();
            fields.forEach((field, index) => {
                const html = renderField(index, field);
                $fieldsContainer.append(html);
            });

            attachRemoveHandler();
            updateFieldIndexes();
            $editor.show();
            $toggleBtn.find('.btn-text').text('Ocultar Editor Visual');
        } catch (e) {
            console.error('Erro ao renderizar campos do JSON:', e);
        }
    }

    renderExistingFieldsFromJson();
});
