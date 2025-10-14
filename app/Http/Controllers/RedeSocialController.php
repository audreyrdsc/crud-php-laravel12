<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rede;

class RedeSocialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //$redes = Rede::all(); //Pega todos os registros da tabela 'redes' sem paginação
        //$redes = Rede::paginate(3); //Pega todos os registros da tabela 'redes' com paginação de 3 em 3
        $busca = $request->input('busca'); //Pega o valor do campo de busca
        $redes = Rede::when($busca, function($query, $busca) {
            return $query->where('nome', 'like', "%{$busca}%")
                         ->orWhere('link', 'like', "%{$busca}%");
        })->paginate(2); //Pega os registros da tabela 'redes' com paginação de 3 em 3, filtrando pela busca se houver
        
        //Acessa a pasta resources/views/redes-sociais/index.blade.php
        return view('redes-sociais.index', compact('redes', 'busca'));
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
        $rede->video = $request->video;

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
        $rede = Rede::findOrFail($id); //Busca a rede social pelo ID, ou retorna erro 404 se não encontrar
        return view('redes-sociais.edit', compact('rede')); //Acessa a view de edição, passando a rede social encontrada
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'link' => ['required', 'url', 'max:255'],
        ]);

        $rede = Rede::findOrFail($id); //Busca a rede social pelo ID, ou retorna erro 404 se não encontrar

        $rede->nome = $request->nome;
        $rede->link = $request->link;
        $rede->video = $request->video;

        $rede->save();

        return redirect()
                ->route('redes-sociais.index')
                ->with('updated', 'Rede Social atualizada com sucesso!');
                //sucess é uma variavel de sessão que retornará a mensagem em index.blade.php
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rede = Rede::findOrFail($id); //Busca a rede social pelo ID, ou retorna erro 404 se não encontrar
        $rede->delete(); //Deleta o registro encontrado

        return redirect()
                ->route('redes-sociais.index')
                ->with('deleted', 'Rede Social deletada com sucesso!');
                //deleted é uma variavel de sessão que retornará a mensagem em index.blade.php
    }
}
