<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExportService
{
    /**
     * Gera um download de CSV a partir de um array de dados ou query.
     */
    public static function export(iterable $data, array $headers, string $filename = 'export.csv'): StreamedResponse
    {
        return response()->streamDownload(function () use ($data, $headers) {
            $handle = fopen('php://output', 'w');
            
            // Adicionar BOM para compatibilidade com Excel no Windows (UTF-8)
            fputs($handle, "\xEF\xBB\xBF");

            // Escrever cabeçalhos
            fputcsv($handle, $headers, ';'); // Ponto e vírgula é melhor para Excel BR

            // Escrever dados
            foreach ($data as $row) {
                // Garantir que a ordem dos dados corresponda aos cabeçalhos se for assoc
                // Se for objeto, converter para array
                if (is_object($row)) {
                    $row = (array) $row;
                }
                
                fputcsv($handle, $row, ';');
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
