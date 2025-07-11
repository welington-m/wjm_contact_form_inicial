<?php
use WJM\Infra\Repositories\FormMessageRepository;
use WJM\Application\Controllers\FormMessageController;

global $wpdb, $current_user;
$repo = new FormMessageRepository($wpdb);
$controller = new FormMessageController($repo);

// Filtros
$page = $_GET['paged'] ?? 1;
$perPage = 10;
$filters = [
    'form_id' => $_GET['form_id'] ?? null,
    'date' => $_GET['date'] ?? null
];

if (isset($_GET['export'])) {
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=mensagens.csv");
    echo $controller->export($filters);
    exit;
}

$messages = $controller->list($filters, $page, $perPage);
$total = $controller->total($filters);
$totalPages = ceil($total / $perPage);
?>

<div class="wrap">
  <h1>Mensagens Enviadas</h1>
  <form method="get">
    <input type="hidden" name="page" value="wjm-contact-messages" />
    <input type="text" name="form_id" placeholder="Formulário ID" value="<?= esc_attr($_GET['form_id'] ?? '') ?>">
    <input type="date" name="date" value="<?= esc_attr($_GET['date'] ?? '') ?>">
    <button type="submit" class="button">Filtrar</button>
    <a href="?page=wjm-contact-messages&export=1" class="button button-secondary">Exportar CSV</a>
  </form>

  <table class="widefat striped">
    <thead><tr>
      <th>ID</th><th>Formulário</th><th>Data</th><th>Conteúdo</th><th>Visualizado por</th>
    </tr></thead>
    <tbody>
      <?php foreach ($messages as $msg): ?>
        <?php $controller->markViewed($msg->id, $current_user->user_login); ?>
        <tr>
          <td><?= $msg->id ?></td>
          <td><?= $msg->form_id ?></td>
          <td><?= $msg->created_at ?></td>
          <td><pre><?= esc_html($msg->data) ?></pre></td>
          <td><?= $msg->viewed_by ?> (<?= $msg->viewed_at ?>)</td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="tablenav">
    <div class="tablenav-pages">
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a class="button <?= $i == $page ? 'button-primary' : '' ?>" href="?page=wjm-contact-messages&paged=<?= $i ?>"><?= $i ?></a>
      <?php endfor; ?>
    </div>
  </div>
</div>
