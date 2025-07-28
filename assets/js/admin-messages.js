jQuery(document).ready(function($) {
    // Modal de visualização
    const modal = $(
        `<div class="wjm-message-modal">
            <div class="wjm-message-modal-content">
                <span class="wjm-message-modal-close">&times;</span>
                <div class="wjm-message-content"></div>
            </div>
        </div>`
    ).appendTo('body');

    // Abrir modal ao clicar em "Ver"
    $(document).on('click', '.view-message', function(e) {
        e.preventDefault();
        const messageId = $(this).data('id');
        
        // Mostrar loading
        modal.find('.wjm-message-content').html('<p>Carregando...</p>');
        modal.fadeIn();
        
        // Buscar detalhes da mensagem via AJAX
        $.ajax({
            url: ajaxurl,
            type: 'GET',
            data: {
                action: 'wjm_get_message_details',
                id: messageId,
                nonce: wjm_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    modal.find('.wjm-message-content').html(response.data.html);
                } else {
                    modal.find('.wjm-message-content').html(
                        `<p class="error">${response.data.message || 'Erro ao carregar mensagem'}</p>`
                    );
                }
            },
            error: function() {
                modal.find('.wjm-message-content').html(
                    '<p class="error">Erro ao carregar mensagem. Tente novamente.</p>'
                );
            }
        });
    });

    // Fechar modal
    modal.on('click', '.wjm-message-modal-close', function() {
        modal.fadeOut();
    });

    // Fechar ao clicar fora do conteúdo
    modal.on('click', function(e) {
        if ($(e.target).hasClass('wjm-message-modal')) {
            modal.fadeOut();
        }
    });

    // Melhorar a tabela para mobile
    function adaptTableForMobile() {
        if ($(window).width() <= 782) {
            $('.wjm-messages-container table.widefat tbody td').each(function() {
                const $td = $(this);
                const header = $td.closest('table').find('th').eq($td.index()).text();
                $td.attr('data-label', header);
            });
        }
    }

    // Executar na carga e no redimensionamento
    adaptTableForMobile();
    $(window).resize(adaptTableForMobile);
});