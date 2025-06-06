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
        $aux = $this->contaService->criarConta($request->conta, $request->tipo);
        
        if($aux->status() == 200){
            return redirect()->route('formLogin')->with(['mensagem' => 'Conta criada com sucesso']);
        }
        
        if($aux->status() == 400) {
            return redirect()->back()->with(['mensagem' => $aux->original["mensagem"]]);
        }

        return redirect()->back()->with(['mensagem' => 'Erro ao criar conta']);
    }

    public function getBalance(Request $request)
    {
        $aux = $this->contaService->getConta($request->conta);

        if($aux->status() == 200){
            return redirect()->back()->with(['saldo' => $aux->original['conta']->saldo]);
        }
        if($aux->status() == 400) {
            return redirect()->back()->with(['mensagem' => 'Conta não encontrada']);
        }
        return redirect()->back()->with(['mensagem' => 'Erro ao buscar conta']);
    }


    public function subtractValue(Request $request)
    {
        $aux = $this->contaService->subtractValue($request->conta, $request->valor);
        if($aux->status() == 200){
            return redirect()->back()->with(['mensagem' => 'Valor removido com sucesso', 'saldo' => $aux->original['conta']->saldo]);
        }
        if($aux->status() == 400) {
            return redirect()->back()->with(['mensagem' => $aux->original["mensagem"]]);
        }
    }
  
    public function addValue(Request $request)
    {
        $aux = $this->contaService->addValue($request->conta, $request->valor);

        if($aux->status() == 200){
            return redirect()->back()->with(['mensagem' => 'Valor adicionado com sucesso', 'saldo' => $aux->original['conta']->saldo]);
        }
        if($aux->status() == 400) {
            return redirect()->back()->with(['mensagem' => 'Conta não encontrada']);
        }
    }

    public function transferValue(Request $request)
    {
        $aux = $this->contaService->transferValue($request->conta, $request->conta2, $request->valor);

        if($aux->status() == 200){
            return redirect()->back()->with(['mensagem' => 'Valor adicionado com sucesso', 'saldo' => $aux->original['conta']->saldo, 'saldo2' => $aux->original['conta2']->saldo]);
        }
        if($aux->status() == 400) {
            return redirect()->back()->with(['mensagem' => $aux->original["mensagem"]]);
        }
    }

    public function renderJuros(Request $request) {
        $aux = $this->contaService->renderJuros($request->taxaPercentual);
        if($aux->status() == 200){
            return redirect()->back()->with(['mensagem' => 'Juros renderizados com sucesso']);
        }
        if($aux->status() == 400) {
            return redirect()->back()->with(['mensagem' => $aux->original["mensagem"]]);
        }
    }
}