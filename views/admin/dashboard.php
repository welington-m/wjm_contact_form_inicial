<div class="wrap">
    <h1>📊 Painel de Controle - WJM Contact Form</h1>

    <div style="margin: 20px 0;">
        <a href="<?= esc_url($new_form_url) ?>" class="button button-primary">➕ Novo Formulário</a>
        <a href="<?= esc_url($messages_url) ?>" class="button">📨 Ver Mensagens</a>
    </div>

    <h2>📝 Formulários Cadastrados</h2>

    <?php if (empty($forms)) : ?>
        <p>Nenhum formulário encontrado.</p>
    <?php else : ?>
        <table class="widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Shortcode</th>
                    <th>Criado em</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($forms as $form) : ?>
                    <tr>
                        <td><?= esc_html($form->getId()) ?></td>
                        <td><?= esc_html($form->getTitle()) ?></td>
                        <td>[contact-form id="<?= esc_attr($form->getId()) ?>"]</td>
                        <td><?= esc_html($form->getCreatedAt()->format('Y-m-d')) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div style="margin-top: 40px;">
        <img src="<?= esc_url($banner_url) ?>" alt="WJM Banner" style="max-width: 470px; height: auto;" />
    </div>
</div>
