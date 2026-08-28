<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GrabacionCanal;
use Illuminate\Support\Str;

class GrabacionController extends Controller
{
    public function index()
    {
        // Obtenemos los 4 canales de la DeckLink Quad 2 ordenados por su índice
        $canales = GrabacionCanal::orderBy('canal_index', 'asc')->get();

        return view('admin.grabaciones.index', compact('canales'));
    }

    public function toggleGrabacion(Request $request, $id)
    {
        try {
            $canal = GrabacionCanal::findOrFail($id);

            if ($canal->estado === 'grabando') {
                // Lógica para detener la captura de la DeckLink
                $canal->estado = 'detenido';
                $canal->archivo_actual = null;
                $mensajeTexto = 'Grabación detenida correctamente para ' . $canal->nombre;
            } else {
                // 1. Definir la ruta física en la Unidad D
                $directorioDestino = 'D:/GRABACIONES/';

                // 2. Verificar si la carpeta existe en el equipo, si no, crearla
                if (!file_exists($directorioDestino)) {
                    mkdir($directorioDestino, 0755, true);
                }

                // 3. Obtener formato y nombre personalizado
                $nombrePersonalizado = $request->input('nombre_archivo');
                $formatoSeleccionado = $request->input('formato', 'mxf'); // Por defecto MXF

                if (!empty($nombrePersonalizado)) {
                    $nombreLimpio = Str::slug($nombrePersonalizado, '_');
                    $nombreArchivoFinal = $nombreLimpio . '_' . date('His') . '.' . $formatoSeleccionado;
                } else {
                    $nombreArchivoFinal = 'captura_' . date('Y-m-d_H-i-s') . '.' . $formatoSeleccionado;
                }

                // Ruta absoluta completa donde se guardará el archivo físico
                $rutaCompletaArchivo = $directorioDestino . $nombreArchivoFinal;

                // Aquí puedes guardar $rutaCompletaArchivo si deseas registrar la ruta entera en la BD
                $canal->estado = 'grabando';
                $canal->archivo_actual = $nombreArchivoFinal; // O $rutaCompletaArchivo si prefieres ver la ruta completa
                $mensajeTexto = 'Iniciando grabación en Unidad D: ' . $nombreArchivoFinal;
            }

            $canal->save();

            return redirect()->route('admin.grabaciones.index')->with([
                'mensaje' => $mensajeTexto,
                'icono' => 'success'
            ]);

        } catch (\Exception $e) {
            return redirect()->route('admin.grabaciones.index')->with([
                'mensaje' => 'Ocurrió un error al cambiar el estado del canal: ' . $e->getMessage(),
                'icono' => 'error'
            ]);
        }
    }
}
