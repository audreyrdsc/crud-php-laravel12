<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rede;
use Carbon\Carbon;

class RedeSocialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //data de hoje formatada no padrão BR
        $hoje = Carbon::now()->Format('d/m/Y');
        //data de hoje formatada no padrão do BD para conultas
        //$hoje = Carbon::now()->Format('Y/m/d');
        //PHP puro: $hoje = date('d/m/Y H:i:s');
        //$amanha = Carbon::now()->addDay()->Format('d/m/Y');
        //$hoje = $amanha;
        //PHP puro: $hoje = date('d/m/Y, strtotime(" + 1 day"))
        //Semana passada: $semPassada = Carbon::now()->subWeek();
        //Se for hoje: $hoje = Carbon::now()->isToday(); //True ou False
        //Se Sábado ou Domingo: $hoje = Carbon::now()->isWeekend(); //True ou False

        //True se for o mesmo dia, usado em cobrança
        //$data = '2025-10-08'; //Data de referência
        //$hoje = Carbon::now()->isSameDay($data); //True ou False

        //$redes = Rede::all(); //Pega todos os registros da tabela 'redes' sem paginação
        //$redes = Rede::paginate(3); //Pega todos os registros da tabela 'redes' com paginação de 3 em 3
        $busca = $request->input('busca'); //Pega o valor do campo de busca
        $redes = Rede::when($busca, function($query, $busca) {
            return $query->where('nome', 'like', "%{$busca}%")
                         ->orWhere('link', 'like', "%{$busca}%");
        })
        //->whereDate('created_at', $hoje) //filtra registros pela data específica
        //->whereDate('created_at', '<=', $hoje) //filtra registros até a data de hoje
        //->whereMonth('created_at', '<=', 7) //filtra registros até o mês 7
        //->whereYear('created_at', '<=', 2024) //filtra registros até o ano 2024
        //pode combinar por data, mês e ano
        ->orderBy('created_at', 'desc')
        ->paginate(4); //Pega os registros da tabela 'redes' com paginação de 3 em 3, filtrando pela busca se houver
        
        //Acessa a pasta resources/views/redes-sociais/index.blade.php
        return view('redes-sociais.index', compact(
            'redes', 
            'busca',
            'hoje'));
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
            'capa' => 'required|image|max:3000', //validação para imagem, max 3MB
        ]);

        $rede = new Rede();

        if($request->hasFile('capa')) {
            $arquivo = $request->file('capa'); //Pega o arquivo
            $nomeArquivo = uniqid() . '-msflix-.' . $arquivo->getClientOriginalExtension(); //Gera um nome único para o arquivo
            $arquivo->move(public_path('uploads'), $nomeArquivo); //Move o arquivo para a pasta public/uploads
            $rede->capa = 'uploads/' . $nomeArquivo; //Salva o caminho da imagem no banco de dados
        }

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
