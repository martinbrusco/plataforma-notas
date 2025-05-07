<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Models\Estudiante;
use App\Models\Nota;
use App\Models\Materia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Objects\Update;

class TelegramBotController extends Controller
{
    public function handle(Request $request)
    {
        try {
            Log::info('Webhook recibido', ['request' => $request->all()]);

            $data = $request->all();
            Log::info('Datos recibidos del webhook', ['data' => $data]);

            $update = Telegram::getWebhookUpdates();
            Log::info('Update procesado', ['update' => $update ? $update->toArray() : null]);

            if (!$update || !($update instanceof Update)) {
                Log::error('No se recibió una actualización válida de Telegram', ['data' => $data]);
                return response('Error: No se recibió una actualización válida', 400);
            }

            $chatId = $this->getChatId($update);
            if ($chatId === null) {
                Log::error('No se pudo obtener el chat ID', ['update' => $update->toArray()]);
                return response('Error: No se pudo obtener el chat ID', 400);
            }

            $this->processUpdate($update, $chatId);

            return response('OK', 200);
        } catch (\Exception $e) {
            Log::error('Error al procesar el webhook de Telegram', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response('Error interno', 500);
        }
    }

    private function getChatId(Update $update)
    {
        if ($update->getMessage()) {
            return $update->getMessage()->getChat()->getId();
        } elseif ($update->getCallbackQuery()) {
            return $update->getCallbackQuery()->getMessage()->getChat()->getId();
        }
        return null;
    }

    private function processUpdate(Update $update, int $chatId)
    {
        Log::info('Procesando actualización', ['chat_id' => $chatId, 'update' => $update->toArray()]);

        try {
            if ($update->getMessage() && $update->getMessage()->getContact()) {
                $this->handleContact($update, $chatId);
            } elseif ($update->getCallbackQuery()) {
                $this->handleCallbackQuery($update, $chatId);
            } else {
                $this->requestPhoneNumber($chatId);
            }
        } catch (\Exception $e) {
            Log::error('Error en processUpdate', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    private function handleContact(Update $update, int $chatId)
    {
        try {
            $phoneNumber = $update->getMessage()->getContact()->getPhoneNumber();
            Log::info('Número de teléfono recibido', ['phone_number' => $phoneNumber, 'chat_id' => $chatId]);

            // Limpia el número de teléfono (elimina el "+" si está presente)
            $phoneNumber = ltrim($phoneNumber, '+');
            Log::info('Número de teléfono limpio', ['phone_number' => $phoneNumber]);

            $estudiante = Estudiante::where('telefono', 'LIKE', '%' . $phoneNumber)->first();
            Log::info('Estudiante encontrado', ['estudiante' => $estudiante ? $estudiante->toArray() : null]);

            if ($estudiante) {
                // Almacenar el estudiante_id en caché asociado al chat_id (expira en 1 hora)
                Cache::put('student_chat_' . $chatId, $estudiante->id, 3600);
                Log::info('Estudiante almacenado en caché', ['chat_id' => $chatId, 'estudiante_id' => $estudiante->id]);

                $notas = Nota::where('estudiante_id', $estudiante->id)->with('asignatura')->get();
                Log::info('Notas recuperadas', ['notas' => $notas->toArray()]);

                $materias = $notas->isNotEmpty() ? $notas->pluck('asignatura')->unique() : collect();
                Log::info('Materias únicas', ['materias' => $materias->values()->toArray()]);

                if ($materias->isEmpty()) {
                    Telegram::sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'No tienes materias registradas.',
                    ]);
                    Log::info('Mensaje enviado: No tienes materias registradas');
                    return;
                }

                $buttons = [];
                foreach ($materias as $materia) {
                    if ($materia && isset($materia->nombre)) {
                        $buttons[] = [
                            ['text' => $materia->nombre . ' ⭐', 'callback_data' => 'materia_' . $materia->id],
                        ];
                    }
                }

                // Editar el mensaje inicial con una animación temporal
                $messageId = Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Cargando materias... ✨',
                ])->getMessageId();

                // Reemplazar con los botones después de un breve retraso
                sleep(1);
                Telegram::editMessageText([
                    'chat_id' => $chatId,
                    'message_id' => $messageId,
                    'text' => 'Selecciona una materia para ver tus notas:',
                    'reply_markup' => json_encode([
                        'inline_keyboard' => $buttons,
                    ]),
                ]);
                Log::info('Mensaje con botones enviado', ['message_id' => $messageId]);
            } else {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'No estás registrado como estudiante. Por favor, contacta a un administrador.',
                ]);
                Log::info('Mensaje enviado: No estás registrado');
            }
        } catch (\Exception $e) {
            Log::error('Error en handleContact', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    private function handleCallbackQuery(Update $update, int $chatId)
    {
        try {
            $callbackData = $update->getCallbackQuery()->getData();
            $materiaId = str_replace('materia_', '', $callbackData);
            Log::info('Callback recibido', ['callback_data' => $callbackData, 'materia_id' => $materiaId, 'chat_id' => $chatId]);

            // Recuperar el estudiante_id desde el caché
            $estudianteId = Cache::get('student_chat_' . $chatId);
            Log::info('Buscando estudiante en caché', ['chat_id' => $chatId, 'estudiante_id' => $estudianteId]);

            if (!$estudianteId) {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'No se encontró tu información. Por favor, comparte tu número de teléfono nuevamente.',
                ]);
                Log::info('Mensaje enviado: Estudiante no encontrado en caché');
                return;
            }

            $estudiante = Estudiante::find($estudianteId);
            if (!$estudiante) {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Estudiante no encontrado. Por favor, comparte tu número de teléfono nuevamente.',
                ]);
                Log::info('Mensaje enviado: Estudiante no encontrado en la base de datos');
                return;
            }

            $notas = Nota::where('materia_id', $materiaId)
                         ->where('estudiante_id', $estudiante->id)
                         ->get();

            if ($notas->isEmpty()) {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'No tienes notas para esta materia.',
                ]);
                Log::info('Mensaje enviado: No tienes notas');
            } else {
                // Obtener el nombre de la materia
                $materia = Materia::find($materiaId);
                $materiaNombre = $materia ? $materia->nombre : 'Materia';

                $notaFinalMax = $notas->max('nota_final');
                $effectId = null;
                $emoji = '';

                if ($notaFinalMax >= 9) {
                    $effectId = 6249751749914165760; // Efecto "fuego artificial" (onfire)
                    $emoji = '🔥';
                } elseif ($notaFinalMax >= 7) {
                    $effectId = 5854473919286936938; // Efecto "confeti"
                    $emoji = '🎉';
                } elseif ($notaFinalMax >= 5) {
                    $emoji = '👍';
                } else {
                    $emoji = '😞';
                }

                // Formato vertical con MarkdownV2
                $response = "Notas de $materiaNombre $emoji:\n\n";
                foreach ($notas as $index => $nota) {
                    $t1 = $nota->trimestre1 ?? 'N/A';
                    $t2 = $nota->trimestre2 ?? 'N/A';
                    $t3 = $nota->trimestre3 ?? 'N/A';
                    $nf = $nota->nota_final ?? 'N/A';
                    // Escapar puntos en valores numéricos para MarkdownV2
                    $t1 = is_numeric($t1) ? str_replace('.', '\.', $t1) : $t1;
                    $t2 = is_numeric($t2) ? str_replace('.', '\.', $t2) : $t2;
                    $t3 = is_numeric($t3) ? str_replace('.', '\.', $t3) : $t3;
                    $nf = is_numeric($nf) ? str_replace('.', '\.', $nf) : $nf;
                    $response .= "Trimestre I: **$t1**\n";
                    $response .= "Trimestre II: **$t2**\n";
                    $response .= "Trimestre III: **$t3**\n"; // Corregido de "Trimestre II" a "Trimestre III"
                    $response .= "Nota Final: **$nf**\n";
                    if ($index < $notas->count() - 1) {
                        $response .= "────────────\n"; // Separador entre notas si hay más de una
                    }
                }

                // Enviar mensaje con efecto y formato
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $response,
                    'effect' => $effectId,
                    'parse_mode' => 'MarkdownV2',
                ]);
                Log::info('Mensaje con notas enviado', ['response' => $response, 'effect' => $effectId]);
            }
        } catch (\Exception $e) {
            Log::error('Error en handleCallbackQuery', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    private function requestPhoneNumber(int $chatId)
    {
        Log::info('Solicitando número de teléfono', ['chat_id' => $chatId]);

        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => 'Por favor, comparte tu número de teléfono para verificar tu identidad.',
            'reply_markup' => json_encode([
                'keyboard' => [[
                    ['text' => 'Compartir número de teléfono', 'request_contact' => true],
                ]],
                'one_time_keyboard' => true,
                'resize_keyboard' => true,
            ]),
        ]);
    }
}