<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rede;

class RedeSocialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $redes = Rede::all(); //Pega todos os registros da tabela 'redes'
        
        //Acessa a pasta resources/views/redes-sociais/index.blade.php
        return view('redes-sociais.index', compact('redes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Acessa a pasta resources/views/redes-sociais/create.blade.php
        return view('redes-sociais.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'nome' => 'required|string|max:100',
            'link' => 'required|url|max:255',
        ]);

        $rede = new Rede();

        $rede->nome = $request->nome;
        $rede->link = $request->link;

        $rede->save();

        return redirect()
                ->route('redes-sociais.index')
                ->with('success', 'Rede Social cadastrada com sucesso!');
                //sucess é uma variavel de sessão que retornará a mensagem em index.blade.php

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
