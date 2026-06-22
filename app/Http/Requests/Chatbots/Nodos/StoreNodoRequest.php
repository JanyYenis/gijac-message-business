<?php

namespace App\Http\Requests\Chatbots\Nodos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreNodoRequest extends FormRequest
{
    /* ─── Tipos de nodo válidos (alineados con NODE_DEFS del frontend) ──── */
    const TIPOS_NODO = [
        'start', 'text', 'image', 'video', 'doc', 'audio',
        'buttons', 'list', 'question', 'capture',
        'condition', 'variable', 'tag', 'goto',
        'webhook', 'api', 'agent', 'end',
        'ai', 'pdf', 'generate',
    ];

    /* ─── Tipos de nodo que requieren archivo multimedia ────────────────── */
    const NODOS_MEDIA = ['image', 'video', 'doc', 'audio', 'pdf'];

    /* ─── Acciones permitidas ───────────────────────────────────────────── */
    const ACCIONES = ['draft', 'published', 'export'];

    /* =====================================================================
       Preparar datos antes de validar
       El payload llega como JSON string en el campo "payload".
       Lo decodificamos y lo mezclamos con el request para poder validarlo.
       ===================================================================== */
    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();
        $raw = $this->input('payload');

        if (!$raw) return;

        $decoded = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) return;

        // Mezclar el payload decodificado con el request
        $this->merge([
            'action'      => $decoded['action']      ?? null,
            'drawflow'    => $decoded['drawflow']     ?? null,
            'nodes'       => $decoded['nodes']        ?? [],
            'connections' => $decoded['connections']  ?? [],
        ]);
    }

    /* =====================================================================
       Reglas de validación
       ===================================================================== */
    public function rules(): array
    {
        return [

            /* ── Raíz ───────────────────────────────────────────────────── */
            'action'       => ['required', 'string', 'in:' . implode(',', self::ACCIONES)],
            'drawflow'     => ['required', 'array'],
            'nodes'        => ['required', 'array', 'min:1'],
            'connections'  => ['nullable', 'array'],

            /* ── Nodos ───────────────────────────────────────────────────── */
            'nodes.*.drawflow_id'  => ['required', 'string'],
            'nodes.*.type'         => ['required', 'string', 'in:' . implode(',', self::TIPOS_NODO)],
            'nodes.*.label'        => ['required', 'string', 'max:120'],
            'nodes.*.pos_x'        => ['required', 'numeric'],
            'nodes.*.pos_y'        => ['required', 'numeric'],
            'nodes.*.inputs'       => ['required', 'integer', 'min:0', 'max:10'],
            'nodes.*.outputs'      => ['required', 'integer', 'min:0', 'max:10'],
            'nodes.*.principal'    => ['required', 'integer', 'in:0,1'],
            'nodes.*.config'       => ['nullable', 'array'],

            /* ── Conexiones ──────────────────────────────────────────────── */
            'connections.*.source_node_drawflow_id' => ['required', 'string'],
            'connections.*.target_node_drawflow_id' => ['required', 'string'],
            'connections.*.source_output'           => ['required', 'string', 'regex:/^output_\d+$/'],
            'connections.*.target_input'            => ['required', 'string', 'regex:/^input_\d+$/'],
        ];
    }

    /* =====================================================================
       Validaciones adicionales (after)
       ===================================================================== */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {

            $nodes       = collect($this->input('nodes', []));
            $connections = collect($this->input('connections', []));
            $nodeIds     = $nodes->pluck('drawflow_id');

            /* 1. Debe existir exactamente un nodo de inicio */
            $starts = $nodes->where('principal', 1);
            if ($starts->count() === 0) {
                $v->errors()->add('nodes', 'El flujo debe tener al menos un nodo de Inicio.');
            }
            if ($starts->count() > 1) {
                $v->errors()->add('nodes', 'El flujo solo puede tener un nodo de Inicio.');
            }

            /* 2. Debe existir al menos un nodo de fin */
            if ($nodes->where('type', 'end')->count() === 0) {
                $v->errors()->add('nodes', 'El flujo debe tener al menos un nodo de Finalizar.');
            }

            /* 3. Los drawflow_id en conexiones deben existir en el listado de nodos */
            foreach ($connections as $i => $conn) {
                if (!$nodeIds->contains($conn['source_node_drawflow_id'])) {
                    $v->errors()->add(
                        "connections.{$i}.source_node_drawflow_id",
                        'El nodo origen de la conexión no existe en el flujo.'
                    );
                }
                if (!$nodeIds->contains($conn['target_node_drawflow_id'])) {
                    $v->errors()->add(
                        "connections.{$i}.target_node_drawflow_id",
                        'El nodo destino de la conexión no existe en el flujo.'
                    );
                }
            }

            /* 4. Validar config interna según el tipo de cada nodo */
            foreach ($nodes as $i => $node) {
                $this->validarConfigNodo($v, $node, $i, $nodeIds->toArray());
            }

            /* 5. Archivos requeridos para nodos multimedia sin URL previa */
            foreach ($nodes as $node) {
                if (!in_array($node['type'], self::NODOS_MEDIA)) continue;

                $cfg = $node['config'] ?? [];
                $nid = $node['drawflow_id'];

                // Si no tiene media_url guardada, debe llegar el archivo en el request
                if (empty($cfg['media_url']) && !$this->hasFile("file_{$nid}")) {
                    $v->errors()->add(
                        'nodes',
                        "El nodo \"{$node['label']}\" ({$node['type']}) requiere un archivo multimedia."
                    );
                }
            }

            /* 6. Solo se permite un nodo principal (is_start) */
            $principales = $nodes->where('principal', 1);
            if ($principales->count() > 1) {
                $v->errors()->add('nodes', 'Solo puede existir un nodo marcado como principal.');
            }
        });
    }

    /* =====================================================================
       Validar la config interna de un nodo según su tipo
       ===================================================================== */
    private function validarConfigNodo(Validator $v, array $node, int $idx, array $nodeIds): void
    {
        $type   = $node['type'];
        $cfg    = $node['config'] ?? [];
        $label  = $node['label'] ?? "Nodo #{$idx}";
        $prefix = "nodes.{$idx}.config";

        // Helper local para agregar error con contexto del nodo
        $err = function (string $campo, string $msg) use ($v, $prefix, $label) {
            $v->errors()->add("{$prefix}.{$campo}", "[{$label}] {$msg}");
        };

        switch ($type) {

            case 'text':
            case 'question':
                if (empty(trim($cfg['message'] ?? ''))) {
                    $err('message', 'El mensaje es obligatorio.');
                } elseif (mb_strlen($cfg['message']) > 4096) {
                    $err('message', 'El mensaje no puede superar 4096 caracteres (límite WhatsApp).');
                }
                break;

            case 'buttons':
                if (empty(trim($cfg['message'] ?? ''))) {
                    $err('message', 'El mensaje es obligatorio.');
                }
                $buttons = $cfg['buttons'] ?? [];
                if (count($buttons) < 1) {
                    $err('buttons', 'Debe configurar al menos 1 botón.');
                }
                if (count($buttons) > 3) {
                    $err('buttons', 'WhatsApp permite máximo 3 botones.');
                }
                foreach ($buttons as $bi => $btn) {
                    if (empty(trim($btn['label'] ?? ''))) {
                        $err("buttons.{$bi}.label", 'La etiqueta del botón es obligatoria.');
                    } elseif (mb_strlen($btn['label']) > 20) {
                        $err("buttons.{$bi}.label", 'La etiqueta del botón no puede superar 20 caracteres (límite WhatsApp).');
                    }
                    if (!empty($btn['target_node_id']) && !in_array($btn['target_node_id'], $nodeIds)) {
                        $err("buttons.{$bi}.target_node_id", 'El nodo destino del botón no existe en el flujo.');
                    }
                }
                break;

            case 'list':
                if (empty(trim($cfg['message'] ?? ''))) {
                    $err('message', 'El mensaje es obligatorio.');
                }
                if (empty(trim($cfg['list_title'] ?? ''))) {
                    $err('list_title', 'El título de la lista es obligatorio.');
                }

                // Filtrar las filas que tengan un label válido (no vacío)
                $validRows = array_filter($cfg['rows'] ?? [], fn($r) => !empty(trim($r['label'] ?? '')));

                if (count($validRows) < 1) {
                    $err('rows', 'Debe agregar al menos 1 opción a la lista.');
                }
                if (count($validRows) > 10) {
                    $err('rows', 'WhatsApp permite máximo 10 opciones por lista.');
                }

                // Iterar las rows para validar cada propiedad internamente
                foreach ($cfg['rows'] ?? [] as $ri => $row) {
                    if (empty(trim($row['label'] ?? ''))) {
                        $err("rows.{$ri}.label", 'La etiqueta de la opción es obligatoria.');
                    } elseif (mb_strlen($row['label']) > 24) {
                        $err("rows.{$ri}.label", 'La opción no puede superar 24 caracteres (límite WhatsApp).');
                    }

                    // Validar que el nodo destino exista en el flujo (igual que haces con los botones)
                    if (!empty($row['target_node_id']) && !in_array($row['target_node_id'], $nodeIds)) {
                        $err("rows.{$ri}.target_node_id", 'El nodo destino de la opción no existe en el flujo.');
                    }
                }

                if (!empty($cfg['list_target']) && !in_array($cfg['list_target'], $nodeIds)) {
                    $err('list_target', 'El nodo destino de la lista no existe en el flujo.');
                }
                break;

            case 'condition':
                if (empty(trim($cfg['variable'] ?? ''))) {
                    $err('variable', 'La variable a evaluar es obligatoria.');
                }
                if (empty($cfg['operator'])) {
                    $err('operator', 'El operador es obligatorio.');
                }
                $operadores = ['equals', 'contains', 'greater_than', 'less_than', 'regex'];
                if (!in_array($cfg['operator'] ?? '', $operadores)) {
                    $err('operator', 'Operador no válido.');
                }
                if (empty(trim($cfg['compare'] ?? ''))) {
                    $err('compare', 'El valor de comparación es obligatorio.');
                }
                if (!empty($cfg['true_target']) && !in_array($cfg['true_target'], $nodeIds)) {
                    $err('true_target', 'El nodo de la rama Verdadero no existe en el flujo.');
                }
                if (!empty($cfg['false_target']) && !in_array($cfg['false_target'], $nodeIds)) {
                    $err('false_target', 'El nodo de la rama Falso no existe en el flujo.');
                }
                break;

            case 'webhook':
            case 'api':
                $metodos = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];
                if (!in_array(strtoupper($cfg['method'] ?? ''), $metodos)) {
                    $err('method', 'El método HTTP no es válido.');
                }
                if (empty(trim($cfg['url'] ?? ''))) {
                    $err('url', 'La URL del webhook/API es obligatoria.');
                } elseif (!filter_var($cfg['url'], FILTER_VALIDATE_URL)) {
                    $err('url', 'La URL no tiene un formato válido.');
                }
                // Validar que headers sea JSON válido si viene como string
                if (!empty($cfg['headers']) && is_string($cfg['headers'])) {
                    json_decode($cfg['headers']);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $err('headers', 'Las cabeceras deben ser un JSON válido.');
                    }
                }
                break;

            case 'ai':
            case 'generate':
                if (empty(trim($cfg['system_prompt'] ?? ''))) {
                    $err('system_prompt', 'El prompt del sistema es obligatorio.');
                }
                if (isset($cfg['temperature']) && ($cfg['temperature'] < 0 || $cfg['temperature'] > 1)) {
                    $err('temperature', 'La temperatura debe estar entre 0 y 1.');
                }
                if (isset($cfg['max_tokens']) && $cfg['max_tokens'] < 1) {
                    $err('max_tokens', 'Los tokens máximos deben ser mayor a 0.');
                }
                break;

            case 'capture':
                if (empty(trim($cfg['variable'] ?? ''))) {
                    $err('variable', 'El nombre de la variable es obligatorio.');
                } elseif (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $cfg['variable'])) {
                    $err('variable', 'El nombre de variable solo puede contener letras, números y guion bajo.');
                }
                $tiposDato = ['text', 'number', 'email', 'phone', 'date'];
                if (!in_array($cfg['data_type'] ?? '', $tiposDato)) {
                    $err('data_type', 'El tipo de dato no es válido.');
                }
                break;

            case 'variable':
                if (empty(trim($cfg['var_name'] ?? ''))) {
                    $err('var_name', 'El nombre de la variable es obligatorio.');
                } elseif (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $cfg['var_name'])) {
                    $err('var_name', 'El nombre de variable solo puede contener letras, números y guion bajo.');
                }
                if (!isset($cfg['var_value'])) {
                    $err('var_value', 'El valor de la variable es obligatorio.');
                }
                break;

            case 'goto':
                if (empty($cfg['target_node_id'])) {
                    $err('target_node_id', 'Debe seleccionar el nodo destino.');
                } elseif (!in_array($cfg['target_node_id'], $nodeIds)) {
                    $err('target_node_id', 'El nodo destino no existe en el flujo.');
                }
                break;

            case 'agent':
                if (empty(trim($cfg['department'] ?? ''))) {
                    $err('department', 'El departamento es obligatorio.');
                }
                break;

            case 'start':
                if (($cfg['trigger'] ?? 'any') === 'keyword') {
                    $keywords = array_filter($cfg['keywords'] ?? [], fn($k) => trim($k) !== '');
                    if (count($keywords) === 0) {
                        $err('keywords', 'Debe agregar al menos una palabra clave cuando el disparador es "Palabra clave".');
                    }
                }
                break;

            case 'end':
                // if (empty(trim($cfg['close_message'] ?? ''))) {
                //     $err('close_message', 'El mensaje de cierre es obligatorio.');
                // }
                break;

            // image, video, doc, audio, pdf, tag → validados en el after o no requieren config obligatoria
        }
    }

    /* =====================================================================
       Mensajes personalizados
       ===================================================================== */
    public function messages(): array
    {
        return [
            'action.required'             => 'La acción (guardar/publicar) es obligatoria.',
            'action.in'                   => 'La acción no es válida.',
            'drawflow.required'           => 'El estado del canvas es obligatorio.',
            'nodes.required'              => 'El flujo no tiene nodos.',
            'nodes.min'                   => 'El flujo debe tener al menos un nodo.',
            'nodes.*.drawflow_id.required'=> 'Cada nodo debe tener un ID de Drawflow.',
            'nodes.*.type.required'       => 'Cada nodo debe tener un tipo.',
            'nodes.*.type.in'             => 'El tipo de nodo ":input" no es válido.',
            'nodes.*.label.required'      => 'Cada nodo debe tener una etiqueta.',
            'nodes.*.label.max'           => 'La etiqueta del nodo no puede superar 120 caracteres.',
            'nodes.*.pos_x.required'      => 'La posición X del nodo es obligatoria.',
            'nodes.*.pos_y.required'      => 'La posición Y del nodo es obligatoria.',
            'connections.*.source_output.regex' => 'El puerto de salida debe tener el formato output_N.',
            'connections.*.target_input.regex'  => 'El puerto de entrada debe tener el formato input_N.',
        ];
    }

    /* =====================================================================
       Respuesta en caso de fallo (siempre JSON para ajax)
       ===================================================================== */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'El flujo tiene errores de validación.',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}
