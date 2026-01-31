<?php

namespace App\Helpers;
use App\Models\PadraoTipo;
use Illuminate\Support\Str;

class Tools {

    public static function formatarTelefone($telefone)
    {
        // Remove tudo que não for número
        $telefone = preg_replace('/[^0-9]/', '', $telefone);

        // Verifica se tem 11 dígitos (com DDD + 9 dígitos)
        if (strlen($telefone) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);
        }

        // Verifica se tem 10 dígitos (com DDD)
        if (strlen($telefone) === 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $telefone);
        }

        // Se não for nenhum dos formatos acima, retorna sem formatação
        return $telefone;
    }

    public static function signUrl($url)
    {
        $key = config('app.key');

        $signature = hash_hmac('sha256', $url, $key);

        return $url . (Str::contains($url, '?') ? '&' : '?')  . "signature=" . $signature;
    }

    public static function divide($num1, $num2)
    {
        return ($num1 == 0 || $num2 == 0 ? 0 : $num1/$num2);
    }

    public static function formatarCep(string $cep): string
    {
        // Remove tudo que não for número
        $cep = preg_replace('/\D/', '', $cep);

        if (strlen($cep) !== 8) {
            return $cep; // ou lance uma exceção, se preferir
        }

        return substr($cep, 0, 2) . '.' . substr($cep, 2, 3) . '-' . substr($cep, 5, 3);
    }

    public static function limparValor($valor) {
        if (is_null($valor)) {
            return 0;
        }
        // Remove tudo que não for número, vírgula ou ponto
        $valor = preg_replace('/[^0-9,]/', '', $valor);
        // Troca vírgula por ponto
        $valor = str_replace(',', '.', $valor);
        // Remove pontos extras
        $valor = preg_replace('/\.(?=.*\.)/', '', $valor);
        return (float) $valor;
    }


}
