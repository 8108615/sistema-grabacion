<x-layouts::app title="Panel de Grabación">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        <!-- Cabecera de la sección -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">Panel de Grabación SDI</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Control y monitoreo de canales físicos de la DeckLink Quad 2.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center rounded-md bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-400/10 dark:text-green-400">
                    <span class="mr-1.5 h-2 w-2 rounded-full bg-green-500 animate-pulse"></span> Sistema Activo
                </span>
            </div>
        </div>

        <!-- Cuadrícula de Canales (4 canales de la DeckLink) -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            @foreach($canales as $canal)
                <div class="flex flex-col justify-between rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">

                    <!-- Cabecera de la Tarjeta -->
                    <div>
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-zinc-800 dark:text-zinc-100">
                                <i class="fas fa-video mr-2 text-indigo-500"></i> {{ $canal->nombre }}
                            </h2>
                            <!-- Indicador de Estado -->
                            @if($canal->estado == 'grabando')
                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-600 dark:bg-red-900/30 dark:text-red-400">
                                    🔴 GRABANDO
                                </span>
                            @else
                                <span class="rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">
                                    ⚪ DETENIDO
                                </span>
                            @endif
                        </div>

                        <!-- Detalles técnicos -->
                        <div class="mt-4 space-y-2 text-sm text-zinc-600 dark:text-zinc-400">
                            <div class="flex justify-between border-b border-zinc-100 pb-2 dark:border-zinc-800">
                                <span class="font-medium">Índice SDI:</span>
                                <span>Canal {{ $canal->canal_index }}</span>
                            </div>
                            <div class="flex justify-between border-b border-zinc-100 pb-2 dark:border-zinc-800">
                                <span class="font-medium">Resolución:</span>
                                <span class="font-mono text-xs bg-zinc-100 px-2 py-0.5 rounded dark:bg-zinc-800 text-zinc-300">{{ $canal->resolucion }}</span>
                            </div>
                            <div class="flex justify-between border-b border-zinc-100 pb-2 dark:border-zinc-800">
                                <span class="font-medium">Destino:</span>
                                <span class="font-mono text-xs text-zinc-400">D:\GRABACIONES\</span>
                            </div>
                            <div class="flex justify-between pb-2">
                                <span class="font-medium">Archivo actual:</span>
                                <span class="truncate max-w-[180px] text-zinc-400 font-mono text-xs">{{ $canal->archivo_actual ?? 'Ninguno' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Botones y Formulario de Acción -->
                    <div class="mt-6 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                        @if($canal->estado == 'grabando')
                            <!-- Si está grabando, solo mostramos el botón para detener -->
                            <form action="{{ route('admin.grabaciones.toggle', $canal->id) }}" method="POST" class="w-full">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 transition-colors cursor-pointer">
                                    <i class="fas fa-stop"></i> Detener Grabación
                                </button>
                            </form>
                        @else
                            <!-- Si está detenido, mostramos el campo de texto para personalizar el nombre y el botón de iniciar -->
                            <form action="{{ route('admin.grabaciones.toggle', $canal->id) }}" method="POST" class="w-full space-y-3">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-2 gap-2">
                                    <!-- Selector de Formato -->
                                    <div>
                                        <label for="formato_{{ $canal->id }}" class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1">Formato:</label>
                                        <select id="formato_{{ $canal->id }}"
                                                name="formato"
                                                class="w-full rounded-lg border border-zinc-300 bg-zinc-50 px-2 py-1.5 text-sm text-zinc-900 focus:border-white focus:outline-none focus:ring-1 focus:ring-white dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                                            <option value="mxf" selected>MXF (Pro)</option>
                                            <option value="mp4">MP4</option>
                                            <option value="mov">MOV</option>
                                            <option value="avi">AVI</option>
                                            <option value="mp3">MP3 (Audio)</option>
                                        </select>
                                    </div>

                                    <!-- Nombre del archivo -->
                                    <div>
                                        <label for="nombre_archivo_{{ $canal->id }}" class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1">Nombre:</label>
                                        <input type="text"
                                            id="nombre_archivo_{{ $canal->id }}"
                                            name="nombre_archivo"
                                            placeholder="ej. Bolivia_vs_Peru"
                                            class="w-full rounded-lg border border-zinc-300 bg-zinc-50 px-3 py-1.5 text-sm text-zinc-900 focus:border-white focus:outline-none focus:ring-1 focus:ring-white dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-500">
                                    </div>
                                </div>

                                <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors cursor-pointer">
                                    <i class="fas fa-circle text-red-400"></i> Iniciar Grabación
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</x-layouts::app>
