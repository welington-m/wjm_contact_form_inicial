jQuery(document).ready(function ($) {
    console.log('✅ form-editor.js carregado com sucesso!');

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
        const fieldHtml = `
            <div class="wjm-field-card" data-index="${index}">
                <div class="wjm-field-header">
                    <h3>Campo #${index + 1}</h3>
                    <button type="button" class="button button-secondary wjm-remove-field">
                        <span class="dashicons dashicons-trash"></span> Remover
                    </button>
                </div>
                <div class="wjm-field-body">
                    <p>
                        <label>Nome: <input type="text" class="wjm-field-name" placeholder="Ex: email" required></label>
                    </p>
                    <p>
                        <label>Rótulo: <input type="text" class="wjm-field-label" placeholder="Ex: Seu Email" required></label>
                    </p>
                    <p>
                        <label>Tipo:
                            <select class="wjm-field-type">
                                <option value="text">Texto</option>
                                <option value="email">Email</option>
                                <option value="textarea">Textarea</option>
                                <option value="select">Select</option>
                            </select>
                        </label>
                    </p>
                    <p>
                        <label><input type="checkbox" class="wjm-field-required"> Obrigatório</label>
                    </p>
                </div>
            </div>
        `;

        $fieldsContainer.append(fieldHtml);
        attachRemoveHandler();
        updateFieldIndexes();
    });

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

            fields.push({ name, label, type, required });
        });

        if (!valid) return;

        const json = JSON.stringify({ fields }, null, 2);
        $jsonTextarea.val(json);
        alert('✅ JSON atualizado com sucesso!');
    });

    // Mostrar editor caso já existam campos (modo edição)
    if ($fieldsContainer.children().length > 0) {
        $editor.show();
        $toggleBtn.find('.btn-text').text('Ocultar Editor Visual');
    }
});
