<?php

namespace App\Http\Controllers;
use App\Models\Conta;
use Illuminate\Http\Request;
use App\Services\ContaService;

class ContaController extends Controller
{

    private $contaService;
    public function __construct(ContaService $contaService)
    {
        $this->contaService = $contaService;
    }
    

    public function store(Request $request)
    {
        $aux = $this->contaService->criarConta($request->conta);
        
        if($aux->status() == 200){
            return redirect()->route('formLogin')->with(['mensagem' => 'Conta criada com sucesso']);
        }
        
        if($aux->status() == 400) {
            return redirect()->back()->with(['mensagem' => 'Conta já existe']);
        }

        return redirect()->back()->with(['mensagem' => 'Erro ao criar conta']);
    }
}