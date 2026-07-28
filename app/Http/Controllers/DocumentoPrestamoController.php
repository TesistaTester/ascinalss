<?php
// app/Http/Controllers/DocumentoPrestamoController.php

namespace App\Http\Controllers;

use App\Models\CategoriaPrestamo;
use App\Models\DocumentoPrestamo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentoPrestamoController extends Controller
{
    public function store(Request $request, CategoriaPrestamo $categoriasPrestamo)
    {
        $datos = $request->validate([
            'doc_tipo' => 'required|in:requisitos,contrato,formulario',
            'doc_etiqueta' => 'required|string|max:255',
            'doc_pdf_archivo' => 'required|file|mimes:pdf|max:5120',
            'doc_orden' => 'nullable|integer',
        ]);

        $datos['doc_pdf_archivo'] = $request->file('doc_pdf_archivo')->store('prestamos/documentos', 'public');
        $datos['doc_categoria_id'] = $categoriasPrestamo->cat_id;
        $datos['doc_estado'] = true;
        $datos['doc_orden'] = $datos['doc_orden'] ?? 0;

        DocumentoPrestamo::create($datos);

        return back()->with('exito', 'Documento agregado correctamente.');
    }

    public function destroy(DocumentoPrestamo $documento)
    {
        if ($documento->doc_pdf_archivo) {
            Storage::disk('public')->delete($documento->doc_pdf_archivo);
        }

        $documento->delete();

        return back()->with('exito', 'Documento eliminado correctamente.');
    }
}