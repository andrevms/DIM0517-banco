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

    public function criarConta(int $conta, string $tipo)
    {
        if ($tipo != 'bonus' && $tipo != 'tradicional' && $tipo!= 'poupanca') {
            return response()->json(['mensagem' => 'Tipo de conta inválido'], 400);
        }

        $contaExistente = $this->contaModel->where('conta', $conta)->first();
        if ($contaExistente) {
            return response()->json(['mensagem' => 'Conta já existente'], 400);
        }

        $pontos = ($tipo == 'bonus') ? 10 : 0;

        DB::table('contas')->insert([
            'conta' => $conta,
            'saldo' => 0,
            'tipo' => $tipo,
            'pontos' => $pontos,
        ]);
        
        return response()->json(['mensagem' => 'Conta criada com sucesso'], 200);
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

        if ($contaExistente->saldo < 0) {
            return response()->json(['mensagem' => 'Saldo insuficiente'], 400);
        }

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

        if ($contaExistente->tipo == 'bonus') {
            $contaExistente->pontos += intdiv($valor, 100);
        }

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

        if ($contaExistente->saldo < 0) {
            return response()->json(['mensagem' => 'Saldo insuficiente'], 400);
        }

        $conta2Existente->saldo += $valor;
        $updateData = ['saldo' => $conta2Existente->saldo];

        if ($conta2Existente->tipo == 'bonus') {
            $conta2Existente->pontos += intdiv($valor, 200);
            $updateData['pontos'] = $conta2Existente->pontos;
        }


        DB::table('contas')->where('conta', $conta)->update($updateData);
        DB::table('contas')->where('conta', $conta2)->update(['saldo' => $conta2Existente->saldo]);
        return response()->json(['mensagem' => 'Valor transferido com sucesso', 'conta' => $contaExistente, 'conta2' => $conta2Existente], 200);
    }

    public function renderJuros(float $valor) {

        try {
            if($valor <= 0) {
                return response()->json(['mensagem' => 'Valor inválido'], 400);
            }

            DB::table('contas')
            ->where('tipo', 'poupanca')
            ->update([
                'saldo' => DB::raw('saldo + saldo * ' . ($valor / 100))
            ]);
            return response()->json(['mensagem' => 'Juros renderizados com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['mensagem' => 'Valor inválido'], 400);
        }
    }
}