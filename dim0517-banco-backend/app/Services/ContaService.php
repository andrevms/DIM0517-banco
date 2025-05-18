<?php
namespace App\Services;
use App\Models\Conta;
use Illuminate\Support\Facades\DB;

class ContaService
{

    private $contaModel;
    public function __construct(Conta $contaModel)
    {
        $this->contaModel = $contaModel;
    }

    public function criarConta(int $conta)
    {
        $contaExistente = $this->contaModel->where('conta', $conta)->first();
        if ($contaExistente) {
            return response()->json(['message' => 'Conta já existente'], 400);
        }

        DB::table('contas')->insert([
            'conta' => $conta,
            'saldo' => 0,
        ]);
        
        return response()->json(['message' => 'Conta criada com sucesso'], 200);
    }
    
    public function getConta(int $conta)
    {
        $contaExistente = $this->contaModel->where('conta', $conta)->first();
        if (!$contaExistente) {
            return response()->json(['mensagem' => 'Conta não encontrada'], 400);
        }
        return response()->json(['mensagem' => 'Conta encontrada', 'conta' => $contaExistente], 200);
    }


    public function subtractValue(int $conta, float $valor)
    {
      $contaExistente = $this->contaModel->where('conta', $conta)->first();
        if (!$contaExistente) {
            return response()->json(['mensagem' => 'Conta não encontrada'], 400);
        }
        $contaExistente->saldo -= $valor;

        DB::table('contas')->where('conta', $conta)->update(['saldo' => $contaExistente->saldo]);
        return response()->json(['mensagem' => 'Valor adicionado com sucesso', 'conta' => $contaExistente], 200);
    }

    public function addValue(int $conta, float $valor)
    {
        $contaExistente = $this->contaModel->where('conta', $conta)->first();
        if (!$contaExistente) {
            return response()->json(['mensagem' => 'Conta não encontrada'], 400);
        }

        $contaExistente->saldo += $valor;

        DB::table('contas')->where('conta', $conta)->update(['saldo' => $contaExistente->saldo]);
        return response()->json(['mensagem' => 'Valor adicionado com sucesso', 'conta' => $contaExistente], 200);
    }

    public function transferValue(int $conta, int $conta2, float $valor)
    {
        $contaExistente = $this->contaModel->where('conta', $conta)->first();
        $conta2Existente = $this->contaModel->where('conta', $conta2)->first();
        if (!$contaExistente || !$conta2Existente) {
            return response()->json(['mensagem' => 'Conta não encontrada'], 400);
        }

        $contaExistente->saldo -= $valor;
        $conta2Existente->saldo += $valor;

        DB::table('contas')->where('conta', $conta)->update(['saldo' => $contaExistente->saldo]);
        DB::table('contas')->where('conta', $conta2)->update(['saldo' => $conta2Existente->saldo]);
        return response()->json(['mensagem' => 'Valor transferido com sucesso', 'conta' => $contaExistente, 'conta2' => $conta2Existente], 200);
    }
}