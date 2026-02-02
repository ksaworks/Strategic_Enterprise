<?php

namespace App\Filament\Resources\Contacts\Pages;

use App\Filament\Resources\Contacts\ContactResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContacts extends ListRecords
{
    protected static string $resource = ContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            
            \Filament\Actions\Action::make('import')
                ->label('Importar CSV')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    \Filament\Forms\Components\FileUpload::make('file')
                        ->label('Arquivo CSV')
                        ->helperText('Formato: Nome, Sobrenome, Email, Telefone, Cargo')
                        ->acceptedFileTypes(['text/csv', 'text/plain', 'application/csv', 'application/vnd.ms-excel'])
                        ->required()
                        ->storeFiles(false), // Não salva no disco, processa em memória
                ])
                ->action(function (array $data) {
                    $file = $data['file'];
                    
                    // Se for objeto UploadedFile (livewire)
                    $path = $file->getRealPath();
                    
                    $handle = fopen($path, 'r');
                    
                    // Ler cabeçalho (assumindo que tem)
                    $header = fgetcsv($handle, 1000, ';'); // Tenta ponto e vírgula primeiro
                    
                    // Se o cabeçalho tiver apenas 1 coluna, tenta vírgula
                    if (count($header) == 1) {
                         rewind($handle);
                         $header = fgetcsv($handle, 1000, ',');
                         $delimiter = ',';
                    } else {
                        $delimiter = ';';
                    }
                    
                    // Voltar para processar dados (se quisermos ignorar header, não damos rewind total)
                    // Mas vamos ignorar a primeira linha se parecer header
                    
                    $count = 0;
                    while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                        if (count($row) < 3) continue; // Pula linhas vazias ou incompletas
                        
                        try {
                            \App\Models\Contact::create([
                                'first_name' => $row[0] ?? '',
                                'last_name' => $row[1] ?? '',
                                'email' => $row[2] ?? null,
                                'phone' => $row[3] ?? null,
                                'job_title' => $row[4] ?? null,
                                'is_active' => true,
                            ]);
                            $count++;
                        } catch (\Exception $e) {
                            // Ignora erros de duplicidade ou validação por enquanto
                        }
                    }
                    
                    fclose($handle);
                    
                    \Filament\Notifications\Notification::make()
                        ->title("Importação concluída")
                        ->body("$count contatos importados com sucesso.")
                        ->success()
                        ->send();
                }),
        ];
    }
}
