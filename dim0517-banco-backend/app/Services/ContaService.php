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
}