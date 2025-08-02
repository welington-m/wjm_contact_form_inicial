<?php

namespace WJM\Application\Controllers;

use WJM\Domain\Repositories\FormMessageExportRepositoryInterface;

class FormMessageExportController
{
    public function __construct(private FormMessageExportRepositoryInterface $repository) {}

    public function exportCsv(): void
    {
        $messages = $this->repository->getAllExportData();

        if (empty($messages)) {
            wp_die('Nenhuma mensagem para exportar.');
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=mensagens_formulario.csv');

        $output = fopen('php://output', 'w');

        // Cabeçalhos padrão
        fputcsv($output, ['ID', 'Form ID', 'Data', 'Enviado em', 'IP', 'Visualizado']);

        foreach ($messages as $msg) {
            $dataString = implode(', ', array_map(
                fn($k, $v) => "$k: $v",
                array_keys($msg['data']),
                array_values($msg['data'])
            ));

            fputcsv($output, [
                $msg['id'],
                $msg['form_id'],
                $dataString,
                $msg['submitted_at'],
                $msg['ip_address'],
                $msg['viewed'] ? 'Sim' : 'Não',
            ]);
        }

        fclose($output);
        exit;
    }
}
