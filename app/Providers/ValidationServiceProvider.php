<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory as ValidationFactory;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(ValidationFactory $validator): void
    {
        $validator->extend('formato_cpf', function ($attribute, $value, $parameters, $validator) {
            // Remove caracteres não numéricos
            $cpf = preg_replace('/[^0-9]/', '', $value);
            
            // Verifica se tem 11 dígitos
            if (strlen($cpf) != 11) {
                return false;
            }
            
            // Verifica se todos os dígitos são iguais
            if (preg_match('/(\d)\1{10}/', $cpf)) {
                return false;
            }
            
            return true;
        });

        $validator->extend('cpf', function ($attribute, $value, $parameters, $validator) {
            // Remove caracteres não numéricos
            $cpf = preg_replace('/[^0-9]/', '', $value);
            
            // Verifica se tem 11 dígitos
            if (strlen($cpf) != 11) {
                return false;
            }
            
            // Verifica se todos os dígitos são iguais
            if (preg_match('/(\d)\1{10}/', $cpf)) {
                return false;
            }
            
            // Validação do CPF
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf[$c] * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf[$c] != $d) {
                    return false;
                }
            }
            
            return true;
        });

        $validator->extend('formato_cnpj', function ($attribute, $value, $parameters, $validator) {
            // Remove caracteres não numéricos
            $cnpj = preg_replace('/[^0-9]/', '', $value);
            
            // Verifica se tem 14 dígitos
            if (strlen($cnpj) != 14) {
                return false;
            }
            
            // Verifica se todos os dígitos são iguais
            if (preg_match('/(\d)\1{13}/', $cnpj)) {
                return false;
            }
            
            return true;
        });

        $validator->extend('cnpj', function ($attribute, $value, $parameters, $validator) {
            // Remove caracteres não numéricos
            $cnpj = preg_replace('/[^0-9]/', '', $value);
            
            // Verifica se tem 14 dígitos
            if (strlen($cnpj) != 14) {
                return false;
            }
            
            // Verifica se todos os dígitos são iguais
            if (preg_match('/(\d)\1{13}/', $cnpj)) {
                return false;
            }
            
            // Validação do CNPJ
            $tamanho = strlen($cnpj) - 2;
            $numeros = substr($cnpj, 0, $tamanho);
            $digitos = substr($cnpj, $tamanho);
            $soma = 0;
            $pos = $tamanho - 7;
            
            for ($i = $tamanho; $i >= 1; $i--) {
                $soma += $numeros[$tamanho - $i] * $pos--;
                if ($pos < 2) {
                    $pos = 9;
                }
            }
            
            $resultado = $soma % 11 < 2 ? 0 : 11 - $soma % 11;
            
            if ($resultado != $digitos[0]) {
                return false;
            }
            
            $tamanho = $tamanho + 1;
            $numeros = substr($cnpj, 0, $tamanho);
            $soma = 0;
            $pos = $tamanho - 7;
            
            for ($i = $tamanho; $i >= 1; $i--) {
                $soma += $numeros[$tamanho - $i] * $pos--;
                if ($pos < 2) {
                    $pos = 9;
                }
            }
            
            $resultado = $soma % 11 < 2 ? 0 : 11 - $soma % 11;
            
            return $resultado == $digitos[1];
        });

        $validator->extend('formato_cep', function ($attribute, $value, $parameters, $validator) {
            // Remove caracteres não numéricos
            $cep = preg_replace('/[^0-9]/', '', $value);
            
            // Verifica se tem 8 dígitos
            return strlen($cep) == 8;
        });

        // Mensagens personalizadas
        $validator->replacer('formato_cpf', function ($message, $attribute, $rule, $parameters) {
            return __('messages.customer.validation.cpf.formato_cpf');
        });

        $validator->replacer('cpf', function ($message, $attribute, $rule, $parameters) {
            return __('messages.customer.validation.cpf.cpf');
        });

        $validator->replacer('formato_cnpj', function ($message, $attribute, $rule, $parameters) {
            return __('messages.customer.validation.cnpj.formato_cnpj');
        });

        $validator->replacer('cnpj', function ($message, $attribute, $rule, $parameters) {
            return __('messages.customer.validation.cnpj.cnpj');
        });

        $validator->replacer('formato_cep', function ($message, $attribute, $rule, $parameters) {
            return __('messages.customer.validation.endereco.cep.formato_cep');
        });
    }
}
