<?php

namespace App\Http\Controllers;

use App\Models\Archivo;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function __construct()
    {
        $this -> middleware('auth') -> except('index', 'show');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $productos = Producto::all();
        return view('productos/productoIndex', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('productos.productoCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        //Validacion
        $request -> validate([
            'nombre_producto' => 'required|max:255',
            'marca' => 'required',
            'descripcion' => ['required', 'min:10'],
            'precio' => ['required', 'decimal:0, 2' ],
            'categoria' => 'required',
            'deporte' => 'required',
            'estado' => 'required',
            'tienda' => 'required',
            'archivo' => 'required',
//+++++++++Comentar Para Realizar test+++++++++++++++++++++++++
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        ]);
    
        //Guardar
        $request->merge(['user_id' => Auth::id()]);
        // Producto::create($request->all());
        $producto = Producto::create($request->all());

//++++++++++++++Comentar para Realizar el test***********************************************************************
        if ($request->file('archivo')->isValid()) {
            $producto->archivos()->create([
                'ubicacion' => $request->archivo->store('archivos_productos', 'public'),
                'nombre_original' => $request->archivo->getClientOriginalName(),
                'mime' => $request->file('archivo')->getClientMimeType(),
            ]);
        }
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

        //Redireccionar
        return redirect()->route('producto.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        //
        return view('productos.productoShow', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        //
        $this->authorize('update', $producto);

        return view('productos.productoEdit', compact('producto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        //
        $this->authorize('update', $producto);
         //Validacion
         $request -> validate([
            'nombre_producto' => 'required|max:255',
            'marca' => 'required',
            'descripcion' => ['required', 'min:10'],
            'precio' => ['required', 'decimal:0, 2' ],
            'categoria' => 'required',
            'deporte' => 'required',
            'estado' => 'required',
            'tienda' => 'required',


        ]);
    

        $producto->update($request->all());
    
        //Redireccionar
        return redirect()->route('producto.show', $producto);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        //
        $this->authorize('delete', $producto);
        
        $producto -> delete();
        return redirect() -> route('producto.index');
    }

    public function download(Archivo $archivo)
    {
        return response()
        ->download(storage_path('app/public/' . $archivo->ubicacion), $archivo->nombre_original);
    }
}
